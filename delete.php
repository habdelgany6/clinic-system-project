<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: index.html");
        exit;
    } else {
        echo "Failed to delete appointment.";
    }
    $stmt->close();
} else {
    echo "Invalid appointment ID.";
}

$conn->close();
?>
