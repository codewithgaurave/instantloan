<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'instantloan');

// Get form data
$loan_id = $db->real_escape_string($_POST['loan_id']);
$application_date = $db->real_escape_string($_POST['application_date']);
$customer_name = $db->real_escape_string($_POST['customer_name']);
$father_name = $db->real_escape_string($_POST['father_name']);
$loan_type = $db->real_escape_string($_POST['loan_type']);
$application_number = $db->real_escape_string($_POST['application_number']);
$loan_amount = $db->real_escape_string($_POST['loan_amount']);
$interest_rate = $db->real_escape_string($_POST['interest_rate']);
$tenure = $db->real_escape_string($_POST['tenure']);
$emi = $db->real_escape_string($_POST['emi']);
$processing_fees = $db->real_escape_string($_POST['processing_fees']);
$phone_number = $db->real_escape_string($_POST['phone_number']); // New field
$address = $db->real_escape_string($_POST['address']);

// Update database
$query = "UPDATE loans SET
    application_date = '$application_date',
    customer_name = '$customer_name',
    father_name = '$father_name',
    loan_type = '$loan_type',
    application_number = '$application_number',
    loan_amount = $loan_amount,
    interest_rate = $interest_rate,
    tenure = $tenure,
    emi = $emi,
    processing_fees = $processing_fees,
    phone_number = '$phone_number',
    address = '$address',
    updated_at = NOW()
WHERE id = $loan_id";


if ($db->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $db->error]);
}

$db->close();
?>