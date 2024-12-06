<?php
session_start();

// Check if the user is logged in, redirect to login if not
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusQueue Dashboard</title>
    <link rel="stylesheet" href="./style/user_dashboard.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            height: 100vh;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px 20px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar ul li a:hover {
            background: #34495e;
            padding-left: 30px;
            transition: 0.3s;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            background: #ecf0f1;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile i {
            font-size: 24px;
        }

        #map {
            width: 100%;
            height: 500px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>BusQueue</h2>
        <ul>
            <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="pages/route.php"><i class="fas fa-route"></i> Route</a></li>
            <li><a href="pages/aboutus.html"><i class="fas fa-info-circle"></i> About us</a></li>
            <li><a href="pages/feedback.html"><i class="fas fa-comments"></i> Feedback</a></li>
            <li><a href="./components/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="top-bar">
            <h1>Bus Route Map</h1>
            <div class="user-profile">
                <i class="fas fa-user-circle"></i>
                <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
            </div>
        </div>
        <div id="map"></div>
    </div>

    <script>
        // Initialize the map
        const map = L.map('map').setView([27.675855, 85.431662], 13); // Adjust coordinates and zoom level as needed

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Optional: Add a marker for demonstration
        
        L.Routing.control({
  waypoints: [
        L.latLng(27.675855, 85.431662), // Start point
        L.latLng(30.67685, 85.432662),
        //L.latLng(27.677855, 85.433662),
        L.latLng(27.675855, 85.434662) // Loop back to start point
        //L.latLng(27.675855, 85.431662)
  ]
}).addTo(map);
    </script>
</body>
</html>
