<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 6/16/18
 * Time: 12:35 PM
 */
include 'Welcome.php';
class Categories extends Welcome
{
    public function __construct()
    {
        parent::__construct();
    }

    public function category ($slug) {
        $this->store_info();
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where_in('category_slug', $slug);
        $category = $this->db->get()->row();
        $result['category'] = $category;
        $products = $this->db->select('*')
            ->where('category_product.category_id', $category->category_id)
            ->where_in('product.is_active', 1)
            ->join('product', 'product.product_id=category_product.product_id', 'inner')
            ->get('category_product', 8, 0)->result();
        $result['show_first'] = $this->db
            ->select('*')
            ->from('category_product')
            ->where('category_product.category_id', $category->category_id)
            ->limit(3)
            ->join('product', 'product.product_id=category_product.product_id', 'inner')
            ->where('product.priority', 1)
            ->get()->result();
        $result['offset'] = 0;
        $result['limit'] = 8;
        $result['id'] = $category->category_id;
        $result['products'] = $products;
        $result['product_count'] = $this->db->select('*')
            ->where('category_product.category_id', $category->category_id)
            ->where_in('product.is_active', 1)
            ->join('product', 'product.product_id=category_product.product_id', 'inner')
            ->get('category_product')->num_rows();
        $this->load->view('frontend/product-grid.html', $result);
        $this->load->view('frontend/footer.html');
    }

    public function productset($id, $limit, $offset){
        $products = $this->db->select('*')
            ->where('category_product.category_id', $id)
            ->where_in('is_active', 1)
            ->join('product', 'product.product_id=category_product.product_id', 'inner')
            ->get('category_product', $limit, $limit*$offset)->result();
        $data['products'] = $products;
        echo json_encode($this->load->view('frontend/col-3-grid.html', $data));
    }

}