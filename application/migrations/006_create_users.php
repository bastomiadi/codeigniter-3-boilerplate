<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users extends CI_Migration {

    public function up() {
        // Create users table
        $this->db->query("
            CREATE TABLE `users` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `username` VARCHAR(50) NOT NULL,
                `email` VARCHAR(100) NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `role_id` INT NOT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT `pk_users` PRIMARY KEY(`id`),
                FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
            ) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;
        ");
    }

    public function down() {
        $this->dbforge->drop_table('users', TRUE);
    }
}
