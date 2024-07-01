<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class Product extends CI_Controller {

//     public function __construct() {
//         parent::__construct();
//         $this->load->model('product_model');
//     }

//     public function index() {
//         $data['products'] = $this->product_model->get_products();
//         $this->load->view('backend/product/index', $data);
//     }

//     public function add_product() {
//         $productName = $this->input->post('productName');
//         $productPrice = $this->input->post('productPrice');
//         $productCategory = $this->input->post('productCategory');
//         $this->product_model->add_product($productName, $productPrice, $productCategory);
//         echo json_encode(['status' => 'success']);
//     }

//     public function edit_product() {
//         $productId = $this->input->post('id');
//         $productName = $this->input->post('productName');
//         $productPrice = $this->input->post('productPrice');
//         $productCategory = $this->input->post('productCategory');
//         $this->product_model->edit_product($productId, $productName, $productPrice, $productCategory);
//         echo json_encode(['status' => 'success']);
//     }

//     public function soft_delete_product() {
//         $productId = $this->input->post('id');
//         $this->product_model->soft_delete_product($productId);
//         echo json_encode(['status' => 'success']);
//     }

//     public function delete_product() {
//         $productId = $this->input->post('id');
//         $this->product_model->delete_product($productId);
//         echo json_encode(['status' => 'success']);
//     }

//     // Add more CRUD operations as needed
// }

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->helper('url');
        // Check if user is logged in and has 'member' role
        // if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
        //     redirect('backend/auth/login'); // Redirect unauthorized users to login page
        // }
    }

    public function index() {
        $data['title'] = 'Product';
        $data['page_title'] = 'Product';
        $data['contents'] = $this->load->view('backend/product/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
        // $this->load->view('backend/product/index');
    }

    public function fetch() {
        $products = $this->Product_model->get_all();
        echo json_encode($products);
    }

    public function create() {
        $this->load->view('backend/product/create');
    }

    public function store() {
        $data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'category_id' => $this->input->post('category_id'),
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->Product_model->insert($data);
        echo json_encode(array("status" => TRUE));
    }

    public function edit($id) {
        $product = $this->Product_model->get_by_id($id);
        echo json_encode($product);
    }

    public function update() {
        $data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'category_id' => $this->input->post('category_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $this->Product_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function delete($id) {
        $this->Product_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
}
