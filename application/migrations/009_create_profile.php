<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_profile extends CI_Migration {

    public function up() {
      
        $fields = array(
            'profile_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'address' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'image_profile' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
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
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'updated_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'deleted_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'restored_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('profile_id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('profile', TRUE, array(
            'ENGINE' => 'InnoDB',
            'DEFAULT CHARACTER SET' => 'utf8',
            'COLLATE' => 'utf8_general_ci'
        ));
    }

    public function down() {
        // Drop the profile table
        $this->dbforge->drop_table('profile', TRUE);
    }
}
