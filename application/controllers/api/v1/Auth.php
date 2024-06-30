<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Auth extends RestController {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function register_post() {
        $data = [
            'username' => $this->post('username'),
            'password' => password_hash($this->post('password'), PASSWORD_BCRYPT),
            'role' => 'member'
        ];
        $this->User_model->insert($data);
        $this->response(['status' => 'User registered successfully'], RestController::HTTP_OK);
    }

    public function login_post() {
        $username = $this->post('username');
        $password = $this->post('password');
        $user = $this->User_model->get_by_username($username);
        if ($user && md5($password, $user->password)) {
            $this->response(['status' => 'Login successful'], RestController::HTTP_OK);
        } else {
            $this->response(['status' => 'Invalid username or password'], RestController::HTTP_UNAUTHORIZED);
        }
    }
}
