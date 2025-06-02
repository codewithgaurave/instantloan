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
$address = $db->real_escape_string($_POST['address']);
$phone_number = $db->real_escape_string($_POST['phone_number']); 

// Insert into database
$query = "INSERT INTO loans (
    application_date, customer_name, father_name, loan_type, 
    application_number, loan_amount, interest_rate, tenure, 
    emi, processing_fees, address, phone_number
) VALUES (
    '$application_date', '$customer_name', '$father_name', '$loan_type',
    '$application_number', $loan_amount, $interest_rate, $tenure,
    $emi, $processing_fees, '$address', '$phone_number'
)";

if ($db->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $db->error]);
}

$db->close();
?>