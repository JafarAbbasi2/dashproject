<?php
include("../config/db.php");
session_start();
header("Content-Type: application/json");

if(!isset($_SESSION['id'], $_SESSION['role'])){
    http_response_code(401);
    echo json_encode([
        "status" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode([
        "status" => false,
        "message" => "Invalid request method"
    ]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$contractId = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

if(!$contractId || $contractId < 1){
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Invalid contract ID"
    ]);
    exit;
}

$userid = (int) $_SESSION['id'];
$role = $_SESSION['role'];

if($role === 'user') {

    $sql = "SELECT id FROM contracts WHERE id = ? AND (userID = ? OR managerID = ?)";
    $result = $con->prepare($sql);
    $result->bind_param("iii", $contractId, $userid, $userid);

} elseif ($role === 'admin') {

    $sql = "SELECT id FROM contracts WHERE id = ?";
    $result = $con->prepare($sql);
    $result->bind_param("i", $contractId);

} else {
    http_response_code(403);
    echo json_encode([
        "status" => false,
        "message" => "Unauthorized role"
    ]);
    exit;
}

$result->execute();
$contractIdResult = $result->get_result();
$hasAccess = $contractIdResult->num_rows > 0;
$result->close();

if(!$hasAccess){
    http_response_code(403);
    echo json_encode([
        "status" => false,
        "message" => "Access denied to this contract"
    ]);
    exit;
}

// -----------------fetch-submission-for-this-contract------------------

$sql = "SELECT id, contract_id, user_id, submission_title, submission_description, work_file FROM contract_submissions WHERE contract_id = ? ORDER BY id DESC";

$result = $con->prepare($sql);
$result->bind_param("i", $contractId);
$result->execute();

$submissionsResult = $result->get_result();
$submissions = array();

while($row = $submissionsResult->fetch_assoc()){
    $submissions[] = $row;
}

$result->close();

echo json_encode([
    "status" => true,
    "submissions" => $submissions
]);




?>