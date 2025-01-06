<?php
namespace app\Models;

use Flight;
use PDO;

class userModel {
    protected $db;
    protected $table_name;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * ---------------------------
     * Authentication 
     * ---------------------------
     */
    public function authenticateUser($username, $password) {
        // Checking if the user already exist at first place
        $query = "SELECT * FROM christmas_user WHERE username = ? LIMIT 1";
        $STH = $this->db->prepare($query);
        $STH->execute([$username]);

        $user = $STH->fetch(PDO::FETCH_ASSOC); // Fetch we only need: one row
        if(!$user) 
            return ['status' => 'error', 'message'=> 'User not found', 'user' => null];

        if ($user['password'] !== $password) 
            return ['status' => 'error', 'message' => 'Invalid password', 'user' => null];

        return ['status' => 'success', 'message' => 'User found successfully', 'user' => $user];
    }

    public function authenticateAdmin($username, $password) {
        $result = $this->authenticateUser($username, $password);
        if ($result['status'] == 'error')
            return $result;

        $user = $result['user'];
        if ($user['is_admin'] != 1) 
            return ['status' => 'error', 'message' => 'You are not authorized as an admin.', 'user' => null];
    
        return ['status' => 'success', 'message' => 'Administrator found successfully', 'user' => $user];
    }

    public function addUser($username, $password) {
        // Check if that user already exist
        $query = "SELECT * FROM christmas_user WHERE username = ? LIMIT 1";
        $STH = $this->db->prepare($query);
        $STH->execute([$username]);
        
        if ($STH->fetch(PDO::FETCH_ASSOC)) 
            return ['status' => 'error', 'message' => 'Username already exists'];

        // Insert 
        $query = "INSERT INTO christmas_user (username, password) VALUES (?, ?)"; // is_admin is by default 0 
        $STH = $this->db->prepare($query);

        if ($STH->execute([$username, $password])) 
            return ['status' => 'success', 'message' => 'New user registered'];

        return ['status' => 'error', 'message' => 'Failed to register user'];
    }

    public function removeUser($username, $password) {
        $query = "SELECT * FROM christmas_user WHERE username = ? LIMIT 1";
        $STH = $this->db->prepare($query);
        $STH->execute([$username]);
        
        if (!$STH->fetch(PDO::FETCH_ASSOC)) 
            return ['status' => 'success', 'message' => 'Username removed'];
        
        return ['status' => 'error', 'message' => 'User doesn\'t exist'];
    }

    /**
     * ---------------------------
     * Moves 
     * ---------------------------
     */
    public function getUserBalance($user_id) {
        $query = "SELECT current_balance FROM christmas_user_balance_view WHERE user_id = ?";
        $STH = $this->db->prepare($query);
        $STH->execute([$user_id]);
        $row = $STH->fetch(PDO::FETCH_ASSOC); // Fetch we only need: one row
        return $row['current_balance'] ?? 0;
    }

    // Called in giftModel too
    public function getActualUserBalance() {
        if (!isset($_SESSION['user'])) {
            $error = "You must be logged in to access the dashboard.";
            Flight::render('error', ['message' => "AuthController->__construct(): " . $error]);
        }
        $user = $_SESSION['user'];
        return $this->getUserBalance($user['user_id']);
    }
}