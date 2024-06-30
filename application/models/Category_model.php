<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    var $table = "categories";
    var $select_column = array("id", "name");
    var $order_column = array(null, "name", null);
    
    public function __construct() {
        parent::__construct();
    }

    public function get_categories() {
        // Retrieve categories that are not soft-deleted
        return $this->db->where('deleted_at', NULL)->get('categories')->result();
    }

    public function add_category($name) {
        $data = array(
            'name' => $name
        );
        $this->db->insert('categories', $data);
    }

    public function edit_category($id, $name) {
        $data = array(
            'name' => $name
        );
        $this->db->where('id', $id)->update('categories', $data);
    }

    public function soft_delete_category($id) {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id)->update('categories', $data);
    }

    public function delete_category($id) {
        $this->db->where('id', $id)->delete('categories');
    }

    public function make_query() {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST["search"]["value"])) {
            $this->db->like("name", $_POST["search"]["value"]);
            // $this->db->or_like("description", $_POST["search"]["value"]);
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'DESC');
        }
    }

    public function make_datatables() {
        $this->make_query();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_all_data() {
        $this->db->select("*");
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_select2($searchTerm = "") {
        $this->db->select('id, name as text');
        $this->db->from('categories');
        if ($searchTerm != "") {
            $this->db->like('name', $searchTerm);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
}
