<?php
namespace app\Controllers;

use Flight;

class AdminController {
    protected $moveModel;
    protected $crudModel;
    protected $giftModel;


    protected $user;
    
    public function __construct() { 
        $this->moveModel = Flight::moveModel();
        $this->crudModel = Flight::crudModel();
        $this->giftModel = Flight::giftModel();
        $this->user = $_SESSION['user'];
    }

    public function renderDashboard() {
        $deposits = $this->getNonAcceptedDeposits();
        $gifts = $this->giftModel->getAvailableGifts();
        $data = [
            'title' => 'Giftmas Admin Dashboard',
            'page' => 'index',
            'username' => $this->user['username'],
            'deposits' => $deposits,
            'gifts' => $gifts
        ];
        Flight::render('admin/template', $data);
    }

    // Ajax after accepting or rejecting a deposit
    public function getNonAcceptedDeposits() {
        $deposits = $this->moveModel->getNonAcceptedDeposits();
        // Format the date and the money to look user friendly
        $deposits = array_map(function($deposit){
            $deposit['date'] = date('d-m-Y h:m:s', strtotime($deposit['date']));
            $deposit['amount'] = number_format($deposit['amount'],2);
            return $deposit;
        }, $deposits);
        return $deposits;        
    }

    // After changes, echo the rest of the deposits
    public function acceptDeposit() {
        $deposit_id = Flight::request()->data->deposit_id;
        $this->moveModel->acceptDeposit($deposit_id);
        $deposits = $this->getNonAcceptedDeposits();
        Flight::json($deposits);
    }

    public function rejectDeposit() {
        $deposit_id = Flight::request()->data->deposit_id;
        $this->moveModel->rejectDeposit($deposit_id);
        $this->getNonAcceptedDeposits();
        $deposits = $this->getNonAcceptedDeposits();
        Flight::json($deposits);
    }
    
    // ----------------------------------------------------
    // CRUD Methods
    // ----------------------------------------------------

    public function createGift() {
        $data = [
            'gift_name' => $_POST['gift_name'],
            'category_id' => $_POST['category_id'],
            'price' => $_POST['price'],
            'description' => $_POST['description'],
            'stock_quantity' => $_POST['stock_quantity'],
            'pic' => $_POST['pic']
        ];
        $this->crudModel->insert('gift', $data);
        Flight::redirect('/admin');
    }
    
    public function updateGift($id) {
        $data = [
            'gift_name' => $_POST['gift_name'],
            'category_id' => $_POST['category_id'],
            'price' => $_POST['price'],
            'description' => $_POST['description'],
            'stock_quantity' => $_POST['stock_quantity'],
            'pic' => $_POST['pic']
        ];
        $this->crudModel->update('gift', $data, $id);
        Flight::redirect('/admin');
    }
    
    public function deleteGift($id) {
        $this->crudModel->delete('gift', $id);
        Flight::redirect('/admin');
    }
    
}