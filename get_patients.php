<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "clinic_db");
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$result = $conn->query("SELECT patient_id, name FROM patients");
$patients = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

echo json_encode($patients);
$conn->close();
?>
