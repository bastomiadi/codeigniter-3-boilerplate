<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// application/middleware/Auth_middleware.php
class Auth_middleware {
    private $ci;

    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->model('User_model');
        $this->ci->load->model('UserRole_model');
        $this->ci->load->model('Roles_permissions_model');
    }

    public function check_permission($required_permission) {
        $user_id = $this->ci->session->userdata('user_id');
        if (!$user_id) {
            // Determine the current route to decide the redirect URL
            $current_route = $this->ci->uri->segment(1); // Get the first segment of the URI
            $redirect_url = ($current_route === 'backend') ? 'backend/login' : 'frontend/login';
            redirect($redirect_url);
        }

        $user_roles = $this->ci->UserRole_model->get_roles_by_user_id($user_id);
        foreach ($user_roles as $role) {
            $permissions = $this->ci->Roles_permissions_model->get_permissions_by_role_id($role->role_id);
            foreach ($permissions as $permission) {
                if ($permission->permission_name === $required_permission) {
                    return true;
                }
            }
        }

        show_error('You do not have permission to access this page.', 403);
    }
}
