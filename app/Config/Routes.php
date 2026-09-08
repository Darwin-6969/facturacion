<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// --------------------------------------------------------------------
// 1. Rutas compartidas (Accesibles por 'administrador' y 'encargado')
// --------------------------------------------------------------------
$routes->group('', ['filter' => ['auth']], function($routes) {
    // Redirección del Inicio / Dashboard según rol
    $routes->get('/', 'Home::index');
    $routes->get('dashboard', 'Home::index');

    // Módulo de Facturación
    $routes->get('facturas', 'FacturacionController::index');
    $routes->get('facturas/nueva', 'FacturacionController::nueva');
});

// --------------------------------------------------------------------
// 2. Rutas exclusivas de Administrador (Requieren 'auth' y 'admin')
// --------------------------------------------------------------------
$routes->group('', ['filter' => ['auth', 'admin']], function($routes) {
    
    // Categorías
    $routes->get('categorias', 'CategoriaController::index');
    $routes->post('categorias/guardar', 'CategoriaController::guardar');
    $routes->get('categorias/eliminar/(:num)', 'CategoriaController::eliminar/$1');

    // Marcas
    $routes->get('marcas', 'MarcaController::index');
    $routes->post('marcas/guardar', 'MarcaController::guardar');
    $routes->get('marcas/eliminar/(:num)', 'MarcaController::eliminar/$1');

    // Clientes
    $routes->get('clientes', 'ClienteController::index');
    $routes->post('clientes/guardar', 'ClienteController::guardar');
    $routes->get('clientes/eliminar/(:num)', 'ClienteController::eliminar/$1');

    // Proveedores
    $routes->group('proveedores', function($routes) {
        $routes->get('', 'ProveedorController::index');
        $routes->get('index', 'ProveedorController::index');
        $routes->post('guardar', 'ProveedorController::guardar');
        $routes->get('eliminar/(:num)', 'ProveedorController::eliminar/$1');
    });

    // Usuarios
    $routes->group('usuarios', function($routes) {
        $routes->get('', 'UsuarioController::index');
        $routes->get('index', 'UsuarioController::index');
        $routes->post('guardar', 'UsuarioController::guardar');
        $routes->get('eliminar/(:num)', 'UsuarioController::eliminar/$1');
    });

    // Productos
    $routes->group('productos', function($routes) {
        $routes->get('', 'ProductoController::index');
        $routes->get('index', 'ProductoController::index');
        $routes->post('guardar', 'ProductoController::guardar');
        $routes->get('eliminar/(:num)', 'ProductoController::eliminar/$1');
    });
});

// --------------------------------------------------------------------
// 3. Rutas AJAX compartidas para la Facturación
// --------------------------------------------------------------------
$routes->group('', ['filter' => ['auth', 'ajax']], function($routes) {
    $routes->get('facturacion/buscarCliente', 'FacturacionController::buscarCliente');
    $routes->get('facturacion/buscarProducto', 'FacturacionController::buscarProducto');
    $routes->get('facturacion/verDetalle/(:num)', 'FacturacionController::verDetalle/$1');
    $routes->post('facturacion/guardar', 'FacturacionController::guardar');
});