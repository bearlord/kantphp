<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryModel
 *
 * @author jannick
 */
class CategoryModel extends BaseModel {

//	protected $adapter = 'default';
    protected $table = 'cateogory';
    protected $primary = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $res = $this->db->from($this->table)->fetch();
        var_dump($this->db);
        return $res;
    }

}

?>
