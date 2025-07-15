<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('user', function (RouteCollection $routes) {
    $routes->post('create', 'UserManager::createUser');
    $routes->post('update', 'UserManager::updateUser');
    $routes->post('update/(:any)', 'UserManager::updateUser/$1');
    $routes->post('delete', 'UserManager::deleteUser');
});

$routes->group('auth', function (RouteCollection $routes) {
    $routes->post('login', 'Auth::validateLogin');
    $routes->post('logout', 'Auth::logout');
    $routes->post('updatePassword', 'Auth::updatePassword');
    $routes->post('deleteDefaultCookie', 'Auth::deleteDefaultCookie');
    $routes->get('validate-page', 'Auth::validatePageRequest');
});

$routes->group('pegawai', function (RouteCollection $routes) {
    $routes->post('get-data',        'Pegawai::getData');
    $routes->post('save',            'Pegawai::save');
    $routes->delete('delete/(:num)', 'Pegawai::delete/$1');
    $routes->get('detail/(:num)',    'Pegawai::detail/$1');
});

$routes->group('siswa', function (RouteCollection $routes) {
    $routes->post('get-data', 'Siswa::getData');
    $routes->post('save', 'Siswa::save');
    $routes->delete('delete/(:num)', 'Siswa::delete/$1');
    $routes->get('detail/(:num)', 'Siswa::detail/$1');
});


service('auth')->routes($routes);
