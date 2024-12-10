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
$dbname = 'bus_queue';
$dbUsername = 'root';
$dbPassword = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch user details from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        echo "User not found.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = trim($_POST['username']);
    $newEmail = trim($_POST['email']);
    $newPassword = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    
    // Basic validation
    if (empty($newUsername) || empty($newEmail) || empty($newPassword) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $newPassword)) {
        $error = "Password must be at least 8 characters long and include both letters and numbers.";
    } else {
        // Update the user's details in the database

            $updateStmt = $pdo->prepare("UPDATE users SET username = :username, password = :password, email = :email WHERE username = :currentUsername");
            $updateStmt->bindParam(':username', $newUsername);
            $updateStmt->bindParam(':password', $newPassword); // Store password in plain text
            $updateStmt->bindParam(':email', $newEmail);
            $updateStmt->bindParam(':currentUsername', $username);
            $updateStmt->execute();
            
            // Update session with new username
            $_SESSION['username'] = $newUsername;
            
            // Redirect to dashboard after successful update
        } 
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Details</title>
    <link rel="stylesheet" href="../style/user_detail.css">
</head>
<body>
<form action="userdetails.php" method="POST">
    <div>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    <div>
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div>
        <button type="submit">Update Details</button>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
</form>

<!-- Button to redirect to the dashboard -->
<button onclick="window.location.href='../user_dashboard.php';" 
        style="background-color: #007bff; margin-top:20px; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
    Go to user dashboard Dashboard
</button>
</body>
</html>
