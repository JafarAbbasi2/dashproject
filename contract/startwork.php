
<?php

session_start();
include("../config/db.php");

header("Content-Type: application/json");

// 1. Make sure the user is logged in.
if (!isset($_SESSION['id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Please log in first."
    ]);
    exit;
}

// 2. Read the contract ID sent by AJAX.
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid contract ID."
    ]);
    exit;
}

$contractId = (int) $data['id'];
$userId = (int) $_SESSION['id'];

// 3. Start a transaction so the status and activity stay consistent.
$con->begin_transaction();

try {

    // 4. Only the assigned worker can start a pending contract.
    $sql = "UPDATE contracts
            SET status = 'ongoing'
            WHERE id = ?
            AND userID = ?
            AND status = 'pending'";

    $stmt = $con->prepare($sql);

    if (!$stmt) {
        throw new Exception("Could not prepare contract update.");
    }

    $stmt->bind_param("ii", $contractId, $userId);
    $stmt->execute();

    // No row changed means the contract was not eligible.
    if ($stmt->affected_rows !== 1) {
        $stmt->close();
        $con->rollback();

        echo json_encode([
            "success" => false,
            "message" => "Contract could not be started. It may not be assigned to you or may no longer be pending."
        ]);
        exit;
    }

    $stmt->close();

    // 5. Record the event only after the status update succeeds.
 
$activitySql = "INSERT INTO contract_activity
                (contract_id, user_id, action, details)
                VALUES (?, ?, ?, ?)";

$activityStmt = $con->prepare($activitySql);

if (!$activityStmt) {
    throw new Exception("Could not prepare activity record.");
}

$action = "work_started";
$details = "Worker started work on this contract.";

$activityStmt->bind_param(
    "iiss",
    $contractId,
    $userId,
    $action,
    $details
);

$activityStmt->execute();
$activityStmt->close();


    // 6. Save both database changes.
    $con->commit();

    echo json_encode([
        "success" => true,
        "message" => "Work started successfully!"
    ]);
} catch (Throwable $e) {

    $con->rollback();

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
