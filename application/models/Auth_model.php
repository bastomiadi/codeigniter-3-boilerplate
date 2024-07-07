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
        $this->db->where('users.password', md5($password)); // Example: Use secure password hashing
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row(); // Return user object with roles
        } else {
            return false; // User not found or multiple users found (should not happen with user_id as primary key)
        }
    }

    public function register($username, $email, $password, $role_id) {
        $data = array(
            'username' => $username,
            'email' => $email,
            'password' => md5($password), // Example: Use secure password hashing
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
    }
}
