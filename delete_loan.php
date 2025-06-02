<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'instantloan');

$loan_id = $db->real_escape_string($_GET['id']);
$query = "DELETE FROM loans WHERE id = $loan_id";

if ($db->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $db->error]);
}

$db->close();
?>