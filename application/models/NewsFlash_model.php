<?php

class NewsFlash_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_newsflash($inserted_data)
    {
        $query = $this->db->insert('manage_news_flash', $inserted_data);
        return $this->db->insert_id();
    }

    public function fetch_newsflash_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
    {
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('mnf.news_id', 'DESC');
        }
        if ($where) {
            $this->db->where($where);
        }

        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('mnf.title', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('mnf.created_date', $search);
            $this->db->group_end();
        }

        $query = $this->db->select('mnf.news_id, mnf.title, ap.admin_fname, mnf.created_date, mnf.status, mnf.aws_path')
            ->from('manage_news_flash as mnf')
            ->join('admin_profile as ap', 'ap.uidnr_admin = mnf.created_by', 'left')
            ->limit($limit, $start)
            ->get();
        if ($query->num_rows() > 0) {
            if ($count != NULL) {
                return $query->num_rows();
            } else {
                return $query->result();
            }
        } else {
            return false;
        }
    }

    public function delete_newsflash($news_id)
    {
        $this->db->where($news_id);
        $query = $this->db->delete('manage_news_flash');
        return $query;
    }

    public function fetch_newsflash_for_edit($data)
    {
        $query = $this->db->get_where('manage_news_flash', $data);
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    // public function update_newsflash($post) {
    //     $checkUser = (array) $this->session->userdata('user_data');
    //     $this->db->where('news_id', $post['news_id']);
    //     $this->db->update('manage_news_flash', array('title' => $post['title'], 'content' => htmlentities($post['content']), 'updated_on' =>date('Y-m-d H:i:s'), 'updated_by' => $checkUser['uidnr_admin']));        
    //     if ($this->db->affected_rows() > 0) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function update_newsflash_status($news_id)
    {
        $query = $this->db->get_where('manage_news_flash', $news_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('manage_news_flash', $post, $news_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    public function fetch_created_person()
    {
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.admin_fname, ap.admin_lname,  ap.uidnr_admin')
            ->from('admin_profile as ap')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_titles()
    {
        $query = $this->db->select('mnf.news_id, mnf.title')
            ->from('manage_news_flash as mnf')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_log_data($id)
    {

        $where = array();
        $where['ul.source_module'] = 'NewsFlash';
        $where['ul.record_id'] = $id;

        $this->db->select('ul.action_taken,ul.created_on as taken_at,ul.text, CONCAT(ap.admin_fname," ",ap.admin_lname) as taken_by');
        $this->db->from('user_log_history ul');
        $this->db->join('admin_profile ap', 'ul.created_by = ap.uidnr_admin', 'left');
        $this->db->order_by('ul.id', 'DESC');
        $this->db->where($where);
        $result = $this->db->get();
        // echo $this->db->last_query();die;
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    // added by millan on 07-05-2021
    public function fetch_nsl_data(){
        $query = $this->db->select('nlc.nsl_content_id, nlc.subject, nlc.content_desc, GROUP_CONCAT(nli.ns_image_path) image_path')
                ->from('newsletter_content nlc')
                ->join('newsletter_image nli', 'nli.newsltr_cont_id = nlc.nsl_content_id', 'left')
                ->where('nlc.newsletter_status', 1)
                ->get();
        if($query->num_rows() > 0){
            return $query->row();
        } else{
            return false;
        }
    }

    // added by millan on 08-05-2021
    public function fetch_ns_data($get_data){
        $query = $this->db->select('nlc.subject, nlc.content_desc, GROUP_CONCAT(nli.ns_image_path) image_path')
                ->from('newsletter_content nlc')
                ->join('newsletter_image nli', 'nli.newsltr_cont_id = nlc.nsl_content_id', 'left')
                ->where('nlc.newsletter_status', 1)
                ->where($get_data)
                ->get();
        if($query->num_rows() > 0){
            return $query->row();
        } else{
            return false;
        }
    }

    // added by millan on 08-05-2021
    public function get_customers_list(){
        $result = array();

        $query = $this->db->select('cust.customer_id, cust.mst_branch_id, concat(cust.email, ",",group_concat(cont.email)) as email')
            ->from('cust_customers cust')
            ->join('contacts cont', 'cont.contacts_customer_id = cust.customer_id', 'LEFT')
            ->where('cust.send_newsletter', 0)
            ->group_by('cust.customer_id')
            ->get();
        if ($query->num_rows() > 0) {
            $result['default'] = $query->result_array();
        }
        $dxb_load = $this->load->database('dxb', TRUE);
        $query2 = $dxb_load->select('cust.customer_id, cust.mst_branch_id, concat(cust.email, ",",group_concat(cont.email)) as email')
            ->from('cust_customers cust')
            ->join('contacts cont', 'cont.contacts_customer_id = cust.customer_id', 'LEFT')
            ->where('cust.send_newsletter', 0)
            ->group_by('cust.customer_id')
            ->get();
        if ($query2->num_rows() > 0) {
            $result['dxb'] = $query2->result_array();
        }

        $bng_load = $this->load->database('bng', TRUE);
        $query3 = $bng_load->select('cust.customer_id, cust.mst_branch_id, concat(cust.email, ",",group_concat(cont.email)) as email')
            ->from('cust_customers cust')
            ->join('contacts cont', 'cont.contacts_customer_id = cust.customer_id', 'LEFT')
            ->where('cust.send_newsletter', 0)
            ->group_by('cust.customer_id')
            ->get();
        if ($query3->num_rows() > 0) {
            $result['bng'] = $query3->result_array();
        }

        return $result;
    }

    // added by millan on 08-05-2021
    public function customer_newsletter_update($cust_id, $db_name){
        $dxb_load = $this->load->database($db_name, TRUE);
        $query2 = $dxb_load->where('cust.customer_id', $cust_id)
            ->update('cust_customers cust', ['send_newsletter' => 1]);
        $dxb_load->close();
        if ($query2) {
            return true;
        }
    }

    // added by millan on 08-05-2021
    public function insert_cust_subs_db($tbl_name, $post, $db_name){
        $dxb_load = $this->load->database($db_name, TRUE);
        $con = $dxb_load->get($tbl_name, $post);
        if ($con->num_rows() > 0) {
            $dxb_load->close();
            return true;
        } else {
            $query2 = $dxb_load->insert($tbl_name, $post);
            $dxb_load->close();
            if ($query2) {
                return true;
            } else {
                return false;
            }
        }
    }
}
