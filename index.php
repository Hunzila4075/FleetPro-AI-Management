<?php 
// 1. ALWAYS start the session at the very first line
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// 2. Security Check: Redirect to login if not authenticated
if (!isset($_SESSION['user'])) { 
    header("Location: login.php"); 
    exit(); 
} 

// 3. Include database and layout parts
include 'db_connect.php'; 
include 'header.php'; 

// 4. Fetch Dynamic Stats with error handling
$bus_count_res = $conn->query("SELECT COUNT(*) as t FROM buses");
$bus_count = ($bus_count_res) ? $bus_count_res->fetch_assoc()['t'] : 0;

$book_count_res = $conn->query("SELECT COUNT(*) as t FROM bookings");
$book_count = ($book_count_res) ? $book_count_res->fetch_assoc()['t'] : 0;

$seat_count_res = $conn->query("SELECT SUM(available_seats) as t FROM buses");
$seat_count = ($seat_count_res) ? $seat_count_res->fetch_assoc()['t'] : 0;

$buses = $conn->query("SELECT * FROM buses ORDER BY id DESC");
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="glass-card" style="padding: 1.5rem; border-left: 4px solid var(--accent); background: rgba(255,255,255,0.05); border-radius: 15px;">
        <p style="color: #64748b; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin: 0;">Active Fleet</p>
        <h2 style="margin: 0.5rem 0; font-size: 1.8rem; color: #fff;"><?php echo $bus_count; ?> <span style="font-size: 1rem; color: #94a3b8;">Buses</span></h2>
    </div>

    <div class="glass-card" style="padding: 1.5rem; border-left: 4px solid var(--success); background: rgba(255,255,255,0.05); border-radius: 15px;">
        <p style="color: #64748b; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin: 0;">Inventory</p>
        <h2 style="margin: 0.5rem 0; font-size: 1.8rem; color: #fff;"><?php echo $seat_count; ?> <span style="font-size: 1rem; color: #94a3b8;">Free Seats</span></h2>
    </div>

    <div class="glass-card" style="padding: 1.5rem; border-left: 4px solid #8b5cf6; background: rgba(255,255,255,0.05); border-radius: 15px;">
        <p style="color: #64748b; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin: 0;">Total Activity</p>
        <h2 style="margin: 0.5rem 0; font-size: 1.8rem; color: #fff;"><?php echo $book_count; ?> <span style="font-size: 1rem; color: #94a3b8;">Tickets Issued</span></h2>
    </div>
</div>

<div class="glass-card" style="background: rgba(255,255,255,0.03); padding: 2rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3 style="margin: 0; font-size: 1.25rem; color: #fff;"><b>FLEET MANAGEMENT</b></h3>
        <a href="add_bus.php" class="btn-prime" style="background: var(--success); color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: bold;">+ Register New Bus</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; color: #fff;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid rgba(255,255,255,0.1);">
                <th style="padding: 1rem; font-size: 0.85rem; color: #94a3b8;">IDENTIFIER</th>
                <th style="padding: 1rem; font-size: 0.85rem; color: #94a3b8;">ROUTE & LOGISTICS</th>
                <th style="padding: 1rem; font-size: 0.85rem; color: #94a3b8;">CAPACITY</th>
                <th style="padding: 1rem; font-size: 0.85rem; color: #94a3b8;">FARE</th>
                <th style="padding: 1rem; font-size: 0.85rem; color: #94a3b8; text-align: right;">MANAGEMENT</th>
            </tr>
        </thead>
        <tbody>
            <?php if($buses && $buses->num_rows > 0): ?>
                <?php while($row = $buses->fetch_assoc()): ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 1.2rem 1rem;">
                        <div style="font-weight: 600; color: #6366f1;"><?php echo htmlspecialchars($row['bus_name']); ?></div>
                        <div style="font-size: 0.75rem; color: #64748b;">ID: #<?php echo $row['bus_number']; ?></div>
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        <div style="font-size: 0.9rem; font-weight: 500;"><?php echo htmlspecialchars($row['route']); ?></div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">👤 Driver: <?php echo htmlspecialchars($row['driver_name']); ?></div>
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        <span style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            <?php echo $row['available_seats']; ?> / <?php echo $row['total_seats']; ?> Seats
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1rem; font-weight: 700; color: #10b981;">RS <?php echo number_format($row['price']); ?></td>
                    <td style="padding: 1.2rem 1rem; text-align: right;">
                        <a href="book_ticket.php?id=<?php echo $row['id']; ?>" style="color: #6366f1; text-decoration: none; font-weight: bold;">Book</a>
                        <a href="edit_bus.php?id=<?php echo $row['id']; ?>" style="margin-left: 1rem; color: #f59e0b; text-decoration: none;">Edit</a>
                        <a href="delete_bus.php?id=<?php echo $row['id']; ?>" style="margin-left: 1rem; text-decoration: none;" onclick="return confirm('Confirm Deletion?')">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="padding: 2rem; text-align: center; color: #64748b;">No buses registered in the system yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>