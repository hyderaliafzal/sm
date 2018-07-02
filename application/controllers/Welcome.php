<?php

class Welcome extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
	{
	    $qurans = $this->db->select('*')
            ->limit(8)
            ->from('category')
            ->where('category.category_slug', 'qurans')
            ->join('category_product', 'category_product.category_id=category.category_id')
            ->join('product', 'product.product_id = category_product.product_id', 'LEFT')
            ->get()->result();
	    $data['qurans'] = $qurans;
        $paintings = $this->db->select('*')
            ->limit(8)
            ->from('category')
            ->where('category.category_slug', 'paintings')
            ->join('category_product', 'category_product.category_id=category.category_id')
            ->join('product', 'product.product_id = category_product.product_id', 'LEFT')
            ->get()->result();
        $data['paintings'] = $paintings;
        $cameras = $this->db->select('*')
            ->limit(8)
            ->from('category')
            ->where('category.category_slug', 'cameras')
            ->join('category_product', 'category_product.category_id=category.category_id')
            ->join('product', 'product.product_id = category_product.product_id', 'LEFT')
            ->get()->result();
        $data['cameras'] = $cameras;
        $watches = $this->db->select('*')
            ->limit(8)
            ->from('category')
            ->where('category.category_slug', 'watches')
            ->join('category_product', 'category_product.category_id=category.category_id')
            ->join('product', 'product.product_id = category_product.product_id', 'LEFT')
            ->get()->result();
        $data['watches'] = $watches;

        $this->store_info();
        $this->load->view('frontend/home-slider.html');
        $this->load->view('frontend/banner-bottom.html');
        $this->load->view('frontend/banner-bottom2.html');
        $this->load->view('frontend/two-grids.html');
        $this->load->view('frontend/new-arrivals.html', $data);
        $this->load->view('frontend/schedule-bottom.html');
        $this->load->view('frontend/footer.html');
	}

	public function store_info () {
        $user = $this->session->userdata;
        if(sizeof($user) > 1){
            if($user['type'] === 'admin'){
                $user = array();
            }
        }
        $data['user'] = $user;
        $data['store'] = $this->db->get('store')->row();
        $categories = $this->db->select('*')
            ->from('category')
            ->where_in('level', 0)
            ->get()->result();
        for ($count = 0; $count < sizeof($categories); $count++){
            $categorychild = $this->db->where('parent_category_id', $categories[$count]->category_id)
                ->where('level', 1)
                ->get('category')->result();
            $categories[$count]->child = $categorychild;
        }
        $data['categories'] = $categories;
        $data['types'] = $this->db->get('product_type')->result();
        $this->db->where('component_id', 'homepage-slider-1');
        $data['slider1'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'homepage-slider-2');
        $data['slider2'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'homepage-slider-3');
        $data['slider3'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'homepage-slider-4');
        $data['slider4'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'homepage-slider-5');
        $data['slider5'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'trending-1');
        $data['trending1'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'trending-2');
        $data['trending2'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'trending-3');
        $data['trending3'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'trending-4');
        $data['trending4'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'trending-5');
        $data['trending5'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'trending-6');
        $data['trending6'] = $this->db->get('page')->row();
        $this->db->where('component_id', 'trending-7');
        $data['trending7'] = $this->db->get('page')->row();
        $new_arrivals = $this->db->select('*')
            ->from('product')
            ->order_by('product.product_id',"desc")
            ->limit(8)
            ->get()
            ->result();
        $data['arrivals'] = $new_arrivals;

        $this->load->view('frontend/top-bar.html', $data);
        $this->load->view('frontend/header-bar.html');
        $this->load->view('frontend/nav-bar.html');
        $this->load->view('frontend/modal1.html');
        $this->load->view('frontend/modal2.html');
    }
}
