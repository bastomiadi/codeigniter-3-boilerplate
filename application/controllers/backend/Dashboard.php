<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->load->model('Menu_model');
        // Check if user is logged in and has 'member' role
        // if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
        //     redirect('backend/auth/login'); // Redirect unauthorized users to login page
        // }
    }

    public function index()
    {
        print_r(APPPATH . 'config/routes_frontend.php');
        die;
        $data['title'] = 'Dashboard';
        $data['page_title'] = 'Dashboard';
        $data['contents'] = $this->load->view('backend/dashboard/index', '', TRUE);
        //$data['menus'] = $this->Menu_model->get_all_menus();
        $this->load->view('backend/layouts/main', $data);
    }
}
