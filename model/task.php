<!--
* Author: Beulah Nwokotubo
* File Name: task.php
* Date Created: 23 November 2023
* Description: defines a Task class representing tasks in the task management system, 
* encapsulating task attributes and providing getter and setter methods for interaction with task data.
-->
<?php
class Task {
    private $taskId;
    private $taskName;
    private $priority;
    private $dueDate;
    private $userId;

    function __construct($taskId, $taskName, $priority, $dueDate, $userId) {
        $this->setTaskId($taskId);
        $this->setTaskName($taskName);
        $this->setPriority($priority);
        $this->setDueDate($dueDate);
        $this->setUserId($userId);
    }

    public function getTaskId() {
        return $this->taskId;
    }

    public function setTaskId($taskId) {
        $this->taskId = $taskId;
    }

    public function getTaskName() {
        return $this->taskName;
    }

    public function setTaskName($taskName) {
        $this->taskName = $taskName;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function getDueDate() {
        return $this->dueDate;
    }

    public function setDueDate($dueDate) {
        $this->dueDate = $dueDate;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }
}
?>
