<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "bus_queue");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if ($password === $user['password']) { // Plain text password check
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: " . ($user['role'] == 'admin' ? "admin_dashboard.php" : "user_dashboard.php"));
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Invalid Username or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./style/login.css">
</head>
<style>
    .container p {
        margin-top: 30px;
        color: #000000;
    }

    .container p a {
        color: #000000;
    }
</style>

<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
            <p>Don't have account yet?
                <a href="register.php">
                    Register here
                </a>
            </p>


        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>

</html>