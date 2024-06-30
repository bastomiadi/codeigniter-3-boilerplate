<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_seeder extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index() {
        $data = array(
            array(
                'name' => 'Laptop',
                'description' => 'A powerful laptop for work and gaming.',
                'price' => '1200.00',
                'category_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ),
            array(
                'name' => 'T-shirt',
                'description' => 'Comfortable cotton t-shirt.',
                'price' => '25.00',
                'category_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ),
            array(
                'name' => 'Java Programming Book',
                'description' => 'A comprehensive guide to Java programming.',
                'price' => '50.00',
                'category_id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            )
        );

        $this->db->insert_batch('products', $data);

        echo "Products Seeder executed successfully.";
    }
}
