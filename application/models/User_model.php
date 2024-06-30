<?php
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function register($data) {
        return $this->db->insert('users', $data);
    }

    public function login($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        $query = $this->db->get('users');
        return $query->row();
    }

    public function get_user($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }
}
