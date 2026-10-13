<?php
include("../config/db.php");
session_start();

$id = $_SESSION["id"];

$post = "SELECT * FROM posts WHERE userid = '$id' ORDER BY time DESC LIMIT 5 ";
$postR = $con->query($post);

$data = array();

while($postD = $postR->fetch_assoc()){

$data[] = $postD;
};



echo json_encode($data);
?>