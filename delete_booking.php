<?php
include 'db_connect.php';

if(isset($_GET['id']) && isset($_GET['bus_id'])){
    $booking_id = (int)$_GET['id'];
    $bus_id = (int)$_GET['bus_id'];

    // 1. Delete the passenger record
    $conn->query("DELETE FROM bookings WHERE id = $booking_id");

    // 2. Add the seat back to the bus (+1)
    $conn->query("UPDATE buses SET available_seats = available_seats + 1 WHERE id = $bus_id");

    header("Location: view_bookings.php?status=deleted");
}
?>