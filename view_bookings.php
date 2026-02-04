<?php 
include 'db_connect.php'; 
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
include 'header.php'; 

// 1. Advanced SQL Join to get Bus Details along with Bookings
$sql = "SELECT b.*, bus.bus_name, bus.route, bus.price, bus.bus_number 
        FROM bookings b 
        JOIN buses bus ON b.bus_id = bus.id 
        ORDER BY b.id DESC";

$result = $conn->query($sql);

// 2. Summary Statistics for the Header
$stats = $conn->query("SELECT COUNT(*) as total_tix, SUM(bus.price) as revenue 
                       FROM bookings b 
                       JOIN buses bus ON b.bus_id = bus.id")->fetch_assoc();
?>

<div class="top-bar">
    <div>
        <h1 style="margin: 0; font-size: 2rem; font-weight: 800; color: #0f172a;">Reservations</h1>
        <p style="color: var(--text-secondary); margin-top: 5px;">Manage passenger manifests and ticket sales.</p>
    </div>
    
    <div style="display: flex; gap: 15px;">
        <div style="background: white; padding: 10px 20px; border-radius: 12px; border: 1px solid var(--border-subtle); text-align: center;">
            <small style="color: var(--text-secondary); font-weight: 700; text-transform: uppercase; font-size: 0.65rem;">Total Bookings</small>
            <div style="font-weight: 800; color: var(--brand-primary);"><?php echo $stats['total_tix']; ?></div>
        </div>
        <div style="background: #ecfdf5; padding: 10px 20px; border-radius: 12px; border: 1px solid #10b981; text-align: center;">
            <small style="color: #065f46; font-weight: 700; text-transform: uppercase; font-size: 0.65rem;">Revenue</small>
            <div style="font-weight: 800; color: #059669;">PKR <?php echo number_format($stats['revenue'] ?? 0); ?></div>
        </div>
    </div>
</div>

<div style="background: white; border-radius: 20px; border: 1px solid var(--border-subtle); box-shadow: var(--shadow-md); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
        <thead style="background: #f8fafc;">
            <tr>
                <th style="padding: 1.25rem 2rem; text-align: left; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Passenger</th>
                <th style="padding: 1.25rem 2rem; text-align: left; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Voyage Details</th>
                <th style="padding: 1.25rem 2rem; text-align: center; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Seat</th>
                <th style="padding: 1.25rem 2rem; text-align: left; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                <th style="padding: 1.25rem 2rem; text-align: right; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr style="border-bottom: 1px solid var(--border-subtle); transition: 0.2s;" onmouseover="this.style.background='#fcfdfe'">
                    
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 700; color: #0f172a;"><?php echo htmlspecialchars($row['passenger_name']); ?></div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);"><?php echo htmlspecialchars($row['passenger_phone']); ?></div>
                    </td>

                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($row['bus_name']); ?></div>
                        <div style="font-size: 0.8rem; color: var(--brand-primary); font-weight: 500;"><?php echo htmlspecialchars($row['route']); ?></div>
                    </td>

                    <td style="padding: 1.5rem 2rem; text-align: center;">
                        <span style="display: inline-block; background: #f1f5f9; color: #475569; padding: 5px 12px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; border: 1px solid #e2e8f0;">
                            <?php echo $row['seat_number']; ?>
                        </span>
                    </td>

                    <td style="padding: 1.5rem 2rem;">
                        <?php 
                            $paid = ($row['payment_status'] == 'Paid Cash');
                            $color = $paid ? '#059669' : '#d97706';
                            $bg = $paid ? '#ecfdf5' : '#fffbe3';
                        ?>
                        <span style="background: <?php echo $bg; ?>; color: <?php echo $color; ?>; padding: 6px 14px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                            <?php echo $paid ? '● Paid' : '○ Pending'; ?>
                        </span>
                    </td>

                    <td style="padding: 1.5rem 2rem; text-align: right;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <a href="print_ticket.php?id=<?php echo $row['id']; ?>" target="_blank" title="Print Ticket" style="padding: 8px; background: #eff6ff; color: #2563eb; border-radius: 8px; text-decoration: none;">🖨️</a>
                            <a href="edit_booking.php?id=<?php echo $row['id']; ?>" title="Edit" style="padding: 8px; background: #f8fafc; color: #64748b; border-radius: 8px; text-decoration: none; border: 1px solid #e2e8f0;">⚙️</a>
                            <a href="delete_booking.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this reservation?')" title="Delete" style="padding: 8px; background: #fff1f2; color: #e11d48; border-radius: 8px; text-decoration: none;">🗑️</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="padding: 5rem; text-align: center;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                        <h3 style="margin: 0; color: var(--text-secondary);">No Bookings Found</h3>
                        <p style="color: #94a3b8;">Passenger records will appear here once tickets are issued.</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>