<!--
* Author: Beulah Nwokotubo
* File Name: register.php
* Date Created: 23 November 2023
* Description: Registration webpage for users.
 -->

<?php
require_once('./dao/userDAO.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form submitted, process registration
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];



    try {
        $userDAO = new UserDAO();

        // Check if the email is already registered
        $existingUser = $userDAO->getUserByEmail($email);
        if ($existingUser) {
            $registrationError = "Email already exists. Please use a different email.";
        } else {
            // Hash the password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create a new user object
            $newUser = new User(null, $email, $firstName, $lastName, password: $hashedPassword);

            // Add the new user to the database
            $userDAO->addUser($newUser);

            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
        }
    } catch (Exception $e) {
        $registrationError = "Error during registration: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
    <script src="js\tasks.js"></script>
</head>
<body>

    <h2 id="reg">Register</h2>
    <!-- Add this line inside your HTML body or container -->
    <div>
    <img src="images/to_do.png" alt="To-Do Image" class="todo-image">
    </div>
  
    
    <div class="container">
    <form name="registerForm" method="post" action="register.php">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <?php if (isset($emailValidationError)) echo '<p style="color: red;">' . $emailValidationError . '</p>'; ?>
        
        <label for="firstName">First Name</label>
        <input type="text" name="firstName" id="firstName" required>
        <?php if (isset($firstNameValidationError)) echo '<p style="color: red;">' . $firstNameValidationError . '</p>'; ?>

        <label for="lastName">Last Name</label>
        <input type="text" name="lastName" id="lastName" required>
        <?php if (isset($lastNameValidationError)) echo '<p style="color: red;">' . $lastNameValidationError . '</p>'; ?>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <?php if (isset($passwordValidationError)) echo '<p style="color: red;">' . $passwordValidationError . '</p>'; ?>        

        <?php
            if (isset($registrationError) && !isset($emailValidationError) && !isset($firstNameValidationError) && !isset($lastNameValidationError) && !isset($passwordValidationError)) {
                echo '<p style="color: red;">' . $registrationError . '</p>';
            }
            ?>

        <div class="btnDiv">
        <input type="submit" name="btnRegister" id="btnRegister" value="Register">
        </div>

        <p class="already">Already have an account? <a href="login.php">Login</a></p>
    </form>
    </div>

    
</body>
</html>