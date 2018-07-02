<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 6/23/18
 * Time: 8:16 AM
 */
include "Login.php";
class Store extends Login
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index () {
        $this->store();
    }
    public function setting () {
        $this->store();
        $form = $this->input->post();
        if(sizeof($form) > 0) {
            $this->db->where('id',1)->update('store', $form);
            $data['link'] = base_url().'index.php/admin/store/setting';
            $this->load->view('admin/success.html', $data);
        } else {
            $data['store'] = $this->db->get('store')->row();
            $this->load->view('admin/store.html', $data);
        }
    }

}