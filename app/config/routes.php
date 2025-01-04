<?php
use App\Controllers\AuthController;
use App\Controllers\MainController;
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
$router->group('/auth', function () use ($router) {
    $router->get('/', [AuthController::class, 'renderUserLogin']); 

    $router->get('/user', [AuthController::class,'renderUserLogin']); // Just entering the page
    $router->post('/check-user', [AuthController::class, 'userLogin']); // Read login authentication method

    $router->get('/admin', [AuthController::class, 'renderAdminLogin']);
    $router->post('/check-admin', [AuthController::class, 'adminLogin']);


    $router->get('/register', [AuthController::class, 'renderRegistration']);
    $router->post('/create-user', [AuthController::class, 'register']);    

    $router->get('/logout', [AuthController::class,'logout']);
});

// Anything about what the user does
$router->group('/main', function () use ($router) {
    $router->get('/', [MainController::class,'renderMainPage']);
    $router->get('/account', [MainController::class,'renderAccountPage']);
    $router->post('/validate-gifts', [MainController::class,'validateGifts']);
});

// Admin
$router->group('/admin', function () use ($router) {
    $router->get('/', [AdminController::class,'renderDashboard']);
});

// Ajax calls
$router->group('/api', function () use ($router) {
    // Gifts
    $router->get('/gifts', [MainController::class, 'getGifts']);
    $router->get('/replace-gift', [MainController::class, 'replaceGift']);

    // Deposits
    // User
    $router->post('/add/deposit', [MoveController::class, 'addDeposit']);
    // Admin
    $router->post('/accept/deposit/', [AdminController::class, 'acceptDeposit']);
    $router->post('/reject/deposit/', [AdminController::class, 'rejectDeposit']);
});
