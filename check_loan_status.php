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
    echo json_encode([
        'success' => true, 
        'loan' => [
            'amount' => $loan['loan_amount'],
            'interest_rate' => $loan['interest_rate'],
            'tenure' => $loan['tenure'],
            'emi' => $loan['emi'],
            'processing_fee' => $loan['processing_fees'],
            'total_payable' => ($loan['emi'] * $loan['tenure']) + $loan['processing_fees'],
            'customer_name' => $loan['customer_name'],
            'application_number' => $loan['application_number']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No loan found with these details. Please contact admin.']);
}

$db->close();
?>