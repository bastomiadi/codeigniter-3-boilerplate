<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->load->model('Menu_model');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['page_title'] = 'Dashboard';
        $data['contents'] = $this->load->view('backend/dashboard/index', '', TRUE);
        //$data['menus'] = $this->Menu_model->get_all_menus();

        // echo '<pre>';
        // print_r($data['menus']);
        // echo '</pre>';
        // die;
        $this->load->view('backend/layouts/main', $data);
    }
}
