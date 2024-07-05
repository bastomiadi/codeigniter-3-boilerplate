<?php
class User_model extends CI_Model {

    var $table = "users";
    var $select_column = array("id", "username", "email");
    var $order_column = array("id", "username", null);

    public function __construct() {
        parent::__construct();
    }

    // public function register($data) {
    //     return $this->db->insert('users', $data);
    // }

    // public function login($userusername, $password) {
    //     $this->db->where('userusername', $userusername);
    //     $this->db->where('password', md5($password));
    //     $query = $this->db->get('users');
    //     return $query->row();
    // }

    public function add_user($username, $created_by) {
        $data = array(
            'username' => $username,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert($this->table, $data);
    }
    
    public function edit_user($id, $username, $updated_by) {
        $data = array(
            'username' => $username,
            'updated_by' => $updated_by,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id)->update($this->table, $data);
    }
    
    public function soft_delete_user($id, $deleted_by) {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deleted_by
        );
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_user($id) {
        $this->db->where('id', $id)->delete($this->table);
    }

    public function make_query() {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        if (isset($_POST["search"]["value"])) {
            $this->db->like("username", $_POST["search"]["value"]);
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
        $this->db->select('id, username as text');
        $this->db->from($this->table);
        $this->db->where('deleted_at', NULL); // Exclude soft deleted records
        if ($searchTerm != "") {
            $this->db->like('username', $searchTerm);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
}
