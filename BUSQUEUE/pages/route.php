<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
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
    <link rel="stylesheet" href="../style/routes.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>Routes Information</h2>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here are the available bus routes.</p>
        </header>

        <!-- Table for Routes -->
        <section>
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
        </section>

        <!-- Back Button -->
        <div>
        <button onclick="window.location.href='../user_dashboard.php'" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
        Go to Admin Dashboard
    </button>
        </div>
    </div>
</body>
</html>
