<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

class Seed extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        // $this->categories();
        // $this->products();
        // $this->users();
        $this->users_seeders();
        $this->categories_seeders();
        $this->products_seeders();
        $this->role_permissions_seeder();
    }

    // public function categories() {
    //     $this->load->controller('seeders/Categories_seeder');
    //     $seeder = new Categories_seeder();
    //     $seeder->index();
    // }

    // public function products() {
    //     $this->load->controller('seeders/Products_seeder');
    //     $seeder = new Products_seeder();
    //     $seeder->index();
    // }

    // public function users() {
    //     $this->load->controller('seeders/Users_seeder');
    //     $seeder = new Users_seeder();
    //     $seeder->index();
    // }

    public function users_seeders(){
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $data = array(
                'username' => $faker->userName,
                'email' => $faker->email,
                'password' => md5('password'), // Example: Use secure password hashing
                'role_id' => 2 // Assuming 'member' role_id is 2
            );

            $this->db->insert('users', $data);
        }

        echo "Users seeding completed.";
    }

    public function categories_seeders() {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $data = array(
                'name' => $faker->word,
                //'description' => $faker->sentence,
                'created_at' => date('Y-m-d H:i:s')
            );

            $this->db->insert('categories', $data);
        }

        echo "Categories seeding completed.";
    }

    public function products_seeders() {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 5000; $i++) {
            $data = array(
                'name' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 10, 1000),
                'category_id' => rand(1, 10), // Assuming you have 20 categories
                'created_at' => date('Y-m-d H:i:s')
            );

            $this->db->insert('products', $data);
        }

        echo "Products seeding completed.";
    }

    public function role_permissions_seeder() {
        // Define role permissions
        $role_permissions = array(
            // Administrator permissions
            array('role_id' => 1, 'permission_id' => 1), // create
            array('role_id' => 1, 'permission_id' => 2), // read
            array('role_id' => 1, 'permission_id' => 3), // update
            array('role_id' => 1, 'permission_id' => 4), // delete

            // Member permissions (only read and create)
            array('role_id' => 2, 'permission_id' => 1), // create
            array('role_id' => 2, 'permission_id' => 2), // read
        );

        // Insert role permissions
        $this->db->insert_batch('role_permissions', $role_permissions);

        echo "Role permissions seeded successfully.";
    }
}
