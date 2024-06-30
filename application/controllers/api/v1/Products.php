<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Products extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function index_get() {
        $products = $this->Product_model->get_all();
        $this->response($products, REST_Controller::HTTP_OK);
    }

    public function index_post() {
        $data = [
            'name' => $this->post('name'),
            'category_id' => $this->post('category_id'),
            'price' => $this->post('price')
        ];
        $this->Product_model->insert($data);
        $this->response(['status' => 'Product created successfully'], REST_Controller::HTTP_OK);
    }

    public function index_put($id) {
        $data = [
            'name' => $this->put('name'),
            'category_id' => $this->put('category_id'),
            'price' => $this->put('price')
        ];
        $this->Product_model->update($id, $data);
        $this->response(['status' => 'Product updated successfully'], REST_Controller::HTTP_OK);
    }

    public function index_delete($id) {
        $this->Product_model->delete($id);
        $this->response(['status' => 'Product deleted successfully'], REST_Controller::HTTP_OK);
    }
}
