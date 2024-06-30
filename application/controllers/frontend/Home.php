<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['contents'] = $this->load->view('frontend/home/index', '', TRUE);
        $this->load->view('backend/layouts/auth', $data);
    }
}
