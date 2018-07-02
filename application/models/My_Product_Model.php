<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/12/18
 * Time: 11:22 AM
 */

class My_Product_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function productList () {
        return $this->db->get('MyProductController');
    }
}