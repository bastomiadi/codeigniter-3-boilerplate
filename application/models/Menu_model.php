<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

    public function get_all_menus() {
        // $this->db->order_by('parent_id', 'asc');
        // $this->db->order_by('menu_name', 'asc');
        // $query = $this->db->get('menus');

        // echo '<pre>';
        // print_r($query->result());
        // echo '</pre>';
        // die;

        // return $query->result();

        $user_id = $this->session->userdata('user_id');

        
        if (!$user_id) {
            return false; // No user logged in, return empty array or handle appropriately
        }

        // Query to fetch menus based on user permissions
        $this->db->select('menus.*');
        $this->db->from('menus');
        $this->db->join('permissions', 'menus.permission_id = permissions.permission_id', 'left');
        $this->db->join('roles_permissions', 'permissions.permission_id = roles_permissions.permission_id', 'left');
        $this->db->join('roles', 'roles_permissions.role_id = roles.role_id', 'left');
        $this->db->join('user_roles', 'roles.role_id = user_roles.role_id', 'left');
        $this->db->where('user_roles.user_id', $user_id);
        $this->db->or_where('menus.permission_id IS NULL'); // Include menus with no specific permission

        $this->db->order_by('menus.parent_id', 'asc');
        $this->db->order_by('menus.menu_name', 'asc');

        $query = $this->db->get();

        

        return $query->result();
    }

    // public function get_all() {
    //     $this->db->order_by('parent_id', 'asc');
    //     $this->db->order_by('menu_name', 'asc');
    //     return $this->db->get('menus')->result();
    // }

    public function get_menu_tree($parent_id = 0, $level = 0) {
        $this->db->select('menu_id, menu_name, parent_id');
        $this->db->from('menus');
        $this->db->where('parent_id', $parent_id);
        $query = $this->db->get();

        $menus = array();
        foreach ($query->result() as $row) {
            $row->level = $level;
            $row->children = $this->get_menu_tree($row->menu_id, $level + 1);
            $menus[] = $row;
        }
        return $menus;
    }

    public function insert_menu($data) {
        return $this->db->insert('menus', $data);
    }

    public function get_menu_by_id($id) {
        return $this->db->get_where('menus', array('menu_id' => $id))->row();
    }

    public function update_menu($id, $data) {
        $this->db->where('menu_id', $id);
        return $this->db->update('menus', $data);
    }

    public function delete_menu($id) {
        $this->db->where('menu_id', $id);
        return $this->db->delete('menus');
    }
}
