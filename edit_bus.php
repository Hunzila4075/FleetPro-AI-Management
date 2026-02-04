<?php 
include 'db_connect.php'; 
include 'header.php'; 

if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = $conn->query("SELECT * FROM buses WHERE id = $id");
$bus = $res->fetch_assoc();

if(!$bus) { die("Bus not found."); }

if(isset($_POST['update'])){
    $b_no   = mysqli_real_escape_string($conn, $_POST['bus_number']);
    $b_name = mysqli_real_escape_string($conn, $_POST['bus_name']);
    $route  = mysqli_real_escape_string($conn, $_POST['route']);
    $driver = mysqli_real_escape_string($conn, $_POST['driver_name']);
    $price  = (float)$_POST['price'];

    $sql = "UPDATE buses SET 
            bus_number='$b_no', 
            bus_name='$b_name', 
            route='$route', 
            driver_name='$driver', 
            price='$price' 
            WHERE id=$id";

    // We use a conditional check on the query execution
    if($conn->query($sql)){
        echo "<script>alert('Bus Updated Successfully!'); window.location='index.php';</script>";
    } else {
        // Check if the error code is 1062 (Duplicate Entry)
        if ($conn->errno == 1062) {
            echo "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin-bottom:20px; border:1px solid #f5c6cb;'>
                    <strong>⚠️ Duplicate Bus Number:</strong> The number '$b_no' is already assigned to another bus. Please use a unique plate number.
                  </div>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<div class="card" style="max-width: 500px; margin: auto;">
    <h2 style="text-align: center;">Edit Bus Info</h2>
    <form method="POST">
        <label>Bus Number:</label>
        <input type="text" name="bus_number" value="<?php echo $bus['bus_number']; ?>" required>
        
        <label>Bus Name:</label>
        <input type="text" name="bus_name" value="<?php echo $bus['bus_name']; ?>" required>
        
        <label>Route:</label>
        <input type="text" name="route" value="<?php echo $bus['route']; ?>" required>
        
        <label>Driver Name:</label>
        <input type="text" name="driver_name" value="<?php echo $bus['driver_name']; ?>" required>

        <label>Fare (RS):</label>
        <input type="number" name="price" value="<?php echo $bus['price']; ?>" required>
        
        <button type="submit" name="update" class="btn btn-primary" style="width:100%; margin-top:10px;">Save Changes</button>
    </form>
</div>