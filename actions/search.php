    <?php 
include("../config/db.php");

$data = stripcslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

$search_data = $data["sd"];

$sql = "SELECT * FROM users WHERE CONCAT(name, email) LIKE '%$search_data%'";
$result = mysqli_query($con, $sql);

$output = array();

while($row = $result->fetch_assoc()) {
    $output[] = $row;
}
echo json_encode($output);


mysqli_close($con);
?>