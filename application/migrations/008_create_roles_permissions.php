<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_roles_permissions extends CI_Migration {

    public function up() {
         // Role Permissions table
         $this->dbforge->add_field(array(
            'role_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'permission_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
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
        $this->dbforge->add_key(array('role_id', 'permission_id'), TRUE);
        $this->dbforge->create_table('roles_permissions');
    }

    public function down() {
        $this->dbforge->drop_table('roles_permissions');
    }
}
