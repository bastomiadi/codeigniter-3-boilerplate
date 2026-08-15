<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // $this->load->library('migration');
    }

    public function index() {
        if ( ! is_cli()) {
            show_error('Perintah ini hanya bisa dijalankan via CLI: php index.php migrate');
        }

        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "Migrations ran successfully.\n";
        }
    }

    public function run() {
        $this->index();
    }

    public function rollback($steps = 1) {
        if ( ! is_cli()) {
            show_error('Perintah ini hanya bisa dijalankan via CLI: php index.php migrate rollback');
        }

        $steps = max(1, (int) $steps);
        $current = 0;
        $row = $this->db->get('migrations')->row();
        if ($row) {
            $current = (int) $row->version;
        }
        $target = max(0, $current - $steps);
        if ($this->migration->version($target) === FALSE) {
            show_error($this->migration->error_string());
        }
        echo "Migration rolled back to version {$target}.\n";
    }

    public function create($name = '') {
        if ( ! is_cli()) {
            show_error('Perintah ini hanya bisa dijalankan via CLI.');
        }

        $name = strtolower(trim($name));
        if ( ! preg_match('/^[a-z0-9_]+$/', $name)) {
            show_error('Nama migration tidak valid. Contoh: php index.php migrate create create_orders');
        }
        if (strpos($name, 'create_') !== 0) {
            $name = 'create_' . $name;
        }

        $path = APPPATH . 'migrations/';
        $max = 0;
        foreach (glob($path . '*.php') as $file) {
            if (preg_match('/^(\d{3})_/', basename($file), $matches)) {
                $max = max($max, (int) $matches[1]);
            }
        }

        $filename = sprintf('%03d', $max + 1) . '_' . $name . '.php';
        if (glob($path . '*' . $name . '.php')) {
            show_error('Migration untuk "' . $name . '" sudah ada.');
        }

        $table = substr($name, strlen('create_'));
        $class = 'Migration_' . ucfirst($name);
        $content = "<?php\n";
        $content .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";
        $content .= "class {$class} extends CI_Migration {\n\n";
        $content .= "    public function up() {\n";
        $content .= "        \$this->dbforge->add_field(array(\n";
        $content .= "            'id' => array(\n";
        $content .= "                'type' => 'INT',\n";
        $content .= "                'unsigned' => TRUE,\n";
        $content .= "                'auto_increment' => TRUE\n";
        $content .= "            ),\n";
        $content .= "            // Tambahkan kolom lain di sini\n";
        $content .= "            'created_at' => array(\n";
        $content .= "                'type' => 'DATETIME',\n";
        $content .= "                'null' => TRUE,\n";
        $content .= "            ),\n";
        $content .= "        ));\n";
        $content .= "        \$this->dbforge->add_key('id', TRUE);\n";
        $content .= "        \$this->dbforge->create_table('{$table}');\n";
        $content .= "    }\n\n";
        $content .= "    public function down() {\n";
        $content .= "        \$this->dbforge->drop_table('{$table}', TRUE);\n";
        $content .= "    }\n";
        $content .= "}\n";

        $this->load->helper('file');
        if (write_file($path . $filename, $content)) {
            echo "Migration dibuat: application/migrations/{$filename}\n";
        } else {
            show_error('Gagal menulis file migration.');
        }
    }
}
