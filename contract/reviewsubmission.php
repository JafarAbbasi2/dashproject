<?php
session_start();
header("Content-Type: application/json");

require_once "../config/db.php";

// 1. Only logged-in regular users can use this endpoint.
if (!isset($_SESSION['id'], $_SESSION['role']) || $_SESSION['role'] !== 'user') {
    http_response_code(403);
    echo json_encode([
        "status" => false,
        "message" => "Unauthorized access."
    ]);
    exit;
}

// 2. Accept POST requests only.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

// 3. Read the decision sent by JavaScript.
$input = json_decode(file_get_contents("php://input"), true);

$contractId = filter_var($input['contract_id'] ?? null, FILTER_VALIDATE_INT);
$submissionId = filter_var($input['submission_id'] ?? null, FILTER_VALIDATE_INT);
$decision = $input['decision'] ?? '';
$feedback = trim($input['feedback'] ?? '');

if (
    !$contractId || $contractId < 1 ||
    !$submissionId || $submissionId < 1 ||
    !in_array($decision, ['approve', 'revision'], true)
) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Invalid review information."
    ]);
    exit;
}

if ($decision === 'revision' && $feedback === '') {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Please provide feedback for the requested revision."
    ]);
    exit;
}

$managerId = (int) $_SESSION['id'];

try {
    $con->begin_transaction();

    // 4. Verify this user is the assigned manager.
    // Lock the contract row while the review is processed.
    $sql = "SELECT status
            FROM contracts
            WHERE id = ? AND managerID = ?
            FOR UPDATE";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $contractId, $managerId);
    $stmt->execute();

    $contract = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$contract) {
        throw new Exception("You are not the manager of this contract.");
    }

    if ($contract['status'] !== 'under_review') {
        throw new Exception("This contract is not awaiting review.");
    }

    // 5. Confirm the submission belongs to this contract.
    $sql = "SELECT id
            FROM contract_submissions
            WHERE id = ? AND contract_id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $submissionId, $contractId);
    $stmt->execute();

    $submission = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$submission) {
        throw new Exception("Submission not found for this contract.");
    }

    // 6. Apply the decision.
    if ($decision === 'approve') {
        $newStatus = 'completed';
        $activityAction = 'work_approved';
        $activityDetails = 'Manager approved the submitted work.';
    } else {
        $newStatus = 'ongoing';
        $activityAction = 'revision_requested';
        $activityDetails = $feedback;
    }

    $sql = "UPDATE contracts
            SET status = ?
            WHERE id = ? AND managerID = ? AND status = 'under_review'";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sii", $newStatus, $contractId, $managerId);
    $stmt->execute();

    if ($stmt->affected_rows !== 1) {
        $stmt->close();
        throw new Exception("The contract status changed. Refresh and try again.");
    }

    $stmt->close();

    // 7. Record the decision in contract activity.
    $sql = "INSERT INTO contract_activity
            (contract_id, user_id, action, details)
            VALUES (?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param(
        "iiss",
        $contractId,
        $managerId,
        $activityAction,
        $activityDetails
    );
    $stmt->execute();
    $stmt->close();

    $con->commit();

    echo json_encode([
        "status" => true,
        "message" => $decision === 'approve'
            ? "Work approved successfully."
            : "Revision requested successfully.",
        "new_status" => $newStatus
    ]);

} catch (Throwable $e) {
    $con->rollback();

    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => $e instanceof mysqli_sql_exception
            ? "A database error occurred while reviewing the submission."
            : $e->getMessage()
    ]);
}
?>