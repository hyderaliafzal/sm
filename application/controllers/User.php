<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 6/13/18
 * Time: 6:57 PM
 */
include 'Welcome.php';
class User extends Welcome
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }
    public function register () {
        $form_data = $this->input->post();
        $msg['msg'] = 'SignUp';
        if(sizeof($form_data) > 0) {
            $this->db->where_in('email', $form_data['email']);
            $result = $this->db->get('account')->row();
            if(sizeof($result) > 0) {
                $msg['msg'] = 'Email Exists.';
            } else {
                $form_data['type'] = 'customer';
                $form_data['password'] = md5($form_data['password']);
                $result = $this->db->insert('account', $form_data);
                if ($result === true) {
                    $this->session->set_userdata($form_data);
                    $data['user'] = $form_data;
                    redirect(base_url().'index.php/user/dashboard',$data);
                }
            }
        }
            $this->store_info();
            $this->load->view('frontend2/signupForm.html', $msg);
            $this->load->view('frontend2/footer.html');
    }
    public function login () {
        $user = $this->session->userdata;
        if (sizeof($user) > 1 && $user['type'] != 'admin') {
           redirect(base_url().'index.php/user/dashboard');
        } else {
            $form_data = $this->input->post();
            $msg['msg'] = 'Login';
            if (sizeof($form_data) > 0) {
                $this->db->where_in('email', $form_data['email']);
                $this->db->where_in('type', 'customer');
                $this->db->where_in('password', md5($form_data['password']));
                $result = $this->db->get('account')->row();
                if (sizeof($result) > 0) {
                    $data = array(
                        'username' => $result->username,
                        'email' => $result->email,
                        'type' => $result->type,
                        'active' => $result->active
                    );
                    $this->session->set_userdata($data);
                    redirect(base_url() . 'Text.php/user/dashboard');
                } else {
                    $msg['msg'] = 'Wrong email/password.';
                }
            }
            $this->store_info();
            $this->load->view('frontend2/loginForm.html', $msg);
            $this->load->view('frontend2/footer.html');
        }
    }

    public function dashboard () {
        $user = $this->session->userdata;
        $data['user'] = $user;
        if(sizeof($data['user']) > 1 && $this->session->userdata('type') != 'admin') {
            $orders =  $this->db->select()
                ->where_in('account_id', $user['account_id'])
                ->from('order')
                ->get()
                ->result();
                $count = 0;
                foreach ($orders as $order){
                    $orders[$count]->products = $this->db->select('order_item.quantity, product.product_title, product.price')
                        ->where_in('order_id', $order->order_id)
                        ->from('order_item')
                        ->join('product', 'product.product_id=order_item.product_id', 'right')
                        ->get()->result();
                    $count++;
                }
             $bidProducts = $this->db->select('*,product.product_title, product.product_slug')
                 ->from('product_bids')
                 ->where_in('account_id', $user['account_id'])
                 ->join('product', 'product.product_id=product_bids.product_id')
                 ->get()->result();
                $data['products'] = $bidProducts;
                $this->store_info();
                $data['orders'] = $orders;
                $this->load->view('frontend/user-dashboard-side-bar.html', $data);
                $this->load->view('frontend/footer.html');
        } else {
            redirect(base_url());
        }
    }

    function logout() {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('type');
        $this->session->unset_userdata('active');
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function message () {
        $form = $this->input->post();
        $result = $this->db->insert('contact', $form);
        if($result === true) echo  true; else false;
    }
}