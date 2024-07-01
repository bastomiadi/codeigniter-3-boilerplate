<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index() {
        $data['title'] = 'Users';
        $data['page_title'] = 'Users';
        $data['contents'] = $this->load->view('backend/users/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);

        // $this->load->view('backend/users/index');
    }

    public function fetch_users() {
        $users = $this->User_model->get_all_users();
        echo json_encode($users);
    }

    public function store() {
        $data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role_id' => $this->input->post('role_id')
        );

        $insert = $this->User_model->insert_user($data);
        echo json_encode(array("status" => $insert));
    }

    public function edit($id) {
        $user = $this->User_model->get_user_by_id($id);
        echo json_encode($user);
    }

    public function update() {
        $data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'role_id' => $this->input->post('role_id')
        );
        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        }

        $update = $this->User_model->update_user($this->input->post('id'), $data);
        echo json_encode(array("status" => $update));
    }

    public function delete($id) {
        $delete = $this->User_model->delete_user($id);
        echo json_encode(array("status" => $delete));
    }
}
