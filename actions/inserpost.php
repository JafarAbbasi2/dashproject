<?php

include("../config/db.php");
session_start();

if (isset($_SESSION["id"])) {

$user_id = $_SESSION["id"];
}else{ $user_id = ""; }

if(isset($_POST["id"])){
    $id = $_POST["id"];
}else{
    $id = "";
}

if(isset($_POST["category"])){

  $category = $_POST["category"];

}else{
    $category = "";
}

$title = $_POST["title"];
$description = $_POST["content"];


$image = "";

if(isset($_FILES["img"]) && $_FILES["img"]["error"] === 0){

    $img = $_FILES["img"]["name"];

    $extension = pathinfo($img, PATHINFO_EXTENSION);

    $image = round(microtime(true)) . "." . $extension;

    $path = "../uploads/" . $image;

    move_uploaded_file(
        $_FILES["img"]["tmp_name"],
        $path
    );
}


if(!empty($id)){

    if(!empty($image) && !empty($category)){

        $sql = "UPDATE posts SET
                title='$title',
                category='$category',
                description='$description',
                thumbnail='$image'
                WHERE id='$id'";

    }else if (empty($image) && !empty($category)){

        $sql = "UPDATE posts SET
                title='$title',
                category='$category',
                description='$description'
                WHERE id='$id'";

    }else if (empty($category) && !empty($image)){

                $sql = "UPDATE posts SET
                title='$title',
                thumbnail='$image',
                description='$description'
                WHERE id='$id'";
    }else{
                $sql = "UPDATE posts SET
                title='$title',
                description='$description'
                WHERE id='$id'";

    }

    if($con->query($sql)){

        echo "Post updated successfully";

    }else{

        echo "Error updating post";
    }

}


// ===============================
// ADD POST
// ===============================

else{

    if(!empty($title) && !empty($category) && !empty($description) && !empty($user_id) && empty($id)){

        $sql = "INSERT INTO posts
                (title, category, description, thumbnail, userid)
                VALUES
                ('$title', '$category', '$description', '$image','$user_id')";


        if($con->query($sql)){

            echo "Post added successfully";

        }else{

            echo "Error adding post";
        }

    }else  if(!empty($title) && !empty($category) && !empty($description)  && empty($id)){

        $sql = "INSERT INTO posts
                (title, category, description, thumbnail)
                VALUES
                ('$title', '$category', '$description', '$image')";


        if($con->query($sql)){

            echo "Post added successfully";

        }else{

            echo "Error adding post";
        }

    }else{

        echo "Please fill everything";
    }
}

?>