<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LayoutHook {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function set_layout() {
        $data = array(
            'title' => 'Your Default Title',
            // You can set other default data here
        );

        // Load your main layout view
        $this->CI->load->view('backend/layouts/main', $data);
    }
}
