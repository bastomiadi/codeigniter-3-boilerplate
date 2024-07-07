<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_library {

    protected $CI;
    protected $menus;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Menu_model');
        $this->menus = $this->CI->Menu_model->get_all_menus();
    }

    public function get_menus() {
        if (!$this->menus) {
            return array(); // Return empty array if no menus retrieved
        }
        return $this->build_tree($this->menus);
    }

    private function build_tree(array $elements, $parentId = 0) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = $this->build_tree($elements, $element->menu_id);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
