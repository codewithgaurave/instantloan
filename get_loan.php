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
$query = "SELECT * FROM loans WHERE id = $loan_id LIMIT 1";
$result = $db->query($query);

if ($result->num_rows === 1) {
    $loan = $result->fetch_assoc();
    echo json_encode($loan);
} else {
    echo json_encode(['success' => false, 'message' => 'Loan not found']);
}

$db->close();
?>