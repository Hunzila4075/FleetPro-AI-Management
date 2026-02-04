<?php
include 'db_connect.php';

// Security: Stop people from typing this URL directly without logging in
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }

if(isset($_GET['id'])){
    $bus_id = (int)$_GET['id'];

    // 1. Delete all bookings for this bus first (Crucial step)
    $conn->query("DELETE FROM bookings WHERE bus_id = $bus_id");

    // 2. Delete the bus record
    $sql = "DELETE FROM buses WHERE id = $bus_id";
    
    if($conn->query($sql)){
        // Redirect back to dashboard with a success flag
        header("Location: index.php?msg=bus_removed");
    } else {
        echo "Error: Could not delete bus. " . $conn->error;
    }
} else {
    header("Location: index.php");
}
?>