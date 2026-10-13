<?php
include("../config/db.php");



$post = "SELECT * FROM posts ORDER BY time DESC LIMIT 5";
$postR = $con->query($post);

$data = array();

while($postD = $postR->fetch_assoc()){

$data[] = $postD;
};



echo json_encode($data);
?>