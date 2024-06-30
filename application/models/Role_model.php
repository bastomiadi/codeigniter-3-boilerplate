<?php
class Role_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_role_by_name($role_name) {
        $this->db->where('role_name', $role_name);
        $query = $this->db->get('roles');
        return $query->row();
    }

    public function get_all() {
        return $this->db->get('roles')->result();
    }

    public function insert($data) {
        $this->db->insert('roles', $data);
    }

    public function update($role_id, $data) {
        $this->db->where('role_id', $role_id);
        $this->db->update('roles', $data);
    }

    public function delete($role_id) {
        $this->db->where('role_id', $role_id);
        $this->db->delete('roles');
    }
}
