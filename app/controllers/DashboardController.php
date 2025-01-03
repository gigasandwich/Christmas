<?php
namespace App\Controllers;

use Flight;

class DashboardController
{
    protected $giftModel;
    protected $userModel;
    protected $user;

    public function __construct()
    {
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
    public function showDashboard()
    {
        $data = [
            'title' => 'Dashboard',
            'page' => 'index',
            'balance' => $this->userModel->getActualUserBalance()
        ];
        Flight::render('dashboard/template', $data);
    }

    public function showAccount()
    {
        $data = [
            'title' => 'Dashboard',
            'page' => 'account',
            'balance' => $this->userModel->getActualUserBalance()
        ];
        Flight::render('dashboard/template', $data);
    }

    /**
     * ---------------------------
     * Gift interaction
     * ---------------------------
     */

    public function getGifts()
    {
        // query for GET and data for POST 😭
        $data = Flight::request()->query;
        $boys = $data->boys;
        $girls = $data->girls;

        $gifts = $this->giftModel->getGiftSuggestions($boys, $girls);
        $totalPrice = $this->giftModel->getTotalPrice();
        // $gifts = $this->giftModel->getAllGifts(); // Uncomment For display testing 
        Flight::json([
            'gifts' => $gifts,
            'total_price' => $totalPrice
        ]);
    }

    public function replaceGift()
    {
        $data = Flight::request()->query;
        $index = $data->index;
        $totalPrice = $this->giftModel->getTotalPrice();
        sleep(1);
        if ($newGift = $this->giftModel->replaceGift($index))
            Flight::json(['new_gift' => $newGift, 'total_price' => $totalPrice]);
        else
            Flight::json(['error' => 'No gift index provided'], 400);
    }

    public function validateGifts()
    {
        $userId = $_SESSION['user']['user_id'];
        $result = $this->giftModel->finalizeSelections($userId, $_SESSION['gift_suggestions']);
        if($result)
            unset($_SESSION['gift_suggestions']);
            // Show dashboard + message
            $data = [
                'title' => 'Dashboard',
                'page' => 'index',
                'balance' => $this->userModel->getActualUserBalance(),
                'message' => 'Gift purchase successfully validated!'
            ];
            Flight::render('dashboard/template', $data);
    }

}