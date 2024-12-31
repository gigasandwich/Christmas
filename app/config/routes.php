<?php
use App\Controllers\AuthController;

use flight\Engine;
use flight\net\Router;
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->get("/", function (){
    Flight::render('landing/index');
});

$router->group('/auth', function () use ($router, $app) {
    $router->get("/", [AuthController::class, 'showUserLogin']); 

    $router->get('/user', [AuthController::class,'showUserLogin']); // Mihiditra page
    $router->get("/check-user", [AuthController::class, 'showUserLogin']); // Login tena login

    $router->get("/admin", [AuthController::class, 'showAdminLogin']);
    $router->post("/check-admin", [AuthController::class, 'adminLogin']);


    $router->get("/register", [AuthController::class, 'showRegistration']);
    $router->post("/create-user", [AuthController::class, 'register']);    
});