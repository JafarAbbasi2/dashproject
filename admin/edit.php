<?php 
include("../config/db.php");


$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

$id = $data["sid"];

if(!empty($id)){
$sql = "SELECT * FROM students WHERE id=$id";
$result = $con->query($sql);

if($row = $result->fetch_assoc()){


  echo json_encode($row);
}

}




mysqli_close($con);
?>