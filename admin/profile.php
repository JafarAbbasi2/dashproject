<?php  
include("../config/db.php");

$sql = "SELECT * FROM users WHERE role = 'admin'";
$result = $con->query($sql);
$row = $result->fetch_assoc();

$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

if(isset($_FILES['pfp']) && $_FILES['pfp']['error'] === 0){

$img = $_FILES["pfp"]["name"];
$extension = pathinfo($img, PATHINFO_EXTENSION);
$pfp = round(microtime(true)). '.' . $extension;

$path = "../uploads/" .$pfp;

$file = move_uploaded_file($_FILES['pfp']['tmp_name'], $path);

if($file){
    $query = "UPDATE users SET pfp ='$pfp' WHERE role = 'admin'";
    $con->query($query);

    echo "uploaded succesfully";
}

}

if(isset($data)){

    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];

    if(!empty($name) && !empty($email) && !empty($password)){

$SQL = "UPDATE users SET name = '$name', email = '$email', password = '$password' WHERE role = 'admin'";

$con->query($SQL);

echo "info updated";

    }else if(empty($password)){

        $SQL = "UPDATE users SET name = '$name', email = '$email' WHERE role = 'admin'";

$con->query($SQL);

echo "info updated";
    }
        else{ echo "you can't  left any field empty";}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>profile</title>
</head>
<body>
    

<div class="profile-page">

    <!-- Profile Header -->
    <div class="profile-header">

        <div class="profile-picture">
            <img src="../uploads/<?php echo $row['pfp']; ?>" id="profileImg" alt="Profile">
            <label for="profileUpload" class="change-pfp">
                Change
            </label>
            <input type="file"  id="profileUpload" hidden>
        </div>

        <div class="profile-basic">
            <h2 id="profileName"><?php echo $row['name']; ?></h2>
            <p id="profileEmail"><?php echo $row['email']; ?></p>
            <span class="role-badge">Admin</span>
        </div>

    </div>
    <div class="profile-top">
    <a href="../index.php" class="back-link">
        <i class="bi bi-arrow-left"></i>
        ← Back to Dashboard
    </a>
</div>
<hr>

    <!-- Profile Information -->
    <div class="profile-card">

        <div class="card-heading">
            <div>
                <h3>Profile Information</h3>
                <p>Update your personal information</p>
            </div>

            <button id="editProfile" class="btn btn-primary">
                Edit Profile
            </button>
        </div>


        <form id="profileForm">

            <div class="form-row">

                <div class="form-group">
                    <label>Name</label>
                    <input
                        type="text"
                        id="name"
                        value="<?php echo $row['name'] ?>"
                        disabled
                    >
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        id="email"
                        value="<?php echo $row['email']; ?>"
                        disabled
                    >
                </div>

            </div>


            <div class="form-row">

                <div class="form-group">
                    <label>New Password</label>
                    <input
                        type="password"
                        id="password"
                        placeholder="Enter new password"
                        disabled
                    >
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input
                        type="password"
                        id="confirmPassword"
                        placeholder="Confirm new password"
                        disabled
                    >
                </div>


            </div>
<div id="msg"></div>

            <div class="form-actions" id="formActions">

                <button
                    type="button"
                    id="cancelBtn"
                    class="btn btn-secondary">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-success">
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js">
</script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>

<script src="../js/ajax.js"></script>
</body>
</html>