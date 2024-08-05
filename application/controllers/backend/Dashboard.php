<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Auth_middleware');
    }

    public function index()
    {
        $this->auth_middleware->check_permission('dashboard');
        $data['title'] = 'Dashboard';
        $data['page_title'] = 'Dashboard';
        $data['contents'] = $this->load->view('backend/dashboard/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
    }
}
