<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 6/20/18
 * Time: 4:20 PM
 */
include "Welcome.php";
class Page extends Welcome
{
    public function __construct()
    {
        parent::__construct();
    }

    public function slug ($slug) {
        $this->store_info();
        $data['page'] = $this->db->where('slug', $slug)->get('page')->row();
        $this->load->view('frontend/page.html', $data);
        $this->load->view('frontend/footer.html');
    }

    public function contact () {
        $this->store_info();
        $this->load->view('frontend/contact-form.html');
        $this->load->view('frontend/footer.html');
    }

}