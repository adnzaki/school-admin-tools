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

service('auth')->routes($routes);
