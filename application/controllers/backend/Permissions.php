<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Permissions_model'); // Load your Permission model
    }

    public function index() {
        $data['title'] = 'Permissions';
        $data['page_title'] = 'Permissions';
        $data['contents'] = $this->load->view('backend/permissions/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
    }

    public function get_permissions() {
        $fetch_data = $this->Permissions_model->make_datatables();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array['permission_id'] = $row->permission_id;
            $sub_array['permission_name'] = $row->permission_name;
            $sub_array['description'] = $row->description;
            $sub_array['actions'] = '
                <button type="button" class="btn btn-warning btn-sm edit-permission" data-toggle="modal" data-target="#editPermissionModal" data-id="'.$row->permission_id.'" data-name="'.$row->permission_name.'" data-description="'.$row->description.'">Edit</button>
                <button type="button" class="btn btn-danger btn-sm delete-permission" data-toggle="modal" data-target="#deletePermissionModal" data-id="'.$row->permission_id.'">Delete</button>
            ';
            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => $this->Permissions_model->get_all_data(),
            "recordsFiltered" => $this->Permissions_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function add_permission() {
        $permissionName = $this->input->post('permissionName');
        $permissionDescription = $this->input->post('permissionDescription');
        $createdBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Add permission logic
        $this->Permissions_model->add_permission($permissionName, $permissionDescription, $createdBy);
        echo json_encode(['status' => 'success']);
    }

    public function edit_permission() {
        $permissionId = $this->input->post('id');
        $permissionName = $this->input->post('permissionName');
        $permissionDescription = $this->input->post('permissionDescription');
        $updatedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Edit permission logic
        $this->Permissions_model->edit_permission($permissionId, $permissionName, $permissionDescription, $updatedBy);
        echo json_encode(['status' => 'success']);
    }

    public function delete_permission() {
        $permissionId = $this->input->post('id');
        $deletedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Soft delete permission logic
        $this->Permissions_model->soft_delete_permission($permissionId, $deletedBy);
        echo json_encode(['status' => 'success']);
    }

    // dropdown get permission for select2
    public function select2() {
        $searchTerm = $this->input->get('q');
        $permissions = $this->Permissions_model->get_select2($searchTerm);
        echo json_encode($permissions);
    }

}
