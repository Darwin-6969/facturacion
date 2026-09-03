<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// 1. Rutas Protegidas que SÍ muestran vistas HTML (Solo requieren 'auth')
$routes->group('', ['filter' => ['auth']], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    $routes->get('categorias', 'CategoriaController::index');
});

// 2. Rutas Protegidas exclusivas para AJAX (Requieren 'auth' y 'ajax')
$routes->group('', ['filter' => ['auth', 'ajax']], function($routes) {
    $routes->post('categorias/guardar', 'CategoriaController::guardar');
    $routes->get('categorias/eliminar/(:num)', 'CategoriaController::eliminar/$1');
    // Agrega aquí otras rutas que solo respondan a JavaScript
});