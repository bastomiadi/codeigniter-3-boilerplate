<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserRole_model extends CI_Model {
    public function get_roles_by_user_id($user_id) {
        $this->db->select('roles.*');
        $this->db->from('user_roles');
        $this->db->join('roles', 'roles.role_id = user_roles.role_id');
        $this->db->where('user_roles.user_id', $user_id);
        return $this->db->get()->result();
    }
}