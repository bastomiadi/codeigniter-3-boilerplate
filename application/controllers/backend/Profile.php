<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary models here
        $this->load->model('User_model');
        $this->load->model('Profile_model');
        $this->load->library('Auth_middleware');
    }

    public function index() {
        $this->auth_middleware->check_permission('update_profile');
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user($user_id);
        $data['profile'] = $this->Profile_model->get_profile($user_id);
        $data['title'] = 'Profile';
        $data['page_title'] = 'Profile';

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('phone', 'Phone', 'required');

            if ($this->form_validation->run() == TRUE) {
                // Update user account
                $this->User_model->edit_user($user_id, $this->input->post('username'), $this->input->post('email'), $user_id);
                $this->session->set_userdata('username', $this->input->post('username'));

                // Update (or create) profile for the logged-in user
                $data_profile = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'address' => $this->input->post('address'),
                    'phone' => $this->input->post('phone'),
                    'updated_by' => $user_id,
                    'updated_at' => date('Y-m-d H:i:s')
                );

                if ($this->Profile_model->save_profile($user_id, $data_profile)) {
                    $this->session->set_flashdata('success', 'Profile updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update profile.');
                }
                return redirect('backend/profile');
            }
        }
        $data['contents'] = $this->load->view('backend/profile/index', $data, TRUE);
        $this->load->view('backend/layouts/main', $data);
        return $this;
    }

    public function change_password() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return redirect('backend/profile');
        }

        $this->auth_middleware->check_permission('update_profile');
        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            return redirect('backend/profile');
        }

        $result = $this->User_model->change_password(
            $user_id,
            $this->input->post('current_password'),
            $this->input->post('new_password')
        );

        if ($result === 'ok') {
            $this->session->set_flashdata('success', 'Password changed successfully.');
        } else {
            $this->session->set_flashdata('error', 'Current password is incorrect.');
        }
        return redirect('backend/profile');
    }
}
