<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('Auth_middleware');
    }

    public function index() {
        $this->auth_middleware->check_permission('menu_user');
        $data['title'] = 'Users';
        $data['page_title'] = 'Users';
        $data['contents'] = $this->load->view('backend/users/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
    }

    public function get_users() {
        $fetch_data = $this->User_model->make_datatables();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array['id'] = $row->id;
            $sub_array['username'] = $row->username;
            $sub_array['email'] = $row->email;
            $sub_array['actions'] = '
                <button type="button" class="btn btn-warning btn-sm edit-user" data-toggle="modal" data-target="#editUserModal" data-id="'.$row->id.'" data-username="'.$row->username.'" data-email="'.$row->email.'">Edit</button>
                <button type="button" class="btn btn-danger btn-sm delete-user" data-toggle="modal" data-target="#deleteUserModal" data-id="'.$row->id.'">Delete</button>
            ';
            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => $this->User_model->get_all_data(),
            "recordsFiltered" => $this->User_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function add_user() {
        $categoryusername = $this->input->post('categoryusername');
        $categoryemail = $this->input->post('categoryemail');
        $createdBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Add category logic
        $this->User_model->add_user($categoryusername, $categoryemail, $createdBy);
        echo json_encode(['status' => 'success']);
    }

    public function edit_user() {
        $categoryId = $this->input->post('id');
        $categoryusername = $this->input->post('categoryusername');
        $categoryemail = $this->input->post('categoryemail');
        $updatedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Edit category logic
        $this->User_model->edit_user($categoryId, $categoryusername, $categoryemail, $updatedBy);
        echo json_encode(['status' => 'success']);
    }

    public function delete_user() {
        $categoryId = $this->input->post('id');
        $deletedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Soft delete category logic
        $this->User_model->soft_delete_user($categoryId, $deletedBy);
        echo json_encode(['status' => 'success']);
    }

    // dropdown get category for select2
    public function select2() {
        $searchTerm = $this->input->get('q');
        $categories = $this->User_model->get_select2($searchTerm);
        echo json_encode($categories);
    }
}
