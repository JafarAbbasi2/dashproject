<?php
include("../config/db.php");

$sql = "SELECT 
            contracts.*,
            worker.name AS worker_name,
            manager.name AS manager_name
            
            FROM contracts
            
            LEFT JOIN users AS worker
            ON contracts.userID = worker.id

            LEFT JOIN users AS manager
            ON contracts.managerID = manager.id

            ORDER BY contracts.id DESC";

$result = $con->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row; 

}

echo json_encode($data);






?>