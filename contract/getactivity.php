
<?php
session_start();

include("../config/db.php");

header("Content-Type: application/json");

if (
    !isset($_SESSION['id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'user'
) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access."
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$contractId = filter_var(
    $data['id'] ?? null,
    FILTER_VALIDATE_INT
);

$userId = (int) $_SESSION['id'];

if (!$contractId) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid contract ID."
    ]);
    exit;
}

$sql = "SELECT a.id, a.action, a.details, a.created_at
        FROM contract_activity AS a
        INNER JOIN contracts AS c
            ON a.contract_id = c.id
        WHERE c.id = ?
          AND (c.userID = ? OR c.managerID = ?)
        ORDER BY a.created_at DESC, a.id DESC
        LIMIT 5";

$stmt = $con->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Could not retrieve activity."
    ]);
    exit;
}

$stmt->bind_param("iii", $contractId, $userId, $userId);
$stmt->execute();

$result = $stmt->get_result();
$activities = [];

while ($row = $result->fetch_assoc()) {
    $activities[] = $row;
}

$stmt->close();

echo json_encode([
    "success" => true,
    "activities" => $activities
]);
?>
