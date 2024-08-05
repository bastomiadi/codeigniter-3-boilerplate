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
            // Retrieve POST data
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $address = $this->input->post('address');
            $phone = $this->input->post('phone');

            // Validate the data
            $this->form_validation->set_rules('first_name', 'Firstname', 'required');
            $this->form_validation->set_rules('last_name', 'Lastname', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('phone', 'phone', 'required');

            if ($this->form_validation->run() == TRUE) {
                // Prepare data for insertion
                $data_profile = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'address' => $address,
                    'phone' => $phone
                );

                // Insert data into the database
                if ($this->Profile_model->update_profile($user_id, $data_profile)) {
                    // Redirect or send success message
                    $this->session->set_flashdata('success', 'Profile updated successfully.');
                } else {
                    // Handle error
                    $this->session->set_flashdata('error', 'Failed to create post.');
                }
                return redirect('backend/profile');
            }
        }
        $data['contents'] = $this->load->view('backend/profile/index', $data, TRUE);
        $this->load->view('backend/layouts/main', $data);
        return $this;
    }
}
