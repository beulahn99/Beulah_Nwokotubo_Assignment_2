<!--
* Author: Beulah Nwokotubo
* File Name: processTask.php
* Date Created: 23 November 2023
* Description: This file processes tasks in the backend
* implementing dynamic functionality based on user interaction.
 -->

<?php
require_once('./dao/taskDAO.php');
require_once('./dao/userDAO.php');

// Assuming you have a user authentication system, you might get the user ID from the session
session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

$userDAO = new UserDAO();
$user = $userDAO->getUserById($_SESSION['userId']);

// Check if the 'action' parameter is set in the URL
if (isset($_GET['action'])) {
    // Check the value of the 'action' parameter
    if ($_GET['action'] == "edit") {
        // Check if all required fields are set in the POST request
        if (
            isset($_POST['taskId']) &&
            isset($_POST['taskName']) &&
            isset($_POST['priority']) &&
            isset($_POST['dueDate'])
        ) {
            // Retrieve data from the form
            $taskId = $_POST['taskId'];
            $taskName = $_POST['taskName'];
            $priority = $_POST['priority'];
            $dueDate = $_POST['dueDate'];
            
            // Create a TaskDAO object
            $taskDAO = new TaskDAO();
            
            // Check if the user is authorized to edit this task
            $task = $taskDAO->getTask($taskId);
            if ($task && $task->getUserId() == $user->getId())  {
                // Call the editTask method with the provided parameters
                $result = $taskDAO->editTask($taskId, $taskName, $priority, $dueDate);

                if ($result !== false) {
                    // Redirect with success message
                    header('Location: editTask.php?recordsUpdated=' . $result . '&taskId=' . $taskId);
                    exit;
                } else {
                    // Redirect with error message
                    header('Location: editTask.php?error=true&taskId=' . $taskId);
                    exit;
                }
            } else {
                // User is not authorized to edit this task
                header('Location: index.php?unauthorized=true');
                exit;
            }
        } else {
            // Redirect with missing fields message
            header('Location: editTask.php?missingFields=true&taskId=' . $_POST['taskId']);
            exit;
        }
    }

}

// If 'action' parameter is not set, redirect to the main page or wherever appropriate
header('Location: index.php');
exit();
?>
