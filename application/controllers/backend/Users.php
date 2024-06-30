<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        
        // Check if user is logged in and has 'superadmin' role
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
            redirect('auth/login'); // Redirect unauthorized users to login page
        }
    }

    public function index() {
        // Load list of users
        $data['users'] = $this->user_model->get_users();
        $this->load->view('backend/users/index', $data); // Adjust view path as per your structure
    }

    public function add_user() {
        // Handle user addition form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $role_id = $this->input->post('role_id'); // Assuming role_id is posted from form

            // Add user
            $this->user_model->add_user($username, $email, $password, $role_id);

            // Optionally, redirect or handle success
            redirect('users'); // Redirect to user list after adding user
        }

        // Load add user view
        $this->load->view('backend/users/add'); // Adjust view path as per your structure
    }

    public function edit_user($user_id) {
        // Handle user edit form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $role_id = $this->input->post('role_id'); // Assuming role_id is posted from form

            // Edit user
            $this->user_model->edit_user($user_id, $username, $email, $password, $role_id);

            // Optionally, redirect or handle success
            redirect('users'); // Redirect to user list after editing user
        }

        // Load edit user view with user details
        $data['user'] = $this->user_model->get_user($user_id);
        $this->load->view('backend/users/edit', $data); // Adjust view path as per your structure
    }

    public function delete_user($user_id) {
        // Delete user
        $this->user_model->delete_user($user_id);

        // Optionally, redirect or handle success
        redirect('users'); // Redirect to user list after deleting user
    }
}
