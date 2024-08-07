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
        $this->form_validation->set_rules('model_path', 'Model Path', 'required');
        $this->form_validation->set_rules('controller_path', 'Controller Path', 'required');
        $this->form_validation->set_rules('view_path', 'View Path', 'required');

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
            preg_match('/var \$table = "(.*?)";/', $model_content, $matches);
            $table_name = $matches[1];

            // get list fields from model file
            preg_match('/var \$select_column = array\((.*?)\);/s', $model_content, $matches);
            $fields = explode(",", str_replace(['"', ' '], '', $matches[1]));

            // Write the controller file
            $controllerFilePath = rtrim(APPPATH . $controller_path, '/') . '/';
            if (!is_dir($controllerFilePath)) {
                // Create the directory with 0755 permissions
                mkdir($controllerFilePath, 0755, true);
            }
            write_file($controllerFilePath . ucfirst($table_name) . '.php', $this->generate_controller($table_name, $fields));

            // generate view file
            $views = ['index'];
            foreach ($views as $view) {
                $viewFilePath = rtrim(APPPATH . $view_path, '/') . '/' . $table_name . '/';
                if (!is_dir($viewFilePath)) {
                    // Create the directory with 0755 permissions
                    mkdir($viewFilePath, 0755, true);
                }
                write_file($viewFilePath . $view . '.php',  $this->generate_index($table_name, $fields));
            }
            $this->session->set_flashdata('success', "Crud generated successfully.");
            return redirect('backend/generator-crud');
        }

        $data['title'] = 'Crud Generator';
        $data['page_title'] = 'Crud Generator';
        $data['contents'] = $this->load->view('backend/generator/generate_crud', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
        return $this;
    }

    function generate_index($table_name, $fields) {
        $template = "";
        
        $template .= "<!-- Main content -->\n";
        $template .= "<section class=\"content\">\n";
        $template .= "    <div class=\"container-fluid\">\n";
        $template .= "        <div class=\"row\">\n";
        $template .= "            <div class=\"col-md-12\">\n";
        $template .= "                <button type=\"button\" class=\"btn btn-primary mb-2\" data-toggle=\"modal\" data-target=\"#add" . ucfirst($table_name) . "Modal\">\n";
        $template .= "                    Create " . ucfirst($table_name) . "\n";
        $template .= "                </button>\n";
        $template .= "                <div class=\"card\">\n";
        $template .= "                    <div class=\"card-body\">\n";
        $template .= "                        <table id=\"" . $table_name . "-table\" class=\"table table-bordered table-hover\">\n";
        $template .= "                        <thead>\n";
        $template .= "                            <tr>\n";
        $template .= "                                <th>ID</th>\n";
        $template .= "                                <th>" . ucfirst($table_name) . "</th>\n";
        $template .= "                                <th>Actions</th>\n";
        $template .= "                            </tr>\n";
        $template .= "                        </thead>\n";
        $template .= "                        <tbody>\n";
        $template .= "                            <!-- Populate table rows dynamically using AJAX -->\n";
        $template .= "                        </tbody>\n";
        $template .= "                        </table>\n";
        $template .= "                    </div>\n";
        $template .= "                </div>\n";
        $template .= "            </div>\n";
        $template .= "        </div>\n";
        $template .= "    </div>\n";
        $template .= "</section>\n\n";
    
        $template .= "<!-- Add " . ucfirst($table_name) . " Modal -->\n";
        $template .= "<div class=\"modal fade\" id=\"add" . ucfirst($table_name) . "Modal\">\n";
        $template .= "    <div class=\"modal-dialog\">\n";
        $template .= "        <div class=\"modal-content\">\n";
        $template .= "            <div class=\"modal-header\">\n";
        $template .= "                <h4 class=\"modal-title\">Add " . ucfirst($table_name) . "</h4>\n";
        $template .= "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n";
        $template .= "                    <span aria-hidden=\"true\">&times;</span>\n";
        $template .= "                </button>\n";
        $template .= "            </div>\n";
        $template .= "            <div class=\"modal-body\">\n";
        $template .= "                <form id=\"add" . ucfirst($table_name) . "Form\">\n";
        
        foreach ($fields as $field) {
            $template .= "                    <div class=\"form-group\">\n";
            $template .= "                        <label for=\"" . $field . "\">" . ucfirst($field) . "</label>\n";
            $template .= "                        <input type=\"text\" class=\"form-control\" id=\"" . $field . "\" name=\"" . $field . "\" required>\n";
            $template .= "                    </div>\n";
        }
    
        $template .= "                    <button type=\"submit\" class=\"btn btn-primary\">Add " . ucfirst($table_name) . "</button>\n";
        $template .= "                </form>\n";
        $template .= "            </div>\n";
        $template .= "        </div>\n";
        $template .= "    </div>\n";
        $template .= "</div>\n\n";
    
        $template .= "<!-- Edit " . ucfirst($table_name) . " Modal -->\n";
        $template .= "<div class=\"modal fade\" id=\"edit" . ucfirst($table_name) . "Modal\">\n";
        $template .= "    <div class=\"modal-dialog\">\n";
        $template .= "        <div class=\"modal-content\">\n";
        $template .= "            <div class=\"modal-header\">\n";
        $template .= "                <h4 class=\"modal-title\">Edit " . ucfirst($table_name) . "</h4>\n";
        $template .= "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n";
        $template .= "                    <span aria-hidden=\"true\">&times;</span>\n";
        $template .= "                </button>\n";
        $template .= "            </div>\n";
        $template .= "            <div class=\"modal-body\">\n";
        $template .= "                <form id=\"edit" . ucfirst($table_name) . "Form\">\n";
        $template .= "                    <input type=\"hidden\" id=\"edit" . ucfirst($table_name) . "Id\" name=\"edit" . ucfirst($table_name) . "Id\">\n";
        
        foreach ($fields as $field) {
            $template .= "                    <div class=\"form-group\">\n";
            $template .= "                        <label for=\"edit" . $field . "\">" . ucfirst($field) . "</label>\n";
            $template .= "                        <input type=\"text\" class=\"form-control\" id=\"edit" . $field . "\" name=\"" . $field . "\" required>\n";
            $template .= "                    </div>\n";
        }
    
        $template .= "                    <button type=\"submit\" class=\"btn btn-primary\">Update " . ucfirst($table_name) . "</button>\n";
        $template .= "                </form>\n";
        $template .= "            </div>\n";
        $template .= "        </div>\n";
        $template .= "    </div>\n";
        $template .= "</div>\n\n";
    
        $template .= "<!-- Delete " . ucfirst($table_name) . " Modal -->\n";
        $template .= "<div class=\"modal fade\" id=\"delete" . ucfirst($table_name) . "Modal\">\n";
        $template .= "    <div class=\"modal-dialog\">\n";
        $template .= "        <div class=\"modal-content\">\n";
        $template .= "            <div class=\"modal-header\">\n";
        $template .= "                <h4 class=\"modal-title\">Delete " . ucfirst($table_name) . "</h4>\n";
        $template .= "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n";
        $template .= "                    <span aria-hidden=\"true\">&times;</span>\n";
        $template .= "                </button>\n";
        $template .= "            </div>\n";
        $template .= "            <div class=\"modal-body\">\n";
        $template .= "                <form id=\"delete" . ucfirst($table_name) . "Form\">\n";
        $template .= "                    <input type=\"hidden\" id=\"delete" . ucfirst($table_name) . "Id\" name=\"delete" . ucfirst($table_name) . "Id\">\n";
        $template .= "                    <p>Are you sure you want to delete this " . ucfirst($table_name) . "?</p>\n";
        $template .= "                    <button type=\"submit\" class=\"btn btn-danger\">Delete</button>\n";
        $template .= "                </form>\n";
        $template .= "            </div>\n";
        $template .= "        </div>\n";
        $template .= "    </div>\n";
        $template .= "</div>\n\n";
        
        $template .= "<!-- AJAX and DataTable initialization -->\n";
        $template .= "<script>\n";
        $template .= "$(document).ready(function() {\n";
        $template .= "    // DataTable initialization\n";
        $template .= "    $('#" . $table_name . "-table').DataTable({\n";
        $template .= "        \"processing\": true,\n";
        $template .= "        \"serverSide\": true,\n";
        $template .= "        \"ajax\": {\n";
        $template .= "            url: \"" . "<?php echo base_url('backend/" . $table_name . "/get_" . $table_name . "'); ?>\",\n";
        $template .= "            type: \"POST\"\n";
        $template .= "        },\n";
        $template .= "        \"columns\": [\n";
        $template .= "            { \"data\": \"id\" },\n";
        $template .= "            { \"data\": \"" . $table_name . "\" },\n";
        $template .= "            { \"data\": \"actions\" }\n";
        $template .= "        ]\n";
        $template .= "    });\n\n";
        $template .= "    // Delete " . $table_name . " via AJAX\n";
        $template .= "    // $('body').on('click', '.delete-" . $table_name . "', function(e) {\n";
        $template .= "    //     e.preventDefault();\n";
        $template .= "    //     var " . $table_name . "Id = $(this).data('id');\n\n";
        $template .= "    //     Swal.fire({\n";
        $template .= "    //         title: 'Are you sure?',\n";
        $template .= "    //         text: \"You won't be able to revert this!\",\n";
        $template .= "    //         icon: 'warning',\n";
        $template .= "    //         showCancelButton: true,\n";
        $template .= "    //         confirmButtonColor: '#3085d6',\n";
        $template .= "    //         cancelButtonColor: '#d33',\n";
        $template .= "    //         confirmButtonText: 'Yes, delete it!'\n";
        $template .= "    //     }).then((result) => {\n";
        $template .= "    //         if (result.isConfirmed) {\n";
        $template .= "    //             $.ajax({\n";
        $template .= "    //                 type: 'POST',\n";
        $template .= "    //                 url: '<?php echo base_url(\"backend/" . $table_name . "/delete_" . $table_name . "\"); ?>',\n";
        $template .= "    //                 data: { id: " . $table_name . "Id },\n";
        $template .= "    //                 success: function(response) {\n";
        $template .= "    //                     // Reload DataTable after deletion\n";
        $template .= "    //                     $('#" . $table_name . "-table').DataTable().ajax.reload();\n";
        $template .= "    //                     Swal.fire(\n";
        $template .= "    //                         'Deleted!',\n";
        $template .= "    //                         'The " . $table_name . " has been deleted.',\n";
        $template .= "    //                         'success'\n";
        $template .= "    //                     );\n";
        $template .= "    //                 }\n";
        $template .= "    //             });\n";
        $template .= "    //         }\n";
        $template .= "    //     });\n";
        $template .= "    // });\n\n";
        $template .= "    // Add " . ucfirst($table_name) . " Modal\n";
        $template .= "    $('#add" . ucfirst($table_name) . "Modal').on('shown.bs.modal', function () {\n";
        $template .= "        //$('#" . $table_name . "Name').focus();\n";
        $template .= "    });\n\n";
        $template .= "    // Edit " . ucfirst($table_name) . " Modal\n";
        $template .= "    $('#edit" . ucfirst($table_name) . "Modal').on('show.bs.modal', function (event) {\n";
        $template .= "        var button = $(event.relatedTarget); // Button that triggered the modal\n";
        $template .= "        var " . $table_name . "Id = button.data('id'); // Extract " . $table_name . " ID from data-id attribute\n";
        $template .= "        var " . $table_name . "Name = button.data('name'); // Extract " . $table_name . " name from data-name attribute\n";
        $template .= "        $('#edit" . ucfirst($table_name) . "Id').val(" . $table_name . "Id);\n";
        $template .= "        $('#edit" . ucfirst($table_name) . "Name').val(" . $table_name . "Name);\n";
        $template .= "    });\n\n";
        $template .= "    // Delete " . ucfirst($table_name) . " Modal\n";
        $template .= "    $('#delete" . ucfirst($table_name) . "Modal').on('show.bs.modal', function (event) {\n";
        $template .= "        var button = $(event.relatedTarget); // Button that triggered the modal\n";
        $template .= "        var " . $table_name . "Id = button.data('id'); // Extract " . $table_name . " ID from data-id attribute\n";
        $template .= "        $('#delete" . ucfirst($table_name) . "Id').val(" . $table_name . "Id);\n";
        $template .= "    });\n\n";
        $template .= "    // Add " . ucfirst($table_name) . " Form Submission via AJAX\n";
        $template .= "    $('#add" . ucfirst($table_name) . "Form').submit(function(e) {\n";
        $template .= "        e.preventDefault();\n";
        $template .= "        var " . $table_name . "Name = $('#" . $table_name . "Name').val();\n\n";
        $template .= "        $.ajax({\n";
        $template .= "            type: 'POST',\n";
        $template .= "            url: '<?php echo base_url(\"backend/" . $table_name . "/add_" . $table_name . "\"); ?>',\n";
        $template .= "            data: { " . $table_name . "Name: " . $table_name . "Name },\n";
        $template .= "            success: function(response) {\n";
        $template .= "                $('#add" . ucfirst($table_name) . "Modal').modal('hide');\n";
        $template .= "                $('#" . $table_name . "-table').DataTable().ajax.reload();\n";
        $template .= "                Swal.fire(\n";
        $template .= "                    'Added!',\n";
        $template .= "                    'The " . $table_name . " has been added.',\n";
        $template .= "                    'success'\n";
        $template .= "                );\n";
        $template .= "            }\n";
        $template .= "        });\n";
        $template .= "    });\n\n";
        $template .= "    // Edit " . ucfirst($table_name) . " Form Submission via AJAX\n";
        $template .= "    $('#edit" . ucfirst($table_name) . "Form').submit(function(e) {\n";
        $template .= "        e.preventDefault();\n";
        $template .= "        var " . $table_name . "Id = $('#edit" . ucfirst($table_name) . "Id').val();\n";
        $template .= "        var " . $table_name . "Name = $('#edit" . $table_name . "Name').val();\n\n";
        $template .= "        $.ajax({\n";
        $template .= "            type: 'POST',\n";
        $template .= "            url: '<?php echo base_url(\"backend/" . $table_name . "/edit_" . $table_name . "\"); ?>',\n";
        $template .= "            data: { id: " . $table_name . "Id, " . $table_name . "Name: " . $table_name . "Name },\n";
        $template .= "            success: function(response) {\n";
        $template .= "                $('#edit" . ucfirst($table_name) . "Modal').modal('hide');\n";
        $template .= "                $('#" . $table_name . "-table').DataTable().ajax.reload();\n";
        $template .= "                Swal.fire(\n";
        $template .= "                    'Updated!',\n";
        $template .= "                    'The " . $table_name . " has been updated.',\n";
        $template .= "                    'success'\n";
        $template .= "                );\n";
        $template .= "            }\n";
        $template .= "        });\n";
        $template .= "    });\n\n";
        $template .= "    // Delete " . ucfirst($table_name) . " via AJAX\n";
        $template .= "    $('#confirmDelete" . ucfirst($table_name) . "').click(function() {\n";
        $template .= "        var " . $table_name . "Id = $('#delete" . ucfirst($table_name) . "Id').val();\n\n";
        $template .= "        $.ajax({\n";
        $template .= "            type: 'POST',\n";
        $template .= "            url: '<?php echo base_url(\"backend/" . $table_name . "/delete_" . $table_name . "\"); ?>',\n";
        $template .= "            data: { id: " . $table_name . "Id },\n";
        $template .= "            success: function(response) {\n";
        $template .= "                $('#delete" . ucfirst($table_name) . "Modal').modal('hide');\n";
        $template .= "                $('#" . $table_name . "-table').DataTable().ajax.reload();\n";
        $template .= "                Swal.fire(\n";
        $template .= "                    'Deleted!',\n";
        $template .= "                    'The " . $table_name . " has been deleted.',\n";
        $template .= "                    'success'\n";
        $template .= "                );\n";
        $template .= "            }\n";
        $template .= "        });\n";
        $template .= "    });\n";
        $template .= "});\n";
        $template .= "</script>\n";
    
        return $template;
    }

    private function generate_controller($table_name, $fields){
        $controller_template = "";

        $controller_template .= "<?php\n";
        $controller_template .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";
        $controller_template .= "class " . ucfirst($table_name) . " extends CI_Controller {\n\n";
        $controller_template .= "    public function __construct() {\n";
        $controller_template .= "        parent::__construct();\n";
        $controller_template .= "        \$this->load->model('" . ucfirst($table_name) . "_model');\n";
        $controller_template .= "    }\n\n";
        $controller_template .= "    public function index() {\n";
        $controller_template .= "        \$data['title'] = '" . ucfirst($table_name) . "';\n";
        $controller_template .= "        \$data['page_title'] = '" . ucfirst($table_name) . "';\n";
        $controller_template .= "        \$data['contents'] = \$this->load->view('backend/" . $table_name . "/index', '', TRUE);\n";
        $controller_template .= "        \$this->load->view('backend/layouts/main', \$data);\n";
        $controller_template .= "    }\n\n";
    
        $controller_template .= "    public function get_" . $table_name . "() {\n";
        $controller_template .= "        \$fetch_data = \$this->" . ucfirst($table_name) . "_model->make_datatables();\n";
        $controller_template .= "        \$data = array();\n";
        $controller_template .= "        foreach (\$fetch_data as \$row) {\n";
        $controller_template .= "            \$sub_array = array();\n";
        $controller_template .= "            foreach (['" . implode("', '", $fields) . "'] as \$field) {\n";
        $controller_template .= "                \$sub_array[\$field] = \$row->\$field;\n";
        $controller_template .= "            }\n";
        $controller_template .= "            \$sub_array['actions'] = '\n";
        $controller_template .= "                <button type=\"button\" class=\"btn btn-warning btn-sm edit-" . $table_name . "\" data-toggle=\"modal\" data-target=\"#edit" . ucfirst($table_name) . "Modal\" data-id=\"'.\$row->id.'\">Edit</button>\n";
        $controller_template .= "                <button type=\"button\" class=\"btn btn-danger btn-sm delete-" . $table_name . "\" data-toggle=\"modal\" data-target=\"#delete" . ucfirst($table_name) . "Modal\" data-id=\"'.\$row->id.'\">Delete</button>\n";
        $controller_template .= "            ';\n";
        $controller_template .= "            \$data[] = \$sub_array;\n";
        $controller_template .= "        }\n";
        $controller_template .= "        \$output = array(\n";
        $controller_template .= "            \"draw\" => intval(\$this->input->post(\"draw\")),\n";
        $controller_template .= "            \"recordsTotal\" => \$this->" . ucfirst($table_name) . "_model->get_all_data(),\n";
        $controller_template .= "            \"recordsFiltered\" => \$this->" . ucfirst($table_name) . "_model->get_filtered_data(),\n";
        $controller_template .= "            \"data\" => \$data\n";
        $controller_template .= "        );\n";
        $controller_template .= "        echo json_encode(\$output);\n";
        $controller_template .= "    }\n\n";
    
        $controller_template .= "    public function add_" . $table_name . "() {\n";
        $controller_template .= "        \$data = array();\n";
        $controller_template .= "        foreach (['" . implode("', '", $fields) . "'] as \$field) {\n";
        $controller_template .= "            \$data[\$field] = \$this->input->post(\$field);\n";
        $controller_template .= "        }\n";
        $controller_template .= "        \$createdBy = \$this->session->userdata('user_id');\n";
        $controller_template .= "        \$data['created_by'] = \$createdBy;\n\n";
        $controller_template .= "        \$this->" . ucfirst($table_name) . "_model->add_" . $table_name . "(\$data);\n";
        $controller_template .= "        echo json_encode(['status' => 'success']);\n";
        $controller_template .= "    }\n\n";
    
        $controller_template .= "    public function edit_" . $table_name . "() {\n";
        $controller_template .= "        \$id = \$this->input->post('id');\n";
        $controller_template .= "        \$data = array();\n";
        $controller_template .= "        foreach (['" . implode("', '", $fields) . "'] as \$field) {\n";
        $controller_template .= "            \$data[\$field] = \$this->input->post(\$field);\n";
        $controller_template .= "        }\n";
        $controller_template .= "        \$updatedBy = \$this->session->userdata('user_id');\n";
        $controller_template .= "        \$data['updated_by'] = \$updatedBy;\n\n";
        $controller_template .= "        \$this->" . ucfirst($table_name) . "_model->edit_" . $table_name . "(\$id, \$data);\n";
        $controller_template .= "        echo json_encode(['status' => 'success']);\n";
        $controller_template .= "    }\n\n";
    
        $controller_template .= "    public function delete_" . $table_name . "() {\n";
        $controller_template .= "        \$id = \$this->input->post('id');\n";
        $controller_template .= "        \$deletedBy = \$this->session->userdata('user_id');\n\n";
        $controller_template .= "        \$this->" . ucfirst($table_name) . "_model->soft_delete_" . $table_name . "(\$id, \$deletedBy);\n";
        $controller_template .= "        echo json_encode(['status' => 'success']);\n";
        $controller_template .= "    }\n";
        $controller_template .= "}\n";
        return $controller_template;
    }
}
