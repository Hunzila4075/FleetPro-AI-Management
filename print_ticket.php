<?php 
include 'db_connect.php';
$id = (int)$_GET['id'];
$sql = "SELECT bookings.*, buses.bus_name, buses.route, buses.price 
        FROM bookings 
        JOIN buses ON bookings.bus_id = buses.id 
        WHERE bookings.id = $id";
$res = $conn->query($sql);
$ticket = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Ticket #<?php echo $id; ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #eee; padding: 20px; }
        .ticket { background: white; width: 400px; margin: auto; padding: 20px; border: 2px dashed #333; }
        .header { text-align: center; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .details { margin: 20px 0; line-height: 1.6; }
        .footer { text-align: center; font-size: 12px; margin-top: 20px; }
        @media print { .no-print { display: none; } body { background: white; } }
    </style>
</head>
<body>

<div class="ticket">
    <div class="header">
        <h2>BUS TRAVEL RECEIPT</h2>
        <p>Ticket ID: <?php echo $ticket['id']; ?></p>
    </div>

    <div class="details">
        <strong>Passenger:</strong> <?php echo $ticket['passenger_name']; ?><br>
        <strong>Phone:</strong> <?php echo $ticket['passenger_phone']; ?><br>
        <hr>
        <strong>Bus Name:</strong> <?php echo $ticket['bus_name']; ?><br>
        <strong>Route:</strong> <?php echo $ticket['route']; ?><br>
        <strong>Seat No:</strong> <?php echo $ticket['seat_number']; ?><br>
        <hr>
        <strong>Fare Paid:</strong> RS <?php echo number_format($ticket['price']); ?><br>
        <strong>Payment:</strong> <?php echo $ticket['payment_status']; ?>
    </div>

    <div class="footer">
        <p>Please arrive 15 mins before departure.<br>Have a safe journey!</p>
    </div>
</div>

<div style="text-align: center; margin-top: 20px;" class="no-print">
    <button onclick="window.print()" style="padding: 10px 20px; background: #27ae60; color: white; border: none; cursor: pointer;">Print Ticket</button>
    <button onclick="window.location='view_bookings.php'" style="padding: 10px 20px; background: #3498db; color: white; border: none; cursor: pointer;">Back</button>
</div>

</body>
</html>