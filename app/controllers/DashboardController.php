<?php
namespace App\Controllers;

use Flight;

class DashboardController {
    public function __construct() { 
        if (!isset($_SESSION['user'])) {
            $error = "You must be logged in to access the dashboard.";
            Flight::render('error', ['message' => "AuthController->__construct(): " . $error]);
        }
    }

    /**
     * ---------------------------
     * Page rendering methods
     * ---------------------------
     */
    public function showDashboard() {
        $data = [
            'title' => 'Dashboard',
            'page' => 'index' 
        ];
        Flight::render('dashboard/template', $data);
    }

    public function showBalance() {
        $data = [
            'title' => 'Dashboard',
            'page' => 'balance' 
        ];
        Flight::render('dashboard/template', $data);
    }
}