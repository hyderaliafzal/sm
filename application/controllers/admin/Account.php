<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/11/18
 * Time: 7:28 PM
 */

class Account extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }

    function index() {
        $session_data = $this->session->userdata;
        // var_dump(sizeof($session_data));
        if (sizeof($session_data) > 1) {
            if($session_data['type'] === 'admin') {
                $account = $this->db->get('account');
                $account = $account->result();
                $data['data'] = $session_data;
                $data['accounts'] = $account;
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');
                $this->load->view('admin/accountlist.html');
                $this->load->view('admin/footer.html');
            } else {
                echo '401';
            }
        } else {
            redirect(base_url().'Text.php/admin/login/adminlogin');
        }
    }

    function createaccount () {
        $session_data = $this->session->userdata;
        if (sizeof($session_data) > 1) {
            if($session_data['type'] === 'admin') {
                $data['data'] = $session_data;
                $data['msg'] = '';
                $data['account'] = (object) array('full_name' => '', 'username' => '', 'email' => '');
                $data['action'] = 'index.php/admin/account/createaccount';
                $data['buttonText'] = 'Create Account';
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');
                if (sizeof($this->input->post()) > 0) {
                    $form_data = $this->input->post();
                    $this->db->where('username', $form_data['username']);
                    $result = $this->db->get('account');
                    if($result->num_rows() != 0) {
                        $data['msg'] = 'Username Already Exists.';
                        $this->load->view('admin/createaccountHtml.html', $data);
                    } else {
                        $form_data['password'] = md5($form_data['password']);
                        $result = $this->db->insert('account', $form_data);
                        if($result = true) {
                            $data['link'] = base_url().'index.php/admin/account';
                            $this->load->view('admin/success.html', $data);
                        } else {
                            $data['msg'] = 'An error occured. Please try again.';
                            $this->load->view('admin/createaccountHtml.html', $data);

                        }
                    }
                } else {
                    $this->load->view('admin/createaccountHtml.html', $data);
                }
                $this->load->view('admin/footer.html');
            } else {
                redirect(base_url().'index.php/admin/login/adminlogin');
            }
        } else {
            redirect(base_url().'index.php/admin/login/adminlogin');
        }
    }

    public function updateaccount ($id) {
        $session_data = $this->session->userdata;
        if (sizeof($session_data) > 1) {
            if ($session_data['type'] === 'admin') {
                $data['data'] = $session_data;
                $data['msg'] = '';
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');
                if($id === null) {
                    redirect(base_url().'index.php/admin/account');
                } else {
                    if(sizeof($this->input->post()) > 0) {
                        $form_data = $this->input->post();
                        $this->db->where('account_id', $id);
                        $result = $this->db->update('account', $form_data);
                        if($result == true) {
                            $data['link'] = base_url().'index.php/admin/account';
                            $this->load->view('admin/success.html', $data);
                        } else {
                            redirect(base_url().'index.php/admin/account');
                        }
                    } else {
                        $this->db->where('account_id', $id);
                        $result = $this->db->get('account');
                        $result = $result->row();
                        if (sizeof($result) > 0) {
                            $data['account'] = $result;
                            $data['buttonText'] = 'Update Account';
                            $data['action'] = 'index.php/admin/account/updateaccount/'.$id;
                            $this->load->view('admin/createaccountHtml.html', $data);
                        } else {
                            $data['buttonText'] = 'Create Account';
                            $data['action'] = 'index.php/admin/account/createaccount';
                            $data['msg'] = 'Account not found.';
                            $this->load->view('admin/createaccountHtml.html', $data);
                        }
                    }
                }
            } else {
                redirect(base_url().'index.php/admin/login/adminlogin');
            }
        } else {
            redirect(base_url().'index.php/admin/login/adminlogin');
        }
    }
}