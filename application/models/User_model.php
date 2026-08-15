<?php
class User_model extends CI_Model {

    var $table = "users";
    var $select_column = array("id", "username", "email");
    var $order_column = array("id", "username", null);

    public function __construct() {
        parent::__construct();
    }

    public function add_user($username, $email, $created_by) {
        $data = array(
            'username' => $username,
            'email' => $email,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert($this->table, $data);
    }
    
    public function edit_user($id, $username, $email, $updated_by) {
        $data = array(
            'username' => $username,
            'email' => $email,
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

    public function get_user($user_id) {
        return $this->db->get_where('users', array('id' => $user_id))->row();
    }

    public function change_password($user_id, $current_password, $new_password) {
        $user = $this->db->get_where('users', array('id' => $user_id))->row();
        if ( ! $user || ! password_verify($current_password, $user->password)) {
            return 'wrong_password';
        }

        $this->db->where('id', $user_id)->update('users', array(
            'password' => password_hash($new_password, PASSWORD_DEFAULT)
        ));
        return 'ok';
    }
}
