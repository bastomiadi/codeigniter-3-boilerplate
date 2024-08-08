<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing_model extends CI_Model {

    var $table = "categories";
    var $select_column = array("id", "name", "description", "created_at", "updated_at", "deleted_at", "created_by", "updated_by", "deleted_by", "restored_by");
    var $order_column = array("id", "name", "description", "created_at", "updated_at", "deleted_at", "created_by", "updated_by", "deleted_by", "restored_by", null);

    public function __construct() {
        parent::__construct();
        // Load database
        $this->load->database();
    }

    public function add_categories($id, $name, $description, $created_at, $updated_at, $deleted_at, $created_by, $updated_by, $deleted_by, $restored_by) {
        $data = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'deleted_at' => $deleted_at,
            'created_by' => $created_by,
            'updated_by' => $updated_by,
            'deleted_by' => $deleted_by,
            'restored_by' => $restored_by,
        );
        $this->db->insert($this->table, $data);
    }

    public function edit_categories($id, $name, $description, $created_at, $updated_at, $deleted_at, $created_by, $updated_by, $deleted_by, $restored_by) {
        $data = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'deleted_at' => $deleted_at,
            'created_by' => $created_by,
            'updated_by' => $updated_by,
            'deleted_by' => $deleted_by,
            'restored_by' => $restored_by,
        );
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function soft_delete_categories($id, $deleted_by) {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deleted_by
        );
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_categories($id) {
        $this->db->where('id', $id)->delete($this->table);
    }

    public function make_query() {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        if (isset($_POST["search"]["value"])) {
            $this->db->like("name", $_POST["search"]["value"]);
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
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        return $this->db->count_all_results();
    }

    public function get_select2($searchTerm = "") {
        $this->db->select('id, name as text');
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        if ($searchTerm != "") {
            $this->db->like('name', $searchTerm);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

}
