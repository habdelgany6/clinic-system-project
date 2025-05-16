<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// استعلام يجلب بيانات الموعد + اسم المريض + اسم الدكتور باستخدام join
$sql = "SELECT 
            a.id,
            p.name AS patient_name,
            d.name AS doctor_name,
            a.appointment_date,
            a.status
        FROM appointments a
        JOIN patients p ON a.patient_id = p.patient_id
        JOIN doctors d ON a.doctor_id = d.doctor_id
        ORDER BY a.appointment_date ASC";

$result = $conn->query($sql);

$appointments = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

echo json_encode($appointments);

$conn->close();
?>
