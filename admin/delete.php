<?php
include("../config/db.php");

$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

$id = $data['sid'];

mysqli_report(MYSQLI_REPORT_OFF);


if(!empty($id)){

$sql = "DELETE FROM posts WHERE id =$id";

if($con->query($sql) == TRUE){
    echo 1 ;
}else{
    echo 0;
}
}else{
    echo 0;
}








mysqli_close($con);
?>