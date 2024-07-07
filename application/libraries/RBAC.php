<?php

class RBAC {

    protected $ci;

    public function __construct() {
        $this->ci =& get_instance();
    }

    public function check_permission($permission) {
        $user_id = $this->ci->session->userdata('user_id');
        if (!$user_id) {
            redirect('login');
        }

        $this->ci->db->select('permissions.permission_name');
        $this->ci->db->from('user_roles');
        $this->ci->db->join('roles', 'user_roles.role_id = roles.role_id');
        $this->ci->db->join('roles_permissions', 'roles.role_id = roles_permissions.role_id');
        $this->ci->db->join('permissions', 'roles_permissions.permission_id = permissions.permission_id');
        $this->ci->db->where('user_roles.user_id', $user_id);
        $this->ci->db->where('permissions.permission_name', $permission);
        $query = $this->ci->db->get();

        if ($query->num_rows() == 0) {
            show_error('You do not have permission to access this page.', 403);
        }
    }

    // public function get_menus() {
    //     $user_id = $this->ci->session->userdata('user_id');
    //     if (!$user_id) {
    //         return [];
    //     }

    //     $this->ci->db->select('menus.*');
    //     $this->ci->db->from('user_roles');
    //     $this->ci->db->join('roles', 'user_roles.role_id = roles.id');
    //     $this->ci->db->join('role_permissions', 'roles.id = role_permissions.role_id');
    //     $this->ci->db->join('permissions', 'role_permissions.permission_id = permissions.id');
    //     $this->ci->db->join('menus', 'permissions.name = menus.url');
    //     $this->ci->db->where('user_roles.user_id', $user_id);
    //     $this->ci->db->order_by('menus.order', 'ASC');
    //     return $this->ci->db->get()->result();
    // }
}
