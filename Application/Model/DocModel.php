<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mdModel
 *
 * @author jannick
 */
class DocModel extends BaseModel {

    protected $adapter = 'pgsql';
    protected $table = 'a';
    protected $primary = 'id';

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $row = $this->find();
        var_dump($this->db);
        return $row;
//		var_dump($this->db);
//		$resource = $this->db->from($this->table)->getResource();
//		return $resource;
    }

    public function chekfield() {
        
    }

    public function insert() {
        $re = $this->db->from('a')->set(array(
                    'name' => 'name' . mt_rand(1, 100)
                ))->insert();
        var_dump($re);
//		$re = $this->db->from('tb')->set(array(
//					'name' => 'name' . mt_rand(1, 100)
//				))->insert();
//		$a = $this->db->lastInsertId('id');
//		var_dump($a);
    }

    public function select() {
        $re = $this->db->from('a')->where(
                        array(
                            'id' => array(
                                'whereNotEqual', array(
                                    '234')
                            ),
                            'name' => array(
                                'whereLike', array(
                                    '%name%'
                                )
                            ),
                        )
                )->where(
                        array(
                            'id' => array(
                                'whereIn', array(
                                    array(139, 140), 'OR'
                                )
                            )
                        )
                )->fetch();
        $a = $this->db->lastInsertId('id');
        var_dump($a);
        var_dump($re);
    }

}

?>
