<?php

use setasign\Fpdi\PdfParser\Filter\Flate;
use Mpdf\Tag\Em;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Approve_quotes extends CI_Controller
{

	public function __construct(Type $var = null)
	{
		parent::__construct();
		$this->load->model('Quotes_model', 'quotes_model');
	}


	public function index($quote_id = NULL, $swal = NULL)
	{

		if ($quote_id != NULL || $swal != NULL) {
			$quote_id = base64_decode($quote_id);
			$condition = base64_decode($swal);

			if ($condition == QUOTE_APPROVER) {

				$check_already = $this->quotes_model->get_row('quote_status', 'quotes', ['quote_id' => $quote_id]);

				if ($check_already && count($check_already) > 0) {
					$check_already = $check_already->quote_status;

					if ($check_already == "Approved" || $check_already == "Cps Approved") {
						echo 'QUOTE IS ALREADY APPROVED!';
					} else {
						$aws_condition = 'S';
						$aws = $this->GeneratePDF_quotes($quote_id, $aws_condition);

						if ($aws && is_array($aws)) {
							$aws_path = $aws['aws_path'];

							$check = $this->approve($quote_id, $aws_path);
							if ($check) {
								echo 'QUOTE APPROVED SUCCESSFULLY!!';
							} else {
								echo 'ERROR IN APPROVING QUOTES';
							}
						} else {
							echo 'ERROR IN APPROVING QUOTES';
						}
					}
				} else {
					echo 'ERROR IN APPROVING QUOTES';
				}
			} else {
				echo 'YOU ARE  NOT AUTHORIZED APPROVER!';
			}
		} else {
			echo 'YOU ARE  NOT AUTHORIZED APPROVER!';
		}
	}

	public function error_msg()
	{
		echo 'NO RECORD FOUND!!';
		die;
	}


	public function approve($quote_id, $aws_path)
	{
		$data = array();
		$where = NULL;
		$where['quote_id'] = $quote_id;
		$data['quote_status'] = "Approved";
		$data['updated_by'] = QUOTE_APPROVER;
		$data['updated_on'] = date("Y-m-d");
		$data['approve_pdf_path'] = $aws_path;

		$check = $this->quotes_model->approve_quotes($data, $where);

		if ($check) {
			return true;
		} else {
			return false;
		}
	}

	public function GeneratePDF_quotes($quote_id, $aws_condition = NULL)
	{
		$html = NULL;
		$upload = "NO DATA IS AVAIALABLE";
		$genPDF = $where = array();
		$where['quote_id'] = $quote_id;
		$genPDF['test_data'] = $genPDF['package_data'] = $genPDF['protocol_data'] =  NULL;
		$genPDF['data'] = $this->quotes_model->get_quote_data($where);
		$sum_amount = 0;

		if ($genPDF['data'] && count($genPDF['data'])) {
			if ($genPDF['data']->reference_no) {
				$gc_no = str_replace('/', '_', $genPDF['data']->reference_no) . "_" . date('Y-m') . ".pdf";
			} else {
				$gc_no = "document" . rand(1000, 9999) . "_" . date('Y-m') . ".pdf";
			}
			$test_ids = $genPDF['data']->test_id;

			if ($genPDF['data']->admin_signature) {
				$sig = $this->getS3Url1($genPDF['data']->admin_signature);
				if ($sig) {
					$genPDF['data']->admin_signature = $sig;
				} else {
					$genPDF['data']->admin_signature = "NO SIGNATURE FOUND PLASSE UPLOAD!";
				}
			} else {
				$genPDF['data']->admin_signature = "NO SIGNATURE FOUND PLASSE UPLOAD!";
			}

			if ($genPDF['data']->approver_signature) {
				$sig = $this->getS3Url1($genPDF['data']->approver_signature);

				if ($sig) {
					$genPDF['data']->approver_signature = $sig;
				} else {
					$genPDF['data']->approver_signature = "NO SIGNATURE FOUND PLASSE UPLOAD!";
				}
			} else {
				$genPDF['data']->approver_signature = "NO SIGNATURE FOUND PLASSE UPLOAD!";
			}


			$tests = $this->quotes_model->get_tests_data_Testing($quote_id);
			if ($tests && count($tests) > 0) {
				$genPDF['test_data'] = $tests;

				if ($genPDF['test_data'] && count($genPDF['test_data']) > 0) {
					$t = $genPDF['test_data'];
					foreach ($t as $k => $ts) {
						$sum_amount += $ts->applicable_charge;
					}
				}
			}

			$result = $this->quotes_model->get_package_details($quote_id);

			if ($result && count($result) > 0) {
				$package_data = array();

				foreach ($result as $key => $value) {
					$some_data['type'] = '1';
					$some_data['currency_id'] = $genPDF['data']->currency_id;
					$package_data = $this->quotes_model->get_tests_by_pac_id($value->package_id, $some_data);

					if ($package_data && count($package_data) > 0) {
						foreach ($package_data as $key1 => $value1) {
							$package_data[$key1]->rate = $value->rate;
							$package_data[$key1]->cost = $value->cost;
							$package_data[$key1]->total_cost = $value->total_cost;
							$package_data[$key1]->discount = $value->discount;
							$package_data[$key1]->sample_type_id = $value1->works_sample_type_id;

							$where1['sample_type_id'] = $value1->works_sample_type_id;

							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where1);

							if ($sample_type_name) {
								$package_data[$key1]->sample_type_name = $sample_type_name->sample_type_name;
							}
						}

						$package_tests =  $package_data_pdf = array();

						$where_pc['package_id'] = $value->package_id;
						$package_name = $this->quotes_model->get_row('package_name', 'packages', $where_pc);

						foreach ($package_data as $kc => $vall) {
							if ($kc == 0) {
								$package_data_pdf['discount'] = $vall->discount;
								$package_data_pdf['package_name'] = $package_name->package_name;
								$package_data_pdf['product_name'] = $vall->sample_type_name;
								$package_data_pdf['total_cost'] = $vall->total_cost;
								$package_data_pdf['cost'] = $vall->cost;
							}
							array_push($package_tests, $vall->test_name);
						}
						$package_data_pdf['test_name'] = $package_tests;
						$genPDF['package_data'] = $package_data_pdf;

						if ($genPDF['package_data'] && count($genPDF['package_data']) > 0) {
							$p = $genPDF['package_data'];

							$sum_amount = $p['total_cost'];
						}
					} else {
						$package_data = NULL;
					}
				}
			}

			$result = $this->quotes_model->get_protocol_details($quote_id);

			if ($result && count($result) > 0) {
				$protocol_data = array();

				foreach ($result as $key => $value) {
					$some_data['type'] = '2';
					$some_data['currency_id'] = $genPDF['data']->currency_id;
					$protocol_data = $this->quotes_model->get_tests_by_pac_id($value->protocol_id, $some_data);

					if ($protocol_data && count($protocol_data) > 0) {
						foreach ($protocol_data as $key2 => $value2) {
							$protocol_data[$key2]->rate = $value->rate;
							$protocol_data[$key2]->cost = $value->cost;
							$protocol_data[$key2]->total_cost = $value->total_cost;
							$protocol_data[$key2]->discount = $value->discount;
							$protocol_data[$key2]->sample_type_id = $value2->works_sample_type_id;

							$where2['sample_type_id'] = $value2->works_sample_type_id;

							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where2);

							if ($sample_type_name) {
								$protocol_data[$key2]->sample_type_name = $sample_type_name->sample_type_name;
							}
						}
						$protocol_tests =  $protocol_data_pdf = array();
						$where_p['protocol_id'] = $value->protocol_id;
						$protocol_name = $this->quotes_model->get_row('protocol_name', 'protocols', $where_p);

						foreach ($protocol_data as $k => $val) {
							if ($k == 0) {
								$protocol_data_pdf['discount'] = $val->discount;
								$protocol_data_pdf['protocol_name'] = $protocol_name->protocol_name;
								$protocol_data_pdf['product_name'] = $val->sample_type_name;
								$protocol_data_pdf['total_cost'] = $val->total_cost;
								$protocol_data_pdf['cost'] = $val->cost;
							}
							array_push($protocol_tests, $val->test_name);
						}
						$protocol_data_pdf['test_name'] = $protocol_tests;
						$genPDF['protocol_data'] = $protocol_data_pdf;

						if ($genPDF['protocol_data'] && count($genPDF['protocol_data']) > 0) {
							$pt = $genPDF['protocol_data'];
							$sum_amount = $pt['total_cost'];
						}
					} else {
						$protocol_data = NULL;
					}
				}
			}

			$customer_id = $genPDF['data']->quotes_customer_id;
			$country_id = $state = NULL;
			$where_customer = array('customer_id' => $customer_id);
			$customer_data = $this->quotes_model->get_row('cust_customers_country_id,cust_customers_province_id,cust_customers_location_id,non_taxable', 'cust_customers', $where_customer);

			if ($customer_data && count($customer_data) > 0) {
				if (!empty($customer_data->cust_customers_country_id)) {
					$country_id = $customer_data->cust_customers_country_id;
				} else {
					$country_id = NULL;
				}

				if (!empty($customer_data->cust_customers_province_id)) {
					$state_id = $customer_data->cust_customers_province_id;
					$state = $this->quotes_model->get_row('province_name', 'mst_provinces', ['province_id' => $state_id]);
					if ($state && count($state) > 0) {
						if (!empty($state->province_name)) {
							$state = $state->province_name;
						} else {
							$state = NULL;
						}
					}
				} else {
					$state = NULL;
				}

				if ($customer_data->non_taxable != NULL || $customer_data->non_taxable != "") {
					$non_taxable = $customer_data->non_taxable;
				} else {
					$non_taxable = NULL;
				}
			}

			if (!empty($genPDF['data']->currency_id)) {
				$currency_id = $genPDF['data']->currency_id;
				$currency_decimal = $this->quotes_model->get_row('currency_decimal', 'mst_currency', ['currency_id' => $currency_id]);
				if ($currency_decimal && count($currency_decimal) > 0) {
					$currency_decimal = $currency_decimal->currency_decimal;
				} else {
					$currency_decimal = NULL;
				}
			};


			if (!empty($genPDF['data']->quotes_branch_id)) {
				$quotes_branch_id = $genPDF['data']->quotes_branch_id;
				$branch_country_id = $this->quotes_model->get_row('mst_branches_country_id', 'mst_branches', ['branch_id' => $quotes_branch_id]);
				if ($branch_country_id && count($quotes_branch_id) > 0) {
					$branch_country_id = $branch_country_id->mst_branches_country_id;
				}
			} else {
				$branch_country_id = NULL;
			}

			if (!empty($genPDF['data']->currency_id)) {
				$currency_id = $genPDF['data']->currency_id;
				$currency_code = $this->quotes_model->get_row('currency_code', 'mst_currency', ['currency_id' => $currency_id]);
				if ($currency_code && count($currency_code) > 0) {
					$currency_code = $currency_code->currency_code;
					$genPDF['data']->currency_code = $currency_code;
				}
			}


			$gst = $this->get_gst_calculation_for_quotes($branch_country_id, $non_taxable, $country_id, $state, $sum_amount, $currency_decimal);


			if (!empty($gst)) {
				$genPDF['data']->gst = $gst;
			} else {
				$genPDF['data']->gst = 0;
			}

			$branch_id = $genPDF['data']->quotes_branch_id;

			$branch_det = $this->quotes_model->get_row('branch_name,branch_telephone,branch_address', 'mst_branches', ['branch_id' => $branch_id]);

			if ($branch_det && count($branch_det) > 0) {
				$genPDF['data']->branch_name = $branch_det->branch_name;
				$genPDF['data']->branch_telephone = $branch_det->branch_telephone;
				$genPDF['data']->branch_address = $branch_det->branch_address;
			} else {
				$genPDF['data']->branch_name = '';
				$genPDF['data']->branch_telephone = '';
				$genPDF['data']->branch_address = '';
			}
			// pre_r($genPDF);die;
			if ($branch_id == '1') {
				$html = $this->load->view('quotes/gurugramPdf', $genPDF, true);
				//$html = $this->load->view('quotes/dubaiPdf', $genPDF, true);
			}
			if ($branch_id == '2') {
				$html = $this->load->view('quotes/dubaiPdf', $genPDF, true);
			}
			if ($branch_id == '4') {
				$html = $this->load->view('quotes/bangladeshpdf', $genPDF, true);
			}


			$this->load->library('M_pdf');
			$this->m_pdf->pdf->charset_in = 'UTF-8';
			$this->m_pdf->pdf->setAutoTopMargin = 'stretch';
			$this->m_pdf->pdf->lang = 'ar';
			$this->m_pdf->pdf->autoLangToFont = true;
			$this->m_pdf->pdf->WriteHTML($html);


			if ($aws_condition == 'S') {
				$pdf_file = $this->m_pdf->pdf->Output($gc_no, $aws_condition);
				$upload = $this->report_upload_aws($pdf_file, $gc_no);
				return $upload;
			} elseif ($aws_condition == 'F') {
				$file_name = LOCAL_PATH . '/' . $gc_no;
				$this->m_pdf->pdf->Output($file_name, $aws_condition);
				return $file_name;
			} else {
				$this->m_pdf->pdf->Output($gc_no . '.pdf', 'I');
			}
		}
	}

	public function getS3Url($path)
	{
		$lasturl = str_replace("s3://" . 'cpslims-prod', "", $path);
		$s3Url = 'https://' . 'cpslims-prod' . '.s3.ap-south-1.amazonaws.com' . $lasturl;
		return $s3Url;
	}

	public function getS3Url1($path)
    {
        if (substr($path, 0, 5) == 's3://') {
            $lasturl = str_replace("s3://" . BUCKETNAME1, "", $path);
            $s3Url = 'https://' . BUCKETNAME1 . '.s3.ap-south-1.amazonaws.com' . $lasturl;
            return $s3Url;
        } else {
            return $path;
        }
    }

	public function report_upload_aws($pdf_body, $file_name)
	{
		require '../vendor/autoload.php';

		$keyName = 'ci_lims/image/' . $file_name;
		// Set Amazon S3 Credentials
		$s3 = S3Client::factory(
			array(
				'credentials' => array(
					'key' => SECRET_ACCESS_KEY,
					'secret' => SECRET_ACCESS_CODE
				),
				'version' => 'latest',
				'region' => 'ap-south-1',
				'signature' => 'v4',
			)
		);
		try {
			// Put on S3
			$result = $s3->putObject(
				array(
					'Bucket' => BUCKETNAME,
					'Key' => $keyName,
					'Body' => $pdf_body,
					'Content=Type' => 'application/pdf',
					'ACL' => 'public-read'
				)
			);

			if ($result['ObjectURL']) {
				return array('aws_path' => $result['ObjectURL']);
			} else {
				return false;
			}
		} catch (S3Exception $e) {
			echo $e->getMessage() . PHP_EOL;
		}
	}



	public function  get_gst_calculation_for_quotes($branch_country_id, $non_taxable, $country_id, $state, $amt, $currency_decimal, $list = NULL)
	{


		$branch_country_code = $this->quotes_model->get_row('country_code', 'mst_country', ['country_id' => $branch_country_id]);
		if ($branch_country_code && count($branch_country_code) > 0) {
			$branch_country_code = $branch_country_code->country_code;
		} else {
			$branch_country_code = NULL;
		}

		$customer_country_code = $this->quotes_model->get_row('country_code', 'mst_country', ['country_id' => $country_id]);
		if ($customer_country_code && count($customer_country_code) > 0) {
			$customer_country_code = $customer_country_code->country_code;
		} else {
			$customer_country_code = NULL;
		}



		$IGST = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "IGST", "cfg_Name")[0]['cfg_Value'];

		if (empty($IGST)) {
			$IGST = 0;
		}

		$SGST = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "SGST", "cfg_Name")[0]['cfg_Value'];

		if (empty($SGST)) {
			$SGST = 0;
		}

		$CGST = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "CGST", "cfg_Name")[0]['cfg_Value'];

		if (empty($CGST)) {
			$CGST = 0;
		}

		$UTGST = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "UTGST", "cfg_Name")[0]['cfg_Value'];

		if (empty($UTGST)) {
			$UTGST = 0;
		}

		$VAT = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "VAT", "cfg_Name")[0]['cfg_Value'];

		if (empty($VAT)) {
			$VAT = 0;
		}

		$BDT_VAT = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "BDT_VAT", "cfg_Name")[0]['cfg_Value'];

		if (empty($BDT_VAT)) {
			$BDT_VAT = 0;
		}

		$templateVars['TAX'] = '';
		$templateVars['TAX'] .= '<tr><td width="50%"></td><td></td><td></td><td>Total Amount</td><td>' . number_format($amt, $currency_decimal) . '</td></tr><br>';
		$gst = 0;
		if ($non_taxable == 0) {

			if ($branch_country_code == 'IND' && $customer_country_code == 'IND') {
				if ($this->quotes_model->gstCalculation_quotes($state, 'IGST', $amt, $IGST, $branch_country_code) > 0) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'IGST', $amt, $IGST, $branch_country_code);
					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>IGST @ ' . $IGST . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
				}
				if ($this->quotes_model->gstCalculation_quotes($state, 'SGST', $amt, $SGST, $branch_country_code) > 0) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'SGST', $amt, $SGST, $branch_country_code);
					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>SGST @ ' . $SGST . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
					$gst = (2 * $gst);
				}
				if ($this->quotes_model->gstCalculation_quotes($state, 'CGST', $amt, $CGST, $branch_country_code) > 0) {


					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>CGST @ ' . $CGST . '%</td><td>' . number_format($this->quotes_model->gstCalculation_quotes($state, 'CGST', $amt, $CGST), $currency_decimal) . '</td></tr><br>';
				}
				if ($this->quotes_model->gstCalculation_quotes($state, 'UTGST', $amt, $UTGST, $branch_country_code)) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'UTGST', $amt, $UTGST, $branch_country_code);

					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>UTGST @ ' . $UTGST . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
				}
			} else if (($branch_country_code == 'UAE' && $customer_country_code == 'UAE') || ($branch_country_code == 'AE' && $customer_country_code == 'AE')) {
				if ($this->quotes_model->gstCalculation_quotes($state, 'VAT', $amt, $VAT, $branch_country_code)) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'VAT', $amt, $VAT, $branch_country_code);
					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>VAT @ ' . $VAT . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
				}
			} else if ($branch_country_code == 'BAD' && $customer_country_code == 'BAD') {

				if ($this->quotes_model->gstCalculation_quotes($state, 'VAT', $amt, $BDT_VAT, $branch_country_code)) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'VAT', $amt, $BDT_VAT, $branch_country_code);
					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>VAT @ ' . $BDT_VAT . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
				}
			} else {


				$gst = round($amt * 5 / 100);

				$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>VAT @ ' . $VAT . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
			}
		}

		$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>_________________</td><td>_________________</td></tr><br>';
		$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>Total (inc.tax)</td><td>' . (number_format(($amt + $gst), $currency_decimal)) . '</td></tr>';

		if ($list != NULL) {
			return $gst;
		} else {
			return $templateVars;
		}
	}
}
