<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed");
}

$sql = "
DELETE a1 FROM appointments a1
JOIN appointments a2 
ON 
    a1.patient_name = a2.patient_name AND
    a1.doctor_name = a2.doctor_name AND
    a1.appointment_date = a2.appointment_date AND
    a1.id > a2.id
";

if ($conn->query($sql) === TRUE) {
    echo "Duplicate appointments deleted.";
} else {
    echo "Error deleting duplicates.";
}

$conn->close();
?>
