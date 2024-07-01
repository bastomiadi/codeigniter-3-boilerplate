<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Ensure the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('backend/auth/login');
        }
        // Load necessary models here
        $this->load->model('User_model');
        $this->load->model('Profile_model');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user($user_id);
        $data['profile'] = $this->Profile_model->get_profile($user_id);
        $this->load->view('backend/profile/index', $data);
    }

    // Add methods to handle profile update if needed
}
