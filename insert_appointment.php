<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['patient_id'], $data['doctor_id'], $data['date'])) {
    echo json_encode(["error" => "Missing patient_id, doctor_id, or date"]);
    exit;
}

$patient_id = intval($data['patient_id']);
$doctor_id = intval($data['doctor_id']);
$date = trim($data['date']);
$status = 'Scheduled';

if (!$patient_id || !$doctor_id || $date === '') {
    echo json_encode(["error" => "All fields are required."]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "clinic_db");

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $patient_id, $doctor_id, $date, $status);

if ($stmt->execute()) {
    echo json_encode(["success" => "Appointment booked successfully", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => "Error saving appointment: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
