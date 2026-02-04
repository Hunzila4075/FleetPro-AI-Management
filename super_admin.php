<?php include 'header.php'; 

// Security Check
if ($_SESSION['role'] !== 'super') {
    echo "<div class='card' style='text-align:center; margin-top:50px;'>
            <h1 style='color:var(--danger)'>Access Restricted</h1>
            <p>Only Super Admins can view financial reports.</p>
            <a href='index.php' style='color:var(--accent)'>Go Back</a>
          </div>";
    include 'footer.php'; exit();
}

// Data Queries
$sales = $conn->query("SELECT SUM(b.price) as total FROM bookings bk JOIN buses b ON bk.bus_id = b.id WHERE bk.payment_status = 'Paid Cash'")->fetch_assoc();
$buses = $conn->query("SELECT COUNT(*) as count FROM buses")->fetch_assoc();
$crew = $conn->query("SELECT COUNT(*) as count FROM drivers")->fetch_assoc();
?>

<h1 style="font-weight: 800; letter-spacing: -1px;">Executive Reports</h1>

<div class="stat-grid">
    <div class="card" style="border-top: 4px solid var(--success);">
        <small style="text-transform:uppercase; color:#64748b; font-weight:700;">Saled Tickets Revenue</small>
        <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-top: 10px;">PKR <?php echo number_format($sales['total'] ?? 0); ?></div>
    </div>
    <div class="card" style="border-top: 4px solid var(--accent);">
        <small style="text-transform:uppercase; color:#64748b; font-weight:700;">Total Fleet Size</small>
        <div style="font-size: 2rem; font-weight: 800; margin-top: 10px;"><?php echo $buses['count']; ?> Units</div>
    </div>
    <div class="card" style="border-top: 4px solid var(--super);">
        <small style="text-transform:uppercase; color:#64748b; font-weight:700;">Total Registered Crew</small>
        <div style="font-size: 2rem; font-weight: 800; margin-top: 10px;"><?php echo $crew['count']; ?> Personnel</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0;">Fleet & Driver Assignment</h3>
    <table style="width:100%; border-collapse:collapse;">
        <tr style="text-align:left; background:#f8fafc;">
            <th style="padding:12px;">Bus Name</th>
            <th style="padding:12px;">Route</th>
            <th style="padding:12px;">Assigned Driver</th>
        </tr>
        <?php
        $res = $conn->query("SELECT * FROM buses");
        while($row = $res->fetch_assoc()): ?>
        <tr style="border-bottom:1px solid #f1f5f9;">
            <td style="padding:12px; font-weight:600;"><?php echo $row['bus_name']; ?></td>
            <td style="padding:12px;"><?php echo $row['route']; ?></td>
            <td style="padding:12px; color:var(--accent);"><?php echo $row['driver_name']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>