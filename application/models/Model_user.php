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
    var $tablesiswa;

    public function __construct()
    {
        parent::__construct();
        $this->tablename    = "m_admin";
        $this->tablesiswa   = "m_siswa";
    }

    public function user_check($data = array())
    {
        $params = array(
            'username'  => $data['username'],
            'password'  => $data['password'],
        );
        $this->db->where($params);
        $query  = $this->db->get($this->tablename);
        return $query->row();
    }
    public function siswa_check($data = array())
    {
        $this->db->where($data);
        $query  = $this->db->get($this->tablesiswa);
        return $query->row();
    }
    public function siswa_create($data = array())
    {
        $query  = $this->db->insert($this->tablesiswa, $data);
        return $query;
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
        $this->db->update($this->tablename, $params);
        if ($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }
}