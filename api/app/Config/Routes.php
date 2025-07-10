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

service('auth')->routes($routes);
