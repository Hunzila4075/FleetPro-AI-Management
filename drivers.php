<?php 
include 'db_connect.php'; 
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
include 'header.php'; 

// Fetch all drivers
$drivers = $conn->query("SELECT * FROM drivers ORDER BY full_name ASC");
?>

<header class="top-bar">
    <div>
        <h1 style="margin: 0; font-size: 2rem; font-weight: 800; color: #0f172a;">Driver Crew</h1>
        <p style="color: var(--text-secondary); margin: 4px 0 0 0;">Manage your fleet personnel and contact information.</p>
    </div>
    <a href="add_driver.php" class="btn-action" style="background: #0f172a;">+ Add Crew Member</a>
</header>

<div style="background: white; border-radius: 24px; border: 1px solid var(--border-subtle); box-shadow: var(--shadow-md); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-subtle);">
                <th style="padding: 20px 30px; text-align: left; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Driver Name</th>
                <th style="padding: 20px 30px; text-align: left; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Contact Info</th>
                <th style="padding: 20px 30px; text-align: left; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">License ID</th>
                <th style="padding: 20px 30px; text-align: left; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Status</th>
                <th style="padding: 20px 30px; text-align: right; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $drivers->fetch_assoc()): ?>
            <tr style="border-bottom: 1px solid var(--border-subtle); transition: 0.2s;" onmouseover="this.style.background='#fcfdfe'">
                <td style="padding: 24px 30px;">
                    <div style="font-weight: 700; color: #0f172a;"><?php echo htmlspecialchars($row['full_name']); ?></div>
                </td>
                <td style="padding: 24px 30px;">
                    <div style="font-weight: 500; font-size: 0.9rem;"><?php echo htmlspecialchars($row['phone_number']); ?></div>
                </td>
                <td style="padding: 24px 30px;">
                    <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px;"><?php echo htmlspecialchars($row['license_number']); ?></code>
                </td>
                <td style="padding: 24px 30px;">
                    <?php 
                        $status_color = $row['status'] == 'Active' ? '#059669' : '#e11d48';
                        $status_bg = $row['status'] == 'Active' ? '#ecfdf5' : '#fff1f2';
                    ?>
                    <span style="background: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800;">
                        <?php echo $row['status']; ?>
                    </span>
                </td>
                <td style="padding: 24px 30px; text-align: right;">
                    <a href="edit_driver.php?id=<?php echo $row['id']; ?>" style="color: var(--brand-primary); text-decoration: none; font-weight: 700;">Edit</a>
                </td>
            </tr>
            <?php endwhile; if($drivers->num_rows == 0): ?>
                <tr><td colspan="5" style="padding: 40px; text-align: center; color: var(--text-secondary);">No crew members found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>