<?php
session_start();

// Егер қолданушы кірмеген болса, логин бетіне жібереді
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffe3f0;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #ff7bac;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .content {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff4d88;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #ff3366;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Dashboard</h1>
    </div>
    <div class="content">
        <h2>Қош келдіңіз, студент!</h2>
        <p>Сіз сәтті кірдіңіз. Мұнда QR кодты сканерлеп, қатысуыңызды белгілей аласыз.</p>
        <a href="scan_qr.php" class="btn">QR кодты сканерлеу</a>
    </div>
</body>
</html>