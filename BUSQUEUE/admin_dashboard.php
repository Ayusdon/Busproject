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

// Fetch available routes for the dropdown
$route_result = $conn->query("SELECT id, route_name FROM route");
$route_available = $route_result->fetch_all(MYSQLI_ASSOC);

// Fetch available buses for the dropdown
$bus_result = $conn->query("SELECT id, bus_name FROM bus");
$buses_available = $bus_result->fetch_all(MYSQLI_ASSOC);

// Handle the addition of a new bus route schedule
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bus_id'])) {
    $bus_id = $_POST['bus_id'];
    $route_id = $_POST['route_id'];
    $departure_time = $_POST['departure_time'];

    // Validate if the bus is already assigned at the same time
    $stmt = $conn->prepare("SELECT COUNT(*) FROM route_schedule WHERE bus_id = ? AND departure_time = ?");
    $stmt->bind_param("is", $bus_id, $departure_time);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Show error if duplicate entry found
        $error_message = "Error: The bus is already assigned to a route at the same time.";
    } else {
        // Insert new data into the route_schedule table
        $stmt = $conn->prepare("INSERT INTO route_schedule (bus_id, route_id, departure_time) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $bus_id, $route_id, $departure_time);
        $stmt->execute();
        $stmt->close();

        // Redirect to refresh the page after adding bus
        header("Location: admin_dashboard.php");
        exit();
    }
}

// Handle route schedule deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM route_schedule WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page after deletion
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch all bus routes
$result = $conn->query("
    SELECT 
        rs.id, 
        b.bus_name, 
        r.route_name, 
        rs.departure_time 
    FROM route_schedule rs
    JOIN bus b ON rs.bus_id = b.id
    JOIN route r ON rs.route_id = r.id
    ORDER BY rs.departure_time
");
$buses = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bus Routes</title>
    <link rel="stylesheet" href="./style/admin.css">
</head>
<body>
    <h2>Admin Dashboard - Add Bus to Queue</h2>
    
    <!-- Form to add bus route -->
    <form method="POST">
        <label for="route_id">Bus Route:</label>
        <select id="route_id" name="route_id" required>
            <option value="">-- Select Route --</option>
            <?php foreach ($route_available as $route): ?>
                <option value="<?= htmlspecialchars($route['id']) ?>"><?= htmlspecialchars($route['route_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="bus_id">Bus Name:</label>
        <select id="bus_id" name="bus_id" required>
            <option value="">-- Select Bus --</option>
            <?php foreach ($buses_available as $bus): ?>
                <option value="<?= htmlspecialchars($bus['id']) ?>"><?= htmlspecialchars($bus['bus_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="departure_time">Departure Time:</label>
        <input type="time" id="departure_time" name="departure_time" required>

        <button type="submit">Deploy Bus</button>
        <a href="pages/add_bus.php">Add New Bus and Route</a>
    </form>

    <!-- Display error message -->
    <?php if (!empty($error_message)): ?>
        <div style="color: red;"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <h3>Current Bus Routes</h3>
    <table>
        <thead>
            <tr>
                <th>Bus Name</th>
                <th>Route</th>
                <th>Departure Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buses as $bus): ?>
                <tr>
                    <td><?= htmlspecialchars($bus['bus_name']) ?></td>
                    <td><?= htmlspecialchars($bus['route_name']) ?></td>
                    <td><?= htmlspecialchars($bus['departure_time']) ?></td>
                    <td>
                        <a href="?delete_id=<?= $bus['id'] ?>" onclick="return confirm('Are you sure you want to delete this route?');">
                            <button>Delete</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="components/logout.php">Logout</a>
</body>
</html>
