<?php

include("../config/db.php");
session_start();

if($_SESSION['role'] === 'admin'){
if(!isset($_GET["id"])){

    header("location: index.php");
    exit();

}} else if($_SESSION['role'] === 'user'){
if(!isset($_GET["id"])){
    header("location: ../user/userpanel.php");
    exit();
}}

$id = $_GET["id"];

$sql = "SELECT * FROM posts WHERE id = '$id'";
$result = $con->query($sql);

if($result->num_rows == 0){
    echo "Post not found";
    exit();
}

$post = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Post</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body{
            background: #f5f6f8;
        }

        .post-container{
            max-width: 850px;
            margin: 50px auto;
        }
        
        .post-card{
            
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }

        .post-image{
            padding-top: 1rem; 
            width: 50%;
            max-height: 450px;
            object-fit: cover;
        }

        .post-content{
            padding: 30px;
        }

        .post-title{
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .post-meta{
            color: #777;
            margin-bottom: 25px;
        }

        .post-description{
            font-size: 17px;
            line-height: 1.8;
            color: #444;
        }

        .back-btn{
            text-decoration: none;
            color: #555;
            font-weight: 500;
        }

        .back-btn:hover{
            color: #000;
        }

    </style>

</head>

<body>

<div class="container post-container">

    <!-- Back button -->

    <div class="mb-3">

        <a href="<?php 
        
        if($_SESSION['role'] == 'admin'){
            echo '../index.php';
        }else if ($_SESSION['role'] == 'user'){
            echo '../user/userpanel.php';
        }
        
        ?>" class="back-btn">
            ← Back 
        </a>

    </div>


    <!-- Post -->

    <div class="post-card">

        <?php if(!empty($post["thumbnail"])): ?>

            <img
                src="../uploads/<?php echo $post["thumbnail"]; ?>"
                class="post-image"
                alt="Post Image"
            >

        <?php endif; ?>


        <div class="post-content">

            <h1 class="post-title">
                <?php echo htmlspecialchars($post["title"]); ?>
            </h1>


            <div class="post-meta">

                <span>
                    User #<?php echo $post["userid"]; ?>
                </span>

                <span class="mx-2">•</span>

                <span>
                    <?php echo $post["time"]; ?>
                </span>

            </div>


            <p class="post-description">

                <?php echo nl2br(htmlspecialchars($post["description"])); ?>

            </p>

        </div>

    </div>

</div>

</body>

</html>