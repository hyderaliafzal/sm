<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/11/18
 * Time: 6:32 PM
 */

class CustomError extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index () {
        echo  'this is error controller';
    }

    public function error404 ( ) {
        $this->load->view('errors/404.html');
    }

}