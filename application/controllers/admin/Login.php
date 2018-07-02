<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/7/18
 * Time: 6:00 PM
 */

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
		        $this->load->library('mcrypt');
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('email');
    }

    public function createUser()
    {
        $controller = 'login/createadmin';
        $this->load->view('admin/createAdminHtml.html');
    }

    public function createAdmin () {
        $form_data = $this->input->post();
        var_dump($form_data);
        $form_data['password'] = md5($this->input->post('password'));
        $form_data['type'] = 'admin';
        $form_data['active'] = 1;
        $result = $this->db->insert('account', $form_data);
        if($result === 1) {
            $this->load->view('');
            echo 'user created';
        } else {
            $this->load->view('');
            echo 'Error';
        }
    }

    public function adminLogin() {
            $error['error'] = null;
            $this->load->view('admin/loginHtml.html', $error);
    }

    public function loggedin ()
    {
        $session_data = $this->session->userdata;
        // var_dump(sizeof($session_data));
        if (sizeof($session_data) > 1) {
            if($session_data['type'] === 'admin') {
                $error['error'] = '401: Not Authorized.';
                $this->adminDashboard($session_data);
            } else {
                redirect(base_url().'index.php/admin/login/adminlogin');
            }
        } else {
            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean($this->input->post('password'));

            $this->db->where('username', $username);
            $this->db->where('password', md5($password));
            $this->db->where('type', 'admin');

            $query = $this->db->get('account');
            // var_dump($query->result());
            if ($query->num_rows() == 1) {
                $row = $query->row();
                $data = array(
                    'username' => $row->username,
                    'type' => $row->type,
                    'active' => $row->active
                    // 'profile_picture' => $row->profile_picture
                );
                if($data['type'] == 'admin') {
                    if ($data['active'] == 0) {
                        $error['error'] = '403: Forbidden.';
                        $this->load->view('admin/loginHtml.html', $error);
                    } else {
                        $this->session->set_userdata($data);
                        $this->adminDashboard($data);
                    }
                } else {
                    $error['error'] = '401: Not Authorized.';
                    $this->load->view('admin/loginHtml.html', $error);
                }
            } else {
                $error['error'] = 'Wrong user name/password';
                $this->load->view('admin/loginHtml.html', $error);
            }
        }
    }

    function adminDashboard ($query) {
        $data['data'] = $query;
        $this->load->view('admin/header.html', $data);
        $this->load->view('admin/sidebar.html');
        $this->load->view('admin/footer.html', $data);
    }

    public function store() {
        $session_data = $this->session->userdata;
        // var_dump(sizeof($session_data));
        if (sizeof($session_data) > 1) {
            if($session_data['type'] === 'admin') {
                $data['data'] = $session_data;
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');

            } else {
                redirect(base_url().'index.php/admin/login/adminlogin');
            }
        }
    }

    public function ifAdmin () {
        $session_data = $this->session->userdata;
        // var_dump(sizeof($session_data));
        if (sizeof($session_data) > 1) {
            if($session_data['type'] === 'admin') {
                return true;
            } else {
                echo false;
            }
        } else {
            return false;
        }
    }

    function logout() {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('type');
        $this->session->sess_destroy();
        redirect('index.php/admin/login/adminlogin');
    }

    function checkEmail($email) {
        if ( strpos($email, '@') !== false ) {
            $split = explode('@', $email);
            return (strpos($split['1'], '.') !== false ? true : false);
        }
        else {
            return false;
        }
    }

}
