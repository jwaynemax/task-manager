<?php
require_once './model/Database.php';

class Controller {
    private $action;
    private $db;
    
    public function __construct() {
        $this->startSession();
        $this->connectToDatabase();
        $this->action = $this->getAction();
    }
    
    public function invoke() {
        switch($this->action) {
            case 'Show Login':
                $this->processShowLogin();
                break;
            case 'Login':
                $this->processLogin();
                break;
            case 'Logout':
                $this->processLogout();
                break;
            case 'Add Task':
                $this->processAddTask();
                break;
            case 'Delete Task':
                $this->processDeleteTask();
                break;
            case 'Show Tasks':
                $this->processShowTasks();
                break;
            case 'Home':
                $this->processShowHomePage();
                break;
            case 'Show Register':
                $this->showRegisterPage();
                break;
            case 'Register':
                $this->processRegister();
                break;
            default:
                $this->processShowHomePage();
                break;
        }
    }
    
    /****************************************************************
     * Process Request
     ***************************************************************/
    private function processShowLogin() {
        $login_message = '';   
        include('./view/login.php');
    }
    
    private function processLogin() {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        if ($this->db->isValidUserLogin($username, $password)) {
            $_SESSION['is_valid_user'] = true;
            $_SESSION['username'] = $username;
            header("Location: .?action=Show Tasks");
        } else {
            $login_message = 'Invalid username or password';
            include('./view/login.php');
        }
    }
    
    private function processShowHomePage() {
        include './view/home.php';
    }
    
    private function processLogout() {
        $_SESSION = array();   // Clear all session data from memory
        session_destroy();     // Clean up the session ID
        $login_message = 'You have been logged out.';
        include('./view/login.php');
    }
    
    private function processShowTasks() {
        if (!isset($_SESSION['is_valid_user'])) {
            $login_message = 'Log in to manage your tasks.';
            include('./view/login.php');
        } else {
            $errors = array();
            $tasks = $this->db->getTasksForUser($_SESSION['username']);
            include './view/task_list.php';
        }
    }
    
    private function processAddTask() {
        $new_task = filter_input(INPUT_POST, 'newtask', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $errors = array();
        if (empty($new_task)) {
            $errors[] = 'The new task cannot be empty.';
        } else {
            $this->db->addTask($_SESSION['username'], $new_task);
        }
        $tasks = $this->db->getTasksForUser($_SESSION['username']);
        include './view/task_list.php';
    }
    
    private function processDeleteTask() {
        $task_id = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        $errors = array();
        if ($task_id === NULL || $task_id === FALSE) {
            $this->errors[] = 'The task cannot be deleted.';
        } else {
            $this->db->deleteTask($task_id);
        }
        $tasks = $this->db->getTasksForUser($_SESSION['username']);
        include './view/task_list.php';
    }
    
    private function showRegisterPage() {
        include './view/register.php';
    }
    
    private function processRegister() {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $usernamePattern = '/^.{1,20}$/';
        $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/';
        
        $check_pattern_username = preg_match($usernamePattern, $username);
        $check_pattern_password = preg_match($passwordPattern, $password);
        if($check_pattern_password && $check_pattern_username) {
            $this->db->registerUser($username, $password);
            $_SESSION['is_valid_user'] = true;
            $_SESSION['username'] = $username;
            header("Location: .?action=Show Tasks");
        } else {
            $printUsernameError = 'Username must be between 1-20 characters';
            $printPasswordError = 'Password must be at least 8 characters, include a number, lowercase, and uppercase letter.';
            include './view/register.php';
        }
    }
    
    
    /****************************************************************
     * Get action from $_GET or $_POST array
     ***************************************************************/
    private function getAction() {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($action === NULL) {
            $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($action === NULL) {
                $action = '';
            }
        }
        return $action;
    }
    
    /****************************************************************
     * Ensure a secure connection and start session
     ***************************************************************/
    private function startSession() {
        session_start();
    }
    
    /****************************************************************
     * Connect to the database
     ***************************************************************/
    private function connectToDatabase() {
        $this->db = new Database();
        if (!$this->db->isConnected()) {
            $error_message = $this->db->getErrorMessage();
            include './view/database_error.php';
            exit();
        }
    }
}

