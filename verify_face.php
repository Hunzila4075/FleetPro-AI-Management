<?php
include 'db_connect.php';

// Set header to return JSON
header('Content-Type: application/json');

// Get the raw POST data from the browser
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['image'])) {
    // 1. Clean the Base64 string from the browser
    $imgData = $data['image'];
    $imgData = str_replace('data:image/jpeg;base64,', '', $imgData);
    $imgData = str_replace(' ', '+', $imgData);
    
    // 2. Convert and save as temp_capture.jpg
    $fileData = base64_decode($imgData);
    $filePath = 'temp_capture.jpg';
    
    if (file_put_contents($filePath, $fileData)) {
        
        // 3. Execute Python "One-Frame" script
        // Note: We use '2>&1' to catch any Python errors in the output
        $result = shell_exec("python verify_one_frame.py 2>&1");
        $result = trim($result);

        if ($result === "MATCH") {
            $_SESSION['user'] = 'Darwesh';
            $_SESSION['role'] = 'super'; // Match your database role
            echo json_encode(['success' => true, 'message' => 'Match Found!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No match', 'debug' => $result]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save temp image.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image data received.']);
}
?>