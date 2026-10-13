<?php
include("config/db.php");

if(isset($_POST["submit"])){
 
$user = $_POST["name"];
$pass = $_POST["password"];

if(!empty($user) && !empty($pass)){

$checksql= "SELECT * FROM users WHERE name = '$user'";
$checkresult = mysqli_query($con,$checksql);

if(mysqli_num_rows($checkresult) == 0){

$sql = "INSERT INTO users (name, password) VALUES ('$user', '$pass')";
$result = mysqli_query($con, $sql);

if($result){
    header("location: login.php");
}else{ echo "try again";}

}else{
    echo " user already exits";
}
}else{
    echo "enter details";
}


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <form action="" method="post">
        <label >name</label>
        <input type="text" name="name"><br>
        <label >Password</label>
        <input type="text" name="password"><br>
    
        <input type="submit" name="submit" value="SignUp">
    </form>
    
</body>
</html>