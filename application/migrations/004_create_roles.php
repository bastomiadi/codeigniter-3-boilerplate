<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_roles extends CI_Migration {

    public function up() {
        // Create roles table
        $this->dbforge->add_field(array(
            'role_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'role_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'not_null' => TRUE
            )
        ));
        $this->dbforge->add_key('role_id', TRUE);
        $this->dbforge->create_table('roles');

        // Insert default roles
        $roles_data = array(
            array('role_name' => 'superadmin'),
            array('role_name' => 'admin'),
            array('role_name' => 'member')
        );
        $this->db->insert_batch('roles', $roles_data);
    }

    public function down() {
        $this->dbforge->drop_table('roles');
    }
}
