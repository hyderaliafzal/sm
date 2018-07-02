<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 6/19/18
 * Time: 2:36 PM
 */

class Loginapi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }
    public function loginapi () {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if(strpos($username, '@')){
            $this->db->where_in('email', $username);
        } else {
            $this->db->where_in('username', $username);
        }
        $this->db->where_in('password', md5($password));
        $this->db->where_in('type', 'customer');
        $this->db->where_in('active', 1);
        $result = $this->db->get('account')->row();
        if (sizeof($result) > 0) {
            $data = array(
                'full_name' => $result->full_name,
                'username' => $result->username,
                'email' => $result->email,
                'type' => $result->type,
                'active' => $result->active,
                'account_id' => $result->account_id,
                'shipping_address' => $result->shipping_address,
                'billing_address' => $result->billing_address,
                'shipping_city_state_country' => $result->shipping_city_state_country,
                'billing_city_state_country' => $result->billing_city_state_county,
                'contact_number' => $result->contact_number
            );
            $this->session->set_userdata($data);
        } else {
            $result = 404;
        }
        echo json_encode($result);
        return json_encode($result);
    }
    public function signupapi () {
        $form_data = $this->input->post();
        $form_data['type'] = 'customer';
        $form_data['active'] = 1;
        $finduser = $this->db->where_in('username', $form_data['username'])
            ->get('account')->result();
        $findemail = $this->db->where_in('email', $form_data['email'])
            ->get('account')->result();
        if(sizeof($finduser) > 0 ) {
            echo json_encode(400);
        }else if(sizeof($findemail) > 0) {
            echo json_encode(400);
        } else {
            $form_data['password'] = md5($form_data['password']);
            $result = $this->db->insert('account', $form_data);
            if ($result === true) {
                $this->session->set_userdata($this->db->where_in('username', $form_data['username'])->get('account')->row());
                echo json_encode(200);
            }
        }

    }
}