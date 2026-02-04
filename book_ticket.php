<?php 
include 'db_connect.php'; 
include 'header.php'; 

if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }

// Get Bus ID safely
$bus_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = $conn->query("SELECT * FROM buses WHERE id = $bus_id");
$bus = $res->fetch_assoc();

if(!$bus) { 
    echo "<div class='card' style='text-align:center;'><h2>⚠️ Bus Not Found</h2><a href='index.php' class='btn btn-primary'>Back to Dashboard</a></div>"; 
    include 'footer.php'; 
    exit; 
}

if(isset($_POST['book'])){
    $name   = mysqli_real_escape_string($conn, $_POST['p_name']);
    $phone  = mysqli_real_escape_string($conn, $_POST['p_phone']);
    $cnic   = mysqli_real_escape_string($conn, $_POST['p_cnic']);
    $seat   = (int)$_POST['seat'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Check if seats are actually available
    if($bus['available_seats'] > 0){
        $sql = "INSERT INTO bookings (bus_id, passenger_name, passenger_phone, passenger_cnic, seat_number, payment_status) 
                VALUES ('$bus_id', '$name', '$phone', '$cnic', '$seat', '$status')";
        
        if($conn->query($sql)){
            // Logic: Decrease available seats by 1
            $conn->query("UPDATE buses SET available_seats = available_seats - 1 WHERE id = $bus_id");
            echo "<script>alert('Booking Confirmed Successfully!'); window.location='view_bookings.php';</script>";
        } else {
            echo "<div style='color:red; background:#fff0f0; padding:10px; text-align:center;'>Error: " . $conn->error . "</div>";
        }
    } else {
        echo "<script>alert('Error: No seats left on this bus!');</script>";
    }
}
?>

<div class="card" style="max-width: 550px; margin: 20px auto;">
    <h2 style="text-align: center; color: var(--primary);">🎟️ Issue New Ticket</h2>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 5px solid #3498db;">
        <table style="width: 100%; font-size: 14px;">
            <tr><td><strong>Bus:</strong> <?php echo htmlspecialchars($bus['bus_name']); ?></td><td><strong>Route:</strong> <?php echo htmlspecialchars($bus['route']); ?></td></tr>
            <tr><td><strong>Driver:</strong> <?php echo htmlspecialchars($bus['driver_name'] ?? 'N/A'); ?></td><td><strong>Fare:</strong> RS <?php echo number_format($bus['price']); ?></td></tr>
        </table>
    </div>

    <form method="POST">
        <label>Passenger Full Name:</label>
        <input type="text" name="p_name" placeholder="Enter name" required>
        
        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Phone Number:</label>
                <input type="text" name="p_phone" placeholder="03xxxxxxxxx" required>
            </div>
            <div style="flex: 1;">
                <label>CNIC (National ID):</label>
                <input type="text" name="p_cnic" placeholder="xxxxx-xxxxxxx-x" required>
            </div>
        </div>
        
        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Seat Number:</label>
                <input type="number" name="seat" min="1" max="<?php echo $bus['total_seats']; ?>" placeholder="1-<?php echo $bus['total_seats']; ?>" required>
            </div>
            <div style="flex: 1;">
                <label>Payment Status:</label>
                <select name="status" style="width:100%; padding:11px; margin-top:10px; border-radius:6px; border:1px solid #ddd;">
                    <option value="Paid Cash">Paid Cash ✅</option>
                    <option value="Pending">Pending ⏳</option>
                </select>
            </div>
        </div>

        <button type="submit" name="book" class="btn" style="width: 100%; background: #27ae60; color: white; padding: 15px; margin-top: 20px; font-size: 16px;">Confirm & Print Ticket</button>
    </form>
</div>

<?php include 'footer.php'; ?>