
<?php
session_start();
require_once "../config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access."
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$contractId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

if (!$contractId) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid contract ID."
    ]);
    exit;
}

$userId = (int) $_SESSION['id'];

// Confirm this user is the worker or manager of this contract.
$check = $con->prepare(
    "SELECT id FROM contracts
     WHERE id = ? AND (userID = ? OR managerID = ?)"
);
$check->bind_param("iii", $contractId, $userId, $userId);
$check->execute();
$access = $check->get_result();

if ($access->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Contract not found or access denied."
    ]);
    exit;
}

// Fetch every activity record for this contract.
$stmt = $con->prepare(
    "SELECT action, details, created_at
     FROM contract_activity
     WHERE contract_id = ?
     ORDER BY created_at DESC, id DESC"
);
$stmt->bind_param("i", $contractId);
$stmt->execute();

$result = $stmt->get_result();
$activities = [];

while ($row = $result->fetch_assoc()) {
    $activities[] = $row;
}

echo json_encode([
    "success" => true,
    "activities" => $activities
]);
?>
