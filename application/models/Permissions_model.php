<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions_model extends CI_Model {

    public function get_all() {
        return $this->db->get('permissions')->result();
    }

    public function insert($data) {
        $this->db->insert('permissions', $data);
    }

    public function update($permission_id, $data) {
        $this->db->where('permission_id', $permission_id);
        $this->db->update('permissions', $data);
    }

    public function delete($permission_id) {
        $this->db->where('permission_id', $permission_id);
        $this->db->delete('permissions');
    }
}
