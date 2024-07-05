<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users extends CI_Migration {

    public function up() {
        $this->load->dbforge();

        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => FALSE,
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => FALSE,
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
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
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users', TRUE, array(
            'ENGINE' => 'InnoDB',
            'DEFAULT CHARACTER SET' => 'utf8',
            'COLLATE' => 'utf8_general_ci'
        ));
    }

    public function down() {
        $this->dbforge->drop_table('users', TRUE);
    }
}
