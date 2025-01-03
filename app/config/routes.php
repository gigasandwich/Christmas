<?php
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AdminController;
use App\Controllers\MoveController;


use flight\Engine;
use flight\net\Router;
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */

session_start();

$router->get('/', function (){
    Flight::render('landing/index');
});

// Auth
$router->group('/auth', function () use ($router, $app) {
    $router->get('/', [AuthController::class, 'showUserLogin']); 

    $router->get('/user', [AuthController::class,'showUserLogin']); // Mihiditra page
    $router->post('/check-user', [AuthController::class, 'userLogin']); // Login tena login

    $router->get('/admin', [AuthController::class, 'showAdminLogin']);
    $router->post('/check-admin', [AuthController::class, 'adminLogin']);


    $router->get('/register', [AuthController::class, 'showRegistration']);
    $router->post('/create-user', [AuthController::class, 'register']);    

    $router->get('/logout', [AuthController::class,'logout']);
});

// Dashboard
$router->group('/dashboard', function () use ($router, $app) {
    $router->get('/', [DashboardController::class,'showDashboard']);
    $router->get('/balance', [DashboardController::class,'showBalance']);
    $router->post('/validate-gifts', [DashboardController::class,'validateGifts']);
});

// Admin
$router->group('/admin', function () use ($router, $app) {
    $router->get('/', [AdminController::class,'showDashboard']);
});

// Ajax calls
$router->group('/api', function () use ($router, $app) {
    $router->get('/gifts', [DashboardController::class, 'getGifts']);

    // Deposits
    // User
    $router->post('/add/deposit', [MoveController::class, 'addDeposit']);
    // Admin
    $router->post('/accept/deposit/', [AdminController::class, 'acceptDeposit']);
    $router->post('/reject/deposit/', [AdminController::class, 'rejectDeposit']);

    // Gifts
    $router->get('/replace-gift', [DashboardController::class, 'replaceGift']);
});
