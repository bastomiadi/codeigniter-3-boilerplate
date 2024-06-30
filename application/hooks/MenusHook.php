<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenusHook {

    public function inject_menus() {
        $CI =& get_instance();
        $CI->load->library('menu_library');
        $menus = $CI->menu_library->get_menus();
        $CI->load->vars('menus', $menus);
    }
}
