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
if (!$id) {
    echo "Invalid appointment ID.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'] ?? '';
    $doctor_id = $_POST['doctor_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $status = $_POST['status'] ?? '';

    if ($patient_id && $doctor_id && $date && $status) {
        $stmt = $conn->prepare("UPDATE appointments SET patient_id = ?, doctor_id = ?, appointment_date = ?, status = ? WHERE id = ?");
        $stmt->bind_param("iissi", $patient_id, $doctor_id, $date, $status, $id);
        if ($stmt->execute()) {
            header("Location: index.html");
            exit;
        } else {
            echo "Error updating appointment.";
        }
        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

$sql = "SELECT * FROM appointments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();
$stmt->close();

if (!$appointment) {
    echo "Appointment not found.";
    exit;
}

// load patients and doctors
$patients = $conn->query("SELECT patient_id, name FROM patients")->fetch_all(MYSQLI_ASSOC);
$doctors = $conn->query("SELECT doctor_id, name FROM doctors")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
    <style>
        body {
            font-family: Arial;
            padding: 40px;
            background-color: #f2f2f2;
        }
        .form-container {
            max-width: 500px;
            background: white;
            padding: 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #00796b;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #00796b;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Appointment</h2>
    <form method="POST">
        <select name="patient_id" required>
            <option value="">Select Patient</option>
            <?php foreach ($patients as $p): ?>
                <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id'] == $appointment['patient_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="doctor_id" required>
            <option value="">Select Doctor</option>
            <?php foreach ($doctors as $d): ?>
                <option value="<?= $d['doctor_id'] ?>" <?= $d['doctor_id'] == $appointment['doctor_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="datetime-local" name="date" value="<?= date('Y-m-d\TH:i', strtotime($appointment['appointment_date'])) ?>" required>

        <select name="status" required>
            <option value="Scheduled" <?= $appointment['status'] === 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
            <option value="Pending" <?= $appointment['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Confirmed" <?= $appointment['status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
            <option value="Cancelled" <?= $appointment['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>

        <button type="submit">Update Appointment</button>
    </form>
</div>

</body>
</html>
