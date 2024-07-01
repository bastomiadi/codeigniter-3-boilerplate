<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once APPPATH . '../vendor/autoload.php';

class Seed extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->menus_seeders();
        $this->roles_seeders();
        $this->permissions_seeders();
        $this->users_seeders();
        $this->profiles_seeders();
        $this->categories_seeders();
        $this->products_seeders();
        // $this->role_permissions_seeder();
    }

    public function roles_seeders(){
        // Insert default roles
        $roles_data = array(
            array('role_name' => 'superadmin'),
            array('role_name' => 'admin'),
            array('role_name' => 'member')
        );
        $this->db->insert_batch('roles', $roles_data);

        echo "Roles seeding completed.";
    }

    public function menus_seeders(){
        // Insert master menu and sub-menus
        $this->db->insert('menus', ['menu_name' => 'Master', 'menu_url' => '#', 'menu_icon' => 'fas fa-cogs', 'parent_id' => null]);
        $master_menu_id = $this->db->insert_id();
        $this->db->insert('menus', ['menu_name' => 'Products', 'menu_url' => 'backend/product', 'menu_icon' => 'fas fa-box', 'parent_id' => $master_menu_id]);
        $this->db->insert('menus', ['menu_name' => 'Categories', 'menu_url' => 'backend/category', 'menu_icon' => 'fas fa-list', 'parent_id' => $master_menu_id]);
        $this->db->insert('menus', ['menu_name' => 'Menus', 'menu_url' => 'backend/menus', 'menu_icon' => 'fas fa-list', 'parent_id' => $master_menu_id]);
        $this->db->insert('menus', ['menu_name' => 'Dashboard', 'menu_url' => 'backend/dashboard', 'menu_icon' => 'fas fa-list', 'parent_id' => null]);

        $this->db->insert('menus', ['menu_name' => 'RBAC', 'menu_url' => '#', 'menu_icon' => 'fas fa-list', 'parent_id' => null]);
        $rbac = $this->db->insert_id();
        $this->db->insert('menus', ['menu_name' => 'Users', 'menu_url' => 'backend/users', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac]);
        $this->db->insert('menus', ['menu_name' => 'Roles', 'menu_url' => 'backend/roles', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac]);
        $this->db->insert('menus', ['menu_name' => 'Permissions', 'menu_url' => 'backend/permissions', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac]);
        $this->db->insert('menus', ['menu_name' => 'Roles Permissions', 'menu_url' => 'backend/roles', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac]);


        echo "Menus seeding completed.";
    }

    public function permissions_seeders(){
         // Insert default permissions
         $permissions_data = array(
            array('permission_name' => 'create'),
            array('permission_name' => 'read'),
            array('permission_name' => 'update'),
            array('permission_name' => 'delete')
        );
        $this->db->insert_batch('permissions', $permissions_data);

        echo "Permissions seeding completed.";
    }

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

    public function profiles_seeders(){
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 5; $i++) {
            $data = array(
                'user_id' => $i,
                'first_name' => $faker->username,
                'last_name' => $faker->username,
                'address' => $faker->word,
                'phone' => $faker->phoneNumber,
            );

            $this->db->insert('profile', $data);
        }

        echo "Profiles seeding completed.";
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
        $this->db->insert_batch('roles_permissions', $role_permissions);

        echo "Role permissions seeded successfully.";
    }
}
