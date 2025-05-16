<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "clinic_db");
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$result = $conn->query("SELECT doctor_id, name FROM doctors");
$doctors = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

echo json_encode($doctors);
$conn->close();
?>
