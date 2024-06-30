<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Categories extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
    }

    public function index_get() {
        $categories = $this->Category_model->get_all();
        $this->response($categories, REST_Controller::HTTP_OK);
    }

    public function index_post() {
        $data = [
            'name' => $this->post('name')
        ];
        $this->Category_model->insert($data);
        $this->response(['status' => 'Category created successfully'], REST_Controller::HTTP_OK);
    }

    public function index_put($id) {
        $data = [
            'name' => $this->put('name')
        ];
        $this->Category_model->update($id, $data);
        $this->response(['status' => 'Category updated successfully'], REST_Controller::HTTP_OK);
    }

    public function index_delete($id) {
        $this->Category_model->delete($id);
        $this->response(['status' => 'Category deleted successfully'], REST_Controller::HTTP_OK);
    }
}
