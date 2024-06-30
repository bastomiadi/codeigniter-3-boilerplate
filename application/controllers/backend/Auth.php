<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function login() {
        // Check if user is already logged in
        if ($this->session->userdata('logged_in')) {
            redirect('backend/dashboard'); // Redirect to dashboard or home page
        }
    
        // Initialize $data
        $data = array();
    
        // Handle login form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
    
            $user = $this->auth_model->login($username, $password);
    
            if ($user) {
                // Set session data
                $user_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role_id' => $user->role_id,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($user_data);
    
                redirect('backend/dashboard'); // Redirect to dashboard or home page after login
            } else {
                // Display error (incorrect credentials)
                $data['error'] = 'Invalid username or password';
            }
        }
    
        // Load login view
        //$this->load->view('backend/auth/login', $data); // Adjust view path as per your structure
        $data['contents'] = $this->load->view('backend/auth/login', '', TRUE);
        $this->load->view('backend/layouts/auth', $data);
    }
    

    public function register() {
        // Handle registration form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Assuming role_id for 'member' is 1 (adjust as per your roles table)
            $role_id = 1; 

            // Register user
            $this->auth_model->register($username, $email, $password, $role_id);

            // Optionally, redirect to login page or handle success
            redirect('backend/auth/login'); // Redirect to login page after successful registration
        }

        // Load register view
        $data['contents'] = $this->load->view('backend/auth/register', '', TRUE);
        $this->load->view('backend/layouts/auth', $data); // Adjust view path as per your structure
    }

    public function logout() {
        // Destroy session and redirect to login page
        $this->session->sess_destroy();
        redirect('backend/auth/login');
    }
}
