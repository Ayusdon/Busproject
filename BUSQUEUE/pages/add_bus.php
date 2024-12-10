<?php
session_start();

// Check if the user is logged in and has admin privileges
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "bus_queue");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all required fields are set and not empty
    if (isset($_POST['bus_name']) && !empty($_POST['bus_name']) &&
        isset($_POST['route_name']) && !empty($_POST['route_name']) &&
        isset($_POST['start_lat']) && !empty($_POST['start_lat']) &&
        isset($_POST['start_lng']) && !empty($_POST['start_lng']) &&
        isset($_POST['end_lat']) && !empty($_POST['end_lat']) &&
        isset($_POST['end_lng']) && !empty($_POST['end_lng'])) {

        // Get form data
        $bus_name = $_POST['bus_name'];  // Bus name input
        $route_name = $_POST['route_name'];  // Route name input
        $start_lat = $_POST['start_lat'];    // Start latitude
        $start_lng = $_POST['start_lng'];    // Start longitude
        $end_lat = $_POST['end_lat'];        // End latitude
        $end_lng = $_POST['end_lng'];        // End longitude

        // Replace commas with periods for latitude and longitude values
        $start_lat = str_replace(",", ".", $start_lat);
        $start_lng = str_replace(",", ".", $start_lng);
        $end_lat = str_replace(",", ".", $end_lat);
        $end_lng = str_replace(",", ".", $end_lng);

        // Step 1: Check if the bus already exists
        $bus_check_sql = "SELECT COUNT(*) FROM bus WHERE bus_name = ?";
        $bus_check_stmt = $conn->prepare($bus_check_sql);
        $bus_check_stmt->bind_param("s", $bus_name);
        $bus_check_stmt->execute();
        $bus_check_stmt->bind_result($bus_exists);
        $bus_check_stmt->fetch();
        $bus_check_stmt->close();

        if ($bus_exists > 0) {
            // If the bus exists, show an error message
            $error_message = "Error: The bus name '$bus_name' already exists.";
        } else {
            // Step 2: Insert the route into the route table
            $route_insert_sql = "INSERT INTO route (route_name, start_lat, start_lng, end_lat, end_lng) VALUES (?, ?, ?, ?, ?)";
            $route_stmt = $conn->prepare($route_insert_sql);
            $route_stmt->bind_param("sdddd", $route_name, $start_lat, $start_lng, $end_lat, $end_lng);
            if ($route_stmt->execute()) {
                // Step 3: Get the newly inserted route ID
                $route_id = $conn->insert_id;

                // Step 4: Insert the bus into the bus table
                $bus_insert_sql = "INSERT INTO bus (bus_name) VALUES (?)"; // Insert only bus_name since no route_id
                $bus_stmt = $conn->prepare($bus_insert_sql);
                $bus_stmt->bind_param("s", $bus_name);
                if ($bus_stmt->execute()) {
                    // Redirect to the admin dashboard after successfully adding the bus
                    header("Location: ../admin_dashboard.php");
                    exit();
                } else {
                    // Show a custom error message if there is an issue with the bus insertion
                    $error_message = "Error: Unable to add bus. Please try again.";
                }

                // Close the bus insert statement
                $bus_stmt->close();
            } else {
                // Show an error message if the route insertion fails
                $error_message = "Error: Unable to add route. Please try again.";
            }

            // Close the route insert statement
            $route_stmt->close();
        }
    } else {
        // If any field is missing or empty, show an error
        $error_message = "All fields are required. Please fill in all the details.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/add_bus.css">
    <title>Add Bus</title>
</head>
<body>
    <h2>Add New Bus</h2>

    <!-- Form to add bus and route -->
    <form method="POST">
        <label for="bus_name">Bus Name:</label>
        <input type="text" id="bus_name" name="bus_name" required>

        <label for="route_name">Route Name:</label>
        <input type="text" id="route_name" name="route_name" required>

        <label for="start_lat">Start Latitude:</label>
        <input type="text" id="start_lat" name="start_lat" required>

        <label for="start_lng">Start Longitude:</label>
        <input type="text" id="start_lng" name="start_lng" required>

        <label for="end_lat">End Latitude:</label>
        <input type="text" id="end_lat" name="end_lat" required>

        <label for="end_lng">End Longitude:</label>
        <input type="text" id="end_lng" name="end_lng" required>

        <button type="submit">Add Bus</button>
    </form>
    <button onclick="window.location.href='../admin_dashboard.php'" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
        Go to Admin Dashboard
    </button>

    <!-- Display error message if bus name already exists -->
    <?php if (isset($error_message)): ?>
        <div style="color: red;"><?= $error_message ?></div>
    <?php endif; ?>
</body>
</html>
