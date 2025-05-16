<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO patients (name, phone, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $email);
        if ($stmt->execute()) {
            header("Location: index.html"); // رجعه لصفحة الحجز
            exit;
        } else {
            $error = "Failed to add patient.";
        }
        $stmt->close();
    } else {
        $error = "Name is required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Patient</title>
    <style>
        body {
            font-family: Arial;
            padding: 40px;
            background-color: #f7f7f7;
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

        input {
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

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Patient</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="phone" placeholder="Phone Number">
        <input type="email" name="email" placeholder="Email Address">
        <button type="submit">Add Patient</button>
    </form>
</div>

</body>
</html>
