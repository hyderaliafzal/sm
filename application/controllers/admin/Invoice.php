<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/11/18
 * Time: 7:34 PM
 */

class Invoice extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    function index() {
        echo 'this is invoice controller';
    }

}