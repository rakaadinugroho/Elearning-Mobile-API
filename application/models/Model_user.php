<?php

/**
 * Creator: Raka Adi Nugroho
 * Mail: nugrohoraka@gmail.com
 * Date: 5/23/17
 * Time: 8:57 PM
 */
class Model_user extends CI_Model
{
    var $tablename;
    public function __construct()
    {
        parent::__construct();
        $this->tablename    = "m_user";
    }

    public function user_check($data = array())
    {
        $params = array(
            'username'  => $data['username'],
            'password'  => $data['password'],
            'status'    => 'active'
        );
        $this->db->where($params);
        $query  = $this->db->get($this->tablename);
        return $query->row();
    }

    public function user_create($data = array())
    {
        $query  = $this->db->insert($this->tablename, $data);
        return $query;
    }
    public function change_password($data = array())
    {
        $params = array(
            'password'  => $data['newpassword']
        );
        $conditions  = array(
            'username'  => $data['username'],
            'password'  => $data['oldpassword']
        );

        $this->db->where($conditions);
        $query  = $this->db->update($this->tablename, $params);
        return $query;
    }
}