<?php

use phpDocumentor\Reflection\Types\Object_;

defined('BASEPATH') or exit('No direct script access allowed');
class Quotes_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db->trans_start();
  }

  public function __destruct()
  {
    $this->db->trans_complete();
  }

  public function get_quotes_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
  {

    if ($sortby != NULL || $order != NULL) {
      $sortby = base64_decode($sortby);
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('qt.quote_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }


    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('cus.customer_name', $search);
      $this->db->or_like('qt.quote_status', $search);
      $this->db->or_like('qt.customer_type', $search);
      $this->db->or_like('qt.reference_no', $search);
      $this->db->or_like('CONCAT(ap.admin_fname,"",ap.admin_lname)', $search);
      $this->db->or_like('DATE_FORMAT(qt.created_on,"%d-%b-%Y")', $search);
      $this->db->or_like('ROUND(ROUND(qt.quote_value+( qt.quote_value*18/100)),2)', $search);
      $this->db->or_like('DATE_FORMAT(qt.quote_date, "%d-%b-%Y")', $search);
      $this->db->group_end();
    }

    $this->db->select('qt.quote_id,cus.customer_name,qt.quotes_opportunity_id,qt.quotes_customer_id,qt.customer_type,qt.reference_no,DATE_FORMAT(qt.quote_date, "%d-%b-%Y") as quote_date, qt.quote_value as quote_value ,qt.quote_status,qt.template_id,DATE_FORMAT(qt.created_on,"%d-%b-%Y") as created_on,version_number as version,job_no_po,attach_file,CONCAT(ap.admin_fname," ",ap.admin_lname) as created_by,qt.reference_no as reference_no,qt.quotes_opportunity_name as opportunity_name,qt.quotes_contact_id as quotes_contact_id,qt.discussion_date as discussion_date,qt.quote_valid_date as quote_valid_date,qt.quotes_currency_id as currency_id,qt.quote_signing_authority_designation_id as desgination_id,qt.quotes_signing_authority_id as approver_id,qt.approve_pdf_path,qt.show_discount,qt.quotes_branch_id, trf_id');
    $this->db->from('quotes qt');
    $this->db->join('admin_profile ap', 'qt.created_by = ap.uidnr_admin', 'left');
    $this->db->join('cust_customers cus', 'cus.customer_id=qt.quotes_customer_id', 'left');
    $this->db->join('trf_registration', 'FIND_IN_SET(quote_id,trf_quote_id) > 0', 'left', false);
    //$this->db->where('cus.isactive','Active');
    $this->db->limit($limit, $start);
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      if ($count != NULL) {
        return $result->num_rows();
      } else {
        return $result->result();
      }
    } else {
      return false;
    }
  }


  public function insert_data_into_table($table, $data = array())
  {
    if ($data && count($data)) {
      $result =  $this->db->insert($table, $data);
      if ($result) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function get_test_details($sample_type_id = NULL, $currency_id = NULL, $test_ids = array())
  {

    $this->db->select("ts.test_id as id,concat(replace(ts.test_name,',',''),'(',replace(replace(ts.test_method,'.',''),',',''),')') as name,if(price.price is NULL,0,price.price) as price,ts.test_method as test_method, div.division_name as work_division_name,ts.test_division_id as test_division_id");
    $this->db->from('tests ts');
    $this->db->join('test_sample_type stm', 'ts.test_id = stm.test_sample_type_test_id', 'left');
    $this->db->join('mst_divisions div', 'div.division_id=ts.test_division_id', 'left');
    $this->db->join('pricelist price', 'price.pricelist_test_id=ts.test_id AND price.currency_id="' . $currency_id . '"', 'left');
    if ($sample_type_id != NULL) {
      $this->db->where('stm.test_sample_type_sample_type_id', $sample_type_id);
      $this->db->where('ts.test_status', 'Active');
      $this->db->order_by('ts.test_name', 'ASC');
      $this->db->group_by('ts.test_id', 'ASC');
      $result = $this->db->get();
    } else {
      if ($test_ids && count($test_ids) > 0) {
        $this->db->where_in('ts.test_id', $test_ids);
        $this->db->where('ts.test_status', 'Active');
        $this->db->order_by('ts.test_name', 'ASC');
        $this->db->group_by('ts.test_id', 'ASC');
        $result = $this->db->get();
      } else {
        return false;
      }
    }


    // echo $this->db->last_query(); die;
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }



  public function get_test_details_division_wise($sample_type_id = array(), $currency_id = NULL, $div_id = array(), $test_id = NULL)
  {
    $this->db->select('ts.test_id as test_id,ts.test_name as test_name,pl.price as price,ts.test_method as test_method,ts.test_division_id as test_division_id,stm.test_sample_type_sample_type_id as work_sample_type_id,mst.sample_type_name as work_sample_name,msd.division_name as test_division_name');
    $this->db->from('tests ts');
    $this->db->join('test_sample_type stm', 'ts.test_id=stm.test_sample_type_test_id', 'left');
    $this->db->join('pricelist pl', 'pl.pricelist_test_id=ts.test_id and pl.currency_id=' . $currency_id, 'left');
    $this->db->join('mst_sample_types mst', 'mst.sample_type_id=stm.test_sample_type_sample_type_id', 'left');
    $this->db->join('mst_divisions msd', 'msd.division_id=ts.test_division_id', 'left');
    $this->db->where('ts.test_status', 'Active');
    $this->db->where_in('stm.test_sample_type_sample_type_id', $sample_type_id);
    $this->db->where_in('ts.test_division_id', $div_id);

    $this->db->group_by('ts.test_id');
    $this->db->order_by('ts.test_name', 'ASC');
    if ($test_id != NULL) {
      $this->db->where_in('ts.test_id', $test_id);
    }
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  public function get_BASIL_employee($designation)
  {
    $this->db->select("admin_profile.uidnr_admin as uidnr_admin,CONCAT(admin_profile.admin_fname,' ',admin_profile.admin_lname) as name");
    $this->db->from('admin_profile');
    $this->db->join('operator_profile', 'operator_profile.uidnr_admin=admin_profile.uidnr_admin', 'left');
    $this->db->join('admin_users', 'admin_users.uidnr_admin=admin_profile.uidnr_admin AND admin_users.admin_active="1"', 'left');
    $this->db->where('operator_profile.admin_designation', $designation);

    // $this->db->where('admin_profile.uidnr_admin','109');


    $data = $this->db->get();
    if ($data->num_rows() > 0) {
      return $data->result();
    } else {
      return false;
    }
  }

  public function insert_quotes($data)
  {
    $data_ = NULL;
    $work_analysis_test = NULL;
    $work = NULL;
    $work_ids = NULL;
    $package_test_data = NULL;

    if (array_key_exists('data', $data)) {
      $data_ = $data['data'];

      if ($data_ && count($data_) > 0) {

        $this->db->insert('quotes', $data_);
        $quote_id = $this->db->insert_id();
        if ($quote_id) {
          if (array_key_exists('work', $data)) {
            $work = $data['work'];
            if ($work && count($work) > 0) {
              foreach ($work as $key => $value) {
                $work[$key]['work_job_type_id'] = $quote_id;
              }

              $result = $this->insert_multiple_data('works', $work);
              if ($result) {
                $this->db->select('work_id');
                $this->db->from('works');
                $this->db->where('work_job_type_id', $quote_id);
                $this->db->where('product_type', "Test");
                $result = $this->db->get();

                if ($result->num_rows() > 0) {
                  $work_ids = $result->result_array();
                  // $work_ids = $this->makeNumericArray($work_ids, 'work_id');
                  if ($work_ids && count($work_ids) > 0) {

                    if (array_key_exists('work_analysis_test', $data)) {
                      $work_analysis_test = $data['work_analysis_test'];
                      if ($work_analysis_test && count($work_analysis_test > 0)) {

                        $i = 0;
                        foreach ($work_analysis_test as $key => $value) {
                          $work_analysis_test[$key]['work_id'] = $work_ids[$i]['work_id'];
                          $i++;
                        }
                        // echo "<pre>"; print_r($work_analysis_test); 
                        $result = $this->insert_multiple_data('works_analysis_test', $work_analysis_test);
                      }
                    }
                  }
                }
              }
            }
          }
          // die;
          if (array_key_exists('work_analysis_pck', $data)) {
            $work_analysis_pck = $data['work_analysis_pck'];
            if ($work_analysis_pck && count($work_analysis_pck) > 0) {
              $result = $this->insert_package_proto($work_analysis_pck, $quote_id, $data);
            }
          }

          if (array_key_exists('work_analysis_protocol', $data)) {
            $work_analysis_protocol = $data['work_analysis_protocol'];
            if ($work_analysis_protocol && count($work_analysis_protocol) > 0) {
              $result = $this->insert_protocols_details($work_analysis_protocol, $quote_id, $data);
            }
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }

    if ($result) {
      $confiq['mst_branch_id'] = $where2['branch_id'] = $data_['quotes_branch_id'];
      $this->db->select_max('mst_serail_no');
      $this->db->from('mst_quote_seral_number');
      $this->db->where('mst_branch_id', $data_['quotes_branch_id']);
      $result =  $this->db->get()->row();

      if ($result) {
        $quote_number_q = $result->mst_serail_no;
        $quote_number_q = $quote_number_q + 1;
        $rand = str_pad($quote_number_q, 5, "0", STR_PAD_LEFT);
        $confiq['mst_serail_no'] = $quote_number_q;
        $confiq['created_on'] = date("Y-m-d H:i:s");
        $confiq['created_by'] = $this->user;
        $result = $this->db->insert('mst_quote_seral_number', $confiq);
      } else {
        return false;
      }

      if ($result) {
        $branch = $this->get_row('branch_code', 'mst_branches', $where2);

        if ($branch) {
          $res['reference_no'] = 'GC/' . $branch->branch_code . '/' . date('Y') . '/' . $rand;
          $this->db->where('quote_id', $quote_id);
          $result = $this->db->update('quotes', $res);

          if ($result) {
            $this->db->where('work_job_type_id', $quote_id);
            $result = $this->db->update('works', $res);
          } else {
            return false;
          }

          if ($result) {
            $logDetails = array(
              'module' => 'sales',
              'old_status' => '',
              'new_status' => $data_['quote_status'],
              'action_message' => 'Quote Created',
              'quote_id' => $quote_id,
              'uidnr_admin' => $data_['created_by']
            );

            $status = $this->insert_data('sales_activity_log', $logDetails);
            if ($status) {
              return $res;
            } else {
              return false;
            }
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function update_quotes($data, $quote_id)
  {

    $package_test_data =  $work_analysis_test = $work = $work_analysis_pck = $work_perameter =  NULL;
    if (array_key_exists('data', $data)) {
      $updateData = $data['data'];

      if ($updateData && count($updateData) > 0) {
        $msg = $updateData['msg'];
        unset($updateData['msg']);
        $updateData['updated_on'] = date("Y-m-d");
        $updateData['updated_by'] = $this->user;
        $updateData['quote_status'] = "Draft";
        $where_quote['quote_id'] = $quote_id;
        $version_no = $this->get_row('version_number', 'quotes', $where_quote);
        $version_no = $version_no->version_number;
        if (empty($version_no)) {
          $version_no = 1;
        } else {
          $version_no += 1;
        }
        $updateData['version_number'] = $version_no;
        $this->db->where('quote_id', $quote_id);
        $result = $this->db->update('quotes', $updateData);
        if ($result) {


          $this->db->where('work_job_type_id', $quote_id);
          $this->db->delete('works');

          if (array_key_exists('work', $data)) {
            $work = $data['work'];
            if ($work && count($work) > 0) {
              foreach ($work as $key => $value) {
                $work[$key]['work_job_type_id'] = $quote_id;
              }

              $result = $this->insert_multiple_data('works', $work);


              if ($result) {
                $this->db->select('work_id');
                $this->db->from('works');
                $this->db->where('work_job_type_id', $quote_id);
                $this->db->where('product_type', 'Test');
                $result = $this->db->get();
                if ($result->num_rows() > 0) {
                  $work_ids = $result->result_array();
                  if ($work_ids && count($work_ids) > 0) {
                    $work_ids = $this->makeNumericArray($work_ids, 'work_id');
                    if (array_key_exists('work_analysis_test', $data)) {
                      $work_analysis_test = $data['work_analysis_test'];

                      if ($work_analysis_test && count($work_analysis_test) > 0) {
                        $i = 0;
                        foreach ($work_analysis_test as $key => $value) {
                          $work_analysis_test[$key]['work_id'] = $work_ids[$i];
                          $i++;
                        }
                        $result = $this->insert_multiple_data('works_analysis_test', $work_analysis_test);
                      } else {
                        return false;
                      }
                    } else {
                      return false;
                    }
                  } else {
                    return false;
                  }
                } else {
                  return false;
                }
              }
            }
          }


          if (array_key_exists('work_analysis_pck', $data)) {
            $work_analysis_pck = $data['work_analysis_pck'];

            if ($work_analysis_pck && count($work_analysis_pck) > 0) {
              $this->insert_multiple_data('works', $work_analysis_pck);
              $this->db->select('work_id');
              $this->db->from('works');
              $this->db->where('work_job_type_id', $quote_id);
              $this->db->where('product_type', "Package");
              $result = $this->db->get();
              if ($result->num_rows() > 0) {
                $work_ids = $result->result_array();
                if ($work_ids && count($work_ids) > 0) {
                  $work_ids = $this->makeNumericArray($work_ids, 'work_id');
                  $result =  $this->edit_package_proto($data, $work_ids);
                } else {
                  $result = $this->insert_package_proto($work_analysis_pck, $quote_id, $data);
                }
              } else {
                $result = $this->insert_package_proto($work_analysis_pck, $quote_id, $data);
              }
            }
          }

          if (array_key_exists('work_analysis_protocol', $data)) {
            $work_analysis_protocol = $data['work_analysis_protocol'];

            if ($work_analysis_protocol && count($work_analysis_protocol) > 0) {

              $result = $this->insert_multiple_data('works', $work_analysis_protocol);
              if ($result) {
                $this->db->select('work_id');
                $this->db->from('works');
                $this->db->where('work_job_type_id', $quote_id);
                $this->db->where('product_type', "Protocol");
                $result = $this->db->get();
                if ($result->num_rows() > 0) {
                  $work_ids = $result->result_array();
                  if ($work_ids && count($work_ids) > 0) {
                    $work_ids = $this->makeNumericArray($work_ids, 'work_id');
                    $result =  $this->edit_proto($data, $work_ids);
                  } else {
                    $result = $this->insert_protocols_details($work_analysis_protocol, $quote_id, $data);
                  }
                } else {
                  $result = $this->insert_protocols_details($work_analysis_protocol, $quote_id, $data);
                }
              }
            }
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }

    if ($result) {

      if ($msg == 'EDIT') {
        $logDetails = array(
          'module' => 'sales',
          'old_status' => 'Draft',
          'new_status' => $updateData['quote_status'],
          'action_message' => 'Quote Updated',
          'quote_id' => $quote_id,
          'uidnr_admin' => $updateData['created_by']
        );
        $status = $this->insert_data('sales_activity_log', $logDetails);
      }
      if ($msg == 'REVISE') {
        $logDetails = array(
          'module' => 'sales',
          'old_status' => 'Approved',
          'new_status' => $updateData['quote_status'],
          'action_message' => 'Quote Revised',
          'quote_id' => $quote_id,
          'uidnr_admin' => $updateData['created_by']
        );
        $revision = array(
          'revised_by' => $this->user,
          'revised_on' => date("Y-m-d H:i:s"),
          'quote_id' => $quote_id,
          'version_number' => $version_no,
        );
        $status = $this->db->insert('quote_revise_history', $revision);
        if ($status) {
          $status = $this->insert_data('sales_activity_log', $logDetails);
        }
      }


      if ($status) {
        return true;
      } else {
        return false;
      }
    }
  }


  public function edit_proto($data, $work_ids = array())
  {
    if (array_key_exists('package_test_data', $data)) {
      $protocol_test_data = $data['protocol_test_data'];

      if ($protocol_test_data && count($protocol_test_data) > 0) {

        $i = 0;
        foreach ($protocol_test_data as $key => $value) {
          $protocol_test_data[$i]['work_id'] = $work_ids[$i];
          $this->db->where('work_analysis_type', 'Protocol');
          $this->db->where('work_id', $work_ids[$i]);
          $this->db->delete('work_analysis_test_parameters');
          $i++;
        }
        $result = $this->insert_multiple_data('works_analysis_package', $protocol_test_data);
        if ($result) {
          if (array_key_exists('work_perameter_protocol', $data)) {
            $work_perameter_protocol = $data['work_perameter_protocol'];
            if ($work_perameter_protocol && count($work_perameter_protocol) > 0) {
              $key = 0;
              foreach ($work_perameter_protocol as $key => $value) {
                $work_perameter_protocol[$key]['work_id'] = $work_ids[$key];
                $key++;
              }
              $result = $this->insert_multiple_data('work_analysis_test_parameters', $work_perameter_protocol);
              if ($result) {
                return true;
              } else {
                return false;
              }
            } else {
              return false;
            }
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function edit_package_proto($data, $work_ids = array())
  {
    if (array_key_exists('package_test_data', $data)) {
      $package_test_data = $data['package_test_data'];

      if ($package_test_data && count($package_test_data) > 0) {

        $i = 0;
        foreach ($package_test_data as $key => $value) {
          $package_test_data[$i]['work_id'] = $work_ids[$i];
          $this->db->where('work_analysis_type', 'Package');
          $this->db->where('work_id', $work_ids[$i]);
          $this->db->delete('work_analysis_test_parameters');
          $i++;
        }
        $result = $this->insert_multiple_data('works_analysis_package', $package_test_data);
        if ($result) {
          if (array_key_exists('work_perameter', $data)) {
            $work_perameter = $data['work_perameter'];
            if ($work_perameter && count($work_perameter) > 0) {
              $key = 0;
              foreach ($work_perameter as $key => $value) {
                $work_perameter[$key]['work_id'] = $work_ids[$key];
                $key++;
              }
              $result = $this->insert_multiple_data('work_analysis_test_parameters', $work_perameter);
              if ($result) {
                return true;
              } else {
                return false;
              }
            } else {
              return false;
            }
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function insert_package_proto($work_analysis_pck = array(), $quote_id, $data = array())
  {
    if ($work_analysis_pck && count($work_analysis_pck) > 0) {
      foreach ($work_analysis_pck as $key => $value) {
        $work_analysis_pck[$key]['work_job_type_id'] = $quote_id;
        $where_q['quote_id'] = $quote_id;
        $ref_no = $this->get_row('reference_no', 'quotes', $where_q);
        $work_analysis_pck[$key]['reference_no'] = $ref_no->reference_no;
      }
      $this->insert_multiple_data('works', $work_analysis_pck);
      $this->db->select('work_id');
      $this->db->from('works');
      $this->db->where('work_job_type_id', $quote_id);
      $this->db->where('product_type', "Package");
      $result = $this->db->get();
      if ($result->num_rows() > 0) {
        $work_ids = $result->result_array();

        if ($work_ids && count($work_ids) > 0) {
          $work_ids = $this->makeNumericArray($work_ids, 'work_id');

          if (array_key_exists('package_test_data', $data)) {
            if ($data['package_test_data'] && count($data['package_test_data'] > 0)) {
              $package_test_data = $data['package_test_data'];
              $i = 0;

              foreach ($package_test_data as $key => $value) {
                $package_test_data[$i]['work_id'] = $work_ids[$i];
                $this->db->where('work_analysis_type', 'Package');
                $this->db->where('work_id', $work_ids[$i]);
                $this->db->delete('work_analysis_test_parameters');

                $i++;
              }

              if (array_key_exists('work_perameter', $data)) {
                $work_perameter = $data['work_perameter'];
                if ($work_perameter && count($work_perameter) > 0) {

                  $key = 0;
                  foreach ($work_perameter as $key => $value) {
                    $work_perameter[$key]['work_id'] = $work_ids[$key];
                    $key++;
                  }
                  $result = $this->insert_multiple_data('works_analysis_package', $package_test_data);
                  if ($result) {
                    $result = $this->insert_multiple_data('work_analysis_test_parameters', $work_perameter);
                    if ($result) {
                      return true;
                    } else {
                      return false;
                    }
                  } else {
                    return false;
                  }
                }
              } else {
                return false;
              }
            } else {
              return false;
            }
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  public function  insert_protocols_details($work_analysis_protocol = array(), $quote_id, $data = array())
  {

    if ($work_analysis_protocol && count($work_analysis_protocol) > 0) {
      foreach ($work_analysis_protocol as $key => $value) {
        $work_analysis_protocol[$key]['work_job_type_id'] = $quote_id;
        $where_q['quote_id'] = $quote_id;
        $ref_no = $this->get_row('reference_no', 'quotes', $where_q);
        $work_analysis_protocol[$key]['reference_no'] = $ref_no->reference_no;
      }

      $this->insert_multiple_data('works', $work_analysis_protocol);
      $this->db->select('work_id');
      $this->db->from('works');
      $this->db->where('work_job_type_id', $quote_id);
      $this->db->where('product_type', "Protocol");
      $result = $this->db->get();
      if ($result->num_rows() > 0) {
        $work_ids = $result->result_array();

        if ($work_ids && count($work_ids) > 0) {
          $work_ids = $this->makeNumericArray($work_ids, 'work_id');

          if (array_key_exists('protocol_test_data', $data)) {
            $protocol_test_data = $data['protocol_test_data'];
            if ($protocol_test_data && count($protocol_test_data > 0)) {

              $i = 0;

              foreach ($protocol_test_data as $key => $value) {
                $protocol_test_data[$i]['work_id'] = $work_ids[$i];
                $this->db->where('work_analysis_type', 'Protocol');
                $this->db->where('work_id', $work_ids[$i]);
                $this->db->delete('work_analysis_test_parameters');

                $i++;
              }

              if (array_key_exists('work_perameter_protocol', $data)) {
                $work_perameter_protocol = $data['work_perameter_protocol'];

                if ($work_perameter_protocol && count($work_perameter_protocol) > 0) {

                  $key = 0;
                  foreach ($work_perameter_protocol as $key => $value) {
                    $work_perameter_protocol[$key]['work_id'] = $work_ids[$key];
                    $key++;
                  }
                  $result = $this->insert_multiple_data('works_analysis_package', $protocol_test_data);

                  if ($result) {
                    $result = $this->insert_multiple_data('work_analysis_test_parameters', $work_perameter_protocol);
                    if ($result) {
                      return true;
                    } else {
                      return false;
                    }
                  } else {
                    return false;
                  }
                }
              } else {
                return false;
              }
            } else {
              return false;
            }
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  public function get_sample_types($division_ids)
  {
    $this->db->select('DISTINCT(sample_type_id) as sample_type_id');
    $this->db->from('tests');
    $this->db->join('test_sample_type stm', 'tests.test_id = stm.test_sample_type_test_id', 'left');
    $this->db->join('mst_sample_types st', 'st.sample_type_id=stm.test_sample_type_sample_type_id', 'left');
    $this->db->where_in('test_division_id', $division_ids);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->result_array();
    } else {
      return false;
    }
  }

  public function get_sample_name($sampleTypes)
  {
    $this->db->select('sample_type_id,sample_type_name');
    $this->db->from('mst_sample_types');
    $this->db->where_in('sample_type_id', $sampleTypes);
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      return $result->result_array();
    } else {
      return false;
    }
  }

  public function makeNumericArray($data = array(), $select)
  {
    $arr = [];
    if ($data && count($data) > 0) {
      foreach ($data as $key => $value) {
        array_push($arr, $value[$select]);
      }
      return $arr;
    } else {
      return false;
    }
  }

  public function get_quote_data($where)
  {
    $this->db->select('qt.quote_id,cus.customer_name,qt.quotes_opportunity_id,qt.quotes_customer_id,qt.customer_type as quote_customer_type,qt.reference_no,DATE_FORMAT(qt.quote_date, "%d-%b-%Y") as quote_date,ROUND(ROUND(qt.quote_value+( qt.quote_value*18/100)),2) AS quote_value ,qt.quote_status,qt.template_id,DATE_FORMAT(qt.created_on,"%d-%b-%Y") as created_on,version_number as version,job_no_po,attach_file,CONCAT(ap.admin_fname," ",ap.admin_lname) as quote_created_user,qt.reference_no as reference_no,qt.quotes_opportunity_name as opportunity_name,qt.quotes_contact_id as quotes_contact_id,DATE_FORMAT(qt.discussion_date, "%d-%b-%Y") as discussion_date,DATE_FORMAT(qt.quote_valid_date, "%d-%b-%Y") as quote_valid_date,qt.quotes_currency_id as currency_id,qt.quote_signing_authority_designation_id as desgination_id,qt.quotes_signing_authority_id as approver_id,qt.payment_terms,qt.sample_retention,qt.additional_notes,qt.terms_conditions,qt.salutation,qt.quote_subject,GROUP_CONCAT(wt.work_test_id) as test_id,GROUP_CONCAT(wt.rate_per_test) as rate_per_test,GROUP_CONCAT(wt.original_cost) as original_cost,GROUP_CONCAT(wt.original_cost) as original_cost,GROUP_CONCAT(wt.total_cost) as total_cost,GROUP_CONCAT(wt.discount) as discount,GROUP_CONCAT(wt.applicable_charge) as applicable_charge,con.*,country.*,op.admin_designation as admin_designation,admin_des.designation_name as designation_name,ae.admin_email as admin_email,aprover_ap.admin_email as approver_email,ap.admin_telephone as admin_telephone,approver.admin_telephone as approver_telephone,GROUP_CONCAT(works.works_sample_type_id ORDER BY works.work_id ASC) as sample_type_id,GROUP_CONCAT(works.work_sample_name ORDER BY works.work_id ASC) as sample_type_name,qt.version_number,quote_desg.designation_name as approver_designation_name,CONCAT(approver.admin_fname," ",approver.admin_lname) as approver,currency.currency_name as quotes_currency_name,(SELECT GROUP_CONCAT(wap.work_package_id) AS package_id FROM works_analysis_package AS wap
    LEFT JOIN works AS wa ON wa.work_id = wap.work_id LEFT JOIN quotes AS quote ON quote.quote_id = wa.work_job_type_id WHERE wa.product_type = "Package" and quote.quote_id=' . $where["quote_id"] . ') as package_id,(SELECT GROUP_CONCAT(wap2.work_package_id) AS protocol_id FROM works_analysis_package AS wap2
    LEFT JOIN works AS wa2 ON wa2.work_id = wap2.work_id LEFT JOIN quotes AS quote2 ON quote2.quote_id = wa2.work_job_type_id WHERE wa2.product_type = "Protocol" and quote2.quote_id=' . $where["quote_id"] . ') as protocol_id,qt.show_discount,admin_sig.sign_path as admin_signature,approver_sig.sign_path as approver_signature,qt.buyer_self_ref,qt.quotes_branch_id,qt.about_us_details,qt.notes_details,qt.terms_details,qt.contact_details,qt.allow_about_us,qt.show_test_method,qt.show_price,qt.introduction,qt.show_division,qt.show_total_amount,qt.show_products');

    $this->db->from('quotes qt');
    $this->db->join('admin_profile ap', 'qt.created_by = ap.uidnr_admin', 'left');
    $this->db->join('mst_currency currency', 'currency.currency_id=qt.quotes_currency_id', 'left');
    $this->db->join('admin_profile approver', 'qt.quotes_signing_authority_id = approver.uidnr_admin', 'left');
    $this->db->join('cust_customers cus', 'cus.customer_id=qt.quotes_customer_id', 'left');
    $this->db->join('works', 'works.work_job_type_id=qt.quote_id', 'left');
    $this->db->join('works_analysis_test wt', 'works.work_id=wt.work_id', 'left');
    $this->db->join('works_analysis_package wp', 'works.work_id=wp.work_id', 'left');
    $this->db->join('contacts con', 'con.contact_id=qt.quotes_contact_id', 'left');
    $this->db->join('mst_country country', 'country.country_id=qt.quotes_country_id', 'left');
    $this->db->join('operator_profile op', 'op.uidnr_admin =qt.created_by', 'left');
    $this->db->join('mst_designations admin_des', 'admin_des.designation_id=op.admin_designation', 'left');
    $this->db->join('admin_users ae', 'ae.uidnr_admin=qt.created_by', 'left');
    $this->db->join('admin_users aprover_ap', 'aprover_ap.uidnr_admin=qt.quotes_signing_authority_id', 'left');
    $this->db->join('mst_designations quote_desg', 'quote_desg.designation_id=qt.quote_signing_authority_designation_id', 'left');


    $this->db->join('admin_signature admin_sig', 'admin_sig.admin_id=qt.created_by', 'left');
    $this->db->join('admin_signature approver_sig', 'approver_sig.admin_id=qt.quotes_signing_authority_id', 'left');

    $this->db->where($where);

    $result = $this->db->get();


    if ($result->num_rows() > 0) {
      return $result->row();
    } else {
      return false;
    }
  }

  // concat(replace(ts.test_name,',',''),'(',replace(replace(ts.test_method,'.',''),',',''),')')

  public function get_tests_data_Testing($quote_id)
  {
    $this->db->select("wt.work_test_id as id,wt.work_test_id as test_id,ts.test_name as name,ts.test_name as test_name,wt.rate_per_test as price,wt.rate_per_test as rate_per_test,ts.test_method as test_method,div.division_name as work_division_name,ts.test_division_id as test_division_id,wt.original_cost as original_cost,wt.original_cost as original_cost,wt.total_cost as total_cost,wt.discount as discount,wt.applicable_charge as applicable_charge,works.works_sample_type_id as sample_type_id,works.work_sample_name as sample_type_name, works.work_id");
    $this->db->from('quotes qt');
    $this->db->join('works', 'works.work_job_type_id = qt.quote_id', 'left');
    $this->db->join('works_analysis_test wt', 'works.work_id = wt.work_id', 'left');
    $this->db->join('tests ts', 'ts.test_id = wt.work_test_id', 'left');
    $this->db->join('mst_divisions div', 'div.division_id = ts.test_division_id', 'left');
    $this->db->where('qt.quote_id', $quote_id);
    $this->db->where('product_type','Test');
    $this->db->where('wt.work_test_id!=', 'NULL');
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  public function get_tests_data_package($quote_id)
  {
    $this->db->select("wt.work_test_id as id,wt.work_test_id as test_id,ts.test_name as name,ts.test_name as test_name,wt.rate_per_test as price,wt.rate_per_test as rate_per_test,ts.test_method as test_method,div.division_name as work_division_name,ts.test_division_id as test_division_id,works.works_sample_type_id as sample_type_id,works.work_sample_name as sample_type_name, rate_per_package as rate, works.discount, works.total_cost as total_cost, works_sample_type_id, product_type_id as package_id, works.work_id");
    $this->db->from('quotes qt');
    $this->db->join('works', 'works.work_job_type_id = qt.quote_id', 'left');
    $this->db->join('works_analysis_test wt', 'works.work_id = wt.work_id', 'left');
    $this->db->join('tests ts', 'ts.test_id = wt.work_test_id', 'left');
    $this->db->join('mst_divisions div', 'div.division_id = ts.test_division_id', 'left');
    $this->db->where('qt.quote_id', $quote_id);
    $this->db->where('product_type','Package');
    $this->db->where('wt.work_test_id!=', 'NULL');
    $this->db->order_by('works.total_cost','DESC');
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  public function get_tests_data_protocol($quote_id)
  {
    $this->db->select("wt.work_test_id as id,wt.work_test_id as test_id,ts.test_name as name,ts.test_name as test_name,wt.rate_per_test as price,wt.rate_per_test as rate_per_test,ts.test_method as test_method,div.division_name as work_division_name,ts.test_division_id as test_division_id,works.works_sample_type_id as sample_type_id,works.work_sample_name as sample_type_name, rate_per_package as rate, works.discount, works.total_cost as total_cost, works_sample_type_id, product_type_id as protocol_id, works.work_id");
    $this->db->from('quotes qt');
    $this->db->join('works', 'works.work_job_type_id = qt.quote_id', 'left');
    $this->db->join('works_analysis_test wt', 'works.work_id = wt.work_id', 'left');
    $this->db->join('tests ts', 'ts.test_id = wt.work_test_id', 'left');
    $this->db->join('mst_divisions div', 'div.division_id = ts.test_division_id', 'left');
    $this->db->where('qt.quote_id', $quote_id);
    $this->db->where('product_type','Protocol');
    $this->db->where('wt.work_test_id!=', 'NULL');
    $this->db->order_by('works.total_cost','DESC');
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }




  public function generate_quote($data, $where)
  {
    $status =  $this->update_data('quotes', $data, $where);

    if ($status) {

      $logDetails = array(
        'old_status' => 'Draft',
        'new_status' => $data['quote_status'],
        'action_message' => 'Quote Generated.',
        'quote_id' => $where['quote_id'],
        'uidnr_admin' => $this->user

      );

      $status = $this->insert_data('sales_activity_log', $logDetails);
      if ($status) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function approve_quotes($data, $where)
  {
    $status =  $this->update_data('quotes', $data, $where);
    if ($status) {

      $logDetails = array(
        'old_status' => 'Draft',
        'new_status' => $data['quote_status'],
        'action_message' => 'Quote Approved',
        'quote_id' => $where['quote_id'],
        'uidnr_admin' => QUOTE_APPROVER
      );

      $status = $this->insert_data('sales_activity_log', $logDetails);
      if ($status) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function get_packages_protocols($data)
  {

    if ($data['type'] == '1') {
      $this->db->select('packages.package_id as id ,packages.package_name as name,pricelist.price as price');
      $this->db->from('packages');
      $this->db->join('pricelist', 'pricelist.type_id=packages.package_id AND pricelist.type="Package" AND pricelist.currency_id=' . $data['currency_id'], 'left');
      $this->db->where('packages.packages_sample_type_id', $data['sample_type_id']);
      $this->db->order_by('packages.package_name', 'ASC');
      $result = $this->db->get();
    }
    if ($data['type'] == '2') {
      $this->db->select('protocols.protocol_id as id ,protocols.protocol_name as name,pricelist.price as price');
      $this->db->from('protocols');
      $this->db->join('pricelist', 'pricelist.type_id=protocols.protocol_id AND pricelist.type="Protocol" AND pricelist.currency_id=' . $data['currency_id'], 'left');
      $this->db->where('protocols.protocol_sample_type_id', $data['sample_type_id']);
      $this->db->order_by('protocols.protocol_name', 'ASC');
      $result = $this->db->get();
    }
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  public function get_tests_by_pac_id($test_id, $data)
  {
    // echo $test_id;
    // pre_r($data); die;
    if ($data['type'] == '1') {
      $this->db->select('ts.test_id,ts.test_name,ts.test_method,pricelist.price as test_price,ps.package_id,div.division_name as work_division_name,ts.test_division_id as test_division_id,ps.packages_sample_type_id as works_sample_type_id');
      $this->db->from('packages ps');
      $this->db->join('test_packages tp', 'tp.test_package_packages_id=ps.package_id', 'left');
      $this->db->join('tests ts', 'ts.test_id=tp.test_package_test_id', 'left');
      $this->db->join('mst_divisions div', 'div.division_id=ts.test_division_id', 'left');
      $this->db->join('pricelist price', 'price.pricelist_test_id=ts.test_id AND price.currency_id=' . $data['currency_id'], 'left');
      $this->db->join('pricelist', 'pricelist.type_id=ps.package_id AND pricelist.type="Package" AND pricelist.currency_id=' . $data['currency_id'], 'left');
      $this->db->where('ps.package_id', $test_id);
    }

    if ($data['type'] == "2") {
      $this->db->select('ts.test_id,ts.test_name,ts.test_method,pricelist.price as test_price,proto.protocol_id as package_id ,div.division_name as work_division_name,ts.test_division_id as test_division_id,proto.protocol_sample_type_id as works_sample_type_id');
      $this->db->from('protocols proto');
      $this->db->join('protocol_tests tp', 'tp.protocol_id=proto.protocol_id', 'left');
      $this->db->join('tests ts', 'ts.test_id=tp.protocol_test_id', 'left');
      $this->db->join('mst_divisions div', 'div.division_id=ts.test_division_id', 'left');
      $this->db->join('pricelist', 'pricelist.type_id=proto.protocol_id AND pricelist.type="Protocol" AND pricelist.currency_id=' . $data['currency_id'], 'left');
      $this->db->where('proto.protocol_id', $test_id);
    }
    $result = $this->db->get();
    // echo $this->db->last_query(); die;
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  function get_package_details_old($package_id = array(), $quote_id)
  {
    $this->db->select('perameter.work_id as work_id,wp.rate_per_package as rate, wp.original_cost as cost,wp.total_cost as total_cost, perameter.parameter_id as test_id,work.discount as discount');

    $this->db->from('work_analysis_test_parameters perameter');
    $this->db->join('works work', 'work.work_id=perameter.work_id', 'left');
    $this->db->join('works_analysis_package wp', 'wp.work_id=perameter.work_id', 'left');

    $this->db->where('work.work_job_type_id', $quote_id);
    $this->db->where_in('work_analysis_test_id', $package_id);
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  function get_package_details($quote_id)
  {
    $this->db->select('wat.work_id as work_id,works.rate_per_package as rate, works.original_cost as cost,works.total_cost as total_cost, wat.work_test_id as test_id,works.discount as discount, product_type_id as package_id');    
    $this->db->join('works_analysis_test wat','works.work_id = wat.work_id');
    $this->db->where('product_type','Package');
    $this->db->where('works.work_job_type_id', $quote_id);
    $result = $this->db->get('works');
    // echo $this->db->last_query(); die;
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  function get_protocol_details_old($protocol_id = array(), $quote_id)
  {
    $this->db->select('perameter.work_id as work_id,wp.rate_per_package as rate, wp.original_cost as cost,wp.total_cost as total_cost, perameter.parameter_id as test_id,work.discount as discount');

    $this->db->from('work_analysis_test_parameters perameter');
    $this->db->join('works work', 'work.work_id=perameter.work_id', 'left');
    $this->db->join('works_analysis_package wp', 'wp.work_id=perameter.work_id', 'left');

    $this->db->where('work.work_job_type_id', $quote_id);
    $this->db->where_in('work_analysis_test_id', $protocol_id);
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  function get_protocol_details($quote_id)
  {
    $this->db->select('wat.work_id as work_id,works.rate_per_package as rate, works.original_cost as cost,works.total_cost as total_cost, wat.work_test_id as test_id,works.discount as discount, product_type_id as protocol_id');    
    $this->db->join('works_analysis_test wat','works.work_id = wat.work_id');
    $this->db->where('product_type','Protocol');
    $this->db->where('works.work_job_type_id', $quote_id);
    $result = $this->db->get('works');

    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }



  public function edit_protocols($data, $work_ids)
  {
    $protocol_test_data = NULL;
    if (array_key_exists('protocol_test_data', $data)) {
      $protocol_test_data = $data['protocol_test_data'];

      if ($protocol_test_data && count($protocol_test_data) > 0) {

        $i = 0;
        foreach ($protocol_test_data as $key => $value) {
          $protocol_test_data[$i]['work_id'] = $work_ids[$i];
          $this->db->where('work_analysis_type', 'Protocol');
          $this->db->where('work_id', $work_ids[$i]);
          $this->db->delete('work_analysis_test_parameters');
          $i++;
        }
        $result = $this->insert_multiple_data('works_analysis_package', $protocol_test_data);
        if ($result) {
          if (array_key_exists('work_perameter_protocol', $data)) {
            $work_perameter_protocol = $data['work_perameter_protocol'];
            if ($work_perameter_protocol && count($work_perameter_protocol) > 0) {
              $key = 0;
              foreach ($work_perameter_protocol as $key => $value) {
                $work_perameter_protocol[$key]['work_id'] = $work_ids[$key];
                $key++;
              }
              $result = $this->insert_multiple_data('work_analysis_test_parameters', $work_perameter_protocol);
              if ($result) {
                return true;
              } else {
                return false;
              }
            } else {
              return false;
            }
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function loaddivisions_selected($division_id)
  {
    $this->db->select('division_id,division_name');
    $this->db->from('mst_divisions');
    $this->db->where_in('division_id', $division_id);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  public function get_sample_data($division_ids, $search = NULL)
  {
    $this->db->select('GROUP_CONCAT(DISTINCT(st.sample_type_id)) as sample_type_id');
    $this->db->from('tests ts');
    $this->db->join('test_sample_type stm', 'ts.test_id = stm.test_sample_type_test_id', 'left');
    $this->db->join('mst_sample_types st', 'st.sample_type_id=stm.test_sample_type_sample_type_id', 'left');
    $this->db->where_in('ts.test_division_id', $division_ids);

    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('st.sample_type_name', $search);
      $this->db->group_end();
    }
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->row();
    } else {
      return false;
    }
  }

  // public function get_mst_sample($sample_type_ids){
  //   $this->db->select('sample_type_id,sample_type_name');
  //   $this->db->from('mst_sample_types');
  //   $this->db->where_in('sample_type_id',$sample_type_ids);
  //   $result = $this->db->get();
  //   if($result->num_rows()>0){
  //     return $result->result();
  //   }
  //   else{
  //     return false;
  //   }
  // }



  // public function insert_divsion_quotes($data){

  //   $quotdata = $data['data'];
  //   $division_data = $quotdata['division_data'];
  //   unset($quotdata['division_data']);
  //   $work = $data['work'];
  //   $work_analysis_test = $data['works_analysis_test'];
  //   $work_update = $data['work_update'];
  //   $quot_update = $data['quote_update'];
  //   if($quotdata && count($quotdata)>0){
  //       $this->db->insert('quotes',$quotdata);
  //       $quote_id = $this->db->insert_id();
  //       if($quote_id){
  //           if($work && count($work)>0){
  //             foreach ($work as $key => $value) {
  //               $work[$key]['work_job_type_id'] = $quote_id;
  //             }
  //             $result = $this->insert_multiple_data('works', $work);
  //             if($result){
  //               $this->db->select('work_id');
  //               $this->db->from('works');
  //               $this->db->where('work_job_type_id', $quote_id);
  //               $this->db->where('product_type', "Test");
  //               $work_ids = $this->db->get();
  //               if($work_ids->num_rows()>0){
  //                 $work_ids = $work_ids->result_array();
  //                 $work_ids = $this->makeNumericArray($work_ids, 'work_id');
  //                 if($work_ids && count($work_ids)>0){
  //                     if($work_analysis_test && count($work_analysis_test)>0){
  //                       foreach ($work_analysis_test as $key => $value) {
  //                         $work_analysis_test[$key]['work_id'] = $work_ids[$key];
  //                         $result = $this->insert_multiple_data('works_analysis_test', $work_analysis_test);
  //                       }
  //                     }
  //                     else{
  //                       return false;
  //                     }
  //                 }
  //                 else{
  //                   return false;
  //                 }
  //               }
  //               else{
  //                 return false;
  //               }
  //             }
  //             else{
  //               return false;
  //             }
  //           }
  //           else{
  //             return false;
  //           }
  //       }
  //       else{
  //         return false;
  //       }
  //   }
  //   else{
  //     return false;
  //   }

  //   if ($result) {
  //     $confiq['mst_branch_id'] = $where2['branch_id'] = $data['quotes_branch_id'];
  //     $this->db->select_max('mst_serail_no');
  //     $this->db->from('mst_quote_seral_number');
  //     $this->db->where('mst_branch_id', $data['quotes_branch_id']);
  //     $result =  $this->db->get()->row();

  //     if ($result) {
  //       $quote_number_q = $result->mst_serail_no;
  //       $quote_number_q = $quote_number_q + 1;
  //       $rand = str_pad($quote_number_q, 5, "0", STR_PAD_LEFT);
  //       $confiq['mst_serail_no'] = $quote_number_q;
  //       $confiq['created_on'] = date("Y-m-d H:i:s");
  //       $confiq['created_by'] = $this->user;
  //       $result = $this->db->insert('mst_quote_seral_number', $confiq);
  //     } else {
  //       return false;
  //     }

  //     if ($result) {
  //       $branch = $this->get_row('branch_code', 'mst_branches', $where2);

  //       if ($branch) {
  //         $res['reference_no'] = 'GC/' . $branch->branch_code . '/' . date('Y') . '/' . $rand;
  //         $this->db->where('quote_id', $quote_id);
  //         $result = $this->db->update('quotes', $res);

  //         if ($result) {
  //           $this->db->where('work_job_type_id', $quote_id);
  //           $result = $this->db->update('works', $res);
  //         } else {
  //           return false;
  //         }

  //         if ($result) {
  //           $logDetails = array(
  //             'module' => 'sales',
  //             'old_status' => '',
  //             'new_status' => $data['quote_status'],
  //             'action_message' => 'Quote Created',
  //             'quote_id' => $quote_id
  //           );

  //           $status = $this->insert_data('sales_activity_log', $logDetails);
  //           if ($status) {
  //             return $res;
  //           } else {
  //             return false;
  //           }
  //         } else {
  //           return false;
  //         }
  //       } else {
  //         return false;
  //       }
  //     } else {
  //       return false;
  //     }
  //   } else {
  //     return false;
  //   }
  // }

  public function get_sample_name_by_id_array($sample_type_ids)
  {
    $this->db->select('sample_type_id as product_id,sample_type_name as product_name');
    $this->db->from('mst_sample_types');
    $this->db->where_in('sample_type_id', $sample_type_ids);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  // ADD TEST CODE
  public function insert_test_data($data, $sub_perameter, $price_listlog, $sub_contract = array())
  {


    $this->db->insert('tests', $data);
    $test_id = $this->db->insert_id();

    if ($test_id) {
      $sub_perameter['test_parameters_test_id'] = $price_listlog['pricelist_log_test_id'] = $test_id;
      $this->db->insert('test_parameters', $sub_perameter);
      $this->db->insert('pricelist_log', $price_listlog);
      $test_sample_type['test_sample_type_sample_type_id'] = $data['test_sample_type_id'];
      $test_sample_type['test_sample_type_test_id'] = $test_id;
      $result = $this->db->insert('test_sample_type', $test_sample_type);
      if ($sub_contract && count($sub_contract) > 0) {
        $sub_contract['test_id'] = $test_id;
        $result = $this->db->insert('test_sub_contract_details', $sub_contract);
      }
      if ($result) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }


  public function get_log_history($quote_id)
  {
    $this->db->select('CONCAT(log.action_message," by") as msg,log.log_activity_on as date_time,CONCAT(user.admin_fname,"",user.admin_lname) as user');
    $this->db->from('sales_activity_log log');
    $this->db->join('admin_profile user', 'user.uidnr_admin=log.uidnr_admin', 'left');
    $this->db->where('log.type', 'Quote');
    $this->db->where('log.quote_id', $quote_id);
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }


  public function get_generate_details($where)
  {
    $this->db->select('DATE_FORMAT(qt.quote_date, "%d-%b-%Y") as quote_date,cust.customer_name as customer_name,IF(con.contact_salutation is NULL,"",con.contact_salutation) as salutation,IF(con.contact_name is NULL,"",con.contact_name) as contact_name,IF(con.contacts_designation_id is NULL,"",con.contacts_designation_id) as contact_designation,IF(con.email is NULL,"",con.email) as contact_email,IF(con.telephone is NULL,"",con.telephone) as contact_telephone,IF(con.mobile_no is NULL,"",con.mobile_no) as contact_mobile,qt.reference_no,qt.version_number,CONCAT(ap.admin_fname," ",ap.admin_lname) as quote_created_user,DATE_FORMAT(qt.discussion_date, "%d-%b-%Y") as discussion_date,DATE_FORMAT(qt.quote_valid_date, "%d-%b-%Y") as quote_valid_date,admin_des.designation_name as admin_designation_name,ae.admin_email as admin_email,qt.additional_notes,qt.buyer_self_ref,IF(con.address is NULL,"",con.address) as contact_address,ap.admin_telephone as admin_telephone, show_discount');
    $this->db->from('quotes qt');
    $this->db->join('cust_customers cust', 'cust.customer_id = qt.quotes_customer_id', 'left');
    $this->db->join('contacts con', 'con.contact_id = qt.quotes_contact_id', 'left');
    $this->db->join('admin_profile ap', 'qt.created_by = ap.uidnr_admin', 'left');
    $this->db->join('operator_profile op', 'op.uidnr_admin = qt.created_by', 'left');
    $this->db->join('mst_designations admin_des', 'admin_des.designation_id = op.admin_designation', 'left');
    $this->db->join('admin_users ae', 'ae.uidnr_admin = qt.created_by', 'left');
    $this->db->where($where);
    $result = $this->db->get();
    // echo $this->db->last_query(); die;
    if ($result->num_rows() > 0) {
      return $result->row();
    } else {
      return false;
    }
  }


  public function gstCalculation_quotes($state, $gst_type, $amount, $percentage, $country_code = false)
  {


    $union_territory = $this->get_fields_by_id("sys_configuration", "cfg_Value", "UNION_TERRITORY", "cfg_Name")[0]['cfg_Value'];
    if (empty($union_territory)) {
      $union_territory = '';
      $territtory = '';
    } else {
      $territtory = explode(",", $union_territory);
    }


    $UAE_VAT_PLACES = $this->get_fields_by_id("sys_configuration", "cfg_Value", "UAE_VAT_PLACES", "cfg_Name")[0]['cfg_Value'];

    if (empty($UAE_VAT_PLACES)) {
      $UAE_VAT_PLACES = '';
      $uae_place = '';
    } else {
      $uae_place = explode(",", $UAE_VAT_PLACES);
    }


    $ZERO_RATED_TAX = $this->get_fields_by_id("sys_configuration", "cfg_Value", "ZERO_RATED_TAX", "cfg_Name")[0]['cfg_Value'];


    if (!$country_code)
      $country_code = 'IND';
    $state = trim($state);

    if ($country_code == 'IND') {
      if (strtolower($state) == 'haryana') {
        if ($gst_type == 'SGST')
          $return_gst = 'SGST';
        else
          $return_gst = 'CGST';
      } else if (in_array($state, $territtory)) {
        $return_gst = 'UTGST';
      } else {
        $return_gst = 'IGST';
      }
    } else if ($country_code == 'UAE' || $country_code == 'AE') {

      if (in_array($state, $uae_place)) {
        $return_gst = 'VAT';
      } else {
        $percentage = $ZERO_RATED_TAX;
        $return_gst = 'VAT';
      }
    } else if ($country_code == 'BAD') {
      $return_gst = 'VAT';
    } else {
      $return_gst = 'VAT';
    }


    //echo $gst_type."##".$return_gst;
    if ($gst_type == $return_gst) {

      return ($amount * $percentage / 100);
    } else
      return 0;
  }


  public function get_approver_by_quote_id($quote_id)
  {
    $this->db->select('qt.quotes_signing_authority_id as id,aprover_ap.admin_email as email, CONCAT(created.admin_fname," ",created.admin_lname) as created_by');
    $this->db->from('quotes qt');
    $this->db->join('admin_users aprover_ap', 'aprover_ap.uidnr_admin=qt.quotes_signing_authority_id', 'left');
    $this->db->join('admin_profile created', 'created.uidnr_admin=qt.created_by', 'left');
    $this->db->where('qt.quote_id', $quote_id);
    $result = $this->db->get();
    // echo $this->db->last_query(); die;
    if ($result->num_rows() > 0) {
      return $result->row();
    } else {
      return false;
    }
  }

  // Added by saurabh on 16-06-2022
  public function get_contact_division($key){
    $this->db->select('division_id as id, division_name as name, division_name as full_name');
    $this->db->join('quote_contact_details','division = division_id');
    ($key != NULL)?$this->db->like('division_name',$key):'';
    $query = $this->db->get('mst_divisions');
    if($query->num_rows() > 0){
      return $query->result_array();
    }
    return false;
  }
}
