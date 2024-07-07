<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_menus extends CI_Migration {

    public function up() {
        // Load the dbforge library
        $this->load->dbforge();

        // Define fields
        $fields = array(
            'menu_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'menu_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => FALSE,
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'menu_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'menu_icon' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'parent_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'permission_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                // Add more constraints as needed
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

        // Add the fields to the table
        $this->dbforge->add_field($fields);

        // Define the primary key
        $this->dbforge->add_key('menu_id', TRUE);

        // Create the table
        $this->dbforge->create_table('menus', TRUE, array(
            'ENGINE' => 'InnoDB',
            'DEFAULT CHARACTER SET' => 'utf8',
            'COLLATE' => 'utf8_general_ci'
        ));

        // Add foreign key constraint
        $this->db->query('ALTER TABLE `menus` ADD CONSTRAINT `fk_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`menu_id`)');
    }

    public function down() {
        $this->dbforge->drop_table('menus');
    }
}
