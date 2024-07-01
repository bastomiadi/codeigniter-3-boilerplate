<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
    }

    // public function login() {
    //     // Check if user is already logged in
    //     if ($this->session->userdata('logged_in')) {
    //         $role_id = $this->session->userdata('role_id');
    //         $redirect_url = ($role_id == 1) ? 'dashboard' : (($role_id == 2) ? 'dashboard' : 'category');
    //         redirect($redirect_url);
    //     }

    //     // Handle login form submission
    //     if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //         $username = $this->input->post('username');
    //         $password = $this->input->post('password');

    //         $user = $this->auth_model->login($username, $password);

    //         if ($user) {
    //             // Set session data
    //             $user_data = array(
    //                 'id' => $user->id,
    //                 'username' => $user->username,
    //                 'role_id' => $user->role_id,
    //                 //'logged_in' => TRUE
    //             );
    //             $this->session->set_userdata($user_data);

    //             $redirect_url = ($user->role_id == 1) ? 'dashboard' : (($user->role_id == 2) ? 'dashboard' : 'category');
    //             redirect($redirect_url); // Redirect to appropriate dashboard after login
    //         } else {
    //             // Display error (incorrect credentials)
    //             $data['error'] = 'Invalid username or password';
    //         }
    //     }

    //     // Load login view
    //     $this->load->view('backend/auth/login', $data); // Adjust view path as per your structure
    // }

    public function login() {
        $data = array();

        // Check if user is already logged in
        if ($this->session->userdata('logged_in')) {
            $role_id = $this->session->userdata('role_id');
            $redirect_url = ($role_id == 1) ? 'backend/dashboard' : (($role_id == 2) ? 'backend/dashboard' : 'backend/category');
            redirect($redirect_url);
        }

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

                $redirect_url = ($role_id == 1) ? 'backend/dashboard' : (($role_id == 2) ? 'backend/dashboard' : 'backend/category');
                redirect($redirect_url); // Redirect to appropriate dashboard after login
            } else {
                // Display error (incorrect credentials)
                $data['error'] = 'Invalid username or password';
            }
        }

        // Load login view
        // Load register view
        $data['contents'] = $this->load->view('backend/auth/login', '', TRUE);
        //$this->load->view('backend/auth/login', $data); // Adjust view path as per your structure
        $this->load->view('backend/layouts/auth', $data); // Adjust view path as per your structure
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


    // public function logout() {
    //     $this->session->unset_userdata('user_id');
    //     $this->session->unset_userdata('username');
    //     $this->session->unset_userdata('role_id');
    //     $this->session->unset_userdata('logged_in');
    //     redirect('backend/auth/login');
    // }

    // Other methods for login, register, etc.

}
