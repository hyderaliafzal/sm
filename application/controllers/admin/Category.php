<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/11/18
 * Time: 7:33 PM
 */
include 'Login.php';
class Category extends Login
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Category_model');
    }

    public function index() {
        $session_data = $this->session->userdata;
        // var_dump(sizeof($session_data));
        if (sizeof($session_data) > 1) {
            if($session_data['type'] === 'admin') {
                $categories = $this->db->get('category');
                $categories = $categories->result();
                // var_dump($categories->result());
                $data['data'] = $session_data;
                $cat['categories'] = $categories;
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');
                $this->load->view('admin/categorieslist.html', $cat);
                $this->load->view('admin/footer.html');
            } else {
                echo '401';
            }
        } else {
            redirect(base_url().'index.php/admin/login/adminlogin');
        }
    }
    public function addcategory () {
        $this->store();
                $categories = $this->db->where_in('level', 0)->get('category')->result();
                $images = $this->db->get('images');
                $images = $images->result();
                //var_dump($categories);
                $data['categories'] = $categories;
                $data['images'] = $images;
                $data['category'] = array();
                $data['action'] = base_url().'index.php/admin/category/addcategory/';
                $this->load->view('admin/categoryForm.html', $data);
                $this->load->view('admin/footer.html');

    }

    public function savecategory () {
        $form_data = $this->input->post();
        if(empty($form_data['category_slug'])){
            $form_data['category_slug'] = strtolower(str_replace(" ", "-", $form_data['category_title']));
        }
        $result = $this->db->insert('category', $form_data);
        $id = $this->db->insert_id();
        $count = 0;
        if ($result === true) {
            $data['link'] = base_url().'index.php/admin/category';
            $this->store();
            $this->load->view('admin/success.html', $data);
        } else {

        }
    }

    public function deletecategory () {
        $data = $this->input->post('data');
        $this->db->where_in('category_id', $data);
        $result = $this->db->delete('category');
        $this->db->where_in('category_id', $data);
        $this->db->delete('category_images');
        return $result;
    }

    public function editcategory ($id) {
            $this->store();
                $form = $this->input->post();
                if(sizeof($form) > 0) {
                    $result = $this->db->where('category_id', $id)->update('category', $form);
                    if($result === true) {
                        $data['link'] = base_url().'index.php/admin/category';
                        $this->load->view('admin/success.html', $data);
                    }
                }
                else {
                    $this->db->where('category_id', $id);
                    $category = $this->db->get('category')->row();
                    $images = $this->db->get('images');
                    $images = $images->result();
                    //var_dump($categories);
                    $data['category'] = $category;
                    $data['categories'] = $this->db->where_in('level', 0)->get('category')->result();
                    $data['images'] = $images;
                    $data['action'] = base_url().'index.php/admin/category/editcategory/'.$id;
                    $this->load->view('admin/categoryForm.html', $data);
                    $this->load->view('admin/footer.html');
                }
    }
}