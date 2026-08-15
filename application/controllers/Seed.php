<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seed extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ( ! is_cli()) {
            show_error('Perintah ini hanya bisa dijalankan via CLI: php index.php seed');
        }

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

    private function perm_id($name) {
        $row = $this->db->get_where('permissions', array('permission_name' => $name))->row();
        return $row ? (int) $row->permission_id : 0;
    }

    private function menu($name, $url, $icon, $parent_id, $permission_name) {
        $permission_id = $this->perm_id($permission_name) ?: NULL;
        $existing = $this->db->get_where('menus', array('menu_name' => $name, 'menu_url' => $url))->row();
        if ($existing) {
            if ((int) $existing->permission_id !== $permission_id) {
                $this->db->where('menu_id', $existing->menu_id)->update('menus', array('permission_id' => $permission_id));
            }
            return (int) $existing->menu_id;
        }

        $this->db->insert('menus', array(
            'menu_name' => $name,
            'description' => $name,
            'menu_url' => $url,
            'menu_icon' => $icon,
            'parent_id' => $parent_id,
            'permission_id' => $permission_id
        ));
        return (int) $this->db->insert_id();
    }

    public function roles_seeders() {
        $roles = array(
            array('role_name' => 'superadmin', 'description' => 'Full access to all features'),
            array('role_name' => 'member', 'description' => 'Limited member access'),
        );

        foreach ($roles as $role) {
            if ( ! $this->db->get_where('roles', array('role_name' => $role['role_name']))->row()) {
                $this->db->insert('roles', $role);
            }
        }

        echo "Roles seeding completed.\n";
    }

    public function menus_seeders() {
        $master = $this->menu('Master', '#', 'fas fa-cogs', NULL, 'menu_master');
        $this->menu('Products', 'backend/product', 'fas fa-box', $master, 'menu_product');
        $this->menu('Categories', 'backend/category', 'fas fa-list', $master, 'menu_category');
        $this->menu('Menus', 'backend/menus', 'fas fa-list', $master, 'menu_menu');
        $this->menu('Dashboard', 'backend/dashboard', 'fas fa-list', NULL, NULL);

        $rbac = $this->menu('RBAC', '#', 'fas fa-list', NULL, 'menu_rbac');
        $this->menu('Users', 'backend/users', 'fas fa-list', $rbac, 'menu_user');
        $this->menu('Roles', 'backend/roles', 'fas fa-list', $rbac, 'menu_roles');
        $this->menu('Permissions', 'backend/permissions', 'fas fa-list', $rbac, 'menu_permission');
        $this->menu('Roles Permissions', 'backend/Roles_Permission', 'fas fa-list', $rbac, 'menu_roles_permission');

        $generator = $this->menu('Generator', '#', 'fas fa-list', NULL, 'menu_generator');
        $this->menu('Generate Model', 'backend/generator-model', 'fas fa-list', $generator, 'generator_model');
        $this->menu('Generate CRUD', 'backend/generator-crud', 'fas fa-list', $generator, 'generator_crud');

        echo "Menus seeding completed.\n";
    }

    public function permissions_seeders() {
        $permissions_data = array(
            // auth
            array('permission_name' => 'logout'),
            array('permission_name' => 'dashboard'),
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

            // generator
            array('permission_name' => 'generator_model'),
            array('permission_name' => 'generator_crud'),

            // menu & sub menu
            array('permission_name' => 'menu_category'),
            array('permission_name' => 'menu_product'),
            array('permission_name' => 'menu_menu'),
            array('permission_name' => 'menu_master'),
            array('permission_name' => 'menu_rbac'),
            array('permission_name' => 'menu_user'),
            array('permission_name' => 'menu_roles'),
            array('permission_name' => 'menu_roles_permission'),
            array('permission_name' => 'menu_permission'),
            array('permission_name' => 'menu_generator'),
        );

        foreach ($permissions_data as $permission) {
            if ( ! $this->db->get_where('permissions', array('permission_name' => $permission['permission_name']))->row()) {
                $this->db->insert('permissions', array(
                    'permission_name' => $permission['permission_name'],
                    'route' => '',
                    'description' => '',
                ));
            }
        }

        echo "Permissions seeding completed.\n";
    }

    public function users_seeders() {
        $data = array(
            array('username' => 'admin', 'email' => 'admin@example.com'),
            array('username' => 'member', 'email' => 'member@example.com'),
        );

        foreach ($data as $user) {
            if ( ! $this->db->get_where('users', array('username' => $user['username']))->row()) {
                $user['password'] = password_hash('password', PASSWORD_DEFAULT);
                $this->db->insert('users', $user);
            }
        }

        echo "Users seeding completed.\n";
    }

    public function profiles_seeders() {
        $faker = Faker\Factory::create();

        $users = $this->db->get('users')->result();
        foreach ($users as $user) {
            if ( ! $this->db->get_where('profile', array('user_id' => $user->id))->row()) {
                $this->db->insert('profile', array(
                    'user_id' => $user->id,
                    'first_name' => $faker->username,
                    'last_name' => $faker->username,
                    'address' => $faker->word,
                    'phone' => $faker->phoneNumber,
                ));
            }
        }

        echo "Profiles seeding completed.\n";
    }

    public function categories_seeders() {
        if ($this->db->count_all('categories') > 0) {
            echo "Categories already seeded, skipped.\n";
            return;
        }

        $faker = Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $this->db->insert('categories', array(
                'name' => $faker->word,
                'description' => $faker->sentence,
                'created_at' => date('Y-m-d H:i:s')
            ));
        }

        echo "Categories seeding completed.\n";
    }

    public function products_seeders() {
        if ($this->db->count_all('products') > 0) {
            echo "Products already seeded, skipped.\n";
            return;
        }

        $faker = Faker\Factory::create();
        for ($i = 0; $i < 5000; $i++) {
            $this->db->insert('products', array(
                'name' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 10, 1000),
                'category_id' => rand(1, 10),
                'created_at' => date('Y-m-d H:i:s')
            ));
        }

        echo "Products seeding completed.\n";
    }

    public function users_roles_seeder() {
        $pairs = array(
            array('user_id' => 1, 'role_id' => 1), // admin -> superadmin
            array('user_id' => 2, 'role_id' => 2), // member -> member
        );

        foreach ($pairs as $pair) {
            if ( ! $this->db->get_where('user_roles', $pair)->row()) {
                $this->db->insert('user_roles', $pair);
            }
        }

        echo "Users roles seeding completed.\n";
    }

    public function role_permissions_seeder() {
        // member
        $member = $this->db->get_where('roles', array('role_name' => 'member'))->row();
        if ($member) {
            $member_permissions = array(
                'logout', 'dashboard', 'update_profile',
                'read_category', 'read_product',
                'menu_master', 'menu_category', 'menu_product',
            );
            foreach ($member_permissions as $permission_name) {
                $permission_id = $this->perm_id($permission_name);
                if ($permission_id && ! $this->db->get_where('roles_permissions', array('role_id' => $member->role_id, 'permission_id' => $permission_id))->row()) {
                    $this->db->insert('roles_permissions', array('role_id' => $member->role_id, 'permission_id' => $permission_id, 'created_at' => date('Y-m-d H:i:s')));
                }
            }
        }

        // superadmin -> all permissions
        $superadmin = $this->db->get_where('roles', array('role_name' => 'superadmin'))->row();
        if ($superadmin) {
            foreach ($this->db->get('permissions')->result() as $permission) {
                if ( ! $this->db->get_where('roles_permissions', array('role_id' => $superadmin->role_id, 'permission_id' => $permission->permission_id))->row()) {
                    $this->db->insert('roles_permissions', array('role_id' => $superadmin->role_id, 'permission_id' => $permission->permission_id, 'created_at' => date('Y-m-d H:i:s')));
                }
            }
        }

        echo "Role permissions seeding completed.\n";
    }
}
