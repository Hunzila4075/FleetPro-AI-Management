<?php 
include 'db_connect.php'; 

// Handle Manual Login
$error = "";
if (isset($_POST['manual_login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $res = $conn->query($query);
    if ($res && $res->num_rows > 0) {
        $user_data = $res->fetch_assoc();
        $_SESSION['user'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role']; 
        header("Location: index.php"); 
        exit;
    } else { $error = "❌ Invalid Username or Password"; }
}

// Handle Background AI Launch
if (isset($_GET['launch_ai'])) {
    $cmd = "start /B python C:/laragon/www/bus_system/ai_face_lock.py";
    pclose(popen($cmd, "r"));
    echo json_encode(["status" => "launched"]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BUS SYSTEM | Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --ai: #10b981; --bg: #0f172a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: white; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #1e293b; padding: 2.5rem; border-radius: 20px; width: 350px; text-align: center; border: 1px solid #334155; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        h2 { margin-bottom: 20px; letter-spacing: -1px; }
        .input-group { margin-bottom: 15px; text-align: left; }
        input { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: white; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-primary { background: var(--primary); color: white; margin-top: 10px; }
        .btn-ai { background: transparent; border: 1px solid var(--ai); color: var(--ai); margin-top: 20px; }
        .btn-ai:hover { background: rgba(16, 185, 129, 0.1); }
        .error { color: #ef4444; font-size: 0.8rem; margin-bottom: 15px; }
        #status { display: none; margin-top: 15px; color: var(--ai); font-size: 0.9rem; font-weight: bold; animation: blink 1.5s infinite; }
        @keyframes blink { 50% { opacity: 0.5; } }
    </style>
</head>
<body>
<div class="card">
    <h2>🚍 BUS SYSTEM</h2>
    <h3><b>☽ᴅⱥrͥWeͣsͫђ☾</b></h3>
    <?php if($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST" action="login.php">
        <div class="input-group"><input type="text" name="username" placeholder="Username" required></div>
        <div class="input-group"><input type="password" name="password" placeholder="Password" required></div>
        <button type="submit" name="manual_login" class="btn btn-primary">Sign In Manually</button>
    </form>
    <div style="margin: 20px 0; color: #475569; font-size: 0.8rem;">OR</div>
    <button id="ai-btn" class="btn btn-ai">📷 BIOMETRIC UNLOCK</button>
    <div id="status">🔍 SCANNING FACE...</div>
</div>
<script>
document.getElementById('ai-btn').onclick = function() {
    const status = document.getElementById('status');
    status.style.display = 'block';
    this.disabled = true;
    this.innerText = "ENGINE STARTING...";
    fetch('login.php?launch_ai=1').then(() => {
        const checker = setInterval(() => {
            fetch('check_ai_gate.php').then(res => res.json()).then(data => {
                if (data.status == 1) {
                    status.innerText = "✅ SUCCESS! LOGGING IN...";
                    clearInterval(checker);
                    setTimeout(() => { window.location.href = "index.php"; }, 1000);
                }
            });
        }, 1500);
    });
};
</script>
</body>
</html>