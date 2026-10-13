<?php
include("../config/db.php");

$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data,true);

$id = "";

if($data){
$id = $data['id'];
}

if(empty($id)){

$post = "SELECT * FROM users WHERE role = 'user'";
$postR = $con->query($post);

$data = array();

while($postD = $postR->fetch_assoc()){

$data[] = $postD;
};



echo json_encode($data);

}else if (!empty($id)){
   
$id = $data["id"];

//try $data if not work  

$post = "SELECT * FROM users WHERE id = '$id' AND role ='user'";
$postR = $con->query($post);

$data = array();

while($postD = $postR->fetch_assoc()){

$data[] = $postD;
};



echo json_encode($data);

}
?>