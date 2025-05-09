<?php


abstract class User
{
    private $userId;
    private $username;
    private $password;
    private $role;

    static public $validMajors = ['Computer Science', 'Information Technology', 'Software Engineering', 'Electrical Engineering', 'Mechanical Engineering', 'Civil Engineering', 'Biomedical Engineering', 'Mathematics', 'Statistics', 'Physics', 'Chemistry', 'Biology', 'Environmental Science', 'Psychology', 'Sociology', 'Political Science', 'Economics', 'Business Administration', 'Accounting', 'Finance', 'Marketing', 'Management Information Systems', 'Communications', 'Journalism', 'English Literature', 'History', 'Philosophy', 'Education', 'Nursing', 'Public Health', 'Social Work', 'Criminal Justice', 'Art', 'Music', 'Theater', 'Architecture', 'Graphic Design', 'Data Science', 'Cybersecurity', 'International Relations'];

    public function __construct($username)
    {
        // check input
        if (empty($username)) {
            throw new InvalidArgumentException("Username cannot be empty.");
        }
        if (!is_string($username)) {
            throw new InvalidArgumentException("Username must be strings.");
        }
        
        // initialize properties
        $this->username = $username;
    }

    public function register_user($password, $role)
    {
        // init db
        $dbCnx = require('db.php');
        
        // check input
        if ($role !== 'Admin' && $role !== 'Alumni' && $role !== 'Student' && $role !== 'FacultyStaff') {
            throw new InvalidArgumentException("Role must be either 'Admin', 'Alumni', 'Student', or 'FacultyStaff'.");
        }

        // check input
        if (empty($password)) {
            throw new InvalidArgumentException("password cannot be empty.");
        }
        if (!is_string($password)) {
            throw new InvalidArgumentException("password must be strings.");
        }

        $this->role = $role;
        $this->password = $password;

        // check if username already exists
        $stmt = $dbCnx->prepare("SELECT COUNT(*) FROM User WHERE username = ?");
        $stmt->execute([$this->username]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            throw new Exception("Username already exists.");
        }

        // insert new user
        $stmt = $dbCnx->prepare("INSERT INTO User (username, password_hash, role) VALUES (?, ?, ?)");
        $stmt->execute([$this->username, password_hash($this->password, PASSWORD_BCRYPT), $this->role]);
        $this->userId = $dbCnx->lastInsertId();

        
        return $this->userId;
    }

    public function login_user($password)
    {

        // init db
        $dbCnx = require('db.php');

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

         // check input
         if (empty($password)) {
            throw new InvalidArgumentException("password cannot be empty.");
        }
        if (!is_string($password)) {
            throw new InvalidArgumentException("password must be strings.");
        }

        // check if user is registered
        $stmt = $dbCnx->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!($user && password_verify($password, $user['password_hash']))) {
            throw new Exception("Invalid username or password.");
        }

        $this->userId = $user['user_id'];
        $this->username = $user['username'];
        $this->password = $password;
        $this->role = $user['role'];


        // initalize session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['loggedin'] = true;


        $_SESSION['user'] = serialize($this);
        $this->userId = $user['user_id'];
        $this->username = $user['username'];
        $this->password = $password;
        $this->role = $user['role'];

        return true;
    }

    public function getId()
    {
        // init db
        $dbCnx = require('db.php');

        // check if user is registered
        $stmt = $dbCnx->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new Exception("User not registered, has no id.");
        }

        return $user['user_id'];
    }

    public function getUsername()
    {
        return $this->username;
    }


    public function setUsername($newUsername)
    {
        // init db
        $dbCnx = require('db.php');

        // check if new username already exists
        $stmt = $dbCnx->prepare("SELECT COUNT(*) FROM User WHERE username = ?");
        $stmt->execute([$newUsername]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            throw new Exception("Username already exists.");
        }

        // update username
        $stmt = $dbCnx->prepare("UPDATE User SET username = ? WHERE user_id = ?");
        $stmt->execute([$newUsername, $this->userId]);
        $this->username = $newUsername;

        $_SESSION['username'] = $newUsername;

        return true;
    }

    public function setPassword($newPassword, $oldPassword)
    {
        // init db
        $dbCnx = require('db.php');

        // check input
        if (empty($newPassword)) {
            throw new InvalidArgumentException("password cannot be empty.");
        }
        if (!is_string($newPassword)) {
            throw new InvalidArgumentException("password must be strings.");
        }

        // check if old password is correct
        $stmt = $dbCnx->prepare("SELECT * FROM User WHERE user_id = ?");
        $stmt->execute([$this->userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!($user && password_verify($oldPassword, $user['password_hash']))) {
            throw new Exception("Invalid old password.");
        }
        // check if new password is same as old password
        if (password_verify($newPassword, $user['password_hash'])) {
            throw new Exception("New password cannot be the same as old password.");
        }
        
        // update password
        $stmt = $dbCnx->prepare("UPDATE User SET password_hash = ? WHERE user_id = ?");
        $stmt->execute([password_hash($newPassword, PASSWORD_BCRYPT), $this->userId]);
        $this->password = $newPassword;

        return true;
    }

    public function setRole($newRole)
    {
        // init db
        $dbCnx = require('db.php');

        // check if new role is valid
        if ($newRole !== 'Admin' && $newRole !== 'Alumni' && $newRole !== 'Student' && $newRole !== 'FacultyStaff') {
            throw new InvalidArgumentException("Role must be either 'Admin', 'Alumni', 'Student', or 'FacultyStaff'.");
        }

        // update role
        $stmt = $dbCnx->prepare("UPDATE User SET role = ? WHERE user_id = ?");
        $stmt->execute([$newRole, $this->userId]);
        $this->role = $newRole;

        // insert forign link with Alumni, FacultyStaff, Student, Admin db tables. depending on the inputed role
        switch ($newRole) {
            case 'Alumni':
                $stmt = $dbCnx->prepare("INSERT INTO Alumni (userId) VALUES (?)");
                break;
            case 'FacultyStaff':
                $stmt = $dbCnx->prepare("INSERT INTO FacultyStaff (user_id) VALUES (?)");
                break;
            case 'Student':
                $stmt = $dbCnx->prepare("INSERT INTO Student (userId) VALUES (?)");
                break;
            case 'Admin':
                $stmt = $dbCnx->prepare("INSERT INTO Admin (user_id) VALUES (?)");
                break;
        }
        $stmt->execute([$this->userId]);    
        
        $_SESSION['role'] = $newRole;

        return true;
    }


   
    static public function getUsernameFromId($userId)
    {
        // init db
        $dbCnx = require('db.php');

        // check input
        if (empty($userId)) {
            throw new InvalidArgumentException("No user with this id.");
        }
        // Get username from user ID
        $stmt = $dbCnx->prepare("SELECT username FROM User WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }



    static public function getRole($username)
    {
        if (empty($username) || !is_string($username)) {
            throw new InvalidArgumentException("Username must be a non-empty string.");
        }
    
        // Get DB connection (assuming db.php returns a PDO instance)
        $dbCnx = require('db.php');
    
        // Prepare and execute query
        $stmt = $dbCnx->prepare("SELECT role FROM User WHERE username = ?");
        $stmt->execute([$username]);

        // Fetch result
        $role = $stmt->fetchColumn();
        

        return $role ?: null;
    }

    static public function getRoleById($userId)
    {
        if (empty($userId) || !is_numeric($userId)) {
            throw new InvalidArgumentException("User ID must be a non-empty number.");
        }
    
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT role FROM User WHERE user_id = ?");
        $stmt->execute([$userId]);
    
        $role = $stmt->fetchColumn();
    
        return $role ?: null;
    }

    public function searchAlumni($username)
    {
        // init db
        $dbCnx = require('db.php');

        // check input
        if (empty($username)) {
            throw new InvalidArgumentException("Username cannot be empty.");
        }
        if (!is_string($username)) {
            throw new InvalidArgumentException("Username must be strings.");
        }

        // search for alumni
        $stmt = $dbCnx->prepare("SELECT User.username, Alumni.graduationDate, Alumni.major Alumni.mentor FROM Alumni INNER JOIN User ON Alumni.userId User.user_id WHERE User.username = ?;");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllUserData($userId)
    {
        // init db
        $dbCnx = require('db.php');

        // check if user is registered
        $stmt = $dbCnx->prepare("SELECT * FROM User WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new Exception("User not registered.");
        }

        return $user;
    }

}


?>