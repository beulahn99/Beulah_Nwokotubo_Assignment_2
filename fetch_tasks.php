<!--
* Author: Beulah Nwokotubo
* File Name: fetch_tasks.php
* Date Created: 23 November 2023
* Description: This file fetches tasks from the 
* database to implement search and filter functionalities
 -->

<?php
session_start();
$servername = "127.0.0.1";  // database server name
$username = "root";     // database user name
$password = "MIGNONette99?";     // database password
$dbname = "task_management";   // database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tasks WHERE userId = ?";
$bindParams = ["i", $_SESSION['userId']];

if (isset($_GET['search'])) {
    $search_name = $_GET['search'];
    if (strlen($search_name)) {
        $search_like = "%" . $search_name . "%";
        $sql .= " AND taskName LIKE ?";
        $bindParams[0] .= "s";
        $bindParams[] = $search_like;
    }
}

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    if ($filter !== 'all') {
        $sql .= " AND priority LIKE ?";
        $bindParams[0] .= "s";
        $bindParams[] = "%{$filter}%";
    }
}

$stmt = $conn->prepare($sql);

// Bind parameters
if (count($bindParams) > 1) {
    $stmt->bind_param(...$bindParams);
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<div class="task-table" id="taskTable">';
    echo '<table border="1">';
    echo '<tr><th>Task Name</th><th>Priority</th><th>Due Date</th><th>Manage Task</th></tr>';
    // output every row of data
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["taskName"] . "</td>" .
            "<td>" . $row["priority"] . "</td><td>" . $row["dueDate"] . "</td>" .
            "<td><a href='editTask.php?taskId=" . $row["taskId"] . "'>Edit</a> <a href='index.php?deleteTask=" . $row["taskId"] . "'>Complete</a> " . "</td></tr>";
    }
} else {
    echo "<tr><td colspan='6'>No tasks found</td></tr>";
}

$conn->close();
?>