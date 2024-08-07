<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelGenerator extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->load->helper('form');
        $this->load->database(); // Load database library
    }

    // private function fetchTables() {
    //     $query = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()");
    //     $tables = array();
    //     foreach ($query->result() as $row) {
    //         if (isset($row->table_name)) {
    //             $tables[] = $row->table_name;
    //         } else {
    //             // Log or display error message for debugging
    //             log_message('error', 'table_name property not found in fetchTables()');
    //         }
    //     }        
    //     return $tables;
    // }
    

    public function model() {
        $this->load->library('form_validation');

        // Form validation rules
        $this->form_validation->set_rules('table', 'Table Name', 'required');
        $this->form_validation->set_rules('model_name', 'Model Name', 'required');
        $this->form_validation->set_rules('model_path', 'Model Path', 'required');

        if ($this->form_validation->run() === TRUE && $this->input->server('REQUEST_METHOD') == 'POST') {

            $table = $this->input->post('table');
            $modelName = $this->input->post('model_name');
            $modelPath = $this->input->post('model_path');
            // $namespace = $this->input->post('namespace');

            $primary_key_fields = array();

            // Fetch table fields
            $fields = $this->db->list_fields($table);

            // Get field data
            $fields_data = $this->db->field_data($table);

            foreach ($fields_data as $key => $value) {
                if($value->primary_key == 1){
                    $primary_key_fields = $value->name;
                }
                break;
            }

            // Create the model content
            $modelContent = "<?php\n";

            $modelContent .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";
            //$modelContent .= "use CI_Model;\n\n";
            $modelContent .= "class " . ucfirst($modelName) . "_model extends CI_Model {\n\n";

            // Define table and select columns
            $modelContent .= "    var \$table = \"$table\";\n";
            $modelContent .= "    var \$select_column = array(" . $this->generateArrayCode($fields) . ");\n";
            $modelContent .= "    var \$order_column = array(" . $this->generateArrayCode($fields) . ", null);\n\n";

            // Constructor
            $modelContent .= "    public function __construct() {\n";
            $modelContent .= "        parent::__construct();\n";
            $modelContent .= "        // Load database\n";
            $modelContent .= "        \$this->load->database();\n";
            $modelContent .= "    }\n\n";

            // CRUD methods
            $modelContent .= $this->generateAddMethod($fields);
            $modelContent .= $this->generateEditMethod($fields, $primary_key_fields);
            $modelContent .= $this->generateSoftDeleteMethod($fields_data, $primary_key_fields);
            $modelContent .= $this->generateDeleteMethod($fields, $primary_key_fields);

            // Query methods
            $modelContent .= $this->generateMakeQueryMethod();
            $modelContent .= $this->generateMakeDatatablesMethod();
            $modelContent .= $this->generateGetFilteredDataMethod();
            $modelContent .= $this->generateGetAllDataMethod($fields_data);
            $modelContent .= $this->generateGetSelect2Method();

            $modelContent .= "}\n";

            // Write the model file
            $modelFilePath = rtrim(APPPATH . $modelPath, '/') . '/';
            if (!is_dir($modelFilePath)) {
                // Create the directory with 0755 permissions
                mkdir($modelFilePath, 0755, true);
            }

            if (write_file($modelFilePath . ucfirst($modelName) . '_model.php', $modelContent)) {
                // Redirect or send success message
                $this->session->set_flashdata('success', "Model {$modelName} generated successfully.");
            } else {
                // Handle error
                $this->session->set_flashdata('error', "Model {$modelName} generated failed.");
            }
            return redirect('backend/generator-model');
        }
            
        //$this->load->view('generate_model', $data);
        $data['title'] = 'Model Generator';
        $data['page_title'] = 'Model Generator';
        $data['contents'] = $this->load->view('backend/generator/generate_model', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
        return $this; 
    }

    // Helper function to generate array code for var declaration
    private function generateArrayCode($array) {
        $output = "";
        foreach ($array as $item) {
            $output .= "\"$item\", ";
        }
        return rtrim($output, ", ");
    }

    // Helper functions to generate CRUD methods
    private function generateAddMethod($fields) {
        $methodContent = "    public function add_" . $this->singularize($this->input->post('table')) . "(";
        foreach ($fields as $field) {
            $methodContent .= "\$$field, ";
        }
        $methodContent = rtrim($methodContent, ", ");
        $methodContent .= ") {\n";
        $methodContent .= "        \$data = array(\n";
        foreach ($fields as $field) {
            $methodContent .= "            '$field' => \$$field,\n";
        }
        $methodContent .= "        );\n";
        $methodContent .= "        \$this->db->insert(\$this->table, \$data);\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    private function generateEditMethod($fields, $primary_key_fields) {
        $methodContent = "    public function edit_" . $this->singularize($this->input->post('table')) . "(";
        foreach ($fields as $field) {
            $methodContent .= "\$$field, ";
        }
        $methodContent = rtrim($methodContent, ", ");
        $methodContent .= ") {\n";
        $methodContent .= "        \$data = array(\n";
        foreach ($fields as $field) {
            $methodContent .= "            '$field' => \$$field,\n";
        }
        $methodContent .= "        );\n";
        $methodContent .= "        \$this->db->where('{$primary_key_fields}', \${$primary_key_fields})->update(\$this->table, \$data);\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    private function generateSoftDeleteMethod($fields_data, $primary_key_fields) {
        $methodContent = "    public function soft_delete_" . $this->singularize($this->input->post('table')) . "(\${$primary_key_fields}, \$deleted_by) {\n";
        $methodContent .= "        \$data = array(\n";
        foreach ($fields_data as $key => $value) {
            if($value->name = 'deleted_at'){
                $methodContent .= "            'deleted_at' => date('Y-m-d H:i:s'),\n";
            }
            if($value->name = 'deleted_at'){
                $methodContent .= "            'deleted_by' => \$deleted_by\n";
            }
        }
        $methodContent .= "        );\n";
        $methodContent .= "        \$this->db->where('{$primary_key_fields}', \${$primary_key_fields})->update(\$this->table, \$data);\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    private function generateDeleteMethod($fields, $primary_key_fields) {
        $methodContent = "    public function delete_" . $this->singularize($this->input->post('table')) . "(\${$primary_key_fields}) {\n";
        $methodContent .= "        \$this->db->where('{$primary_key_fields}', \${$primary_key_fields})->delete(\$this->table);\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    // Helper functions to generate query methods
    private function generateMakeQueryMethod() {
        $methodContent = "    public function make_query() {\n";
        $methodContent .= "        \$this->db->select(\$this->select_column);\n";
        $methodContent .= "        \$this->db->from(\$this->table);\n";
        $methodContent .= "        \$this->db->where('deleted_at', NULL); // Exclude soft deleted records\n";
        $methodContent .= "        if (isset(\$_POST[\"search\"][\"value\"])) {\n";
        $methodContent .= "            \$this->db->like(\"name\", \$_POST[\"search\"][\"value\"]);\n";
        $methodContent .= "        }\n";
        $methodContent .= "        if (isset(\$_POST[\"order\"])) {\n";
        $methodContent .= "            \$this->db->order_by(\$this->order_column[\$_POST['order']['0']['column']], \$_POST['order']['0']['dir']);\n";
        $methodContent .= "        } else {\n";
        $methodContent .= "            \$this->db->order_by('id', 'DESC');\n";
        $methodContent .= "        }\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    private function generateMakeDatatablesMethod() {
        $methodContent = "    public function make_datatables() {\n";
        $methodContent .= "        \$this->make_query();\n";
        $methodContent .= "        if (isset(\$_POST[\"length\"]) && \$_POST[\"length\"] != -1) {\n";
        $methodContent .= "            \$this->db->limit(\$_POST['length'], \$_POST['start']);\n";
        $methodContent .= "        }\n";
        $methodContent .= "        \$query = \$this->db->get();\n";
        $methodContent .= "        return \$query->result();\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    private function generateGetFilteredDataMethod() {
        $methodContent = "    public function get_filtered_data() {\n";
        $methodContent .= "        \$this->make_query();\n";
        $methodContent .= "        \$query = \$this->db->get();\n";
        $methodContent .= "        return \$query->num_rows();\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    private function generateGetAllDataMethod($fields_data) {
        $methodContent = "    public function get_all_data() {\n";
        $methodContent .= "        \$this->db->select(\"*\");\n";
        $methodContent .= "        \$this->db->from(\$this->table);\n";

        foreach ($fields_data as $key => $value) {
            if($value->name = 'deleted_at'){
                $methodContent .= "        \$this->db->where('deleted_at', NULL); // Exclude soft deleted records\n";
            }
        }

        $methodContent .= "        return \$this->db->count_all_results();\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    private function generateGetSelect2Method($fields_data) {
        $methodContent = "    public function get_select2(\$searchTerm = \"\") {\n";
        $methodContent .= "        \$this->db->select('id, name as text');\n";
        $methodContent .= "        \$this->db->from(\$this->table);\n";
        foreach ($fields_data as $key => $value) {
            if($value->name = 'deleted_at'){
                $methodContent .= "        \$this->db->where('deleted_at', NULL); // Exclude soft deleted records\n";
            }
        }
        $methodContent .= "        if (\$searchTerm != \"\") {\n";
        //$methodContent .= "            \$this->db->like('name', \$searchTerm);\n";
        $methodContent .= "            \$this->db->like('{$fields_data[1]['name']}', \$searchTerm);\n";
        $methodContent .= "        }\n";
        $methodContent .= "        \$query = \$this->db->get();\n";
        $methodContent .= "        return \$query->result_array();\n";
        $methodContent .= "    }\n\n";
        return $methodContent;
    }

    // Helper function to singularize table name
    private function singularize($table) {
        // You can implement your own logic to singularize table name if needed
        // For simplicity, returning table name as-is
        return $table;
    }
}
