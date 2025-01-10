<?php
namespace app\Controllers;

use Flight;

class AdminController
{
    protected $moveModel;
    protected $crudModel;
    protected $giftModel;


    protected $user;

    public function __construct()
    {
        $this->moveModel = Flight::moveModel();
        $this->crudModel = Flight::crudModel();
        $this->giftModel = Flight::giftModel();
        $this->user = $_SESSION['user'];
    }

    public function renderDashboard()
    {
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
    public function getNonAcceptedDeposits()
    {
        $deposits = $this->moveModel->getNonAcceptedDeposits();
        // Format the date and the money to look user friendly
        $deposits = array_map(function ($deposit) {
            $deposit['date'] = date('d-m-Y h:m:s', strtotime($deposit['date']));
            $deposit['amount'] = number_format($deposit['amount'], 2);
            return $deposit;
        }, $deposits);
        return $deposits;
    }

    // After changes, echo the rest of the deposits
    public function acceptDeposit()
    {
        $deposit_id = Flight::request()->data->deposit_id;
        $this->moveModel->acceptDeposit($deposit_id);
        $deposits = $this->getNonAcceptedDeposits();
        Flight::json($deposits);
    }

    public function rejectDeposit()
    {
        $deposit_id = Flight::request()->data->deposit_id;
        $this->moveModel->rejectDeposit($deposit_id);
        $this->getNonAcceptedDeposits();
        $deposits = $this->getNonAcceptedDeposits();
        Flight::json($deposits);
    }

    // ----------------------------------------------------
    // CRUD Methods
    // ----------------------------------------------------

    public function createGift()
    {
        $data = [
            'gift_name' => $_POST['gift_name'],
            'category_id' => $_POST['category_id'],
            'price' => $_POST['price'],
            'description' => $_POST['description'],
            'stock_quantity' => $_POST['stock_quantity'],
            'pic' => null
        ];

        try {
            // Insert pic first so that I can get the picname after
            $pic = "default_gift.png";
            if (isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
                $pic = $this->uploadFile($_FILES['pic']);
            }
            $data['pic'] = $pic;
            $this->crudModel->insert('gift', $data);
        } catch (\PDOException $e) {
            $message = $e->getMessage();
            Flight::render('error', ['message' => "AdminController->createGift(): " . $message]);
            exit;
        }

        Flight::redirect('/admin');
    }


    function uploadFile($file)
    {
        $ds = DIRECTORY_SEPARATOR;
        $uploadsDir = dirname(__DIR__, 2) . $ds . 'public' . $ds . 'assets' . $ds . 'img' . $ds . 'gifts' . $ds;

        // Avoid overwriting and duplocation (io ilay notenenin'i ramose t@ S2)
        $uploadedFileName = time() . '_' . basename($file['name']);

        // Check if the directory exists and is writable (for Linux)
        if (!is_dir($uploadsDir) || !is_writable($uploadsDir)) {
            $message = "Uploads directory does not exist or is not writable: $uploadsDir";
            Flight::render('error', ['message' => "AdminController->uploadFile(): " . $message]);
            exit;
        }

        // Move the uploaded file to the directory
        if (!move_uploaded_file($file['tmp_name'], $uploadsDir . $uploadedFileName)) {
            $message = "Error uploading file.";
            Flight::render('error', ['message' => "AdminController->uploadFile(): " . $message]);
            exit;
        }

        return $uploadedFileName; // We return it to use it later
    }

    public function updateGift($id)
    {
        $data = [
            'gift_name' => $_POST['gift_name'],
            'category_id' => $_POST['category_id'],
            'price' => $_POST['price'],
            'description' => $_POST['description'],
            'stock_quantity' => $_POST['stock_quantity'],
            'pic' => null
        ];
        
        try {
            $pic = $_POST['previous_pic']; // Default 
            if (isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
                $pic = $this->uploadFile($_FILES['pic']); // Upload the new file
            }
            $data['pic'] = $pic;
            $id = $_POST['gift_id'];
            $this->crudModel->update('gift', $data, $id);
        } catch (\PDOException $e) {
            $message = $e->getMessage();
            Flight::render('error', ['message' => "AdminController->updateGift(): " . $message]);
            exit;
        }

        Flight::redirect('/admin');
    }


    public function deleteGift()
    {   
        $id = $_GET['gift_id'];
        $this->crudModel->delete('gift', $id);
        Flight::redirect('/admin');
    }

}