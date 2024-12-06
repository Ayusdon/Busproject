<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "bus_queue");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from routes table
$sql = "SELECT id, bus_number, route, departure_time FROM routes ORDER BY departure_time";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching data: " . $conn->error);
}

// Fetch all rows
$routes_data = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Routes Data</title>
    <link rel="stylesheet" href="../style/route.css">
</head>
<body>
    <h2>Routes Information</h2>
    <table>
        <thead>
            <tr>
                <th>Bus Number</th>
                <th>Route Name</th>
                <th>Departure Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($routes_data as $route): ?>
                <tr>
                    <td><?= htmlspecialchars($route['bus_number']) ?></td>
                    <td><?= htmlspecialchars($route['route']) ?></td>
                    <td><?= htmlspecialchars($route['departure_time']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
