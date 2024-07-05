<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_user_roles extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'role_id' => array(
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
        $this->dbforge->add_key(array('user_id', 'role_id'), TRUE);
        $this->dbforge->create_table('user_roles');

    }

    public function down() {
        $this->dbforge->drop_table('user_roles', TRUE);
    }

}