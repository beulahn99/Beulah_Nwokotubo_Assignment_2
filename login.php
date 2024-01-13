<!--
* Author: Beulah Nwokotubo
* File Name: login.php
* Date Created: 23 November 2023
* Description: Login webpage for users.
 -->

<?php
require_once('./dao/userDAO.php');

session_start();

if (isset($_POST['btnLogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Authentication successful, redirect to the dashboard or home page
            $_SESSION['userId'] = $user->getId();
            header("Location: index.php");
            exit();
        } else {
            $loginError = "Invalid email or password. Please try again.";
        }
    } catch (Exception $e) {
        $loginError = "Error during login: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <h2 id="log">Login</h2>
    <?php
    if (isset($loginError)) {
        echo '<p style="color: red;">' . $loginError . '</p>';
    }
    ?>
    <div class="container">
        <form name="loginForm" method="post" action="login.php">
            <label for="email" class="label">Email</label>
            <input type="email" name="email" id="email" required>
            
            <label for="password" class="label">Password</label>
            <input type="password" name="password" id="password" required>

            <div class="btnDiv">
                <input type="submit" name="btnLogin" id="btnLogin" value="Login">
            </div>

            <p class="already">Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
    
</body>
</html>