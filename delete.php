<?php
include('config/db.php');
$id = $_GET['id'];
$conn->query("DELETE FROM content_planner WHERE id=$id");
header("Location: index.php");
exit;
?>
