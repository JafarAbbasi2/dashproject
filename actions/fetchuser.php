<?php 
include("../config/db.php");


$sql = "SELECT * FROM users WHERE role ='user'";
$result = $con->query($sql);

$data = array();

while($row = $result->fetch_assoc()){

$data[] = $row;

}


echo json_encode($data);

?>