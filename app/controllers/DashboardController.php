<?php
namespace App\Controllers;

use Flight;

class DashboardController {
    protected $giftModel;
    protected $userModel;
    protected $user;

    public function __construct() { 
        if (!isset($_SESSION['user'])) {
            $error = "You must be logged in to access the dashboard.";
            Flight::render('error', ['message' => "AuthController->__construct(): " . $error]);
        }
        $this->giftModel = Flight::giftModel();
        $this->userModel = Flight::userModel(); 
        $this->user = $_SESSION['user'];
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

    /**
     * ---------------------------
     * Gift interaction
     * ---------------------------
     */

    public function getGifts() {
        // query for GET and data for POST 😭
        $data = Flight::request()->query;
        $boys = $data->boys;
        $girls = $data->girls;
        $gifts = $this->giftModel->getGiftSuggestions($boys, $girls); 
        // $gifts = $this->giftModel->getAllGifts(); // Uncomment For display testing 
        Flight::json($gifts);
    }

    public function replaceGift() {
        $data = Flight::request()->query; 
        $index = $data->index;
        sleep(1);
        if ($response = $this->giftModel->replaceGift($index))
            Flight::json($response); 
        else 
        Flight::json(['error' => 'No gift index provided'], 400); 
    }
}