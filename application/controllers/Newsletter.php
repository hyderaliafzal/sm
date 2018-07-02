<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 6/12/18
 * Time: 2:34 PM
 */

class Newsletter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }
    public function subscribe () {
        $email = $this->input->post();
        $find = $this->db->where_in('email', $email['email'])
            ->get('newsletter_subscription')->row();
        if (sizeof($find) > 0) {
              echo('Already Subscribed');
        } else if ($find === null) {
            if($this->session->userdata()){
                $email['account_id'] = $this->session->userdata('account_id');
            }
            $insert = $this->db->insert('newsletter_subscription', $email);
             echo('Newsletter subscribed.');
        }
    }

}