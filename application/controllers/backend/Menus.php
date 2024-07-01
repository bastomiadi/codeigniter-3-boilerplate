<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Menu_model');

        // Check if user is logged in and has 'superadmin' role
        // if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
        //     redirect('backend/auth/login'); // Redirect unauthorized users to login page
        // }
    }

    public function index() {
        $data['title'] = 'Menus';
        $data['page_title'] = 'Menus';
        $data['contents'] = $this->load->view('backend/menus/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
        //$this->load->view('backend/menus/index');
    }

    public function get_menus() {
        $menus = $this->Menu_model->get_all_menus();

        // Check if the data is valid JSON
        $response = json_encode(array('data' => $menus));

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Log JSON error
            log_message('error', 'JSON encoding error: ' . json_last_error_msg());
            show_error('JSON encoding error: ' . json_last_error_msg(), 500);
        }

        echo $response;
    }

    public function save() {
        $parent_id = $this->input->post('parent_id');
        $data = array(
            'menu_name' => $this->input->post('menu_name'),
            'menu_url' => $this->input->post('menu_url'),
            'menu_icon' => $this->input->post('menu_icon'),
            'parent_id' => empty($parent_id) ? NULL : $parent_id
        );
    
        if ($this->input->post('menu_id')) {
            $this->Menu_model->update_menu($this->input->post('menu_id'), $data);
        } else {
            $this->Menu_model->insert_menu($data);
        }
        
        echo json_encode(array("status" => TRUE));
    }

    public function edit($id) {
        $data = $this->Menu_model->get_menu_by_id($id);
        echo json_encode($data);
    }

    public function delete($id) {
        $this->Menu_model->delete_menu($id);
        echo json_encode(array("status" => TRUE));
    }
}
