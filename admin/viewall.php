<?php
include("../config/db.php");

$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data,true);

$id = "";

if($data){
$id = $data['id'];
}

if(empty($id)){

$post = "SELECT * FROM posts ORDER BY time DESC";
$postR = $con->query($post);

$data = array();

while($postD = $postR->fetch_assoc()){

$data[] = $postD;
};



echo json_encode($data);
}
else if (!empty($id)){
   
$id = $data["id"];

//try $data if not work  

$post = "SELECT * FROM posts WHERE id = '$id'";
$postR = $con->query($post);

$data = array();

while($postD = $postR->fetch_assoc()){

$data[] = $postD;
};



echo json_encode($data);

}

?>