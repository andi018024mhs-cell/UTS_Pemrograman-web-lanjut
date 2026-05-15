<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// ============================================
// ROUTES UNTUK CRUD MAHASISWA
// ============================================

// Custom routes untuk AJAX endpoints
$routes->get('mahasiswa', 'Mahasiswa::index');                          // Display view
$routes->get('mahasiswa/getData', 'Mahasiswa::getData');                // Get all data
$routes->post('mahasiswa/store', 'Mahasiswa::store');                   // Insert data
$routes->get('mahasiswa/show/(:num)', 'Mahasiswa::show/$1');            // Get by ID
$routes->post('mahasiswa/update', 'Mahasiswa::update');                 // Update data
$routes->post('mahasiswa/delete', 'Mahasiswa::delete');                 // Delete data
