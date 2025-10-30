<?php

class Approve_Revise_Report_Model extends MY_Model
{
    function make_query($post)
    {
        $role_id = $this->session->userdata('user_data')->id_admin_role;
        // For Super Admin
        if ($role_id == 43) {
            $this->db->select(['gr.report_id', 'gr.report_num', '(CONCAT(u.admin_fname, " ", u.admin_lname)) as user_name', 'gr.gr_revise_type', 'gr.gr_revise_reason', 'gr.sample_reg_id']);
            $this->db->from('generated_reports gr');
            $this->db->join('admin_profile u', 'gr.gr_revise_requester_id = u.uidnr_admin', 'left');
            $this->db->where(['gr.gr_revise_flag' => 1]);
            $this->db->group_by('gr.report_id');
            if (isset($post['search']['value']) && !empty($post['search']['value'])) {
                $this->db->like('gr.report_num', $post['search']['value']);
                $this->db->or_like('CONCAT(u.admin_fname, " ", u.admin_lname)', $post["search"]["value"]);
            }
            if (isset($post["order"])) {
                $this->db->order_by(['gr.report_id', 'gr.report_num', 'gr.gr_revise_requester_id', 'gr.gr_revise_type'][$post['order']['0']['column']], $post['order']['0']['dir']);
            } else {
                $this->db->order_by('gr.report_id', 'DESC');
            }
        } else {
            $division_id = $this->session->userdata('user_data')->default_division_id;
            if (!empty($division_id)) {
                $this->db->select(['gr.report_id', 'gr.report_num', '(CONCAT(u.admin_fname, " ", u.admin_lname)) as user_name', 'gr.gr_revise_type', 'gr.gr_revise_reason', 'gr.sample_reg_id']);
                $this->db->from('generated_reports gr');
                $this->db->join('sample_registration sr', 'gr.sample_reg_id = sr.sample_reg_id', 'inner');
                $this->db->join('admin_users ad', 'sr.division_id = ad.default_division_id', 'inner');
                $this->db->join('admin_profile u', 'gr.gr_revise_requester_id = u.uidnr_admin', 'left');
                $this->db->where(['gr.gr_revise_flag' => 1, 'sr.division_id' => $division_id]);
                $this->db->group_by('gr.report_id');
                if (isset($post['search']['value']) && !empty($post['search']['value'])) {
                    $this->db->like('gr.report_num', $post['search']['value']);
                    $this->db->or_like('CONCAT(u.admin_fname, " ", u.admin_lname)', $post["search"]["value"]);
                }
                if (isset($post["order"])) {
                    $this->db->order_by(['gr.report_id', 'gr.report_num', 'gr.gr_revise_requester_id', 'gr.gr_revise_type'][$post['order']['0']['column']], $post['order']['0']['dir']);
                } else {
                    $this->db->order_by('gr.report_id', 'DESC');
                }
            } else {
                return false;
            }
        }
    }

    function fetch_records($post)
    {
        $this->make_query($post);
        if ($post["length"] != -1) {
            $this->db->limit($post['length'], $post['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_filtered_data($post)
    {
        $this->make_query($post);
        return $this->db->get()->num_rows();
    }

    function get_all_data($table)
    {
        return $this->db->select('*')->from($table)->where('gr_revise_flag', 1)->count_all_results();
    }

    public function report_log($record_id)
    {
        $query = $this->db->select('CONCAT(u.admin_fname, " ", u.admin_lname) as full_name, c.text as action_message, DATE_FORMAT(c.created_on, "%d-%b-%Y %r") as activity_on')
            ->join('admin_profile u', 'c.created_by = u.uidnr_admin', 'left')
            ->where(['c.record_id' => $record_id, 'source_module' => 'Manage_lab'])
            ->order_by('c.id', 'DESC')
            ->get('user_log_history c');
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

    public function regenerate_sample($sample_reg_id, $report_id)
    {
        $data = array(
            'status'                => 'Retest',
            'revise_flag'           => '1',
            'released_to_client'    => '0'
        );
        $update_sample = $this->db->where('sample_reg_id', $sample_reg_id)->update('sample_registration', $data);

        $this->db->where('report_id', $report_id)->update('generated_reports', ['revise_report' => '1']);

        if ($update_sample) {
            $update_test = $this->db->where('sample_test_sample_reg_id', $sample_reg_id)->update('sample_test', ['status' => 'Retest']);
            return ($update_test) ? true : false;
        } else {
            return false;
        }
    }

    public function additional_test($sample_reg_id, $report_id)
    {
        $data = array(
            'status'                => 'Sample Sent for Evaluation',
            'revise_flag'           => '1',
            'released_to_client'    => '0'
        );
        $update_sample = $this->db->where('sample_reg_id', $sample_reg_id)->update('sample_registration', $data);

        $this->db->where('report_id', $report_id)->update('generated_reports', ['additional_report_flag' => 1]);

        if ($update_sample) {
            $update_test = $this->db->where('sample_test_sample_reg_id', $sample_reg_id)->update('sample_test', ['status' => 'Record Enter Done']);
            return ($update_test) ? true : false;
        } else {
            return false;
        }
    }
}
