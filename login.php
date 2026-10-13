<?php 
include("config/db.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <div class="loginpanel">
    <form action="" method="post">
        <label >username</label><br>
        <input type="text" name="username" ><br>
        <label >password</label><br>
        <input type="password" name="password"><br>
        <input type="submit" name="submit" value="login"><br>
        <label >don't have an Account</label><br>
        <input type="submit" name="signup" value="signup"><br>
    </form>
    </div>
</body>
</html>

<?php  

if(isset($_POST['submit'])){
    if(!empty($_POST['username']) && !empty($_POST["password"])){

        
        
        $username = $_POST["username"];
        $password  = $_POST["password"];
        
        $query = "SELECT * FROM users WHERE name = '$username'";
        $result = $con->query($query);
        $row = $result->fetch_assoc();
        
        $pass = $row["password"];
        $pfp = $row["pfp"];
        $id = $row['id'];

    if($username == $row['name'] && $password == $pass){

        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['pfp'] = $row['pfp'];
        $_SESSION['id'] = $row['id'];

header("location: actions/check.php");

}else{
        echo 'wrong password or username, try again';
    }

}else{
    echo "enter username and password";
}
}
if(isset($_POST["signup"])){
header("location: signup.php");

}
?>