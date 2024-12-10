<?php
session_start();

// Check if the user is logged in, redirect to login if not
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Database connection
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'bus_queue';
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch route and bus data
$routeData = [];
$sql = "
    SELECT 
        rs.id AS schedule_id, 
        rs.route_id, 
        r.route_name, 
        r.start_lat, r.start_lng, r.end_lat, r.end_lng, 
        b.bus_name, 
        rs.departure_time 
    FROM route_schedule rs 
    JOIN route r ON rs.route_id = r.id 
    JOIN bus b ON rs.bus_id = b.id
";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $routeData[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusQueue Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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
            <li><a href="pages/route.php "><i class="fas fa-route"></i> Route</a></li>
            <li><a href="pages/aboutus.html"><i class="fas fa-info-circle"></i> About us</a></li>
            <li><a href="pages/feedback.html"><i class="fas fa-comments"></i> Feedback</a></li>
            <li><a href="pages/userdetails.php"><i class="fas fa-user"></i> Account</a></li>
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
</style>
</head>
<body>
    

    <script>
    // Initialize the map
    const map = L.map('map').setView([27.675855, 85.431662], 13);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Define a custom bus icon
    const busIcon = L.icon({
        iconUrl: 'ayus.png', // Adjust with your image path
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    // PHP data to JavaScript
    const routes = <?php echo json_encode($routeData); ?>;

    // Loop through each route and animate the bus along the route
    routes.forEach(route => {
        const startLatLng = [route.start_lat, route.start_lng];
        const endLatLng = [route.end_lat, route.end_lng];

        // Define route waypoints
        const routeWaypoints = [
            L.latLng(startLatLng),
            L.latLng(endLatLng),
            L.latLng(startLatLng) 
            // Add the starting point again for smooth looping
        ];
        const circularRoute = [
            L.latLng(27.706812, 85.314924), // Point A
          //Point C
            L.latLng(27.680855, 85.426662), // Point D
              // Back to Point A
        ];
        // Add routing control for the route
        const control = L.Routing.control({
            waypoints: routeWaypoints,
            routeWhileDragging: false,
            draggableWaypoints: false,
            addWaypoints: false,
            createMarker: function() { return null; } // Disable default markers
        }).addTo(map);

        // Add a custom bus marker
        const busMarker = L.marker(startLatLng, { icon: busIcon }).addTo(map);

        // Wait for the route to be calculated
        control.on('routesfound', function(e) {
            const routeCoordinates = e.routes[0].coordinates; // Get route coordinates
            let currentIndex = 0;

            // Function to move the bus marker
            function moveBus() {
                if (currentIndex < routeCoordinates.length) {
                    busMarker.setLatLng(routeCoordinates[currentIndex]); // Update position
                    currentIndex++;
                } else {
                    currentIndex = 0; // Loop back to the start smoothly
                }
            }

            // Start moving the bus with a delay between steps
            setInterval(moveBus, 500); // Adjust the interval for speed (500ms = moderate speed)
        });
    });
    
</script>

</body>
</html>