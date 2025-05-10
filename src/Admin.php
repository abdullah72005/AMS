<?php

require_once('User.php');
require_once("../src/Alumni.php");
require_once("../src/FacultyStaff.php");
require_once("../src/Student.php");


class Admin extends User
{
    public function __construct($username)
    {
        parent::__construct($username);
    }

    public function createUser($username, $password, $role)
    {
        // init db
        $dbCnx = require('db.php');

        // Validate inputs
        if (empty($username) || empty($password) || empty($role)) {
            throw new InvalidArgumentException("Username, password, and role cannot be empty.");
        }

        if (!is_string($username) || !is_string($password) || !is_string($role)) {
            throw new InvalidArgumentException("All parameters must be strings.");
        }

        if (!in_array($role, ['Admin', 'Alumni', 'Student', 'FacultyStaff'])) {
            throw new InvalidArgumentException("Invalid role specified.");
        }

        // Check username availability
        $stmt = $dbCnx->prepare("SELECT COUNT(*) FROM User WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Username already exists.");
        }

        // Insert new user
        $stmt = $dbCnx->prepare("INSERT INTO User (username, password_hash, role) VALUES (?, ?, ?)");
        $stmt->execute([
            $username,
            password_hash($password, PASSWORD_BCRYPT),
            $role
        ]);

        $insertedUserId = $dbCnx->lastInsertId();

        if ($role === 'Alumni') {
            $stmt = $dbCnx->prepare("INSERT INTO Alumni (userId) VALUES (:userId)");
        } elseif ($role === 'FacultyStaff') {
            $stmt = $dbCnx->prepare("INSERT INTO FacultyStaff (user_id) VALUES (:userId)");
        } elseif ($role === 'Student') {
            $stmt = $dbCnx->prepare("INSERT INTO Student (userId) VALUES (:userId)");
        } elseif ($role === 'Admin') {
            $stmt = $dbCnx->prepare("INSERT INTO Admin (user_id) VALUES (:userId)");
        }
        $stmt->bindParam(':userId', $insertedUserId);
        $stmt->execute();

        return $insertedUserId;
    }

    public function updateUserData($userId, $newUsername = null, $newPassword = null, $newRole = null)
    {
        // init db
        $dbCnx = require('db.php');

        // Validate at least one parameter is provided
        if ($newUsername === null && $newPassword === null && $newRole === null) {
            throw new InvalidArgumentException("At least one update parameter must be provided.");
        }

        $updates = [];
        $params = [];

        // Handle username update
        if ($newUsername !== null) {
            if (!is_string($newUsername) || empty($newUsername)) {
                throw new InvalidArgumentException("Username must be a valid string.");
            }

            // Check username availability
            $stmt = $dbCnx->prepare("SELECT COUNT(*) FROM User WHERE username = ? AND user_id != ?");
            $stmt->execute([$newUsername, $userId]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Username already exists.");
            }

            $updates[] = "username = ?";
            $params[] = $newUsername;
        }

        // Handle password update
        if ($newPassword !== null) {
            if (!is_string($newPassword) || empty($newPassword)) {
                throw new InvalidArgumentException("Password must be a valid string.");
            }

            $updates[] = "password_hash = ?";
            $params[] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        // Handle role update
        if ($newRole !== null) {
            if (!in_array($newRole, ['Admin', 'Alumni', 'Student', 'FacultyStaff'])) {
                throw new InvalidArgumentException("Invalid role specified.");
            }

            $updates[] = "role = ?";
            $params[] = $newRole;
        }

        // Add user ID to params
        $params[] = $userId;

        // Build and execute query
        $query = "UPDATE User SET " . implode(', ', $updates) . " WHERE user_id = ?";
        $stmt = $dbCnx->prepare($query);
        $stmt->execute($params);
    }

    public function deleteUser($userId)
    {
        // init db
        $dbCnx = require('db.php');
        // Verify user exists
        $stmt = $dbCnx->prepare("SELECT user_id FROM User WHERE user_id = ?");
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            throw new Exception("User not found.");
        }

        // Delete user
        $stmt = $dbCnx->prepare("DELETE FROM User WHERE user_id = ?");
        $stmt->execute([$userId]);

    }

    public function getAllUsers()
    {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->query("SELECT user_id, username, role FROM User");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser($username)
    {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("SELECT user_id, username, role FROM User WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            throw new Exception("User not found.");
        }
        
        return $user;
    }
    public function register_user($password, $role)
    {
        // init db
        $dbCnx = require('db.php');

        try {
            $id = parent::register_user($password, $role); 
            $stmt = $dbCnx->prepare("INSERT INTO Admin (user_id) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $this->login_user($password); // Log in the user after registration
            return $id;
        }
        catch (Exception $e) {
            throw new Exception("Failed to register student: " . $e->getMessage());
        }

    }

    public function login_user($password)
    {
        parent::login_user($password);

        $_SESSION['userObj'] = $this;
    }


    public static function getAllUserData($userId)
    {
        $arr1 = parent::getAllUserData($userId);

        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM `Admin` WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $arr2 = $stmt->fetch(PDO::FETCH_ASSOC);

        return array_merge($arr1, $arr2);
    }
}
