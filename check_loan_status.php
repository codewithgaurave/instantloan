<?php
session_start();
header('Content-Type: application/json');

// Database connection
$db = new mysqli('localhost', 'root', '', 'instantloan');

// Get form data
$mobile_number = $db->real_escape_string($_POST['mobile_number']);
$application_number = $db->real_escape_string($_POST['application_number']);

// Check if loan exists
$query = "SELECT * FROM loans WHERE phone_number = '$mobile_number' AND application_number = '$application_number' LIMIT 1";
$result = $db->query($query);

if ($result->num_rows === 1) {
    $loan = $result->fetch_assoc();
    echo json_encode(['success' => true, 'loan' => $loan]);
} else {
    echo json_encode(['success' => false, 'message' => 'No loan found with these details. Please contact admin.']);
}

$db->close();
?>