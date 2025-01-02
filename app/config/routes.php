<?php
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

use flight\Engine;
use flight\net\Router;
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */

session_start();

$router->get("/", function (){
    Flight::render('landing/index');
});

$router->group('/auth', function () use ($router, $app) {
    $router->get("/", [AuthController::class, 'showUserLogin']); 

    $router->get('/user', [AuthController::class,'showUserLogin']); // Mihiditra page
    $router->post("/check-user", [AuthController::class, 'userLogin']); // Login tena login

    $router->get("/admin", [AuthController::class, 'showAdminLogin']);
    $router->post("/check-admin", [AuthController::class, 'adminLogin']);


    $router->get("/register", [AuthController::class, 'showRegistration']);
    $router->post("/create-user", [AuthController::class, 'register']);    

    $router->get("/logout", [AuthController::class,"logout"]);
});

$router->group("/dashboard", function () use ($router, $app) {
    $router->get("/", [DashboardController::class,"showDashboard"]);
    $router->get("/balance", [DashboardController::class,"showBalance"]);
});