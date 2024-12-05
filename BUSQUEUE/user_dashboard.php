<?php
session_start();

// Check if the user is logged in, redirect to login if not
if   (!isset($_SESSION['username'])) {
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
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <div class="dashboard">
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
                <h1 style="text-align: center;">Bus Route Map</h1>
                <div class="user-profile">
                    <i class="fas fa-user-circle"></i>
                    <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
                </div>
            </div>

            <!-- Bus Route Data Section -->
            

            <!-- Bus Route Map -->
            <div id="map" style="height: 500px;"></div>
        </div>
    </div>

    
    <script>
        let markers = []; // Array to store markers
        let polyline; // Store the polyline (route line)

        // Function to fetch and display routes
        function fetchRoutes() {

            // ============json data fetching from routes.php=====================
            fetch('./route/routes.php') // Fetch route data from routes.php  
                .then(response => response.json())
                .then(routes => {
                    const tableBody = document.getElementById('routeTable').querySelector('tbody');
                    tableBody.innerHTML = ''; // Clear existing rows
                    markers.forEach(marker => marker.remove()); // Remove old markers
                    markers = []; // Clear markers array
                    if (polyline) {
                        polyline.remove(); // Remove old route line
                    }

                    // Loop through the routes and create table rows
                    routes.forEach(route => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${route.route_name}</td>
                            <td>${route.departure}</td>
                            <td>${route.arrival}</td>
                            <td>${route.status}</td>
                            <td>${route.stops[route.stops.length - 1].estimated_time}</td> <!-- Last stop's time -->
                        `;
                        tableBody.appendChild(row);

                        // Plot the bus stops on the map
                       

                      
                    });
                })
                .catch(error => {
                    console.error('Error fetching route data:', error);
                });
        }

    
        fetchRoutes();

    

        // Initialize the map (Leaflet.js)
        const map = L.map('map').setView([27.675855, 85.431662], 13); // Default coordinates (adjust as needed)

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        //_____________//
        
//
    var path1Coordinates = [
    [27.675855, 85.431662],
    [27.675840, 85.431577],
    [27.675826, 85.431549],
    [27.675807, 85.431496],
    [27.675790, 85.431405],
    [27.675764, 85.431317],
    [27.675738, 85.431238],
    [27.675696, 85.431118],
    [27.675664, 85.431029],
    [27.675636, 85.430936],
    [27.675614, 85.430870],
    [27.675578, 85.430752],
    [27.675555, 85.430673],
    [27.675528, 85.430586],
    [27.675502, 85.430478],
    [27.675475, 85.430366],
    [27.675447, 85.430259],
    [27.675428, 85.430152],
    [27.675414, 85.430074],
    [27.675395, 85.429985],
    [27.675374, 85.429812],
    [27.675368, 85.429729],
    [27.675359, 85.429649],
    [27.675348, 85.429563],
    [27.675334, 85.429451],
    [27.675324, 85.429373],
    [27.675316, 85.429325],
    [27.675307, 85.429232],
    [27.675296, 85.429142],
    [27.675284, 85.429040],
    [27.675266, 85.428928],
    [27.675249, 85.428833],
    [27.675237, 85.428745],
    [27.675226, 85.428710],
    [27.675208, 85.428631],
    [27.675187, 85.428540],
    [27.675165, 85.428464],
    [27.675137, 85.428361],
    [27.675120, 85.428291],
    [27.675097, 85.428209],
    [27.675071, 85.428123],
    [27.674987, 85.427861],
    [27.674948, 85.427793],
    [27.674924, 85.427755],
    [27.674887, 85.427681],
    [27.674841, 85.427592],
    [27.674816, 85.427528],
    [27.674792, 85.427421],
    [27.674785, 85.427354],
    [27.674787, 85.427283],
    [27.674787, 85.427190],
    [27.674796, 85.427070],
    [27.674834, 85.426851],
    [27.674836, 85.426857],
    [27.674877, 85.426665],
    [27.674912, 85.426496],
    [27.674969, 85.426274],
    [27.675016, 85.426060],
    [27.675155, 85.425340],
    [27.675288, 85.424466],
    [27.675428, 85.423692],
    [27.675446, 85.422814],
    [27.675364, 85.422367],
    [27.675362, 85.421783],
    [27.675505, 85.421229],
    [27.675627, 85.420876],
    [27.675782, 85.420317],
    [27.675846, 85.419574],
    [27.675851, 85.419017],
    [27.675932, 85.418330],
    [27.676006, 85.417752],
    [27.676108, 85.417028],
    [27.676113, 85.416368],
    [27.676063, 85.415838],
    [27.676027, 85.415249],
    [27.675966, 85.414866],
    [27.675898, 85.414411],
    [27.675810, 85.413899],
    [27.675746, 85.413395],
    [27.675679, 85.412652],
    [27.675646, 85.412330],
    [27.675605, 85.411852],
    [27.675544, 85.411260],
    [27.675418, 85.410525],
    [27.675330, 85.410042],
    [27.675223, 85.409487],
    [27.675169, 85.409299],
    [27.675066, 85.409323],
    [27.674967, 85.409388],
    [27.674720, 85.409490],
    [27.674435, 85.409637],
    [27.674192, 85.409715],
    [27.673921, 85.409661],
    [27.673559, 85.409496],
    [27.673164, 85.409246],
    [27.672681, 85.408958],
    [27.672203, 85.408925],
    [27.671590, 85.408991],
    [27.671155, 85.409268],
    [27.670683, 85.409760],
    [27.670224, 85.410380],
    [27.670002, 85.410851],
    [27.669643, 85.411359],
    [27.669317, 85.412153],
    [27.669081, 85.412629],
    [27.668663, 85.413565],
    [27.668245, 85.414440],
    [27.667870, 85.415233],
    [27.667247, 85.416585],
    [27.666977, 85.417138],
    [27.666753, 85.417594],
    [27.666487, 85.418466],
    [27.666345, 85.419115],
    [27.666297, 85.419530],
    [27.666129, 85.420534],
    [27.666062, 85.421330],
    [27.665941, 85.422186],
    [27.665863, 85.422819],
    [27.665735, 85.423431],
    [27.665697, 85.423735],
    [27.665697, 85.424010],
    [27.665680, 85.424286],
    [27.665673, 85.424595],
    [27.665690, 85.424961],
    [27.665687, 85.425151],
    [27.665649, 85.425667],
    [27.665581, 85.426302],
    [27.665521, 85.426787],
    [27.665473, 85.427382],
    [27.665440, 85.427769],
    [27.665334, 85.428478],
    [27.665271, 85.428828],
    [27.665216, 85.429278],
    [27.665459, 85.430110],
    [27.665573, 85.430367],
    [27.665763, 85.430636],
    [27.665934, 85.431204],
    [27.666371, 85.432213],
    [27.666479, 85.432661],
    [27.666480, 85.433212],
    [27.666461, 85.433639],
    [27.666451, 85.434036],
    [27.666469, 85.434562],
    [27.666488, 85.435324],
    [27.666422, 85.436011],
    [27.666526, 85.436515],
    [27.666821, 85.436440],
    [27.667400, 85.436515],
    [27.667562, 85.436622],
    [27.668075, 85.437105],
    [27.668987, 85.437395],
    [27.668987, 85.437395],
    [27.669548, 85.437524],
    [27.670118, 85.437674],
    [27.671077, 85.437963],
    [27.671476, 85.438038],
    [27.671914, 85.438178],
    [27.672370, 85.438275],
    [27.672873, 85.438328],
    [27.673557, 85.438296],
    [27.674010, 85.438149],
    [27.674235, 85.437936],
    [27.674523, 85.437590],
    [27.674776, 85.437264],
    [27.675254, 85.437070],
    [27.675803, 85.437111],
    [27.676488, 85.437009],
    [27.677092, 85.436958],
    [27.677279, 85.436914],
    [27.677222, 85.436646],
    [27.677099, 85.436066],
    [27.676785, 85.435530],
    [27.676424, 85.434682],
    [27.676073, 85.433942],
    [27.676225, 85.433427],
    [27.676263, 85.432687],
    [27.676092, 85.432225],
    [27.676063, 85.432129],
    [27.675855, 85.431662]
];

// Define coordinates for the path
var path2Coordinates = [
    [27.682172, 85.377716],
    [27.682053, 85.378596],
    [27.681966, 85.379465],
    [27.681943, 85.379835],
    [27.682038, 85.380527],
    [27.682144, 85.381095],
    [27.682267, 85.381926],
    [27.682410, 85.382688],
    [27.682538, 85.383520],
    [27.682603, 85.384068],
    [27.682715, 85.384696],
    [27.682759, 85.385089],
    [27.682784, 85.385318],
    [27.682819, 85.385555],
    [27.682860, 85.385782],
    [27.683079, 85.387058],
    [27.683045, 85.387633],
    [27.682975, 85.388013],
    [27.682944, 85.388172],
    [27.682933, 85.388182],
    [27.682914, 85.388381],
    [27.682872, 85.388616],
    [27.682861, 85.388682],
    [27.682831, 85.388840],
    [27.682815, 85.388958],
    [27.682788, 85.389076],
    [27.682764, 85.389192],
    [27.682764, 85.389192],
    [27.682728, 85.389389],
    [27.682728, 85.389455],
    [27.682672, 85.389780],
    [27.682665, 85.389836],
    [27.682637, 85.389928],
    [27.682482, 85.390319],
    [27.682268, 85.390695],
    [27.682021, 85.391199],
    [27.682021, 85.391199],
    [27.681526, 85.392145],
    [27.681288, 85.392612],
    [27.681127, 85.393224],
    [27.680979, 85.393760],
    [27.680837, 85.394474],
    [27.680628, 85.395273],
    [27.680542, 85.395740],
    [27.680390, 85.396464],
    [27.680305, 85.396936],
    [27.680238, 85.397370],
    [27.680048, 85.397998],
    [27.679944, 85.398620],
    [27.679887, 85.398974],
    [27.679735, 85.399701],
    [27.679634, 85.400217],
    [27.679513, 85.400634],
    [27.679426, 85.401066],
    [27.679308, 85.401578],
    [27.679254, 85.401908],
    [27.679227, 85.402117],
    [27.679146, 85.402382],
    [27.679086, 85.402682],
    [27.679044, 85.402903],
    [27.678995, 85.403140],
    [27.678914, 85.403518],
    [27.678855, 85.403771],
    [27.678820, 85.403945],
    [27.678799, 85.404090],
    [27.678745, 85.404301],
    [27.678687, 85.404547],
    [27.678657, 85.404664],
    [27.678611, 85.404891],
    [27.678576, 85.405080],
    [27.678530, 85.405262],
    [27.678485, 85.405473],
    [27.678422, 85.405750],
    [27.678347, 85.406047],
    [27.678278, 85.406319],
    [27.678249, 85.406484],
    [27.678223, 85.406590],
    [27.678199, 85.406724],
    [27.678163, 85.406813],
    [27.678133, 85.406993],
    [27.678112, 85.407072],
    [27.678082, 85.407152],
    [27.678034, 85.407250],
    [27.677995, 85.407327],
    [27.677980, 85.407348],
    [27.677854, 85.407506],
    [27.677667, 85.407683],
    [27.677481, 85.407793],
    [27.677250, 85.407987],
    [27.677037, 85.408112],
    [27.676716, 85.408309],
    [27.676502, 85.408441],
    [27.676245, 85.408601],
    [27.676090, 85.408699],
    [27.675991, 85.408762],
    [27.675903, 85.408817],
    [27.675751, 85.408906],
    [27.675648, 85.408971],
    [27.675283, 85.409187],
    [27.675183, 85.409249],
    [27.675035, 85.409335],
    [27.674938, 85.409384],
    [27.674826, 85.409435],
    [27.674745, 85.409488],
    [27.674587, 85.409548],
    [27.674357, 85.409702],
    [27.674184, 85.409732],
    [27.674000, 85.409683],
    [27.673823, 85.409609],
    [27.673664, 85.409527],
    [27.673553, 85.409472],
    [27.673331, 85.409343],
    [27.673101, 85.409207],
    [27.672867, 85.409061],
    [27.672267, 85.408724],
    [27.672162, 85.408635],
    [27.671995, 85.408305],
    [27.671950, 85.408038],
    [27.671919, 85.407724],
    [27.672009, 85.407384],
    [27.672123, 85.406970],
    [27.672209, 85.406621],
    [27.672478, 85.405682],
    [27.672630, 85.405162],
    [27.672763, 85.404557],
    [27.672942, 85.403952],
    [27.673078, 85.403422],
    [27.673304, 85.402621],
    [27.673414, 85.402261],
    [27.673604, 85.401583],
    [27.673755, 85.401023],
    [27.673949, 85.400263],
    [27.674167, 85.399489],
    [27.674323, 85.398810],
    [27.674503, 85.397687],
    [27.674558, 85.396789],
    [27.674549, 85.396555],
    [27.674552, 85.395993],
    [27.674502, 85.395556],
    [27.674461, 85.395105],
    [27.674409, 85.394674],
    [27.674353, 85.394125],
    [27.674234, 85.393356],
    [27.674149, 85.392790],
    [27.674075, 85.392218],
    [27.673994, 85.391481],
    [27.673902, 85.390800],
    [27.673835, 85.390097],
    [27.673740, 85.389499],
    [27.673652, 85.388957],
    [27.673600, 85.388426],
    [27.673548, 85.387927],
    [27.673474, 85.387369],
    [27.673398, 85.386841],
    [27.673334, 85.386192],
    [27.673227, 85.385352],
    [27.673254, 85.384707],
    [27.673257, 85.384346],
    [27.673265, 85.383281],
    [27.673288, 85.382618],
    [27.673341, 85.381910],
    [27.673370, 85.381246],
    [27.673440, 85.380506],
    [27.673491, 85.379607],
    [27.673533, 85.378991],
    [27.673584, 85.377875],
    [27.673638, 85.377026],
    [27.673714, 85.376120],
    [27.673768, 85.375352],
    [27.673805, 85.374895],
    [27.673855, 85.373969],
    [27.674159, 85.374049],
    [27.674315, 85.374153],
    [27.674475, 85.374258],
    [27.674628, 85.374371],
    [27.674903, 85.374462],
    [27.675149, 85.374523],
    [27.675344, 85.374598],
    [27.675587, 85.374704],
    [27.675829, 85.374850],
    [27.676322, 85.375058],
    [27.676426, 85.375126],
    [27.676574, 85.375203],
    [27.676713, 85.375265],
    [27.676829, 85.375341],
    [27.676920, 85.375418],
    [27.677042, 85.375495],
    [27.677157, 85.375573],
    [27.677243, 85.375601],
    [27.677389, 85.375612],
    [27.677524, 85.375610],
    [27.677753, 85.375623],
    [27.677951, 85.375657],
    [27.678106, 85.375701],
    [27.678292, 85.375764],
    [27.678673, 85.375874],
    [27.678848, 85.375916],
    [27.678986, 85.375947],
    [27.679153, 85.375989],
    [27.679347, 85.376042],
    [27.679470, 85.376075],
    [27.680088, 85.376243],
    [27.680330, 85.376330],
    [27.680538, 85.376409],
    [27.680753, 85.376479],
    [27.680942, 85.376555],
    [27.681118, 85.376643],
    [27.681304, 85.376745],
    [27.681752, 85.376952],
    [27.682015, 85.377064],
    [27.682172, 85.377716]
];



// Add a polyline to visualize the path
var path1 = L.polyline(path1Coordinates, {
    color: 'blue',
    weight: 5,
    opacity: 0.5,
}).addTo(map);
var path2 = L.polyline(path2Coordinates, {
    color: 'red',
    weight: 5,
    opacity: 0.5,
}).addTo(map);

// Add popups or interactions
path1.bindPopup("Path 1: Blue");
path2.bindPopup("Path 2: Red");


// Custom bus icon
var busIcon = L.icon({
    iconUrl: 'ayus.png',  // path to your bus icon image
    iconSize: [15, 10],  // adjust size as per your image
    iconAnchor: [16, 16], // anchor to center of the icon
    popupAnchor: [0, -16],  // optional: adjust popup anchor
});
var startingIndex1 = 2; // Bus 1 starts at the 3rd point of path1 (index 2)
var startingIndex2 = 4; // Bus 2 starts at the 5th point of path2 (index 4)
var startingIndex3 = 80; // Bus 3 starts at the 2nd point of path1 (index 1)
var startingIndex4 = 30; // Bus 4 starts at the 4th point of path2 (index 3)

// Initialize the markers with the bus icon at their respective starting points
var busMarker1 = L.marker(path1Coordinates[startingIndex1], { icon: busIcon }).addTo(map);
var busMarker2 = L.marker(path2Coordinates[startingIndex2], { icon: busIcon }).addTo(map);
var busMarker3 = L.marker(path1Coordinates[startingIndex3], { icon: busIcon }).addTo(map);
var busMarker4 = L.marker(path2Coordinates[startingIndex4], { icon: busIcon }).addTo(map);

// Initialize indices for all buses
var currentIndex1 = startingIndex1;
var totalPoints1 = path1Coordinates.length;

var currentIndex2 = startingIndex2;
var totalPoints2 = path2Coordinates.length;

var currentIndex3 = startingIndex3;
var totalPoints3 = path1Coordinates.length;

var currentIndex4 = startingIndex4;
var totalPoints4 = path2Coordinates.length;

// Function to animate the buses along their paths
function animateBuses() {
    // Animate bus 1 along path 1
    if (currentIndex1 < totalPoints1) {
        busMarker1.setLatLng(path1Coordinates[currentIndex1]);
        currentIndex1++;
    } else {
        currentIndex1 = 0; // Loop back to the start of path 1
    }

    // Animate bus 2 along path 2
    if (currentIndex2 < totalPoints2) {
        busMarker2.setLatLng(path2Coordinates[currentIndex2]);
        currentIndex2++;
    } else {
        currentIndex2 = 0; // Loop back to the start of path 2
    }

    // Animate bus 3 along path 1
    if (currentIndex3 < totalPoints3) {
        busMarker3.setLatLng(path1Coordinates[currentIndex3]);
        currentIndex3++;
    } else {
        currentIndex3 = 0; // Loop back to the start of path 1
    }

    // Animate bus 4 along path 2
    if (currentIndex4 < totalPoints4) {
        busMarker4.setLatLng(path2Coordinates[currentIndex4]);
        currentIndex4++;
    } else {
        currentIndex4 = 0; // Loop back to the start of path 2
    }
}

// Move all bus markers every 1 second
setInterval(animateBuses, 1000);

        //-----------------------
        var busStopIcon = L.icon({
    iconUrl: 'bus.png', // Replace with the path to your icon
    iconSize: [32, 32],            // Size of the icon
    iconAnchor: [16, 32],          // Anchor point of the icon
    popupAnchor: [0, -32]          // Popup position relative to the icon
});

// Coordinates for bus stops
var busStops = [
    [27.675364, 85.429755],
    [27.674799, 85.426926],
    [27.676053, 85.415170],
    [27.666527, 85.417999],
    [27.666454, 85.436485],
    [27.676280, 85.437107],
    [27.676117, 85.432091],
    [27.679905, 85.398877],
    [27.683045, 85.387633],
    [27.682665, 85.389836],
    [27.680628, 85.395273],
    [27.682410, 85.382688],
    [27.682819, 85.385555],
    [27.679308, 85.401578],
    [27.672009, 85.407384],
    [27.673584, 85.377875],
    [27.673949, 85.400263]
];

// Add a marker for each bus stop
busStops.forEach(function (coords) {
    L.marker(coords, { icon: busStopIcon }).addTo(map)
        .bindPopup('Bus Stop'); // Optional: Add a popup to each marker
});
        // const marker = L.marker([27.675364, 85.429755]).addTo(map);

        // Add a popup to the marker
        //marker.bindPopup('<b>Hello Kathmandu!</b><br>This is a marker.').openPopup();
    </script>
</body>
</html>