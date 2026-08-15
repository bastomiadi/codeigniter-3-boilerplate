<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Categories extends RestController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->library('jwt_auth');

        if ( ! $this->jwt_auth->validate($this->jwt_auth->token_from_request())) {
            $this->response(array('status' => 'Unauthorized'), RestController::HTTP_UNAUTHORIZED);
        }
    }

    public function index_get() {
        $categories = $this->Category_model->get_all();
        $this->response($categories, RestController::HTTP_OK);
    }

    public function index_post() {
        $data = [
            'name' => $this->post('name'),
            'description' => $this->post('description'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->Category_model->insert($data);
        $this->response(['status' => 'Category created successfully'], RestController::HTTP_OK);
    }

    public function index_put($id) {
        $data = [
            'name' => $this->put('name'),
            'description' => $this->put('description'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->Category_model->update(['id' => $id], $data);
        $this->response(['status' => 'Category updated successfully'], RestController::HTTP_OK);
    }

    public function index_delete($id) {
        $this->Category_model->delete_by_id($id);
        $this->response(['status' => 'Category deleted successfully'], RestController::HTTP_OK);
    }
}
