<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// application/config/hooks.php
// $hook['pre_controller'] = array(
//     'class'    => 'MenusHook',
//     'function' => 'inject_menus',
//     'filename' => 'MenusHook.php',
//     'filepath' => 'hooks',
// );


// application/config/hooks.php
$hook['post_controller_constructor'] = array(
    'class'    => 'MenusHook',
    'function' => 'inject_menus',
    'filename' => 'MenusHook.php',
    'filepath' => 'hooks',
);
