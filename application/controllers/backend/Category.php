<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');

        // // Check if user is logged in and has 'member' role
        // if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
        //     redirect('backend/auth/login'); // Redirect unauthorized users to login page
        // }
        // $this->check_auth();

        $this->load->library('rbac'); // Load your RBAC library
    }

    // protected function check_auth() {

    //     // echo '<pre>';
    //     // print_r($this->session->userdata('logged_in'));
    //     // echo '</pre>';
    //     // die;

    //     // Check if user is logged in
    //     if (!$this->session->userdata('logged_in')) {
    //         redirect('backend/auth/login');
    //     }

    //     // Check if user has required role
    //     $role_id = $this->session->userdata('role_id');
    //     $controller = $this->router->fetch_class();

    //     // Define role-based access
    //     $access = array(
    //         'member' => array('category', 'product'), // Member can access category and product
    //         'admin' => array('dashboard', 'category', 'product', 'user'), // Add other controllers for admin
    //         'superadmin' => array('dashboard', 'category', 'product', 'user', 'settings') // Add other controllers for superadmin
    //     );

    //     $role_name = ($role_id == 1) ? 'superadmin' : (($role_id == 2) ? 'admin' : 'member');

    //     // Check if current controller is in allowed controllers for the role
    //     if (!in_array($controller, $access[$role_name])) {
    //         redirect('backend/auth/permission_denied'); // Redirect to permission denied page
    //     }
    // }

    public function index() {
        $data['title'] = 'Category';
        $data['page_title'] = 'Category';
        $data['contents'] = $this->load->view('backend/category/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
    }

    public function get_categories() {
        
        $this->rbac->check_permission('update'); // Check if the user can edit a post

        $fetch_data = $this->Category_model->make_datatables();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array['id'] = $row->id;
            $sub_array['name'] = $row->name;
            $sub_array['description'] = $row->description;
            $sub_array['actions'] = '
                <button type="button" class="btn btn-warning btn-sm edit-category" data-toggle="modal" data-target="#editCategoryModal" data-id="'.$row->id.'" data-name="'.$row->name.'" data-description="'.$row->description.'">Edit</button>
                <button type="button" class="btn btn-danger btn-sm delete-category" data-toggle="modal" data-target="#deleteCategoryModal" data-id="'.$row->id.'">Delete</button>
            ';
            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => $this->Category_model->get_all_data(),
            "recordsFiltered" => $this->Category_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function add_category() {
        $categoryName = $this->input->post('categoryName');
        $categoryDescription = $this->input->post('categoryDescription');
        $createdBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Add category logic
        $this->Category_model->add_category($categoryName, $categoryDescription, $createdBy);
        echo json_encode(['status' => 'success']);
    }

    public function edit_category() {
        $categoryId = $this->input->post('id');
        $categoryName = $this->input->post('categoryName');
        $categoryDescription = $this->input->post('categoryDescription');
        $updatedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Edit category logic
        $this->Category_model->edit_category($categoryId, $categoryName, $categoryDescription, $updatedBy);
        echo json_encode(['status' => 'success']);
    }

    public function delete_category() {
        $categoryId = $this->input->post('id');
        $deletedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Soft delete category logic
        $this->Category_model->soft_delete_category($categoryId, $deletedBy);
        echo json_encode(['status' => 'success']);
    }

    // dropdown get category for select2
    public function select2() {
        $searchTerm = $this->input->get('q');
        $categories = $this->Category_model->get_select2($searchTerm);
        echo json_encode($categories);
    }
}
