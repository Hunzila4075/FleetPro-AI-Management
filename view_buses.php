<?php 
include 'db_connect.php'; 
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
include 'header.php'; 

$buses = $conn->query("SELECT * FROM buses");
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Manage Fleet</h2>
    <a href="add_bus.php" class="btn btn-success">+ Add New Bus</a>
</div>

<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #eee;">
            <th style="padding: 15px;">Bus No</th>
            <th style="padding: 15px;">Name</th>
            <th style="padding: 15px;">Route</th>
            <th style="padding: 15px;">Driver</th>
            <th style="padding: 15px;">Seats</th>
            <th style="padding: 15px;">Price</th>
            <th style="padding: 15px;">Actions</th>
        </tr>
        <?php while($row = $buses->fetch_assoc()): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;"><?php echo $row['bus_number']; ?></td>
            <td style="padding: 15px;"><strong><?php echo $row['bus_name']; ?></strong></td>
            <td style="padding: 15px;"><?php echo $row['route']; ?></td>
            <td style="padding: 15px;"><?php echo $row['driver_name']; ?></td>
            <td style="padding: 15px;"><?php echo $row['available_seats']; ?>/<?php echo $row['total_seats']; ?></td>
            <td style="padding: 15px;">RS <?php echo number_format($row['price'] ?? 0); ?></td>
            <td style="padding: 15px;">
                <a href="edit_bus.php?id=<?php echo $row['id']; ?>" style="color: #3498db; text-decoration: none;">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>