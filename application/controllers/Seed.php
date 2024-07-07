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
        $this->db->insert('menus', ['menu_name' => 'Master', 'menu_url' => '#', 'menu_icon' => 'fas fa-cogs', 'parent_id' => null, 'permission_id' => 16]);
        $master_menu_id = $this->db->insert_id();
        $this->db->insert('menus', ['menu_name' => 'Products', 'menu_url' => 'backend/product', 'menu_icon' => 'fas fa-box', 'parent_id' => $master_menu_id, 'permission_id' => 6]);
        $this->db->insert('menus', ['menu_name' => 'Categories', 'menu_url' => 'backend/category', 'menu_icon' => 'fas fa-list', 'parent_id' => $master_menu_id, 'permission_id' => 7]);
        $this->db->insert('menus', ['menu_name' => 'Menus', 'menu_url' => 'backend/menus', 'menu_icon' => 'fas fa-list', 'parent_id' => $master_menu_id, 'permission_id' => 9]);
        $this->db->insert('menus', ['menu_name' => 'Dashboard', 'menu_url' => 'backend/dashboard', 'menu_icon' => 'fas fa-list', 'parent_id' => null]);

        $this->db->insert('menus', ['menu_name' => 'RBAC', 'menu_url' => '#', 'menu_icon' => 'fas fa-list', 'parent_id' => null, 'permission_id' => 17]);
        $rbac = $this->db->insert_id();
        $this->db->insert('menus', ['menu_name' => 'Users', 'menu_url' => 'backend/users', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac, 'permission_id' => 8]);
        $this->db->insert('menus', ['menu_name' => 'Roles', 'menu_url' => 'backend/roles', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac, 'permission_id' => 13]);
        $this->db->insert('menus', ['menu_name' => 'Permissions', 'menu_url' => 'backend/permissions', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac, 'permission_id' => 14]);
        $this->db->insert('menus', ['menu_name' => 'Roles Permissions', 'menu_url' => 'backend/Roles_Permission', 'menu_icon' => 'fas fa-list', 'parent_id' => $rbac, 'permission_id' => 15]);

        echo "Menus seeding completed.";
    }

    public function permissions_seeders(){
         // Insert default permissions
         $permissions_data = array(
            // Create permissions
            array('permission_name' => 'create'),
            array('permission_name' => 'read'),
            array('permission_name' => 'update'),
            array('permission_name' => 'delete'),
            array('permission_name' => 'detail'),

            array('permission_name' => 'manage_products'),
            array('permission_name' => 'manage_categories'),
            array('permission_name' => 'manage_users'),
            array('permission_name' => 'manage_menus'),
            array('permission_name' => 'update_own'),

            array('permission_name' => 'restored'),
            array('permission_name' => 'dashboard'),
            array('permission_name' => 'manage_roles'),
            array('permission_name' => 'manage_permissions'),
            array('permission_name' => 'manage_roles_permissions'),

            array('permission_name' => 'menu_master'),
            array('permission_name' => 'menu_rbac'),
        );
        $this->db->insert_batch('permissions', $permissions_data);

        echo "Permissions seeding completed.";
    }

    public function users_seeders(){
        // $faker = Faker\Factory::create();

        // for ($i = 1; $i <= 2; $i++) {
        //     $data = array(
        //         'username' => $faker->userName,
        //         'email' => $faker->email,
        //         'password' => md5('password'), // Example: Use secure password hashing
        //         // 'role_id' => 2 // Assuming 'member' role_id is 2
        //     );

        //     $this->db->insert('users', $data);
        // }

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
    }

    public function role_permissions_seeder() {
        // // Define role permissions
        // $role_permissions = array(
        //     // Administrator permissions
        //     array('role_id' => 1, 'permission_id' => 1), // create
        //     array('role_id' => 1, 'permission_id' => 2), // read
        //     array('role_id' => 1, 'permission_id' => 3), // update
        //     array('role_id' => 1, 'permission_id' => 4), // delete

        //     // Member permissions (only read and create)
        //     array('role_id' => 2, 'permission_id' => 1), // create
        //     array('role_id' => 2, 'permission_id' => 2), // read
        // );

        // // Insert role permissions
        // $this->db->insert_batch('roles_permissions', $role_permissions);

        // echo "Role permissions seeded successfully.";

        // Assign permissions to roles
        $role_permissions = [
            
            ['role_id' => 2, 'permission_id' => 1],
            ['role_id' => 2, 'permission_id' => 2],

            // superadmin
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' => 2],
            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 1, 'permission_id' => 6],
            ['role_id' => 1, 'permission_id' => 7],
            ['role_id' => 1, 'permission_id' => 8],
            ['role_id' => 1, 'permission_id' => 9],
            ['role_id' => 1, 'permission_id' => 13],
            ['role_id' => 1, 'permission_id' => 14],
            ['role_id' => 1, 'permission_id' => 15],
            ['role_id' => 1, 'permission_id' => 16],
            ['role_id' => 1, 'permission_id' => 17],
        ];
        $this->db->insert_batch('roles_permissions', $role_permissions);

        // echo "Role permissions seeded successfully.";

    }
}
