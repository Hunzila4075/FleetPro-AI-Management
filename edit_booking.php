<?php 
include 'db_connect.php'; 
include 'header.php'; 

if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }

// 1. Get Booking ID safely
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Fetch current booking and join with bus info to show context
$sql = "SELECT bookings.*, buses.bus_name, buses.route 
        FROM bookings 
        JOIN buses ON bookings.bus_id = buses.id 
        WHERE bookings.id = $booking_id";

$res = $conn->query($sql);
$data = $res->fetch_assoc();

if(!$data) { 
    echo "<div class='top-bar'><h1>⚠️ Record Not Found</h1></div>";
    include 'footer.php'; exit; 
}

// 3. Handle Update Request
if(isset($_POST['update_booking'])){
    $name   = mysqli_real_escape_string($conn, $_POST['p_name']);
    $phone  = mysqli_real_escape_string($conn, $_POST['p_phone']);
    $seat   = (int)$_POST['seat'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update_sql = "UPDATE bookings SET 
                   passenger_name = '$name', 
                   passenger_phone = '$phone', 
                   seat_number = '$seat', 
                   payment_status = '$status' 
                   WHERE id = $booking_id";

    if($conn->query($update_sql)){
        echo "<script>alert('Booking Updated!'); window.location='view_bookings.php';</script>";
    } else {
        echo "<div style='color:red;'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="top-bar">
    <div>
        <h1 style="margin: 0; font-size: 2rem; font-weight: 800;">Modify Reservation</h1>
        <p style="color: var(--text-secondary);">Updating ticket for: <strong><?php echo $data['bus_name']; ?></strong> (<?php echo $data['route']; ?>)</p>
    </div>
</div>

<div style="background: white; padding: 2.5rem; border-radius: 24px; box-shadow: var(--shadow-md); max-width: 600px; border: 1px solid var(--border-subtle);">
    <form method="POST">
        <label>Passenger Name</label>
        <input type="text" name="p_name" value="<?php echo htmlspecialchars($data['passenger_name']); ?>" required>

        <label>Contact Number</label>
        <input type="text" name="p_phone" value="<?php echo htmlspecialchars($data['passenger_phone']); ?>" required>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label>Assigned Seat</label>
                <input type="number" name="seat" value="<?php echo $data['seat_number']; ?>" required>
            </div>
            <div>
                <label>Payment Status</label>
                <select name="status">
                    <option value="Paid Cash" <?php if($data['payment_status'] == 'Paid Cash') echo 'selected'; ?>>Paid Cash ✅</option>
                    <option value="Pending" <?php if($data['payment_status'] == 'Pending') echo 'selected'; ?>>Pending ⏳</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 15px;">
            <button type="submit" name="update_booking" class="btn-action" style="flex: 2;">Save Changes</button>
            <a href="view_bookings.php" class="btn-action" style="background: #f1f5f9; color: #0f172a; flex: 1; text-align: center; box-shadow: none;">Cancel</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>