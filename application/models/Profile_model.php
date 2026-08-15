<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_profile($user_id) {
        return $this->db->get_where('profile', array('user_id' => $user_id))->row();
    }

    public function update_profile($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('profile', $data);
    }

    public function create_profile($data) {
        return $this->db->insert('profile', $data);
    }

    public function save_profile($user_id, $data) {
        if ($this->db->get_where('profile', array('user_id' => $user_id))->num_rows() > 0) {
            $this->db->where('user_id', $user_id);
            return $this->db->update('profile', $data);
        }

        $data['user_id'] = $user_id;
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('profile', $data);
    }
}
