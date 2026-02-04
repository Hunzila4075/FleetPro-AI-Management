<?php 
include 'db_connect.php'; 
include 'header.php'; 

if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }

if(isset($_POST['submit'])){
    // Sanitize all inputs including the missing bus_number
    $b_no   = mysqli_real_escape_string($conn, $_POST['bus_number']); // Added this
    $b_name = mysqli_real_escape_string($conn, $_POST['bus_name']);
    $route  = mysqli_real_escape_string($conn, $_POST['route']);
    $driver = mysqli_real_escape_string($conn, $_POST['driver_name']);
    $seats  = (int)$_POST['total_seats'];
    $price  = (float)$_POST['price'];

    // Updated SQL to include bus_number
    $sql = "INSERT INTO buses (bus_number, bus_name, route, driver_name, total_seats, available_seats, price) 
            VALUES ('$b_no', '$b_name', '$route', '$driver', '$seats', '$seats', '$price')";

    if($conn->query($sql)){
        echo "<script>alert('Bus & Driver Added Successfully!'); window.location='index.php';</script>";
    } else {
        echo "<div style='color:red; text-align:center;'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="card" style="max-width: 500px; margin: 20px auto;">
    <h2 style="text-align: center;">➕ Add New Bus</h2>
    <form method="POST">
        <label>Bus Number (Plate No):</label>
        <input type="text" name="bus_number" placeholder="e.g. BSA-123" required>

        <label>Bus Name:</label>
        <input type="text" name="bus_name" placeholder="e.g. Faisal Movers" required>
        
        <label>Route:</label>
        <input type="text" name="route" placeholder="e.g. Multan to Lahore" required>
        
        <label>Driver Name:</label>
        <input type="text" name="driver_name" placeholder="Driver Full Name" required>
        
        <label>Total Seats:</label>
        <input type="number" name="total_seats" value="40" required>

        <label>Fare (RS):</label>
        <input type="number" name="price" placeholder="Ticket Price" required>
        
        <button type="submit" name="submit" class="btn btn-success" style="width:100%; margin-top:20px;">Save Bus Record</button>
    </form>
</div>

<?php include 'footer.php'; ?>