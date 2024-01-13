<!--
* Author: Beulah Nwokotubo
* File Name: abstractDAO.php
* Date Created: 23 November 2023
* Decription: abstract data access object from whick user and task data objects inherit.
 -->

<?php

class AbstractDAO {
    protected $mysqli;

    protected static $DB_HOST = "127.0.0.1";
    protected static $DB_USERNAME = "root";
    protected static $DB_PASSWORD = "MIGNONette99?";
    protected static $DB_DATABASE = "task_management";

    function __construct() {
        try {
            $this->mysqli = new mysqli(self::$DB_HOST, self::$DB_USERNAME, self::$DB_PASSWORD, self::$DB_DATABASE);

            if ($this->mysqli->connect_error) {
                die("Connection failed: " . $this->mysqli->connect_error);
            }
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    public function getMysqli() {
        return $this->mysqli;
    }
}

?>
