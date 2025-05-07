<?php
include('config/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Update status menjadi 'Sudah'
    $sql = "UPDATE content_planner SET status = 'Sudah' WHERE id = $id";
    
    if ($conn->query($sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?> 