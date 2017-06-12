<?php

/**
 * Creator: Raka Adi Nugroho
 * Mail: nugrohoraka@gmail.com
 * Date: 5/24/17
 * Time: 11:12 PM
 */
class Model_category extends CI_Model
{
    var $tablename;
    public function __construct()
    {
        parent::__construct();
        $this->tablename    = "m_mapel";
    }

    public function category_all()
    {
        $query  = $this->db->get($this->tablename);
        return $query->result();
    }
    public function category_get($data = array())
    {
        $this->db->where($data);
        $query  = $this->db->get($this->tablename);
        return $query->row();
    }
}