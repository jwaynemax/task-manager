<?php

class Database {

    private $db;
    private $error_message;

    /**
     * connect to the database
     */
    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=task_manager';
        $username = 'mgs_user';
        $password = 'pa55word';
        $this->error_message = '';
        try {
            $this->db = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            $this->error_message = $e->getMessage();
        }
    }

    /**
     * check the connection to the database
     *
     * @return boolean - true if a connection to the database has been established
     */
    public function isConnected() {
        return ($this->db != Null);
    }

    public function getErrorMessage() {
        return $this->error_message;
    }

    public function isValidUserLogin($username, $password) {
        $query = 'SELECT password FROM users
              WHERE username = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        if (!$row) {
            return false;
        }
        $hash = $row['password'];
        return password_verify($password, $hash);
    }

    public function getTasksForUser($username) {
        $query = 'SELECT * FROM tasks
                  WHERE tasks.username = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $tasks = $statement->fetchAll();
        $statement->closeCursor();
        return $tasks;
    }

    public function addTask($username, $task) {
        $query = 'INSERT INTO tasks (username, task)
                  VALUES (:username, :task)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':task', $task);
        $statement->execute();
        $statement->closeCursor();
    }

    public function deleteTask($task_id) {
        $query = 'DELETE FROM tasks
                  WHERE taskID = :task_id';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':task_id', $task_id);
        $statement->execute();
        $statement->closeCursor();
    }

    public function registerUser($username, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $query = 'INSERT INTO users (username, password)
                  VALUES (:username, :password)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hash);
        $statement->execute();
        $statement->closeCursor();
    }

}

?>