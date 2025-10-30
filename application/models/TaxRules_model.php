<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TaxRules_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function taxrules_list($per_page = NULL, $page = 0, $where, $search_lbl, $count = NULL)
    {
        $this->db->limit($per_page, $page);
        if (count($where) > 0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search_lbl) {
            $this->db->group_start();
            $this->db->or_like('LOWER(syc.cfg_Name)', strtolower($search_lbl));
            $this->db->group_end();
        }
        $this->db->select("syc.cfg_Name, syc.cfg_Value");
        $this->db->where_in('syc.cfg_Name', ['IGST', 'SGST', 'CFST', 'UTGST', 'ZERO_RATED_TAX', 'BDT_VAT', 'VAT' ]);
        $this->db->from('sys_configuration syc');
        $query = $this->db->get();
        if ($count) {
            return $query->num_rows();
        } else {
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
    }
}
