<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 6/17/18
 * Time: 11:47 AM
 */
include 'Welcome.php';
class Product extends Welcome
{

    public function __construct()
    {
        parent::__construct();
    }
    public function detail ($slug) {
        $this->store_info();
        $this->db->where_in('product_slug', $slug);
        $product = $this->db->get('product')->row();
        if($product->product_type === 'auction') {
            $bids = $this->db->select('*')
                ->from('product_bids')
                ->where_in('product_id', $product->product_id)
                ->join('account', 'account.account_id=product_bids.account_id')
                ->get()->result();
            $data['bids'] = $bids;
        }
        $data['product'] = $product;
         $this->load->view('frontend/product-detail.html', $data);
        $this->load->view('frontend/footer.html');
    }
    public function type ($type) {
        $this->store_info();
        $products = $this->db->where_in('product_type', $type)
            ->where_in('is_active', 1)
            ->order_by('product_id', 'Desc')
            ->get('product', 9, 0)
            ->result();
        $data['items'] = $products;
        $data['offset'] = 0;
        $data['limit'] = 9;
        $data['type'] = $type;
        $data['product_count'] = $this->db->where_in('product_type', $type)
            ->where_in('is_active', 1)->get('product')->num_rows();
        $this->load->view('frontend/types-grid.html',$data);
        $this->load->view('frontend/footer.html');
    }
    public function search () {
        $query = $this->input->post('query');
        $this->db->select('*')
            ->from('product')
            ->like('product_title', $query)
            ->like('product_description', $query);
        $products = $this->db->get()->result();
        $data['items'] = $products;
        $this->store_info();
        $this->load->view('frontend/types-grid.html', $data);
        $this->load->view('frontend/footer.html');
    }

    public function checkout () {
        $this->store_info();
        $this->load->view('frontend/checkout.html');
        $this->load->view('frontend/footer.html');
    }

    public function placeorder () {
        $form_data = $this->input->post();
        $items = $form_data['products'];
        unset($form_data['products']);
        $user = $this->session->userdata;
        if (sizeof($user) > 1){
            $form_data['account_id'] = $user['account_id'];
        }
        $form_data['status'] = 'Pending';
        $result = $this->db->insert('order', $form_data);
        $id = $this->db->insert_id();
        if ($result === true) {
            foreach ($items as $item){
                $this->db->insert('order_item', array('product_id' => $item['id'], 'order_id' => $id, 'quantity' => $item['quantity']));
                $quantity = $this->db->select('quantity')->from('product')->where('product_id', $item['id'])->get()->row();
                $quantity = $quantity->quantity-$item['quantity'];
                $this->db->where('product_id', $item['id'])->update('product', array('quantity'=>$quantity));
            }
            echo json_encode(array('status'=>200, 'message' => 'Order placed Successfully.'));
        } else {
            echo json_encode(array('status'=>500, 'message' => 'Error Occured.'));
        }
    }

    public function bid() {
        $form = $this->input->post();
        $result = $this->db->insert('product_bids', $form);
        if($result === true) echo true; else false;
    }

    public function productset($type, $limit, $offset){
        $products = $this->db->where_in('product_type', $type)
            ->where_in('is_active', 1)
            ->order_by('product_id', 'Desc')
            ->get('product', $limit, $limit*$offset)
            ->result();
        $data['items'] = $products;
        echo json_encode($this->load->view('frontend/col-4-grid.html', $data));
    }
}