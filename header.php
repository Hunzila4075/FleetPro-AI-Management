<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FleetPro Admin | Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f172a; /* Deep Navy */
            --accent: #3b82f6;  /* Electric Blue */
            --success: #10b981; /* Emerald */
            --danger: #ef4444;  /* Rose */
            --bg: #f8fafc;      /* Soft Slate */
            --card-bg: #ffffff;
            --super: #f59e0b;   /* Amber for Super Admin */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: #1e293b;
            margin: 0;
            line-height: 1.5;
        }

        /* Modern Navigation */
        nav {
            background: var(--primary);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .logo-area { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            color: white; 
            font-weight: 800; 
            font-size: 1.2rem;
            letter-spacing: -0.5px;
        }

        .nav-links { display: flex; align-items: center; gap: 1rem; }
        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .nav-links a:hover { background: rgba(255,255,255,0.1); color: white; }
        
        /* Highlight for Super Admin */
        .super-link { border: 1px solid var(--super); color: var(--super) !important; }
        .super-link:hover { background: var(--super) !important; color: white !important; }

        .logout-btn { background: var(--danger); color: white !important; padding: 0.5rem 1.2rem !important; margin-left: 10px; }

        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }

        /* KPI Cards for Super Admin */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .stat-card h3 { margin: 0; color: var(--text-secondary); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-card .value { font-size: 1.8rem; font-weight: 800; margin-top: 0.5rem; display: block; }
    </style>
</head>
<body>
    <nav>
        <div class="logo-area">
            <span style="background: var(--accent); padding: 5px 10px; border-radius: 6px;">🚍</span>
            <span>FLEETPRO</span>
        </div>
        <div class="nav-links">
            <a href="super_admin.php" class="super-link">👑 Super Admin</a>
            <a href="index.php">Dashboard</a>
            <a href="view_bookings.php">Bookings</a>
            <a href="drivers.php">Crew</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
    <div class="container">