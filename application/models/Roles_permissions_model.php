<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_permissions_model extends CI_Model {

    var $table = "roles_permissions";
    var $select_column = array("role_id", "permission_id");
    var $order_column = array("role_id", "permission_id", null);
    
    public function __construct() {
        parent::__construct();
    }

    public function add_role($role_name, $created_by) {
        $data = array(
            'role_name' => $role_name,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert($this->table, $data);
    }
    
    // public function edit_role($role_id, $role_name, $updated_by) {
    //     $data = array(
    //         'role_name' => $role_name,
    //         'updated_by' => $updated_by,
    //         'updated_at' => date('Y-m-d H:i:s')
    //     );
    //     $this->db->where('role_id', $role_id)->update($this->table, $data);
    // }
    
    public function soft_delete_role($role_id, $deleted_by) {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deleted_by
        );
        $this->db->where('role_id', $role_id)->update($this->table, $data);
    }

    public function delete_role($role_id) {
        $this->db->where('role_id', $role_id)->delete($this->table);
    }

    public function make_query() {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        if (isset($_POST["search"]["value"])) {
            $this->db->like("role_name", $_POST["search"]["value"]);
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('role_id', 'DESC');
        }
    }

    public function make_datatables() {
        $this->make_query();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_all_data() {
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        return $this->db->count_all_results();
    }

    public function get_select2($searchTerm = "") {
        $this->db->select('role_id, role_name as text');
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        if ($searchTerm != "") {
            $this->db->like('role_name', $searchTerm);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    //testing
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

    // public function update_role_permission($role_id, $permission_id, $data) {
    //     $this->db->where('role_id', $role_id);
    //     $this->db->where('permission_id', $permission_id);
    
    //     $result = $this->db->update('roles_permissions', $data);
    
    //     // Debugging information
    //     if (!$result) {
    //         log_message('error', 'Failed to update role_permission: ' . $this->db->last_query());
    //         log_message('error', 'DB Error: ' . print_r($this->db->error(), true));
    //     }
    
    //     return $result;
    // }
    
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
