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
    // Get form data
    $bus_number = $_POST['bus_number'];
    $route = $_POST['route'];

    // Check if the bus number already exists in the buses table
    $check_sql = "SELECT COUNT(*) FROM buses WHERE bus_number = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $bus_number);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // If the bus number already exists, show an error message
        $error_message = "Error: The bus number '$bus_number' already exists.";
    } else {
        // Insert data into the buses table if no duplicates
        $sql = "INSERT INTO buses (bus_number, route) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            // Handle query preparation error
            $error_message = "Error preparing the insert query.";
        } else {
            $stmt->bind_param("ss", $bus_number, $route);
            if ($stmt->execute()) {
                // Redirect to the admin dashboard after successfully adding the bus
                header("Location: ../admin_dashboard.php");
                exit();
            } else {
                // Show a custom error message if there is an issue with the query
                $error_message = "Error: Unable to add bus. Please try again.";
            }

            // Close the statement
            $stmt->close();
        }
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

    <!-- Form to add bus without departure time -->
    <form method="POST">
        <label for="bus_number">Bus Number:</label>
        <input type="text" id="bus_number" name="bus_number" required>

        <label for="route">Route:</label>
        <input type="text" id="route" name="route" required>

        <button type="submit">Add Bus</button>
    </form>
    <button onclick="window.location.href='../admin_dashboard.php'" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
        Go to Admin Dashboard
    </button>
    <!-- Display error message if bus number already exists -->
    <?php if (isset($error_message)): ?>
        <div style="color: red;"><?= $error_message ?></div>
    <?php endif; ?>
</body>
</html>
