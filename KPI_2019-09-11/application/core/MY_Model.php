<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 6/26/2018
 * Time: 11:50 PM
 */

class MY_Model extends CI_Model {
    var $table_name;
    var $fields_encrypted;
    var $fields_md5_hashed;

    public function __construct() {
        parent::__construct();
        if (!is_array($this->fields_encrypted))
            $this->fields_encrypted = array();


    }

    public function set_fields_encrypted($fields) {
        $this->fields_encrypted = $fields;
    }

    public function set_fields_md5_hashed($fields) {
        $this->fields_md5_hashed = $fields;
    }

    public function my_select()
    {
        $select = '*';
        if (is_array($this->fields_encrypted) && sizeof($this->fields_encrypted) > 0) {
            foreach($this->fields_encrypted as $field) {
                $select .= sprintf(', AES_DECRYPT(%2$s, "%1$s") as %2$s_', DB_CRYPT_KEY, $field);
            }
        }
        $this->db->select($select);
    }

    public function get_data($where, $orderby = false)
    {
        $this->my_select();

        $this->set_where($where);

        if ($orderby !== false) {
            $this->db->order_by($orderby);
        }

        $query = $this->db->get($this->table_name);
        return $query->result_array();
    }

    public function insert_data($data)
    {
        $this->set_data($data);
        $this->db->insert($this->table_name);
        return $this->db->insert_id();
    }

    public function delete_data($where)
    {
        $this->set_where($where);
        $this->db->delete($this->table_name);
    }

    public function insert_update_data($data,$where) {
        $this->db->select('id');
        $this->set_where($where);
        $query = $this->db->get($this->table_name);
        if($query->num_rows() > 0) {
            $this->set_where($where);
            $this->set_data($data);
            $this->db->update($this->table_name);
            return $query->result_array()[0]['id'];
        } else {
            $this->set_data($data);
            $this->db->insert($this->table_name);
            return $this->db->insert_id();
        }

    }
    public function update_data($data, $where)
    {
        $this->set_data($data);
        $this->set_where($where);
        $this->db->update($this->table_name);
    }

    function set_where($where)
    {
        if ($where !== FALSE && is_array($where)) {
            foreach ($where as $key => $value) {
                if (in_array($key, $this->fields_encrypted)) {
                    $value = sprintf('AES_ENCRYPT("%s", "%s")', $value, DB_CRYPT_KEY);
                    $this->db->where($key, $value, FALSE);
                } else
                    $this->db->where($key, $value);
            }
        }
    }

    public function set_data($data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fields_encrypted)) {
                $value = sprintf('AES_ENCRYPT("%s", "%s")', $value, DB_CRYPT_KEY);
                $this->db->set($key, $value, FALSE);
            }
            else if (!empty($this->fields_md5_hashed) && in_array($key, $this->fields_md5_hashed)) {
                $value = sprintf('md5("%s")', $value);
                $this->db->set($key, $value, FALSE);
            }
            else if ($key == 'created_at') {
                if ($value == 'NOW()')
                    $this->db->set($key, $value, FALSE);
                else
                    $this->db->set($key, $value);
            }
            else
                $this->db->set($key, $value);
        }
    }
}