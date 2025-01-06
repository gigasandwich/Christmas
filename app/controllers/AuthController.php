<?php
namespace app\Controllers;

use Flight;

class AuthController {
    protected $userModel;
    public function __construct() { 
        $this->userModel = Flight::userModel(); // That variable of Flight has been created in service.php
    }

    /**
     * ---------------------------
     * Page rendering methods
     * ---------------------------
     */
    public function renderUserLogin() {
        $data = [
            'title' => 'Giftmas login',
            'page' => 'user' // Not auth/user because the template file is already in auth folder
        ];
        Flight::render('auth/template', $data);
    }

    public function renderAdminLogin() {
        $data = [
            'title' => 'Giftmas admin login',
            'page' => 'admin' 
        ];
        Flight::render('auth/template', $data);
    }
    
    public function renderRegistration() {
        $data = [
            'title' => 'Giftmas registration',
            'page' => 'register' 
        ];
        Flight::render('auth/template', $data);
    }

    /**
     * ---------------------------
     * Authentication methods
     * ---------------------------
     */

    // User goes to dashboard page
    public function userLogin() {
        $data = Flight::request()->data;
        $username = $data->username;
        $password = $data->password;

        try {
            $result = $this->userModel->authenticateUser($username, $password);

            if ($result['status'] === 'success') {
                // Session is started at bootstrap.php
                $_SESSION['user'] = $result['user'];
                $_SESSION['loading'] = true;
                Flight::redirect('/main');
            } else {
                $data = ['page' => 'user', 'message' => "Invalid username or password."]; // Message is not the one from the model, because it's more convenient to do this
                Flight::render('auth/template', $data);
            }
        } catch (\Exception $e) {
            Flight::render('error', ['message' => "AuthController->userLogin(): " . $e->getMessage()]); // error is the first file in view/
        }
    }

    // The admin goes to CRUD
    public function adminLogin() {
        $data = Flight::request()->data;
    
        $username = $data->username;
        $password = $data->password;
    
        try {
            $result = $this->userModel->authenticateAdmin($username, $password);
    
            if ($result['status'] === 'success') {
                $_SESSION['user'] = $result['user'];
                Flight::redirect('/admin');
            } else {
                $data = ['page' => 'admin', 'message' => $result['message'] ]; // Specified message because it looks cool compared to normal user authentication 😭
                Flight::render('auth/template', $data);
            }
        } catch (\Exception $e) {
            Flight::render('error', ['message' => "AuthController->adminLogin(): " . $e->getMessage()]);
        }
    }

    public function register() {
        $data = Flight::request()->data;

        $username = $data->username;
        $password = $data->password;
        $confirm_password = $data->confirm_password;

        try {
            // Password validation 
            if ($password !== $confirm_password) {
                $data = ['page' => 'register', 'message' => 'Passwords do not match.'];
                Flight::render('auth/template', $data);
                return;
            }

            // Add user
            $result = $this->userModel->addUser($username, $password);

            if ($result['status'] === 'success') {
                $data = ['page' => 'user', 'message' => 'User created.'];
                Flight::render('auth/template', $data);
            } else {
                $data = ['page' => 'register', 'message' => $result['message']];
                Flight::render('auth/template', $data);
            }
        } catch (\Exception $e) {
            Flight::render('error', ['message' => "AuthController->register(): " . $e-> getMessage()]);
        }
    }

    public function logout() {
        session_destroy();
        Flight::redirect('/');
    }
}