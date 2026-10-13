<?php
include("../config/db.php");

$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

$id = $data['id'];

if($id){

$sql = "DELETE FROM contracts WHERE id = '$id'";
$result = $con->query($sql);

if($result){

echo 1;
}

}


?>