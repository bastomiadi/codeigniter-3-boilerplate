<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('permissions_model');
        
        // Check if user is logged in and has 'superadmin' role
        // if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
        //     redirect('backend/auth/login'); // Redirect unauthorized users to login page
        // }
    }

    public function index() {
        $data['title'] = 'Permissions';
        $data['page_title'] = 'Permissions';
        $data['contents'] = $this->load->view('backend/permissions/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
        //$this->load->view('backend/permissions/index');
    }

    public function fetch_permissions() {
        $data = $this->permissions_model->get_all();
        echo json_encode($data);
    }

    public function save() {
        $data = array(
            'permission_name' => $this->input->post('permission_name')
        );

        if ($this->input->post('permission_id')) {
            $this->permissions_model->update($this->input->post('permission_id'), $data);
            echo json_encode(array("status" => TRUE));
        } else {
            $this->permissions_model->insert($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function delete($permission_id) {
        $this->permissions_model->delete($permission_id);
        echo json_encode(array("status" => TRUE));
    }
}
