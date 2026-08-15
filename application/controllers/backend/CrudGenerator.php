<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CrudGenerator extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }

    public function crud() {

        $this->load->library('form_validation');

        // Form validation rules
        $this->form_validation->set_rules('model_path', 'Model Path', 'required|regex_match[/^[a-zA-Z0-9_\/]+$/]');
        $this->form_validation->set_rules('controller_path', 'Controller Path', 'required|regex_match[/^[a-zA-Z0-9_\/]+$/]');
        $this->form_validation->set_rules('view_path', 'View Path', 'required|regex_match[/^[a-zA-Z0-9_\/]+$/]');

        if ($this->form_validation->run() === TRUE && $this->input->server('REQUEST_METHOD') == 'POST') {
            $model_path = $this->input->post('model_path');
            $controller_path = $this->input->post('controller_path');
            $view_path = $this->input->post('view_path');
            $model_file_path = APPPATH . $model_path . '.php'; // Construct the file path

            if(!file_exists($model_file_path)){
                $this->session->set_flashdata('error', "Crud generated failed.");
                return redirect('backend/generator-crud');
            }

            // get content table name from model file
            $model_content = file_get_contents($model_file_path);
            if (!preg_match('/var \$table = "(.*?)";/', $model_content, $matches)) {
                $this->session->set_flashdata('error', "Crud generated failed. Model tidak memiliki var \$table.");
                return redirect('backend/generator-crud');
            }
            $table_name = $matches[1];

            // get model class name from model file
            preg_match('/class\s+(\w+)/', $model_content, $class_matches);
            $model_name = isset($class_matches[1]) ? $class_matches[1] : basename($model_path);

            // get list fields from model file
            preg_match('/var \$select_column = array\((.*?)\);/s', $model_content, $matches);
            if (!isset($matches[1])) {
                $this->session->set_flashdata('error', "Crud generated failed. Model tidak memiliki var \$select_column.");
                return redirect('backend/generator-crud');
            }
            $fields = explode(",", str_replace(['"', ' '], '', $matches[1]));

            // get primary key field from the table
            $pk_field = 'id';
            foreach ($this->db->field_data($table_name) as $field) {
                if ($field->primary_key == 1) {
                    $pk_field = $field->name;
                    break;
                }
            }

            // fields that are editable from the form (exclude pk + audit columns)
            $editable = array_values(array_diff($fields, array(
                $pk_field,
                'created_at', 'updated_at', 'deleted_at',
                'created_by', 'updated_by', 'deleted_by', 'restored_by'
            )));

            // Write the controller file
            $controllerFilePath = rtrim(APPPATH . $controller_path, '/') . '/';
            if (!is_dir($controllerFilePath)) {
                // Create the directory with 0755 permissions
                mkdir($controllerFilePath, 0755, true);
            }
            write_file($controllerFilePath . ucfirst($table_name) . '.php', $this->generate_controller($table_name, $fields, $model_name, $pk_field, $editable));

            // generate view file
            $views = ['index'];
            foreach ($views as $view) {
                $viewFilePath = rtrim(APPPATH . $view_path, '/') . '/' . $table_name . '/';
                if (!is_dir($viewFilePath)) {
                    // Create the directory with 0755 permissions
                    mkdir($viewFilePath, 0755, true);
                }
                write_file($viewFilePath . $view . '.php',  $this->generate_index($table_name, $fields, $pk_field, $editable));
            }
            $this->session->set_flashdata('success', "Crud generated successfully.");
            return redirect('backend/generator-crud');
        }

        $data['title'] = 'Crud Generator';
        $data['page_title'] = 'Crud Generator';
        $data['models'] = $this->fetchModels();
        $data['contents'] = $this->load->view('backend/generator/generate_crud', $data, TRUE);
        $this->load->view('backend/layouts/main', $data);
        return $this;
    }

    private function fetchModels() {
        $models = array();
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(APPPATH . 'models/'));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $models[] = 'models/' . substr($iterator->getSubPathName(), 0, -4);
            }
        }
        sort($models);
        return $models;
    }

    private function generate_index($table_name, $fields, $pk_field, $editable) {
        $template = "";

        $template .= "<!-- Main content -->\n";
        $template .= "<section class=\"content\">\n";
        $template .= "    <div class=\"container-fluid\">\n";
        $template .= "        <div class=\"row\">\n";
        $template .= "            <div class=\"col-md-12\">\n";
        $template .= "                <button type=\"button\" class=\"btn btn-primary mb-2\" data-toggle=\"modal\" data-target=\"#add" . ucfirst($table_name) . "Modal\">Create " . ucfirst($table_name) . "</button>\n";
        $template .= "                <div class=\"card\">\n";
        $template .= "                    <div class=\"card-body\">\n";
        $template .= "                        <table id=\"" . $table_name . "-table\" class=\"table table-bordered table-hover\">\n";
        $template .= "                            <thead><tr>\n";
        $template .= "                                <th>" . ucfirst($pk_field) . "</th>\n";
        foreach ($editable as $field) {
            $template .= "                                <th>" . ucfirst($field) . "</th>\n";
        }
        $template .= "                                <th>Actions</th>\n";
        $template .= "                            </tr></thead>\n";
        $template .= "                            <tbody></tbody>\n";
        $template .= "                        </table>\n";
        $template .= "                    </div>\n";
        $template .= "                </div>\n";
        $template .= "            </div>\n";
        $template .= "        </div>\n";
        $template .= "    </div>\n";
        $template .= "</section>\n\n";

        $template .= "<div class=\"modal fade\" id=\"add" . ucfirst($table_name) . "Modal\">\n";
        $template .= "    <div class=\"modal-dialog\">\n";
        $template .= "        <div class=\"modal-content\">\n";
        $template .= "            <div class=\"modal-header\"><h4 class=\"modal-title\">Add " . ucfirst($table_name) . "</h4><button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span>&times;</span></button></div>\n";
        $template .= "            <div class=\"modal-body\">\n";
        $template .= "                <form id=\"add" . ucfirst($table_name) . "Form\">\n";
        foreach ($editable as $field) {
            $template .= "                    <div class=\"form-group\">\n";
            $template .= "                        <label for=\"add_" . $field . "\">" . ucfirst($field) . "</label>\n";
            $template .= "                        <input type=\"text\" class=\"form-control\" id=\"add_" . $field . "\" name=\"" . $field . "\" required>\n";
            $template .= "                    </div>\n";
        }
        $template .= "                    <button type=\"submit\" class=\"btn btn-primary\">Save</button>\n";
        $template .= "                </form>\n";
        $template .= "            </div>\n";
        $template .= "        </div>\n";
        $template .= "    </div>\n";
        $template .= "</div>\n\n";

        $template .= "<div class=\"modal fade\" id=\"edit" . ucfirst($table_name) . "Modal\">\n";
        $template .= "    <div class=\"modal-dialog\">\n";
        $template .= "        <div class=\"modal-content\">\n";
        $template .= "            <div class=\"modal-header\"><h4 class=\"modal-title\">Edit " . ucfirst($table_name) . "</h4><button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span>&times;</span></button></div>\n";
        $template .= "            <div class=\"modal-body\">\n";
        $template .= "                <form id=\"edit" . ucfirst($table_name) . "Form\">\n";
        $template .= "                    <input type=\"hidden\" name=\"" . $pk_field . "\">\n";
        foreach ($editable as $field) {
            $template .= "                    <div class=\"form-group\">\n";
            $template .= "                        <label for=\"edit_" . $field . "\">" . ucfirst($field) . "</label>\n";
            $template .= "                        <input type=\"text\" class=\"form-control\" id=\"edit_" . $field . "\" name=\"" . $field . "\" required>\n";
            $template .= "                    </div>\n";
        }
        $template .= "                    <button type=\"submit\" class=\"btn btn-primary\">Save Changes</button>\n";
        $template .= "                </form>\n";
        $template .= "            </div>\n";
        $template .= "        </div>\n";
        $template .= "    </div>\n";
        $template .= "</div>\n\n";

        $template .= "<div class=\"modal fade\" id=\"delete" . ucfirst($table_name) . "Modal\">\n";
        $template .= "    <div class=\"modal-dialog\">\n";
        $template .= "        <div class=\"modal-content\">\n";
        $template .= "            <div class=\"modal-header\"><h4 class=\"modal-title\">Delete " . ucfirst($table_name) . "</h4><button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span>&times;</span></button></div>\n";
        $template .= "            <div class=\"modal-body\">\n";
        $template .= "                <form id=\"delete" . ucfirst($table_name) . "Form\">\n";
        $template .= "                    <input type=\"hidden\" name=\"" . $pk_field . "\">\n";
        $template .= "                    <p>Are you sure you want to delete this " . $table_name . "?</p>\n";
        $template .= "                </form>\n";
        $template .= "            </div>\n";
        $template .= "            <div class=\"modal-footer\">\n";
        $template .= "                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Cancel</button>\n";
        $template .= "                <button type=\"button\" class=\"btn btn-danger\" id=\"confirmDelete" . ucfirst($table_name) . "\">Delete</button>\n";
        $template .= "            </div>\n";
        $template .= "        </div>\n";
        $template .= "    </div>\n";
        $template .= "</div>\n\n";

        $template .= "<script>\n";
        $template .= "\$(function() {\n";
        $template .= "    var table = \$('#" . $table_name . "-table').DataTable({\n";
        $template .= "        'ajax': {\n";
        $template .= "            'url': '<?php echo site_url('backend/" . $table_name . "/get_" . $table_name . "'); ?>',\n";
        $template .= "            'type': 'GET'\n";
        $template .= "        },\n";
        $template .= "        'columns': [\n";
        $template .= "            { 'data': '" . $pk_field . "' },\n";
        foreach ($editable as $field) {
            $template .= "            { 'data': '" . $field . "' },\n";
        }
        $template .= "            { 'data': 'actions', 'orderable': false }\n";
        $template .= "        ]\n";
        $template .= "    });\n\n";

        $template .= "    \$('#" . $table_name . "-table').on('click', '.edit-" . $table_name . "', function() {\n";
        $template .= "        var id = \$(this).data('id');\n";
        $template .= "        var row = table.rows().data().filter(function(r) { return String(r['" . $pk_field . "']) === String(id); })[0];\n";
        $template .= "        \$('#edit" . ucfirst($table_name) . "Form [name=\"" . $pk_field . "\"]').val(row['" . $pk_field . "']);\n";
        foreach ($editable as $field) {
            $template .= "        \$('#edit" . ucfirst($table_name) . "Form [name=\"" . $field . "\"]').val(row['" . $field . "']);\n";
        }
        $template .= "        \$('#edit" . ucfirst($table_name) . "Modal').modal('show');\n";
        $template .= "    });\n\n";

        $template .= "    \$('#" . $table_name . "-table').on('click', '.delete-" . $table_name . "', function() {\n";
        $template .= "        \$('#delete" . ucfirst($table_name) . "Form [name=\"" . $pk_field . "\"]').val(\$(this).data('id'));\n";
        $template .= "        \$('#delete" . ucfirst($table_name) . "Modal').modal('show');\n";
        $template .= "    });\n\n";

        $template .= "    \$('#add" . ucfirst($table_name) . "Form').on('submit', function(e) {\n";
        $template .= "        e.preventDefault();\n";
        $template .= "        \$.ajax({\n";
        $template .= "            type: 'POST',\n";
        $template .= "            url: '<?php echo site_url('backend/" . $table_name . "/add_" . $table_name . "'); ?>',\n";
        $template .= "            data: \$(this).serialize(),\n";
        $template .= "            dataType: 'json',\n";
        $template .= "            success: function(response) {\n";
        $template .= "                \$('#add" . ucfirst($table_name) . "Modal').modal('hide');\n";
        $template .= "                \$('#add" . ucfirst($table_name) . "Form')[0].reset();\n";
        $template .= "                table.ajax.reload();\n";
        $template .= "                if (response.status === 'success') { Swal.fire('Success', '" . ucfirst($table_name) . " saved.', 'success'); }\n";
        $template .= "                else { Swal.fire('Error', response.message || 'Failed to save.', 'error'); }\n";
        $template .= "            }\n";
        $template .= "        });\n";
        $template .= "    });\n\n";

        $template .= "    \$('#edit" . ucfirst($table_name) . "Form').on('submit', function(e) {\n";
        $template .= "        e.preventDefault();\n";
        $template .= "        \$.ajax({\n";
        $template .= "            type: 'POST',\n";
        $template .= "            url: '<?php echo site_url('backend/" . $table_name . "/edit_" . $table_name . "'); ?>',\n";
        $template .= "            data: \$(this).serialize(),\n";
        $template .= "            dataType: 'json',\n";
        $template .= "            success: function(response) {\n";
        $template .= "                \$('#edit" . ucfirst($table_name) . "Modal').modal('hide');\n";
        $template .= "                table.ajax.reload();\n";
        $template .= "                if (response.status === 'success') { Swal.fire('Success', '" . ucfirst($table_name) . " updated.', 'success'); }\n";
        $template .= "                else { Swal.fire('Error', response.message || 'Failed to update.', 'error'); }\n";
        $template .= "            }\n";
        $template .= "        });\n";
        $template .= "    });\n\n";

        $template .= "    \$('#confirmDelete" . ucfirst($table_name) . "').on('click', function() {\n";
        $template .= "        \$.ajax({\n";
        $template .= "            type: 'POST',\n";
        $template .= "            url: '<?php echo site_url('backend/" . $table_name . "/delete_" . $table_name . "'); ?>',\n";
        $template .= "            data: \$('#delete" . ucfirst($table_name) . "Form').serialize(),\n";
        $template .= "            dataType: 'json',\n";
        $template .= "            success: function(response) {\n";
        $template .= "                \$('#delete" . ucfirst($table_name) . "Modal').modal('hide');\n";
        $template .= "                table.ajax.reload();\n";
        $template .= "                if (response.status === 'success') { Swal.fire('Deleted!', '" . ucfirst($table_name) . " deleted.', 'success'); }\n";
        $template .= "                else { Swal.fire('Error', response.message || 'Failed to delete.', 'error'); }\n";
        $template .= "            }\n";
        $template .= "        });\n";
        $template .= "    });\n";
        $template .= "});\n";
        $template .= "</script>\n";

        return $template;
    }

    private function generate_controller($table_name, $fields, $model_name, $pk_field, $editable){
        $controller_template = "";

        $controller_template .= "<?php\n";
        $controller_template .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";
        $controller_template .= "class " . ucfirst($table_name) . " extends CI_Controller {\n\n";
        $controller_template .= "    public function __construct() {\n";
        $controller_template .= "        parent::__construct();\n";
        $controller_template .= "        \$this->load->model('" . $model_name . "');\n";
        $controller_template .= "    }\n\n";
        $controller_template .= "    public function index() {\n";
        $controller_template .= "        \$data['title'] = '" . ucfirst($table_name) . "';\n";
        $controller_template .= "        \$data['page_title'] = '" . ucfirst($table_name) . "';\n";
        $controller_template .= "        \$data['contents'] = \$this->load->view('backend/" . $table_name . "/index', '', TRUE);\n";
        $controller_template .= "        \$this->load->view('backend/layouts/main', \$data);\n";
        $controller_template .= "    }\n\n";
    
        $controller_template .= "    public function get_" . $table_name . "() {\n";
        $controller_template .= "        \$fetch_data = \$this->" . $model_name . "->make_datatables();\n";
        $controller_template .= "        \$data = array();\n";
        $controller_template .= "        foreach (\$fetch_data as \$row) {\n";
        $controller_template .= "            \$sub_array = array();\n";
        $controller_template .= "            foreach (['" . implode("', '", $fields) . "'] as \$field) {\n";
        $controller_template .= "                \$sub_array[\$field] = \$row->\$field;\n";
        $controller_template .= "            }\n";
        $controller_template .= "            \$sub_array['actions'] = '\n";
        $controller_template .= "                <button type=\"button\" class=\"btn btn-warning btn-sm edit-" . $table_name . "\" data-toggle=\"modal\" data-target=\"#edit" . ucfirst($table_name) . "Modal\" data-id=\"'.\$row->" . $pk_field . ".'\">Edit</button>\n";
        $controller_template .= "                <button type=\"button\" class=\"btn btn-danger btn-sm delete-" . $table_name . "\" data-toggle=\"modal\" data-target=\"#delete" . ucfirst($table_name) . "Modal\" data-id=\"'.\$row->" . $pk_field . ".'\">Delete</button>\n";
        $controller_template .= "            ';\n";
        $controller_template .= "            \$data[] = \$sub_array;\n";
        $controller_template .= "        }\n";
        $controller_template .= "        \$output = array(\n";
        $controller_template .= "            \"draw\" => intval(\$this->input->post(\"draw\")),\n";
        $controller_template .= "            \"recordsTotal\" => \$this->" . $model_name . "->get_all_data(),\n";
        $controller_template .= "            \"recordsFiltered\" => \$this->" . $model_name . "->get_filtered_data(),\n";
        $controller_template .= "            \"data\" => \$data\n";
        $controller_template .= "        );\n";
        $controller_template .= "        echo json_encode(\$output);\n";
        $controller_template .= "    }\n\n";
    
        $controller_template .= "    public function add_" . $table_name . "() {\n";
        $controller_template .= "        \$data = array();\n";
        foreach ($editable as $field) {
            $controller_template .= "        \$data['" . $field . "'] = \$this->input->post('" . $field . "');\n";
        }
        $controller_template .= "        \$data['created_at'] = date('Y-m-d H:i:s');\n";
        $controller_template .= "        \$data['created_by'] = \$this->session->userdata('user_id');\n\n";
        $controller_template .= "        \$this->" . $model_name . "->add_" . $table_name . "(" . $this->positionalArgs($fields) . ");\n";
        $controller_template .= "        echo json_encode(['status' => 'success']);\n";
        $controller_template .= "    }\n\n";
    
        $controller_template .= "    public function edit_" . $table_name . "() {\n";
        $controller_template .= "        \$data = array();\n";
        $controller_template .= "        \$data['" . $pk_field . "'] = \$this->input->post('" . $pk_field . "');\n";
        foreach ($editable as $field) {
            $controller_template .= "        \$data['" . $field . "'] = \$this->input->post('" . $field . "');\n";
        }
        $controller_template .= "        \$data['updated_at'] = date('Y-m-d H:i:s');\n";
        $controller_template .= "        \$data['updated_by'] = \$this->session->userdata('user_id');\n\n";
        $controller_template .= "        \$this->" . $model_name . "->edit_" . $table_name . "(" . $this->positionalArgs($fields) . ");\n";
        $controller_template .= "        echo json_encode(['status' => 'success']);\n";
        $controller_template .= "    }\n\n";
    
        $controller_template .= "    public function delete_" . $table_name . "() {\n";
        $controller_template .= "        \$id = \$this->input->post('" . $pk_field . "');\n";
        $controller_template .= "        \$deletedBy = \$this->session->userdata('user_id');\n\n";
        $controller_template .= "        \$this->" . $model_name . "->soft_delete_" . $table_name . "(\$id, \$deletedBy);\n";
        $controller_template .= "        echo json_encode(['status' => 'success']);\n";
        $controller_template .= "    }\n";
        $controller_template .= "}\n";
        return $controller_template;
    }

    private function positionalArgs($fields) {
        $args = array();
        foreach ($fields as $field) {
            $args[] = "\$data['" . $field . "'] ?? null";
        }
        return implode(', ', $args);
    }
}
