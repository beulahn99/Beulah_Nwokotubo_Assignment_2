<!--
* Author: Beulah Nwokotubo
* File Name: taskDAO.php
* Date Created: 23 November 2023
* Description: data access object for the tasks table which inherits from abstractDAO.
-->
<?php
require_once('abstractDAO.php');
require_once('./model/task.php');

error_reporting(0);

/**
 * Description of taskDAO
 *
 * @author Beulah Nwokotubo
 */

class TaskDAO extends AbstractDAO {

    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }


    private $userId;

    public function getTasks($userId) {
        $query = 'SELECT * FROM tasks WHERE userId = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $tasks = array();
    
        while ($row = $result->fetch_assoc()) {
            $task = new Task($row['taskId'], $row['taskName'], $row['priority'], $row['dueDate'], $userId);
            $task->setUserId($row['']);
            $tasks[] = $task;
        }
    
        $result->free();
        return $tasks;
    }

    public function getTask($taskId) {
        $query = 'SELECT * FROM tasks WHERE taskId = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $taskId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $temp = $result->fetch_assoc();
            $task = new Task($temp['taskId'], $temp['taskName'], $temp['priority'], $temp['dueDate'], $temp['userId']);
            $result->free();
            return $task;
        }

        $result->free();
        return false;
    }


    public function addTask(Task $task) {
        // if (!is_numeric($task->getTaskId())) {
        //     return 'TaskId must be a number.';
        // }
    
        if (!$this->mysqli->connect_errno) {
            $query = 'INSERT INTO tasks (taskName, priority, dueDate, userId) VALUES (?,?,?,?)';
           
            $stmt = $this->mysqli->prepare($query);
    
            $stmt->bind_param('sssi',
                $task->getTaskName(),
                $task->getPriority(),
                $task->getDueDate(),
                $task->getUserId() 
            );
    
            $stmt->execute();
    
            if ($stmt->error) {
                return $stmt->error;
            } else {
                return $task->getTaskName() . ' added successfully!';
            }
        } else {
            return 'Could not connect to Database.';
        }
    }
    
    public function deleteTask($taskId){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM tasks WHERE taskId = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $taskId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function editTask($taskId, $taskName, $priority, $dueDate){
        if(!$this->mysqli->connect_errno){
            $query = 'UPDATE tasks SET taskName = ?, priority = ?, dueDate = ?  WHERE taskId = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('sssi', $taskName, $priority, $dueDate, $taskId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return $stmt->affected_rows;
            }
        } else {
            return false;
        }
    }

    public function __destruct() {
        $this->mysqli->close();
    }

    // Existing constructor...

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    // TaskDAO.php



    public function searchTasks($userId, $search) {
        $tasks = array();
    
        try {
            $conn = $this->mysqli;
    
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
    
            $searchName = '%' . $search . '%';
    
            $sql = "SELECT * FROM tasks WHERE userId = ? AND taskName LIKE ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $userId, $searchName);
    
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                // Assuming you have a Task class to represent a task
                $task = new Task($row['taskId'], $row['taskName'], $row['priority'], $row['dueDate'], $row['userId']);
                $tasks[] = $task;
            }
    
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            throw new Exception("Error searching tasks: " . $e->getMessage());
        }
    
        return $tasks;
    }
}

?>