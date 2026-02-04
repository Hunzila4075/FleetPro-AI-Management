<?php
include 'db_connect.php';
session_start();

echo "<h2>AI Gate Debugger</h2>";

// 1. Check Database Connection
if ($conn) {
    echo "✅ Database Connected.<br>";
} else {
    echo "❌ Database Connection Failed.<br>";
}

// 2. Check the ai_gate table
$result = $conn->query("SELECT * FROM ai_gate WHERE id = 1");
if ($row = $result->fetch_assoc()) {
    echo "✅ Row Found! Current Status in DB: <b>" . $row['status'] . "</b><br>";
    echo "<i>(If this is 0, Python hasn't updated it yet. If it's 1, PHP should be logging you in.)</i>";
} else {
    echo "❌ Row with ID 1 NOT FOUND in ai_gate table.<br>";
}

// 3. Check Session
echo "<h3>Session Info:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>