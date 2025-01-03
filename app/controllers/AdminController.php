<?php
namespace App\Controllers;

use Flight;

class AdminController {
    protected $moveModel;
    public function __construct() { 
        $this->moveModel = Flight::moveModel();
    }

    public function showDashboard() {
        $deposits = $this->moveModel->getNonAcceptedDeposits();
        // Format the date and the money to look user friendly
        $deposits = array_map(function($deposit){
            $deposit['date'] = date('d-m-Y h:m:s', strtotime($deposit['date']));
            return $deposit;
        }, $deposits);

        $data = [
            'title' => 'Giftmas Admin Dashboard',
            'page' => 'index',
            'deposits' => $deposits
        ];
        Flight::render('admin/template', $data);
    }

    // Ajax after accepting or rejecting a deposit
    public function getNonAcceptedDeposits() {
        $deposits = $this->moveModel->getNonAcceptedDeposits();
        // Format the date and the money to look user friendly
        $deposits = array_map(function($deposit){
            $deposit['date'] = date('d-m-Y h:m:s', strtotime($deposit['date']));
            return $deposit;
        }, $deposits);
        
        Flight::json($deposits);
    }

    // After changes, echo the rest of the deposits
    public function acceptDeposit($deposit_id) {
        $this->moveModel->acceptDeposit($deposit_id);
        // if ($result) 
        //     Flight::json(['status' => 'success', 'message' => 'Deposit accepted']);
        // else 
        //     Flight::json(['status' => 'error', 'message' => 'Failed to accept deposit']);
        $this->getNonAcceptedDeposits();
    }

    public function rejectDeposit($deposit_id) {
        $this->moveModel->rejectDeposit($deposit_id);
        $this->getNonAcceptedDeposits();

    }
}