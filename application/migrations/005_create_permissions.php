<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_permissions extends CI_Migration {

    public function up() {
        // Create permissions table
        $this->dbforge->add_field(array(
            'permission_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'permission_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'not_null' => TRUE
            )
        ));
        $this->dbforge->add_key('permission_id', TRUE);
        $this->dbforge->create_table('permissions');

        // Insert default permissions
        $permissions_data = array(
            array('permission_name' => 'create'),
            array('permission_name' => 'read'),
            array('permission_name' => 'update'),
            array('permission_name' => 'delete')
        );
        $this->db->insert_batch('permissions', $permissions_data);
    }

    public function down() {
        $this->dbforge->drop_table('permissions');
    }
}
