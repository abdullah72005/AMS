<?php

// Set session cookie to last 30 days
ini_set('session.cookie_lifetime', 30 * 24 * 60 * 60); // 30 days
ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);   // 30 days

// Security options (recommended)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_secure', 0); 


// Start session
session_start();


class User
{
    private $userId;
    private $username;
    private $password;
    private $role;
    protected $dbCnx;

    public function __construct($username, $password)
    {
        // check input
        if (empty($username) || empty($password)) {
            throw new InvalidArgumentException("Username, password, and role cannot be empty.");
        }
        if (!is_string($username) || !is_string($password)) {
            throw new InvalidArgumentException("Username, password, and role must be strings.");
        }
        

        // initialize properties
        $this->username = $username;
        $this->password = $password;

        // initialize database connection
        $this->dbCnx = require('db.php');
    }

    public function register_user($role)
    {
        // Set session cookie to last 30 days
        ini_set('session.cookie_lifetime', 30 * 24 * 60 * 60); // 30 days
        ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);   // 30 days

        // Security options (recommended)
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_secure', 0); 


        // Start session
        session_start();

        
        // check input
        if ($role !== 'Admin' && $role !== 'Alumni' && $role !== 'Student' && $role !== 'FacultyStaff') {
            throw new InvalidArgumentException("Role must be either 'Admin', 'Alumni', 'Student', or 'FacultyStaff'.");
        }
        $this->role = $role;

        // check if username already exists
        $stmt = $this->dbCnx->prepare("SELECT COUNT(*) FROM User WHERE username = ?");
        $stmt->execute([$this->username]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            throw new Exception("Username already exists.");
        }

        // insert new user
        $stmt = $this->dbCnx->prepare("INSERT INTO User (username, password_hash, role) VALUES (?, ?, ?)");
        $stmt->execute([$this->username, password_hash($this->password, PASSWORD_BCRYPT), $this->role]);
        $this->userId = $this->dbCnx->lastInsertId();

        // set session variables
        $_SESSION['user_id'] = $this->userId;
        $_SESSION['username'] = $this->username;
        $_SESSION['role'] = $this->role;

        // log user in
        $this->login_user();
        
        return $this->userId;
    }

    public function login_user()
    {
        session_unset();
        session_destroy();

        // Set session cookie to last 30 days
        ini_set('session.cookie_lifetime', 30 * 24 * 60 * 60); // 30 days
        ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);   // 30 days

        // Security options (recommended)
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_secure', 0); 


        // Start session
        session_start();

        // check if user is registered
        $stmt = $this->dbCnx->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!($user && password_verify($this->password, $user['password_hash']))) {
            throw new Exception("Invalid username or password.");
        }

        // initalize session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['loggedin'] = true;

        $this->userId = $user['user_id'];
        $this->username = $user['username'];
        $this->role = $user['role'];

        return true;
    }

    public function getId()
    {
        return $this->userId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setUsername($newUsername)
    {
        // check if new username already exists
        $stmt = $this->dbCnx->prepare("SELECT COUNT(*) FROM User WHERE username = ?");
        $stmt->execute([$newUsername]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            throw new Exception("Username already exists.");
        }

        // update username
        $stmt = $this->dbCnx->prepare("UPDATE User SET username = ? WHERE user_id = ?");
        $stmt->execute([$newUsername, $this->userId]);
        $this->username = $newUsername;

        return true;
    }
    public function setPassword($newPassword)
    {
        // update password
        $stmt = $this->dbCnx->prepare("UPDATE User SET password_hash = ? WHERE user_id = ?");
        $stmt->execute([password_hash($newPassword, PASSWORD_BCRYPT), $this->userId]);
        $this->password = $newPassword;

        return true;
    }

}

?>