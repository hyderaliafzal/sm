<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/11/18
 * Time: 7:28 PM
 */
include 'Login.php';
class Order extends Login
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    function index() {
       $this->store();
                $order = $this->db->get('order');
                $order = $order->result();
                // var_dump($order);
                $data['orders'] = $order;
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');
                $this->load->view('admin/orderlist.html');
                $this->load->view('admin/footer.html');
    }

    public function editorder ($id) {
        $this->store();
                $this->db->where('order_id', $id);
                $order = $this->db->get('order');
                $order = $order->result();
                $product = $this->db->query('SELECT * FROM order_item JOIN product on order_item.product_id=product.product_id WHERE order_item.order_id ='.$id);
                $product = $product->result();
                // var_dump($product);
                $data['order'] = $order;
                $data['orderproducts'] = $product;
                $this->load->view('admin/header.html', $data);
                $this->load->view('admin/sidebar.html');
                // $this->load->view('admin/orderlist.html');
                $this->load->view('admin/footer.html');
    }

    public function orderdetail ($id) {
        $this->store();
        $data['order']  = $this->db->where_in('order_id', $id)->get('order')->row();
        $data['items'] = $this->db->select('order_item.quantity, product.product_title, product.price')
            ->from('order_item')
            ->where_in('order_item.order_id', $id)
            ->join('product', 'product.product_id=order_item.product_id')
            ->get()->result();
        $this->load->view('admin/order-detail.html', $data);
        $this->load->view('admin/footer.html');
    }

    public function confirm($id){
        $result = $this->db->where_in('order_id', $id)->update('order', array('status'=>'Confirmed'));
        echo $result;
    }

}