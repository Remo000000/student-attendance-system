<?php
$mysqli = new mysqli("localhost", "root", "", "attendance_system");

$result = $mysqli->query("SELECT * FROM attendance ORDER BY date DESC");

echo "<h2>Қатысқан студенттер тізімі</h2>";
echo "<table border='1'>
<tr><th>ID</th><th>Аты</th><th>Пән</th><th>Күні</th><th>Уақыты</th></tr>";

while($row = $result->fetch_assoc()) {
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['student_name']}</td>
    <td>{$row['subject']}</td>
    <td>{$row['date']}</td>
    <td>{$row['time']}</td>
    </tr>";
}

echo "</table>";
echo "<br><a href='export_excel.php'>⬇ Excel-ге жүктеу</a>";
?>