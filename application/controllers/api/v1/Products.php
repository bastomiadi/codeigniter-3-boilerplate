<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Products extends RestController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('jwt_auth');

        if ( ! $this->jwt_auth->validate($this->jwt_auth->token_from_request())) {
            $this->response(array('status' => 'Unauthorized'), RestController::HTTP_UNAUTHORIZED);
        }
    }

    public function index_get() {
        $products = $this->Product_model->get_all();
        $this->response($products, RestController::HTTP_OK);
    }

    public function index_post() {
        $data = [
            'name' => $this->post('name'),
            'category_id' => $this->post('category_id'),
            'price' => $this->post('price'),
            'description' => $this->post('description'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->Product_model->insert($data);
        $this->response(['status' => 'Product created successfully'], RestController::HTTP_OK);
    }

    public function index_put($id) {
        $data = [
            'name' => $this->put('name'),
            'category_id' => $this->put('category_id'),
            'price' => $this->put('price'),
            'description' => $this->put('description'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->Product_model->update(['id' => $id], $data);
        $this->response(['status' => 'Product updated successfully'], RestController::HTTP_OK);
    }

    public function index_delete($id) {
        $this->Product_model->delete_by_id($id);
        $this->response(['status' => 'Product deleted successfully'], RestController::HTTP_OK);
    }
}
