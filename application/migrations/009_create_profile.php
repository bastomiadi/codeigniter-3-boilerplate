<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_profile extends CI_Migration {

    public function up() {
        // Define profile table fields
        $this->dbforge->add_field(array(
            'profile_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'not_null' => TRUE,
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
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));

        // Add primary key
        $this->dbforge->add_key('profile_id', TRUE);

        // Create the profile table
        $this->dbforge->create_table('profile');

        // Add foreign key to users table
        $this->db->query('ALTER TABLE `profile` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down() {
        // Drop the profile table
        $this->dbforge->drop_table('profile', TRUE);
    }
}
