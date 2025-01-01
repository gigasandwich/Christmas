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
    $router->post("/check-user", [AuthController::class, 'userLogin']); // Login tena login

    $router->get("/admin", [AuthController::class, 'showAdminLogin']);
    $router->post("/check-admin", [AuthController::class, 'adminLogin']);


    $router->get("/register", [AuthController::class, 'showRegistration']);
    $router->post("/create-user", [AuthController::class, 'register']);    
});

$router->group("/dashboard", function () use ($router, $app) {
    // TODO: Create the controller for this
});