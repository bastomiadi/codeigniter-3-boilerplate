<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->helper('url');
        $this->load->library('Auth_middleware');
    }

    public function index() {
        $this->auth_middleware->check_permission('menu_product');
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
