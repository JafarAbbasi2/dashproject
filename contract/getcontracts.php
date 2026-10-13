<?php
include("../config/db.php");

session_start();

if(!isset($_SESSION['id'])){
    echo json_encode([
        "status" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

$userid = $_SESSION['id'];

$sql = "
SELECT
c.id,
c.title,
c.description,
c.userID,
c.managerID,
c.deadline,
c.status,
c.time,

worker.name AS worker_name,
manager.name AS manger_name

FROM contracts AS c

LEFT JOIN users AS worker
ON c.userID = worker.id

LEFT JOIN users AS manager
ON c.managerID = manager.id

WHERE c.userID = '$userid'
OR c.managerID = '$userid'

ORDER BY c.id DESC
";

$result = $con->query($sql);

if(!$result){
    echo json_encode([
        "status" => fasle,
        "message" => "DATABASE query failed",
        "error" => mysqli_error($con)
    ]);
    exit;
}

$contract = array();

while($row = mysqli_fetch_assoc($result)){

if($row['userID'] == $userid){
    $row['user_role'] = "worker";
}else if($row['managerID'] == $userid){
    $row['user_role'] = "manager";
}

$contract[] =$row; 
}


echo json_encode([
    "status" => true,
    "contract" => $contract

]);










?>