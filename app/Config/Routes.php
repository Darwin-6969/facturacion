<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// 1. Rutas Protegidas del Sistema (Requieren filtro 'auth')
$routes->group('', ['filter' => ['auth']], function($routes) {
    // Inicio / Dashboard
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    
    // Módulo de Categorías
    $routes->get('categorias', 'CategoriaController::index');
    $routes->post('categorias/guardar', 'CategoriaController::guardar');
    $routes->get('categorias/eliminar/(:num)', 'CategoriaController::eliminar/$1');

    // Módulo de Marcas
    $routes->get('marcas', 'MarcaController::index');
    $routes->post('marcas/guardar', 'MarcaController::guardar');
    $routes->get('marcas/eliminar/(:num)', 'MarcaController::eliminar/$1');

    // Módulo de Clientes
    $routes->get('clientes', 'ClienteController::index');
    $routes->post('clientes/guardar', 'ClienteController::guardar');
    $routes->get('clientes/eliminar/(:num)', 'ClienteController::eliminar/$1');

    // Módulo de Proveedores
    $routes->group('proveedores', function($routes) {
        $routes->get('', 'ProveedorController::index');
        $routes->get('index', 'ProveedorController::index');
        $routes->post('guardar', 'ProveedorController::guardar');
        $routes->get('eliminar/(:num)', 'ProveedorController::eliminar/$1');
    });

    // Módulo de Usuarios
    $routes->group('usuarios', function($routes) {
        $routes->get('', 'UsuarioController::index');
        $routes->get('index', 'UsuarioController::index');
        $routes->post('guardar', 'UsuarioController::guardar');
        $routes->get('eliminar/(:num)', 'UsuarioController::eliminar/$1');
    });

});

// 2. Rutas Protegidas exclusivas para AJAX
$routes->group('', ['filter' => ['auth', 'ajax']], function($routes) {
    // Agrega aquí únicamente rutas que retornen JSON o fragmentos HTML vía AJAX.
});