<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class Product_model extends CI_Model {

//     public function __construct() {
//         parent::__construct();
//     }

//     public function get_products() {
//         // Retrieve products that are not soft-deleted
//         return $this->db->where('deleted_at', NULL)->get('products')->result();
//     }

//     public function add_product($name, $price, $category_id) {
//         $data = array(
//             'name' => $name,
//             'price' => $price,
//             'category_id' => $category_id
//         );
//         $this->db->insert('products', $data);
//     }

//     public function edit_product($id, $name, $price, $category_id) {
//         $data = array(
//             'name' => $name,
//             'price' => $price,
//             'category_id' => $category_id
//         );
//         $this->db->where('id', $id)->update('products', $data);
//     }

//     public function soft_delete_product($id) {
//         $data = array(
//             'deleted_at' => date('Y-m-d H:i:s')
//         );
//         $this->db->where('id', $id)->update('products', $data);
//     }

//     public function delete_product($id) {
//         $this->db->where('id', $id)->delete('products');
//     }
// }

class Product_model extends CI_Model {

    public function get_all() {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_id($id) {
        $this->db->from('products');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insert($data) {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    public function update($where, $data) {
        $this->db->update('products', $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {
        $this->db->where('id', $id);
        $this->db->delete('products');
    }
}
