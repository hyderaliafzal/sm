<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/17/18
 * Time: 12:12 PM
 */

include 'Login.php';
class Page extends Login
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    function index() {
        $this->store();
                $pages = $this->db->get('page');
                $pages = $pages->result();
                $data['pages'] = $pages;
                $this->load->view('admin/pagelist.html', $data);
                $this->load->view('admin/footer.html');
    }
    public function edit($id) {
        $this->store();
        $form_data = $this->input->post();
        if (sizeof($form_data) > 0) {
            $result = $this->db->where_in('id', $id)->update('page', $form_data);
            $data['link'] = base_url().'index.php/admin/page';
            $this->load->view('admin/success.html', $data);
        } else {
            $page['page'] = $this->db->where_in('id', $id)->get('page')->row();
            $page['images'] = $this->db->get('images')->result();
            $page['base'] = base_url() . 'index.php/';
            $page['action'] = 'admin/page/edit/';
            $this->load->view('admin/pageForm.html', $page);
            $this->load->view('admin/footer.html');
        }
    }
}