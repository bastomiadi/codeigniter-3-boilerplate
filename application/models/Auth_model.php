<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function login($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password)); // Example: Use secure password hashing
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function register($username, $email, $password, $role_id) {
        $data = array(
            'username' => $username,
            'email' => $email,
            'password' => md5($password), // Example: Use secure password hashing
            'role_id' => $role_id
        );
        $this->db->insert('users', $data);
    }
}
