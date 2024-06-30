<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_products extends CI_Migration {

    public function up() {
        // Define fields for the products table
        $this->dbforge->add_field(array(
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
            'price' => array(
                'type' => 'DECIMAL(10,2)',
                'default' => '0.00',
            ),
            'category_id' => array(
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
        ));

        // Set primary key
        $this->dbforge->add_key('id', TRUE);

        // Create the table
        $this->dbforge->create_table('products');

        // Add foreign key constraint for category_id
        $this->db->query('ALTER TABLE `products` ADD CONSTRAINT `fk_category_id`
                          FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
                          ON DELETE RESTRICT ON UPDATE CASCADE');
    }

    public function down() {
        // Drop the products table
        $this->dbforge->drop_table('products', TRUE);
    }
}
