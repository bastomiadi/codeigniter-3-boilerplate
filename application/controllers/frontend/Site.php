<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model');
    }

    public function register() {
        if ($this->input->post()) {
            $data = array(
                'username' => $this->input->post('username'),
                'password' => md5($this->input->post('password')),
                'email' => $this->input->post('email'),
                'role' => 'member',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->User_model->register($data);
            redirect('frontend/login');
        } else {
            $this->load->view('frontend/register');
        }
    }

    public function login() {
        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user = $this->User_model->login($username, $password);

            if ($user) {
                $session_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role
                );
                $this->session->set_userdata($session_data);
                redirect('backend/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect('frontend/login');
            }
        } else {
            $this->load->view('frontend/login');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('frontend/login');
    }
}
