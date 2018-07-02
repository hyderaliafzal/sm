<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/11/18
 * Time: 7:36 PM
 */

class Newsletter extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    function index() {
        $session_data = $this->session->userdata;
        // var_dump(sizeof($session_data));
        if (sizeof($session_data) > 1) {
            if($session_data['type'] === 'admin') {
                $newsletters = $this->db->get('newsletter');
                $newsletters = $newsletters->result();
                // var_dump($products->row());
                $data['data'] = $session_data;
                $data['newsletters'] = $newsletters;
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');
                $this->load->view('admin/newsletterlist.html');
                $this->load->view('admin/footer.html');
            } else {
                echo '401';
            }
        } else {
            redirect(base_url().'Text.php/admin/login/adminlogin');
        }
    }
}