<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('roles_model');
        
        // Check if user is logged in and has 'superadmin' role
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
            redirect('backend/auth/login'); // Redirect unauthorized users to login page
        }
    }

    public function index() {
        $this->load->view('backend/roles/index');
    }

    public function fetch_roles() {
        $data = $this->roles_model->get_all();
        echo json_encode($data);
    }

    public function save() {
        $data = array(
            'role_name' => $this->input->post('role_name')
        );

        if ($this->input->post('role_id')) {
            $this->roles_model->update($this->input->post('role_id'), $data);
            echo json_encode(array("status" => TRUE));
        } else {
            $this->roles_model->insert($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function delete($role_id) {
        $this->roles_model->delete($role_id);
        echo json_encode(array("status" => TRUE));
    }
}
