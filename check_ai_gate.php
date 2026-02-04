<?php
include 'db_connect.php'; 
$response = ['status' => 0];
$result = $conn->query("SELECT status FROM ai_gate WHERE id = 1 LIMIT 1");
if ($row = $result->fetch_assoc()) {
    if ($row['status'] == 1) {
        $_SESSION['user'] = 'Darwesh'; 
        $_SESSION['role'] = 'admin'; 
        $conn->query("UPDATE ai_gate SET status = 0 WHERE id = 1");
        $response['status'] = 1;
    }
}
header('Content-Type: application/json');
echo json_encode($response);
?>