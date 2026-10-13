<?php

include("../config/db.php");
session_start();
$currentusername = $_SESSION['id'];


$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

if(!isset($data['id'])){

echo json_encode([
        "status" => "error",
        "message" => "Contract ID missing"
    ]);
    exit;
}

$id = intval($data['id']);

$sql = "

SELECT 
c.id,
c.title,
c.description,
c.userID,
c.managerID,
c.status,
c.progress,
c.deadline,
c.time,
worker.name AS worker_name,
manager.name AS manager_name

FROM contracts c 

LEFT JOIN users worker
ON c.userID = worker.id

LEFT JOIN users manager
ON c.managerID = manager.id

WHERE c.id = $id";


$result = $con->query($sql);

if($result){

$contract = $result->fetch_assoc();

$contract['current-user-id'] = $currentusername;
echo json_encode($contract);
}else { 
    echo "no contract found";
    }


?>