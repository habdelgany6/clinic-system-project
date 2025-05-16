<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$patient = $_POST['patient'] ?? '';
$doctor = $_POST['doctor'] ?? '';
$date = $_POST['date'] ?? '';

if ($patient && $doctor && $date) {
    $stmt = $conn->prepare("INSERT INTO appointments (patient_name, doctor_name, appointment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $patient, $doctor, $date);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Appointment booked successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color: red;'>Missing input values!</p>";
}

$conn->close();
?>
