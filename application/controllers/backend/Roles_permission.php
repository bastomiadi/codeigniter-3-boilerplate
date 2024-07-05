<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_Permission extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Roles_permissions_model');
        $this->load->model('Role_model');
        $this->load->model('Permissions_model');

        // Check if user is logged in and has 'superadmin' role
        // if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
        //     redirect('backend/auth/login'); // Redirect unauthorized users to login page
        // }
    }

    public function index() {
        $data['title'] = 'Roles Permissions';
        $data['page_title'] = 'Roles Permissions';
        $data['contents'] = $this->load->view('backend/roles_permissions/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
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

    // testing
    public function get_role_permissions() {
        $role_permissions = $this->Roles_permissions_model->get_all_role_permissions();
        echo json_encode(array("data" => $role_permissions));
    }

    public function get_roles() {
        $roles = $this->Role_model->get_all_roles();
        echo json_encode($roles);
    }

    public function get_permissions() {
        $permissions = $this->Permissions_model->get_all_permissions();
        echo json_encode($permissions);
    }

    public function add_role_permission() {
        $role_id = $this->input->post('role_id');
        $permission_id = $this->input->post('permission_id');

        if (empty($role_id) || empty($permission_id)) {
            echo json_encode(array("status" => FALSE, "message" => "Role and Permission cannot be empty."));
            return;
        }

        $data = array(
            'role_id' => $role_id,
            'permission_id' => $permission_id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_id')
        );

        $this->Roles_permissions_model->insert_role_permission($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update_role_permission() {
        $role_id = $this->input->post('role_id');
        $permission_id = $this->input->post('permission_id');
    
        // Log incoming data for debugging
        log_message('debug', 'Received role_id: ' . $role_id);
        log_message('debug', 'Received permission_id: ' . $permission_id);
    
        if (empty($role_id) || empty($permission_id)) {
            echo json_encode(array("status" => FALSE, "message" => "Role and Permission cannot be empty."));
            return;
        }
    
        $data = array(
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );
    
        $this->load->model('Roles_permissions_model');
        $updated = $this->Roles_permissions_model->update_role_permission($role_id, $permission_id, $data);
    
        if ($updated) {
            echo json_encode(array("status" => TRUE));
        } else {
            $error = $this->db->error();
            log_message('error', 'DB Update Error: ' . print_r($error, true));
            echo json_encode(array("status" => FALSE, "message" => "Failed to update role permission.", "error" => $error));
        }
    }
    
    

    public function delete_role_permission() {
        $role_id = $this->input->post('role_id');
        $permission_id = $this->input->post('permission_id');

        if (empty($role_id) || empty($permission_id)) {
            echo json_encode(array("status" => FALSE, "message" => "Role and Permission cannot be empty."));
            return;
        }

        $this->Roles_permissions_model->delete_role_permission($role_id, $permission_id);
        echo json_encode(array("status" => TRUE));
    }
}
