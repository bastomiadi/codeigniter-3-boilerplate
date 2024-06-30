<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_permissions_model extends CI_Model {

    public function get_all() {
        return $this->db->get('roles_permissions')->result();
    }

    public function insert($data) {
        $this->db->insert('roles_permissions', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('roles_permissions', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('roles_permissions');
    }
}
