<?php
/**
 * Created by PhpStorm.
 * User: hyderaliafzal
 * Date: 5/16/18
 * Time: 12:01 PM
 */

class Category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function deleteCategories ($ids) {
        foreach ($ids['categories'] as $id) {
            var_dump($id);
            $this->db->where('category_id', $id);
            $this->db->delete('category');
        }
        return;
    }
}