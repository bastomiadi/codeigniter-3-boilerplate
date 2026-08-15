<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function login($username, $password) {
        $this->db->select('users.*, roles.role_id, roles.role_name');
        $this->db->from('users');
        $this->db->join('user_roles', 'users.id = user_roles.user_id');
        $this->db->join('roles', 'user_roles.role_id = roles.role_id');
        $this->db->where('users.username', $username);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $user = $query->row();
            return password_verify($password, $user->password) ? $user : false;
        }

        return false;
    }

    public function register($username, $email, $password, $role_id) {
        $data = array(
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        );
        $this->db->insert('users', $data);

        // Get the inserted user ID
        $user_id = $this->db->insert_id();

        // Insert into user_roles table
        $user_roles_data = array(
            'user_id' => $user_id,
            'role_id' => $role_id,
            'created_at' => date('Y-m-d H:i:s'), // Example: Use current timestamp or your preferred method
        );
        $this->db->insert('user_roles', $user_roles_data);

        return $user_id;
    }
}
