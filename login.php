<?php
session_start();
header('Content-Type: application/json');

$db = new mysqli('localhost', 'root', '', 'instantloan');
if ($db->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die(json_encode(['success' => false, 'message' => 'Username and password are required']));
}

$username = $db->real_escape_string($_POST['username']);
$password = $db->real_escape_string($_POST['password']);

$query = "SELECT id, username, password FROM admins WHERE username = '$username' LIMIT 1";
$result = $db->query($query);

if (!$result || $result->num_rows === 0) {
    die(json_encode(['success' => false, 'message' => 'Invalid username or password']));
}

$admin = $result->fetch_assoc();

if ($password === $admin['password']) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $db->query("UPDATE admins SET last_login = NOW() WHERE id = {$admin['id']}");
    echo json_encode(['success' => true, 'message' => 'Login successful']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}
?>