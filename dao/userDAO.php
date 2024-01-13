<!--
* Author: Beulah Nwokotubo
* File Name: userDAO.php
* Date Created: 23 November 2023
* Decription: data access object for the users table which inherits from abstractDAO.
-->

<?php
require_once('abstractDAO.php');
require_once('./model/user.php');

class UserDAO extends AbstractDAO {

    function __construct() {
        try {
            parent::__construct();
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    public function getCurrentUser() {
        session_start();
         
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            return $this->getUserById($userId);
        }

        return false;
    }

    public function login($email, $password) {
        $query = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $temp = $result->fetch_assoc();
            $hashedPassword = $temp['hashedPassword']; // Assuming your password is stored as a hash in the database

            if (password_verify($password, $hashedPassword)) {
                // Password is correct, set session
                session_start();
                $_SESSION['userId'] = $temp['id'];
                return true;
            }
        }

        return false;
    }
    

    public function getUserByEmail($email) {
        $query = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $temp = $result->fetch_assoc();
            $user = new User($temp['id'], $temp['email'], $temp['firstName'], $temp['lastName'], $temp['password']);
            $result->free();
            return $user;
        }

        $result->free();
        return false;
    }

    public function getUserById($userId) {
        $query = 'SELECT * FROM users WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $temp = $result->fetch_assoc();
            $user = new User($temp['id'], $temp['email'], $temp['firstName'], $temp['lastName'], $temp['password']);
            $result->free();
            return $user;
        }

        $result->free();
        return false;
    }

    public function addUser(User $user) {
        $query = 'INSERT INTO users (email, firstName, lastName, password) VALUES (?, ?, ?, ?)';
        $stmt = $this->mysqli->prepare($query);
        $email = $user->getEmail();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $password = $user->getPassword(); 

        $stmt->bind_param('ssss', $email, $firstName, $lastName, $password);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUser($userId, $email, $firstName, $lastName) {
        $query = 'UPDATE users SET email = ?, firstName = ?, lastName = ? WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('sssi', $email, $firstName, $lastName, $userId);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($userId) {
        $query = 'DELETE FROM users WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $userId);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    

    public function __destruct() {
        $this->mysqli->close();
    }
}
?>