<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_permissions_model extends CI_Model {

    var $table = "roles_permissions";
    var $select_column = array("role_id", "permission_id");
    var $order_column = array("role_id", "permission_id", null);
    
    public function __construct() {
        parent::__construct();
    }

    public function get_all_role_permissions() {
        $this->db->select('rp.*, r.role_name, p.permission_name');
        $this->db->from('roles_permissions rp');
        $this->db->join('roles r', 'rp.role_id = r.role_id');
        $this->db->join('permissions p', 'rp.permission_id = p.permission_id');
        return $this->db->get()->result();
    }

    public function insert_role_permission($data) {
        return $this->db->insert('roles_permissions', $data);
    }

    public function update_role_permission($role_id, $permission_id, $data) {
        $this->db->where('role_id', $role_id);
        $this->db->where('permission_id', $permission_id);
        return $this->db->update('roles_permissions', $data);
    }
    
    public function delete_role_permission($role_id, $permission_id) {
        $this->db->where('role_id', $role_id);
        $this->db->where('permission_id', $permission_id);
        return $this->db->delete('roles_permissions');
    }

    // tambahan untuk middleware auth
    public function get_permissions_by_role_id($role_id) {
        $this->db->select('permissions.*');
        $this->db->from('roles_permissions');
        $this->db->join('permissions', 'permissions.permission_id = roles_permissions.permission_id');
        $this->db->where('roles_permissions.role_id', $role_id);
        return $this->db->get()->result();
    }

}
