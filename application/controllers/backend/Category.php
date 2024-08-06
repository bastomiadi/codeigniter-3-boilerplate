<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//define('BASEPATH', __DIR__ . '/path/to/codeigniter/system/');
//require_once BASEPATH . 'core/CodeIgniter.php';

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->CI =& get_instance();
        $this->load->model('Category_model');
        $this->load->library('Auth_middleware');
        $this->check_permission();
    }

    public function check_permission(){
        
    }

    public function test() {
        $route = $this->CI->uri->uri_string();
        echo "<pre>";
        print_r($route); // Print unique routes
        echo "</pre>";
        die;
        // // Get the current URL path
        // $currentUrlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // // Optionally trim any leading or trailing slashes
        // $currentUrlPath = trim($currentUrlPath, '/');
        // // Split the path into segments
        // $pathSegments = explode('/', $currentUrlPath);
        // // Adjust the index based on your requirements
        // if (isset($pathSegments[1]) && isset($pathSegments[2])) {
        //     $desiredSegment = $pathSegments[1] . '/' . $pathSegments[2];
        // }
        // // Output the desired segment
        // echo $desiredSegment;
        // //die;

        // // Path to the directory containing PHP files
        // $directory = APPPATH . 'config/routes/';

        // // Use glob to find all PHP files in the directory
        // $files = glob($directory . '*.php');

        // // Function to extract routes from a file
        // function extract_routes($contents) {
        //     $routes = array();
        //     // Remove comments
        //     $contents = preg_replace('/\/\/.*$/m', '', $contents);
        //     // Match routes definitions
        //     preg_match_all('/\$route\[\s*[\'"]([^\'"]+)[\'"]\s*]\s*=\s*[\'"]([^\'"]+)[\'"]/', $contents, $matches, PREG_SET_ORDER);
        //     foreach ($matches as $match) {
        //         $routes[] = $match[2]; // Add only the route definition (the right-hand side of the assignment)
        //     }
        //     return $routes;
        // }

        // // Store all routes
        // $allRoutes = array();
        // // Process each file
        // foreach ($files as $file) {
        //     // Read the file content
        //     $contents = file_get_contents($file);
        //     // Extract routes
        //     $routes = extract_routes($contents);
        //     // Merge routes into the main list
        //     $allRoutes = array_merge($allRoutes, $routes);
        // }

        // // Output all routes
        // echo "<pre>";
        // print_r(array_unique($allRoutes)); // Print unique routes
        // echo "</pre>";
        // die;
    }

    public function index() {
        $this->auth_middleware->check_permission('menu_category');

        //$this->test();

        $data['title'] = 'Category';
        $data['page_title'] = 'Category';
        $data['contents'] = $this->load->view('backend/category/index', '', TRUE);
        $this->load->view('backend/layouts/main', $data);
    }

    public function get_categories() {
        $fetch_data = $this->Category_model->make_datatables();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array['id'] = $row->id;
            $sub_array['name'] = $row->name;
            $sub_array['description'] = $row->description;
            $sub_array['actions'] = '
                <button type="button" class="btn btn-warning btn-sm edit-category" data-toggle="modal" data-target="#editCategoryModal" data-id="'.$row->id.'" data-name="'.$row->name.'" data-description="'.$row->description.'">Edit</button>
                <button type="button" class="btn btn-danger btn-sm delete-category" data-toggle="modal" data-target="#deleteCategoryModal" data-id="'.$row->id.'">Delete</button>
            ';
            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => $this->Category_model->get_all_data(),
            "recordsFiltered" => $this->Category_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function add_category() {
        $categoryName = $this->input->post('categoryName');
        $categoryDescription = $this->input->post('categoryDescription');
        $createdBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Add category logic
        $this->Category_model->add_category($categoryName, $categoryDescription, $createdBy);
        echo json_encode(['status' => 'success']);
    }

    public function edit_category() {
        $categoryId = $this->input->post('id');
        $categoryName = $this->input->post('categoryName');
        $categoryDescription = $this->input->post('categoryDescription');
        $updatedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Edit category logic
        $this->Category_model->edit_category($categoryId, $categoryName, $categoryDescription, $updatedBy);
        echo json_encode(['status' => 'success']);
    }

    public function delete_category() {
        $categoryId = $this->input->post('id');
        $deletedBy = $this->session->userdata('user_id'); // Assuming you store user_id in session

        // Soft delete category logic
        $this->Category_model->soft_delete_category($categoryId, $deletedBy);
        echo json_encode(['status' => 'success']);
    }

    // dropdown get category for select2
    public function select2() {
        $searchTerm = $this->input->get('q');
        $categories = $this->Category_model->get_select2($searchTerm);
        echo json_encode($categories);
    }
}
