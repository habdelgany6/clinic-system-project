<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT 
            a.id,
            p.name AS patient_name,
            d.name AS doctor_name,
            a.appointment_date,
            a.status
        FROM appointments a
        JOIN patients p ON a.patient_id = p.patient_id
        JOIN doctors d ON a.doctor_id = d.doctor_id
        ORDER BY a.appointment_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment List</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #00796b;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #004d40;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #e0f2f1;
            color: #004d40;
        }

        tr:nth-child(even) {
            background-color: #f1f8f9;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>
<body>

<header>
    <h1>Appointments Overview</h1>
</header>

<div class="container">
    <a class="back-link" href="index.html">&larr; Back to Home</a>
    <h2>All Booked Appointments</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['patient_name']}</td>
                        <td>{$row['doctor_name']}</td>
                        <td>" . date('Y-m-d H:i', strtotime($row['appointment_date'])) . "</td>
                        <td>{$row['status']}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='empty'>No appointments found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

<footer>
    <p>&copy; 2025 Clinic Management System</p>
</footer>

</body>
</html>
