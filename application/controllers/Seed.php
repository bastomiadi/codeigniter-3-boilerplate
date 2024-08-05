<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once APPPATH . '../vendor/autoload.php';

class Seed extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->permissions_seeders();
        $this->roles_seeders();
        $this->menus_seeders();
        $this->users_seeders();
        $this->profiles_seeders();
        $this->categories_seeders();
        $this->products_seeders();
        $this->role_permissions_seeder();
        $this->users_roles_seeder();
    }

    public function roles_seeders(){
        // Insert default roles
        $roles_data = array(
            array('role_name' => 'superadmin'),
            array('role_name' => 'member'),
            // array('role_name' => 'member')
        );
        $this->db->insert_batch('roles', $roles_data);

        echo "Roles seeding completed.";
    }

    public function menus_seeders(){
        // Insert master menu and sub-menus
        $this->db->insert('menus', ['menu_name' => 'Master', 'menu_url' => '#', 'menu_icon' => 'fas fa-cogs', 'parent_id' => null, 'permission_id' => 49]);
        $master_menu_id = $this->db->insert_id();
        $this->db->insert('menus', ['menu_name' => 'Products', 'menu_url' => 'backend/product', 'menu_icon' => 'fas fa-box', 'parent_id' => $master_menu_id, 'permission_id' => 47]);
        $this->db->insert('menus', ['menu_name' => 'Categories', 'menu_url' => 'backend/category', 'menu_icon' => 'fas fa-list', 'parent_id' => $master_menu_id, 'permission_id' => 46]);
        $this->db->insert('menus', ['menu_name' => 'Menus', 'menu_url' => 'backend/menus', 'menu_icon' => 'fas fa-list', 'parent_id' => $master_menu_id, 'permission_id' => 48]);
        $this->db->insert('menus', ['menu_name' => 'Dashboard', 'menu_url' => 'backend/dashboard', 'menu_icon' => 'fas fa-list', 'parent_id' => 2]);

        $this->db->insert('menus', ['menu_name' => 'RBAC', 'menu_url' => '#', 'menu_icon' => 'fas fa-list', 'parent_id' => null, 'permission_id' => 50]);
        $rbac = $this->db->insert_id();
        $this->db->insert('menus', ['menu_name' => 'Users', 'menu_url' => 'backend/users', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac, 'permission_id' => 51]);
        $this->db->insert('menus', ['menu_name' => 'Roles', 'menu_url' => 'backend/roles', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac, 'permission_id' => 52]);
        $this->db->insert('menus', ['menu_name' => 'Permissions', 'menu_url' => 'backend/permissions', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac, 'permission_id' => 54]);
        $this->db->insert('menus', ['menu_name' => 'Roles Permissions', 'menu_url' => 'backend/Roles_Permission', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac, 'permission_id' => 53]);

        echo "Menus seeding completed.";
    }

    public function permissions_seeders(){
         // Insert default permissions
         $permissions_data = array(
            // Create permissions

            // auth
            array('permission_name' => 'logout'),

            // dashboard
            array('permission_name' => 'dashboard'),

            // profile
            array('permission_name' => 'update_profile'),

            // category
            array('permission_name' => 'create_category'),
            array('permission_name' => 'read_category'),
            array('permission_name' => 'update_category'),
            array('permission_name' => 'delete_category'),
            array('permission_name' => 'detail_category'),
            array('permission_name' => 'restore_category'),

            // product
            array('permission_name' => 'create_product'),
            array('permission_name' => 'read_product'),
            array('permission_name' => 'update_product'),
            array('permission_name' => 'delete_product'),
            array('permission_name' => 'detail_product'),
            array('permission_name' => 'restore_product'),

            // menu
            array('permission_name' => 'create_menu'),
            array('permission_name' => 'read_menu'),
            array('permission_name' => 'update_menu'),
            array('permission_name' => 'delete_menu'),
            array('permission_name' => 'detail_menu'),
            array('permission_name' => 'restore_menu'),

            // user
            array('permission_name' => 'create_user'),
            array('permission_name' => 'read_user'),
            array('permission_name' => 'update_user'),
            array('permission_name' => 'delete_user'),
            array('permission_name' => 'detail_user'),
            array('permission_name' => 'restore_user'),

            // role
            array('permission_name' => 'create_role'),
            array('permission_name' => 'read_role'),
            array('permission_name' => 'update_role'),
            array('permission_name' => 'delete_role'),
            array('permission_name' => 'detail_role'),
            array('permission_name' => 'restore_role'),

            // permission
            array('permission_name' => 'create_permission'),
            array('permission_name' => 'read_permission'),
            array('permission_name' => 'update_permission'),
            array('permission_name' => 'delete_permission'),
            array('permission_name' => 'detail_permission'),
            array('permission_name' => 'restore_permission'),

            // role permission
            array('permission_name' => 'create_role_permission'),
            array('permission_name' => 'read_role_permission'),
            array('permission_name' => 'update_role_permission'),
            array('permission_name' => 'delete_role_permission'),
            array('permission_name' => 'detail_role_permission'),
            array('permission_name' => 'restore_role_permission'),

            // permission menu & sub menu
            array('permission_name' => 'menu_category'),
            array('permission_name' => 'menu_product'),
            array('permission_name' => 'menu_menu'),
            array('permission_name' => 'menu_master'),
            array('permission_name' => 'menu_rbac'),
            array('permission_name' => 'menu_user'),
            array('permission_name' => 'menu_roles'),
            array('permission_name' => 'menu_roles_permission'),
            array('permission_name' => 'menu_permission'),
        );
        $this->db->insert_batch('permissions', $permissions_data);

        echo "Permissions seeding completed.";
    }

    public function users_seeders(){
        $data = array(
            array(
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => md5('password'), // Example: Use secure password hashing
            ),
            array(
                'username' => 'member',
                'email' => 'member@example.com',
                'password' => md5('password'), // Example: Use secure password hashing
            ),
            // Add more user arrays as needed
        );

        // Insert user into 'users' table with batch
        $this->db->insert_batch('users', $data);

        echo "Users seeding completed.";
    }

    public function profiles_seeders(){
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 2; $i++) {
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
                'description' => $faker->sentence,
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

    public function users_roles_seeder(){
        $this->db->insert('user_roles', [
            'user_id' => 1,
            'role_id' => 1  // member role
        ]);

        $this->db->insert('user_roles', [
            'user_id' => 2,
            'role_id' => 2  // member role
        ]);

        echo "users roles seeded successfully.";
    }

    public function role_permissions_seeder() {
        // Assign permissions to roles
        // member
        $role_permissions = [
            ['role_id' => 2, 'permission_id' => 1],
            ['role_id' => 2, 'permission_id' => 2],
            ['role_id' => 2, 'permission_id' => 3],
            ['role_id' => 2, 'permission_id' => 4],
            ['role_id' => 2, 'permission_id' => 10],
            ['role_id' => 2, 'permission_id' => 49],
            ['role_id' => 2, 'permission_id' => 46],
            ['role_id' => 2, 'permission_id' => 47],
        ];
        $this->db->insert_batch('roles_permissions', $role_permissions);

        $data = array();
        // superadmin
        $query = $this->db->get('permissions');
        // Return the result as an array
        foreach ($query->result_array() as $key => $value) {
            $data[$key] = array(
                'role_id' => 1,
                'permission_id' => $value['permission_id']
            );
        }

        $this->db->insert_batch('roles_permissions', $data);
        
        echo "Role permissions seeded successfully.";

    }
}
