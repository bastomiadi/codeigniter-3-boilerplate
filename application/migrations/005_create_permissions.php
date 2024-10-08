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
            ),
            'route' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'not_null' => TRUE
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'not_null' => TRUE
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'deleted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'created_by' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'updated_by' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'deleted_by' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'restored_by' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('permission_id', TRUE);
        $this->dbforge->create_table('permissions');

    }

    public function down() {
        $this->dbforge->drop_table('permissions');
    }
}
