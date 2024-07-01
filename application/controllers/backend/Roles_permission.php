<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_permissions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('roles_permissions_model');
        
        // Check if user is logged in and has 'superadmin' role
        // if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
        //     redirect('backend/auth/login'); // Redirect unauthorized users to login page
        // }
    }

    public function index() {
        $this->load->view('backend/roles_permissions/index');
    }

    public function fetch_roles_permissions() {
        $data = $this->roles_permissions_model->get_all();
        echo json_encode($data);
    }

    public function save() {
        $data = array(
            'role_name' => $this->input->post('role_name'),
            'permission_name' => $this->input->post('permission_name')
        );

        if ($this->input->post('id')) {
            $this->roles_permissions_model->update($this->input->post('id'), $data);
            echo json_encode(array("status" => TRUE));
        } else {
            $this->roles_permissions_model->insert($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function delete($id) {
        $this->roles_permissions_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
