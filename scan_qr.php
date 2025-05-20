<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>QR код сканер</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body {
            font-family: Arial;
            background-color: #ffe3f0;
            text-align: center;
            padding: 20px;
        }
        #reader {
            width: 300px;
            margin: auto;
        }
        .status {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>QR кодты сканерлеу</h2>
<div id="reader"></div>
<div class="status" id="status">Камера іске қосылды...</div>

<script>
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // Радиус земли (м)
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2 - lat1) * Math.PI/180;
        const Δλ = (lon2 - lon1) * Math.PI/180;

        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                  Math.cos(φ1) * Math.cos(φ2) *
                  Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        return R * c; // Қашықтық (метр)
    }

    const html5QrCode = new Html5Qrcode("reader");

    function onScanSuccess(qrMessage) {
    document.getElementById("status").innerText = "QR оқылды, геолокация тексерілуде...";

    const qrData = JSON.parse(qrMessage);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            const qrLat = parseFloat(qrData.lat);
            const qrLng = parseFloat(qrData.lng);

            const distance = calculateDistance(userLat, userLng, qrLat, qrLng);

            if (distance <= 50) {
                fetch("record_attendance.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        lesson: qrData.lesson,
                        teacher_id: qrData.teacher_id
                    })
                })
                .then(res => res.text())
                .then(response => {
                    document.getElementById("status").innerText = "✅ Сәтті сканерленді. Қатысу тіркелді!";
                });
            } else {
                document.getElementById("status").innerText = "❌ Сіз сабақ орнынан алыссыз. Қатысу тіркелмеді.";
            }

        }, function(error) {
            document.getElementById("status").innerText = "❌ Геолокация рұқсаты берілмеді.";
        });
    } else {
        document.getElementById("status").innerText = "❌ Геолокация қолдау көрсетілмейді.";
    }

    html5QrCode.stop(); // Сканерлеуді тоқтату
}

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 250
        },
        onScanSuccess
    ).catch(err => {
        document.getElementById("status").innerText = "Камера қосу сәтсіз: " + err;
    });
</script>

</body>
</html>