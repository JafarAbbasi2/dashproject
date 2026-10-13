<?php
include("../config/db.php");

$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

$id = $data["id"];


$post = "SELECT * FROM posts WHERE id = '$id'";
$postR = $con->query($post);

$data = array();

while($postD = $postR->fetch_assoc()){

$data[] = $postD;
};



echo json_encode($data);
?>