<?php
class Role_model extends CI_Model {

    var $table = "roles";
    var $select_column = array("role_id", "role_name", "description");
    var $order_column = array("role_id", "role_name", null);
    
    public function __construct() {
        parent::__construct();
    }

    public function add_role($role_name, $description, $created_by) {
        $data = array(
            'role_name' => $role_name,
            'description' => $description,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert($this->table, $data);
    }
    
    public function edit_role($role_id, $role_name, $description, $updated_by) {
        $data = array(
            'role_name' => $role_name,
            'description' => $description,
            'updated_by' => $updated_by,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('role_id', $role_id)->update($this->table, $data);
    }
    
    public function soft_delete_role($role_id, $deleted_by) {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deleted_by
        );
        $this->db->where('role_id', $role_id)->update($this->table, $data);
    }

    public function delete_role($role_id) {
        $this->db->where('role_id', $role_id)->delete($this->table);
    }

    public function make_query() {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        if (isset($_POST["search"]["value"])) {
            $this->db->like("role_name", $_POST["search"]["value"]);
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('role_id', 'DESC');
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
        $this->db->select('role_id, role_name as text');
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        if ($searchTerm != "") {
            $this->db->like('role_name', $searchTerm);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_roles() {
        return $this->db->get('roles')->result();
    }
}
