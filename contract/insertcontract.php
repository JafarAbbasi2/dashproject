<?php
include("../config/db.php");

$title = $_POST['title'];
$id = $_POST['id'];
$decription = $_POST['description'];
$manager = $_POST['manager_id'];
$worker = $_POST['worker_id'];
$deadline = $_POST['deadline'];
$status = $_POST['status'];

if($id){

    $sql = "UPDATE contracts SET title='$title', description='$decription', userID='$worker', managerID='$manager', status='$status', deadline='$deadline' WHERE id = $id";

}else{

$sql = "INSERT INTO contracts (title, description, userID, managerID, status, deadline) 
VALUES ('$title', '$decription', '$worker', '$manager', '$status', '$deadline' )";

}
$result = $con->query($sql);

if($result){
    // echo $id;
}






?>