<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_seeder extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index() {
        $data = array(
            array(
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ),
            array(
                'username' => 'user',
                'email' => 'user@example.com',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            )
        );

        $this->db->insert_batch('users', $data);

        echo "Seeder executed successfully.";
    }
}
