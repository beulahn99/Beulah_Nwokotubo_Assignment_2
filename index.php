<!--
* Author: Beulah Nwokotubo
* File Name: index.php
* Date Created: 23 November 2023
* Description: This file is the index page for the task management system, 
* featuring user authentication, task display, search, and addition functionalities, 
* along with dynamic updates using AJAX and error handling.
 -->

<?php require_once('./dao/taskDAO.php'); 
require_once('./dao/userDAO.php'); // Include the UserDAO file
// Get the current user using the UserDAO
$userDAO = new UserDAO();
$user = $userDAO->getCurrentUser();

if (!$user) {
    // Redirect to the login page or handle unauthorized access
    header("Location: login.php");
    exit;
}


if (isset($_POST['btnLogout'])) {
    // Include the logout logic
    include('logout.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Task Management App</title>
        <link rel="stylesheet" href="css/tasks.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
    <div class="logout-section">
        <!-- Logout Form -->
        <form method="post" action="index.php">
            <input type="submit" name="btnLogout" value="Logout" id="btnLogout">
        </form>
    </div>
        <h1>Task Management System</h1>
        <?php
        try {
            $taskDAO = new TaskDAO();

            $hasError = false;

            $errorMessages = Array();

            if (isset($_POST['btnSearch'])) {
                // Get the search parameter
                $search = isset($_POST['taskSearch']) ? $_POST['taskSearch'] : '';
        
                // Fetch tasks based on the search parameter
                    $tasks = $taskDAO->searchTasks($user->getId(), $search, $tasks);
               
            } else {
                // Fetch all tasks if no specific action is triggered
                $tasks = $taskDAO->getTasks($user->getId());
            }
            

            // Check for Delete Task Request
            if (isset($_GET['deleteTask'])) {
                $deleteTaskId = $_GET['deleteTask'];
                $deleteSuccess = $taskDAO->deleteTask($deleteTaskId);
                echo '<h3>' . ($deleteSuccess ? 'Task completed successfully!' : 'Failed to delete task') . '</h3>';
            }

            if(isset($_POST['taskName']) && 
                isset($_POST['priority']) &&
                isset($_POST['dueDate'])) { 

                    if($_POST['taskName'] == ""){
                        $errorMessages['taskNameError'] = "Please enter a task name.";
                        $hasError = true;
                    }

                    if($_POST['priority'] == ""){
                        $errorMessages['priorityError'] = "Please enter a priority level.";
                        $hasError = true;
                    }

                    if($_POST['dueDate'] == ""){
                        $errorMessages['dueDateError'] = "Please enter a due date.";
                        $hasError = true;
                    }

                    if(!$hasError){
                        $taskName = htmlspecialchars($_POST['taskName']);
                        $priority = htmlspecialchars($_POST['priority']);
                        $dueDate = htmlspecialchars($_POST['dueDate']);

                        $task = new Task($_POST['taskId'], $_POST['taskName'], $_POST['priority'], $_POST['dueDate'], $user->getId());
                        $addSuccess = $taskDAO->addTask($task);
                        echo '<h3>' . $addSuccess . '</h3>';
                    }
                }
                else {
                    if ($hasError) {
                        print_r($errorMessages);
                    }
                }

                if(isset($_GET['deleted'])){
                    if($_GET['deleted'] == true){
                        echo '<h3>Task Deleted</h3>';
                    }
                }
                

                ?>

                <div id="searchResults"></div>

                <div class="task-section">

                    <!-- Task Search and Filter -->
                    <div class="search-filter">
                        <form method="post" action="index.php">
                            <label for="taskSearch">Search:</label>
                            <input type="text" id="taskSearch" name="taskSearch" placeholder="Search for tasks...">
                            <button type="button" id="btnSearch" name="btnSearch">Search</button>
                        </form>

                        <label for="filter">Filter by:</label>

                        <select id="filter" name="filter">
                            <option value="all">All</option>
                            <option value="low">low</option>
                            <option value="medium">medium</option>
                            <option value="high">high</option>
                        </select>
                    </div>

                    <!-- Task Listing -->
                    <ul id="taskList"></ul>

                    <!-- Task Adding -->
                    <div class="add-task">
                        <h3>Add a Task</h3>
                        <form name="addTask" method="post" action="index.php">
                            <table>
                                <tr>
                                    <td><label for="taskName">Task Name:</label></td>
                                    <td><input type="text" name="taskName" id="taskName">
                                        <?php
                                        if (isset($errorMessages['taskNameError'])) {
                                            echo '<span style=\'color:red\'>' . $errorMessages['taskNameError'] . '</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="priority">Priority:</label></td>
                                    <td>
                                        <select name="priority" id="priority" required class="priority-dropdown">
                                            <option value="Low">Low</option>
                                            <option value="Medium">Medium</option>
                                            <option value="High">High</option>
                                        </select>
                                        <?php
                                        if (isset($errorMessages['priorityError'])) {
                                            echo '<span style=\'color:red\'>' . $errorMessages['priorityError'] . '</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                        
                                <tr>
                                    <td><label for="dueDate">Due Date:</label></td>
                                    <td><input type="date" name="dueDate" id="dueDate" class="textbox">
                                        <?php
                                            if (isset($errorMessages['dueDateError'])) {
                                                echo '<span style=\'color:red\'>' . $errorMessages['dueDateError'] . '</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input type="submit" name="btnSubmit" id="btnSubmit" value="Add Task"></td>
                                    <td><input type="reset" name="btnReset" id="btnReset" value="Reset"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                <?php
            
            echo '<h2>Your Tasks</h2>';
            
            $tasks = $taskDAO->getTasks($user->getId());

            if ($tasks) {
                echo '<div class="task-table" id="taskTable">';
                echo '<table border="1">';
                echo '<tr><th>Task ID</th><th>Task Name</th><th>Priority</th><th>Due Date</th><th>Mark As Complete</th></tr>';
                foreach ($tasks as $task) {
                    echo '<tr class="tasks">';
                    echo '<td class="taskId">' . $task->getTaskId() . '</td>';
                    echo '<td class="taskName">' . $task->getTaskName() . '</td>';
                    echo '<td class="priority">' . $task->getPriority() . '</td>';
                    echo '<td class="dueDate">' . $task->getDueDate() . '</td>';
                    echo '<td ><a href="index.php?deleteTask=' . $task->getTaskId() . '">Complete</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            }                            

        } catch (Exception $e) {
            echo '<h3>Error on page.</h3>';
            echo '<p>' . $e->getMessage() . '</p>';
        }
        ?>

<script>

    $(document).ready(function() {
        searchTasks();
        $('#btnSearch').click(function() {
            searchTasks();
        });

        $('#filter').change(function() {
        searchTasks();
        });
    });

    function searchTasks() {
        var searchValue = $('#taskSearch').val(); // obtain search value
        var filterValue = $('#filter').val();

        $.ajax({
            url: 'fetch_tasks.php?search=' + encodeURIComponent(searchValue),   
            type: 'GET',
            data: { search: encodeURIComponent(searchValue), filter: filterValue }, // Pass search and filter 
            success: function(data) {
                $('#taskTable tbody').html(data); // update form content
            }
        });
    }
</script>


 </body>
</html>