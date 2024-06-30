<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_menus extends CI_Migration {

    public function up() {
        // Create menus table using raw SQL to ensure correct default value handling
        $sql = "CREATE TABLE `menus` (
            `menu_id` INT NOT NULL AUTO_INCREMENT,
            `menu_name` VARCHAR(50) NOT NULL,
            `menu_url` VARCHAR(255) NOT NULL,
            `menu_icon` VARCHAR(50) NULL,
            `parent_id` INT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`menu_id`),
            FOREIGN KEY (`parent_id`) REFERENCES `menus` (`menu_id`)
        ) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
        $this->db->query($sql);
    }

    public function down() {
        $this->dbforge->drop_table('menus');
    }
}
