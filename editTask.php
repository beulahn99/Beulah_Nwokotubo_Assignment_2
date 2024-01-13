<!--
* Author: Beulah Nwokotubo
* File Name: editTask.php
* Date Created: 23 November 2023
* Description: This php file contains code that allows users to edit previously created tasks.
-->

<?php
require_once('./dao/taskDAO.php');
require_once('./dao/userDAO.php');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

// Get the logged-in user
$userDAO = new UserDAO();
$user = $userDAO->getUserById($_SESSION['userId']);

if (!isset($_GET['taskId']) || !is_numeric($_GET['taskId'])) {
    // Send the user back to the main page
    header("Location: index.php");
    exit;
} else {
    $taskDAO = new TaskDAO();
    $task = $taskDAO->getTask($_GET['taskId']);

    // Check if the task exists and if the logged-in user is authorized to edit it
    if ($task && $task->getUserId() == $user->getId())  {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>Edit Task - <?php echo htmlspecialchars($task->getTaskName()); ?></title>
            <link rel="stylesheet" href="css/tasks.css">
            <script type="text/javascript">
                function confirmDelete(taskName){
                    return confirm("Do you wish to delete " + taskName + "?");
                }
            </script>
        </head>
        <body>

            <?php
                if(isset($_GET['recordsUpdated'])){
                        if(is_numeric($_GET['recordsUpdated'])){
                            echo '<h3> '. $_GET['recordsUpdated']. ' Task Record Updated.</h3>';
                        }
                }
                if(isset($_GET['missingFields'])){
                        if($_GET['missingFields']){
                            echo '<h3 style="color:red;"> Please enter all required data.</h3>';
                        }
                }
            ?>
            <!-- Edit Task Form -->
            <h3 id="editHeader">Edit Task</h3>
            <form name="editTask" method="post" action="processTask.php?action=edit" id="editForm">
                <div class="editTable">
                    <table>
                        <tr>
                            <td class="label">Task Name:</td>
                            <td><input type="text" name="taskName" class="textbox" id="taskName" value="<?php echo htmlspecialchars($task->getTaskName()); ?>"></td>
                        </tr>
                        <tr>
                            <td class="label">Priority:</td>
                            <td>
                                <select name="priority" id="priority" required class="priority-dropdown">
                                    <option value="Low" <?php echo ($task->getPriority() == 'Low') ? 'selected' : ''; ?>>Low</option>
                                    <option value="Medium" <?php echo ($task->getPriority() == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                    <option value="High" <?php echo ($task->getPriority() == 'High') ? 'selected' : ''; ?>>High</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Due Date:</td>
                            <td><input type="date" name="dueDate" class="textbox" id="dueDate" value="<?php echo htmlspecialchars($task->getDueDate()); ?>"></td>
                        </tr>

                        <tr>
                            <td><input type="submit" name="btnSubmit" id="btnSubmit" value="Update Task"></td>
                            <td><input type="reset" name="btnReset" id="btnReset" value="Reset"></td>
                        </tr>
                    </table>
                    <!-- Include the task ID as a hidden field to identify the task when submitting the form -->
                    <input type="hidden" name="taskId" value="<?php echo $task->getTaskId(); ?>">
                </div>
            </form>
            <h4><a href="index.php">Return to main page</a></h4>
        </body>
        </html>
    <?php
    } else {
        // User is not authorized to edit this task
        header("Location: index.php?unauthorized=true");
        exit;
    }
}
?>
