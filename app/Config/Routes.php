<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// Rutas Protegidas (Requieren autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    // Registra aquí los demás módulos protegidos...

    $routes->get('categorias', 'CategoriaController::index');
    $routes->post('categorias/guardar', 'CategoriaController::guardar');
    $routes->get('categorias/eliminar/(:num)', 'CategoriaController::eliminar/$1');
});