<!--
* Author: Beulah Nwokotubo
* File Name: getTasks.php
* Date Created: 23 November 2023
* Description: This php code selects all tasks from the database to display in the table.
 -->

<?php

require_once('AbstractDAO.php');

class GetTasks extends AbstractDAO {

    public function getTasks() {
        $query = 'SELECT * FROM tasks';
        $result = $this->mysqli->query($query);
        $tasks = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
            $result->free();
            return $tasks;
        }

        $result->free();
        return false;
    }
}

?>
