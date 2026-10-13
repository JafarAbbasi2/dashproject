<?php
include("../config/db.php");
session_start();
// mysqli_report(MYSQLI_REPORT_OFF);

$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data,true);

if($_SESSION['role'] == "user"){
if(isset($_SESSION['id'])){
    $id = $_SESSION['id'];
}else if(isset($_POST["id"])){
$id = $_POST["id"];
}}else {if(isset($_POST["id"])){
$id = $_POST["id"];
}}



$name = $_POST["user"];
$email = $_POST["email"];
$pass = $_POST["pass"];

$image = "";

if(isset($_FILES["img"])){
$img = $_FILES["img"]["name"];
$extension = pathinfo($img, PATHINFO_EXTENSION);
$image = round(microtime(true)). '.' .$extension;

$path = '../uploads/'. $image;
if(move_uploaded_file($_FILES["img"]["tmp_name"], $path)){
    echo " img uploaded succefully";
}else{
    echo "img failed";
}
}


$check = "SELECT id FROM users WHERE name='$name'";
$result = $con->query($check);

if($result->num_rows > 0){

    echo "Username already exists";

}else{


if(!empty($name) && !empty($email) && !empty($pass) && !empty($id)) {
     
     if(empty($image)){
        $sql = "INSERT INTO users (id, name, email, password)
                VALUES ('$id','$name','$email','$pass') ON DUPLICATE KEY
                UPDATE name = '$name', email = '$email', password ='$pass' ";
     }else if (!empty($image)){
$sql = "INSERT INTO users (id, name, email, password, pfp)
        Values('$id','$name','$email','$pass','$image') ON DUPLICATE KEY 
        UPDATE name='$name', email='$email', password='$pass', pfp='$image'";
     }

     
    if($con->query($sql)){
        echo ' uploaded';
    }else{
        echo 'error occured';
    }
}
else if(!empty($name) && !empty($email)&& !empty($pass) ) {

     
     if(empty($image)){
        $sql = "INSERT INTO users ( name, email, password)
                VALUES ('$name','$email','$pass')";
               }else if (!empty($image)){

$sql = "INSERT INTO users ( name, email, password, pfp)
        Values('$name','$email','$pass','$image') ";

     }

     
    if($con->query($sql)){
        echo ' uploaded';
    }else{
        echo 'error occured';
    }
}
else{
    echo "upload everything please!!";
}

}
?>