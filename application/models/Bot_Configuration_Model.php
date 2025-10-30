<?php

class Bot_Configuration_Model extends MY_Model
{
    function make_query($post)
    {
        $this->db->select(['i.id', 'i.intents', '(CONCAT(u.admin_fname, " ", u.admin_lname)) as user_name', 'DATE_FORMAT(i.created_on, "%d/%m/%Y") as date_time', 'i.status', '(SELECT count(q.id) FROM rasa_faq_questions AS q WHERE q.intents_id = i.id AND q.is_deleted = 0) AS question', '(SELECT count(a.id) FROM rasa_faq_answers AS a WHERE a.intents_id = i.id AND a.is_deleted = 0) AS answers']);
        $this->db->from('rasa_faq_intents i');
        $this->db->join('admin_profile u', 'i.created_by = u.uidnr_admin', 'left');
        $this->db->where('i.is_deleted', 0);
        $this->db->group_by('i.id');
        if (isset($post['search']['value']) && !empty($post['search']['value'])) {
            $this->db->like('i.intents', $post['search']['value']);
            $this->db->or_like('CONCAT(u.admin_fname, " ", u.admin_lname)', $post["search"]["value"]);
        }
        if (isset($post["order"])) {
            $this->db->order_by(['i.id', 'i.intents', 'i.created_by', 'i.created_on', 'i.status'][$post['order']['0']['column']], $post['order']['0']['dir']);
        } else {
            $this->db->order_by('i.id', 'DESC');
        }
    }

    function fetch_records($post)
    {
        $this->make_query($post);
        if ($post["length"] != -1) {
            $this->db->limit($post['length'], $post['start']);
        }
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->result();
    }

    function get_filtered_data($post)
    {
        $this->make_query($post);
        return $this->db->get()->num_rows();
    }

    function get_all_data($table)
    {
        return $this->db->select('*')->from($table)->count_all_results();
    }

    public function bot_configuration_log($record_id)
    {
        $query = $this->db->select('CONCAT(u.admin_fname, " ", u.admin_lname) as full_name, c.record_id, c.source_module, c.operation, c.action_message, DATE_FORMAT(c.activity_on, "%d-%b-%Y %r") as activity_on')
            ->join('admin_profile u', 'c.admin_id = u.uidnr_admin', 'left')
            ->where(['c.record_id' => $record_id, 'source_module' => 'Bot_Configuration'])
            ->order_by('c.log_id', 'DESC')
            ->get('rasa_faq_log c');
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }


    // ========================================== QUESTIONS/ANSWERS =====================================
    public function fetch_faq_intents()
    {
        $this->db->distinct();
        $this->db->select('i.id, i.intents');
        $this->db->from('rasa_faq_intents i');
        $this->db->join('rasa_faq_questions q', 'i.id = q.intents_id', 'inner');
        $this->db->join('rasa_faq_answers a', 'i.id = a.intents_id', 'inner');
        $this->db->where(['i.is_deleted' => 0, 'i.status' => 1, 'q.is_deleted' => 0, 'q.status' => 1, 'a.is_deleted' => 0, 'a.status' => 1]);
        $this->db->order_by('i.id', 'ASC');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

    public function fetch_faq_questions($intents_id)
    {
        $this->db->select('e.id as questions_id, e.questions');
        $this->db->from('rasa_faq_questions e');
        $this->db->where(['e.intents_id' => $intents_id, 'e.is_deleted' => 0, 'e.status' => 1]);
        $this->db->order_by('e.id', 'ASC');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

    public function fetch_faq_answers($intents_id)
    {
        $this->db->select('e.id as answers_id, e.answers');
        $this->db->from('rasa_faq_answers e');
        $this->db->where(['e.intents_id' => $intents_id, 'e.is_deleted' => 0, 'e.status' => 1]);
        $this->db->order_by('e.id', 'ASC');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

    // ========================================== ALL CONVERSATIONS =====================================
    function make_query_conversation($post)
    {
        $this->db->distinct();
        $this->db->select(['id', 'sender_id']);
        $this->db->from('events');
        $this->db->group_by('sender_id');
        if (isset($post['search']['value']) && !empty($post['search']['value'])) {
            $this->db->like('sender_id', $post['search']['value']);
        }
        if (isset($post["order"])) {
            $this->db->order_by(['id', 'sender_id'][$post['order']['0']['column']], $post['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'DESC');
        }
    }

    function fetch_conversation($post)
    {
        $this->make_query_conversation($post);
        if ($post["length"] != -1) {
            $this->db->limit($post['length'], $post['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_filtered_data_conversation($post)
    {
        $this->make_query_conversation($post);
        return $this->db->get()->num_rows();
    }


    // ============================ CONVERSATION DETAILS =====================================

    /*
    public function conversation_details($sender_id)
    {
        $this->db->select('id, type_name, intent_name, action_name, data');
        $this->db->where(['sender_id' => $sender_id, 'type_name' => 'bot']);
        $this->db->or_where('type_name', 'user');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('events');
        // echo $this->db->last_query(); die;
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    */

    function make_conversation_details_query($post)
    {
        $this->db->select('id, type_name, data');
        $this->db->from('events');
        $this->db->where('sender_id', $post['sender_id']);
        $this->db->group_start();
        $this->db->or_where('type_name', 'user');
        $this->db->or_where('type_name', 'bot');
        $this->db->group_end();
        if (isset($post["order"]) && !empty($post["order"])) {
            $this->db->order_by(['id', 'type_name', 'data', 'id'][$post['order']['0']['column']], $post['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'ASC');
        }
    }

    function conversation_details($post)
    {
        $this->make_conversation_details_query($post);
        if ($post["length"] != -1) {
            $this->db->limit($post['length'], $post['start']);
        }
        return $this->db->get()->result();
    }

    function get_filtered_conversation($post)
    {
        $this->make_conversation_details_query($post);
        return $this->db->get()->num_rows();
    }

    function get_all_conversation_data($sender_id)
    {
        return $this->db->select('*')->from('events')->where(['sender_id' => $sender_id, 'type_name' => 'bot'])->or_where('type_name', 'user')->count_all_results();
    }
}
