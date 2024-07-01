<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('role_model');
        
        // Check if user is logged in and has 'superadmin' role
        // if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
        //     redirect('backend/auth/login'); // Redirect unauthorized users to login page
        // }
    }

    public function index() {
        $data['title'] = 'Roles';
        $data['page_title'] = 'Roles';
        $data['contents'] = $this->load->view('backend/roles/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
        //$this->load->view('backend/roles/index');
    }

    public function fetch_roles() {
        $data = $this->role_model->get_all();
        echo json_encode($data);
    }

    public function save() {
        $data = array(
            'role_name' => $this->input->post('role_name')
        );

        if ($this->input->post('role_id')) {
            $this->role_model->update($this->input->post('role_id'), $data);
            echo json_encode(array("status" => TRUE));
        } else {
            $this->role_model->insert($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function delete($role_id) {
        $this->role_model->delete($role_id);
        echo json_encode(array("status" => TRUE));
    }
}
