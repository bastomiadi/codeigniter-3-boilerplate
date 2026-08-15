<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

    public function get_all_menus() {
        $user_id = $this->session->userdata('user_id');

        if ( ! $user_id) {
            return array();
        }

        // Collect permission ids owned by the user's roles
        $this->db->select('roles_permissions.permission_id');
        $this->db->from('roles_permissions');
        $this->db->join('user_roles', 'user_roles.role_id = roles_permissions.role_id');
        $this->db->where('user_roles.user_id', $user_id);
        $permission_ids = array_column($this->db->get()->result_array(), 'permission_id');

        // Menus: those matching the user's permissions, plus menus without any permission
        $this->db->select('menus.*');
        $this->db->from('menus');
        $this->db->group_start();
        if ( ! empty($permission_ids)) {
            $this->db->where_in('menus.permission_id', $permission_ids);
            $this->db->or_where('menus.permission_id', 0);
        } else {
            $this->db->where('menus.permission_id', 0);
        }
        $this->db->group_end();

        $this->db->order_by('menus.parent_id', 'asc');
        $this->db->order_by('menus.menu_name', 'asc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return array();
    }

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
