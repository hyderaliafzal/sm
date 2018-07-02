
<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/11/18
 * Time: 5:59 PM
 */

include "Login.php";
class Product extends Login
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
    }

    public function index() {
        $this->store();
                $products = $this->db->get('product');
                $products = $products->result();
                // var_dump($products->row());
                $data['products'] = $products;
                $this->load->view('admin/productlist.html', $data);
                $this->load->view('admin/footer.html');
    }

    public function addProduct () {
            $this->store();
                $product_types = $this->db->get('product_type');
                $product_types = $product_types->result();
                $data['product_types'] = $product_types;
                $product_categories = $this->db->get('category');
                $product_categories = $product_categories->result();
                $data['product_categories'] = $product_categories;
                $data['images'] = $this->db->get('images')->result();
                $data['product'] = array();
                $data['action'] = base_url().'index.php/admin/product/saveproduct';
                $this->load->view('admin/productForm.html', $data);
                $this->load->view('admin/footer.html');
    }

    public function saveProduct () {
        $form_data = $this->input->post();
        if(sizeof($form_data) > 1){
            if (sizeof($form_data['images']) > 0) {
                $images = $form_data['images'];
                unset($form_data['images']);
            }
            $categories = $form_data['product_category'];
            unset($form_data['product_category']);
            $this->db->insert('product', $form_data);
            $pid =(String) $this->db->insert_id();
            foreach ($categories as $category) {
                $this->db->insert('category_product', array('product_id'=> $pid, 'category_id'=>$category ));
            }
            $count = 1;
            foreach($images as $image){
                $this->db->where_in('product_id', $pid)->update('product', array('image_'.$count=>$image));
                $count++;
            }
            $this->store();
            $data['link'] = base_url().'index.php/admin/product';
            $this->load->view('admin/success.html', $data);
        } else {
            redirect(base_url().'index.php/admin/product');
        }

    }

    public function deleteproduct () {
        $data = $this->input->post('data');
        $this->db->where_in('product_id', $data);
        $result = $this->db->delete('product');
        $this->db->where_in('product_id', $data)->delete('category_product');
        return $result;
    }

    public function editproduct ($id) {
        $this->store();
        $form = $this->input->post();
        if(sizeof($form) > 0) {
            if (array_key_exists('images', $form)) {
                $images = $form['images'];
                unset($form['images']);
                $count = 1;
                foreach($images as $image){
                    $this->db->where_in('product_id', $id)->update('product', array('image_'.$count=>$image));
                    $count++;
                }
            }
            if(array_key_exists('product_category', $form)) {
                $categories = $form['product_category'];
                unset($form['product_category']);
                $this->db->where_in('product_id', $id)->delete('category_product');
                foreach ($categories as $category) {
                    $this->db->insert('category_product', array('product_id'=> $id, 'category_id'=>$category ));
                }
            }
            if($form['product_description'] === '') {
                unset($form['product_description']);
            }
            if($form['product_meta_description'] === '') {
                unset($form['product_meta_description']);
            }
            if($form['product_type'] === '') {
                unset($form['product_type']);
            }
            $result = $this->db->where_in('product_id', $id)->update('product', $form);
            if($result === true) {
                $data['link'] = base_url().'index.php/admin/product';
                $this->load->view('admin/success.html', $data);
            }
        } else {
            $data['images'] = $this->db->get('images')->result();
            $data['product_categories'] = $this->db->get('category')->result();
            $data['product'] = $this->db->where_in('product_id', $id)->get('product')->row();
            $data['product_types'] = $this->db->get('product_type')->result();
            $data['action'] = base_url() . 'index.php/admin/product/editproduct/' . $id;
            $this->load->view('admin/productForm.html', $data);
        }
    }
}