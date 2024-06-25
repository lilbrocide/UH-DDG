<?php
session_start();

$error_msg = '';

if (isset($_SESSION['username'])) {
    header("Location: welcome.php");  
    exit();
}

if (isset($_POST['login'])) {
    include 'config.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            header("Location: welcome.php");
            exit();  
        } else {
            $error_msg = "Invalid username or password";
        }
    } else {
        $error_msg = "Invalid username or password";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="index.php" method="post"> 
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <?php
            if (!empty($error_msg)) {
                echo "<p class='error'>$error_msg</p>";
            }
            ?>
            <input type="submit" name="login" value="Login">
        </form>

        <a href="register.php">Don't have an account? Register</a>
    </div>
</body>
</html>
