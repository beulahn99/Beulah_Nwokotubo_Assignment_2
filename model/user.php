<!--
* Author: Beulah Nwokotubo
* File Name: login.php
* Date Created: 23 November 2023
* Description: defines a User class representing users in the task management system, 
* encapsulating user attributes and providing getter and setter methods for interaction with user data.
 -->

<?php

class User {
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $password;

    public function __construct($id, $email, $firstName, $lastName, $password) {
        $this->setId($id);
        $this->setEmail($email);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setPassword($password);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
}
?>
