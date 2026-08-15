<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Auth extends RestController {

    public function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->library('jwt_auth');
    }

    public function register_post() {
        $user_id = $this->auth_model->register(
            $this->post('username'),
            $this->post('email'),
            $this->post('password'),
            2 // member
        );

        $token = $this->jwt_auth->encode($user_id);
        $this->response(array(
            'status' => 'User registered successfully',
            'token' => $token,
            'user_id' => (int) $user_id
        ), RestController::HTTP_CREATED);
    }

    public function login_post() {
        $username = $this->post('username');
        $password = $this->post('password');
        $user = $this->auth_model->login($username, $password);
        if ($user) {
            $token = $this->jwt_auth->encode($user->id);
            $this->response(array(
                'status' => 'Login successful',
                'token' => $token,
                'user' => array(
                    'id' => (int) $user->id,
                    'username' => $user->username,
                    'role' => $user->role_name
                )
            ), RestController::HTTP_OK);
        } else {
            $this->response(array('status' => 'Invalid username or password'), RestController::HTTP_UNAUTHORIZED);
        }
    }
}
