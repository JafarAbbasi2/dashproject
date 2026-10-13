<?php 
session_start();
if(!isset($_SESSION["name"]) && !isset($_SESSION["role"]) ) {

header("location: login.php");
exit("ACCESS DENIED");
}

if($_SESSION['role'] == 'admin'){
        header("location: ../index.php");
       exit();
}else if($_SESSION["role"] == "user"){

header("location: ../user/userpanel.php");
exit();
}

?>