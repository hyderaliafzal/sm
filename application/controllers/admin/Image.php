<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/30/18
 * Time: 8:32 AM
 */

class Image extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index () {
        $session_data = $this->session->userdata;
        // var_dump(sizeof($session_data));
        if (sizeof($session_data) > 1) {
            if($session_data['type'] === 'admin') {
                $data['data'] = $session_data;
                $images = $this->db->get('images');
                $data['images'] = $images->result();
                // var_dump($images->result());
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');
                $this->load->view('admin/imageForm.html');
                $this->load->view('admin/footer.html');
            } else {
                echo '401';
            }
        } else {
            redirect(base_url().'Text.php/admin/login/adminlogin');
        }
    }

    public function saveurl () {
        $data = $_REQUEST['data'];
        $result = $this->db->insert('images', $data);
        return $result;
    }

}