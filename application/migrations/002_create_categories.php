<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_categories extends CI_Migration {

    public function up() {
        // Define fields for the categories table
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'description' => array(
                'type' => 'TEXT',
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

        // Set primary key
        $this->dbforge->add_key('id', TRUE);

        // Create the table
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('categories');
    }

    public function down() {
        // Drop the categories table
        $this->dbforge->drop_table('categories', TRUE);
    }
}
