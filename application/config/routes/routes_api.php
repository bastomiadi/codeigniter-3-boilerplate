<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $route['api/v1/users'] = 'api/v1/users';
// $route['api/v1/users/(:num)'] = 'api/v1/users/view/$1';

// $route['api/v2/users'] = 'api/v2/users';
// $route['api/v2/users/(:num)'] = 'api/v2/users/view/$1';

$route['api/v1/login'] = 'api/v1/auth/login';
$route['api/v1/register'] = 'api/v1/auth/register';
$route['api/v1/categories'] = 'api/v1/categories';
$route['api/v1/products'] = 'api/v1/products';
