<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('role_model');
        $this->load->library('Auth_middleware');
    }

    public function index() {
        $this->auth_middleware->check_permission('menu_roles');
        $data['title'] = 'Roles';
        $data['page_title'] = 'Roles';
        $data['contents'] = $this->load->view('backend/roles/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
    }

    public function get_roles() {
        $fetch_data = $this->role_model->make_datatables();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array['role_id'] = $row->role_id;
            $sub_array['role_name'] = $row->role_name;
            $sub_array['description'] = $row->description;
            $sub_array['actions'] = '
                <button type="button" class="btn btn-warning btn-sm edit-role" data-toggle="modal" data-target="#editRoleModal" data-id="'.$row->role_id.'" data-name="'.$row->role_name.'" data-description="'.$row->description.'">Edit</button>
                <button type="button" class="btn btn-danger btn-sm delete-role" data-toggle="modal" data-target="#deleteRoleModal" data-id="'.$row->role_id.'">Delete</button>
            ';
            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => $this->role_model->get_all_data(),
            "recordsFiltered" => $this->role_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function add_role() {
        $roleName = $this->input->post('roleName');
        $roleDescription = $this->input->post('roleDescription');
        $createdBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Add category logic
        $this->role_model->add_role($roleName, $roleDescription, $createdBy);
        echo json_encode(['status' => 'success']);
    }

    public function edit_role() {
        $categoryId = $this->input->post('id');
        $roleName = $this->input->post('roleName');
        $roleDescription = $this->input->post('roleDescription');
        $updatedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Edit category logic
        $this->role_model->edit_role($categoryId, $roleName, $roleDescription, $updatedBy);
        echo json_encode(['status' => 'success']);
    }

    public function delete_role() {
        $categoryId = $this->input->post('id');
        $deletedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Soft delete category logic
        $this->role_model->soft_delete_role($categoryId, $deletedBy);
        echo json_encode(['status' => 'success']);
    }

    // dropdown get category for select2
    public function select2() {
        $searchTerm = $this->input->get('q');
        $categories = $this->role_model->get_select2($searchTerm);
        echo json_encode($categories);
    }

    public function get_all_roles() {
        return $this->db->get('roles')->result();
    }
}
