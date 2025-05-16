<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO appointments (patient_name, doctor_name, appointment_date, status)
        VALUES 
        ('Ahmed Ali', 'Dr. Samir', '2025-05-20 10:00:00', 'Pending'),
        ('Sara Mahmoud', 'Dr. Mona', '2025-05-21 12:00:00', 'Confirmed'),
        ('Omar Adel', 'Dr. Hany', '2025-05-22 09:30:00', 'Pending')";

if ($conn->query($sql) === TRUE) {
    echo "Dummy data inserted successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
