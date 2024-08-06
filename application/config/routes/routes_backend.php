<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['backend'] = 'backend/dashboard';

$route['backend/login'] = 'backend/auth/login';

$route['backend/generator-model'] = 'backend/ModelGenerator/model';

$route['backend/generator-crud'] = 'backend/CrudGenerator/crud';
