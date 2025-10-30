<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function barcode($filepath = "", $text = "0", $size = "20", $orientation = "horizontal", $code_type = "code128", $print = false, $SizeFactor = 1) {
    $code_string = "";
    // Translate the $text into barcode the correct $code_type
    if (in_array(strtolower($code_type), array("code128", "code128b"))) {
        $chksum = 104;
        // Must not change order of array elements as the checksum depends on the array's key to validate final code
        $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "\`" => "111422", "a" => "121124", "b" => "121421", "c" => "141122", "d" => "141221", "e" => "112214", "f" => "112412", "g" => "122114", "h" => "122411", "i" => "142112", "j" => "142211", "k" => "241211", "l" => "221114", "m" => "413111", "n" => "241112", "o" => "134111", "p" => "111242", "q" => "121142", "r" => "121241", "s" => "114212", "t" => "124112", "u" => "124211", "v" => "411212", "w" => "421112", "x" => "421211", "y" => "212141", "z" => "214121", "{" => "412121", "|" => "111143", "}" => "111341", "~" => "131141", "DEL" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "FNC 4" => "114131", "CODE A" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
        $code_keys = array_keys($code_array);
        $code_values = array_flip($code_keys);
        for ($X = 1; $X <= strlen($text); $X++) {
            $activeKey = substr($text, ($X - 1), 1);
            $code_string .= $code_array[$activeKey];
            $chksum = ($chksum + ($code_values[$activeKey] * $X));
        }
        $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
        $code_string = "211214" . $code_string . "2331112";
    }

    // Pad the edges of the barcode
    $code_length = 20;
    if ($print) {
        $text_height = 30;
    } else {
        $text_height = 0;
    }

    for ($i = 1; $i <= strlen($code_string); $i++) {
        $code_length = $code_length + (int) (substr($code_string, ($i - 1), 1));
    }

    if (strtolower($orientation) == "horizontal") {
        $img_width = $code_length * $SizeFactor;
        $img_height = $size;
    } else {
        $img_width = $size;
        $img_height = $code_length * $SizeFactor;
    }

    $image = imagecreate($img_width, $img_height + $text_height);

    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);

    imagefill($image, 0, 0, $white);
    if ($print) {
        imagestring($image, 5, 31, $img_height, $text, $black);
    }

    $location = 10;
    for ($position = 1; $position <= strlen($code_string); $position++) {
        $cur_size = $location + (substr($code_string, ($position - 1), 1));
        if (strtolower($orientation) == "horizontal")
            imagefilledrectangle($image, $location * $SizeFactor, 0, $cur_size * $SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black));
        else
            imagefilledrectangle($image, 0, $location * $SizeFactor, $img_width, $cur_size * $SizeFactor, ($position % 2 == 0 ? $white : $black));
        $location = $cur_size;
        // echo $location;die;
    }

    //imagepng($image);
    $filepath = BARCODE;
    //str_replace('/', '-', $text);
    //echo $text; exit;
    $savePath = $filepath . $text . ".png";
    @chmod($savePath, 0755);
    $bool = imagepng($image, $savePath);

    // echo $bool;die;

    ob_start();
    imagepng($image);
    $image_data = ob_get_contents();
    //        print_r($image_data);die;
    ob_end_clean();

    return $image_data;
}

function send_mail_function($to = NULL, $from = NULL, $cc = NULL, $msg = NULL, $sub = NULL, $attachment_file = NULL, $attachment_path = NULL, $report = false) {
    // echo $msg; die;
    $CI = &get_instance();
    $CI->load->library('email');

    $message = $msg;

    // print_r($message);die;
    if (is_array($to) && count($to) > 1)
        $to_user = implode(',', $to); // convert email array to string
    else
        $to_user = $to;

    if (is_array($cc) && count($cc) > 1)
        $cc_user = implode(',', $cc); // convert email array to string
    else
        $cc_user = $cc;

    $config['protocol'] = PROTOCOL;
    $config['smtp_host'] = HOST;
    $config['smtp_user'] = USER;
    $config['smtp_pass'] = PASS;
    $config['smtp_port'] = PORT;
    $config['newline'] = "\r\n";
    $config['smtp_crypto'] = CRYPTO;
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $CI->email->initialize($config);
    $CI->email->from(FROM, 'BASIL');
    if ($report) {
        $CI->email->to($to_user);
    } else {
        $CI->email->to($to_user);
    }
    if ($cc_user) {
        $CI->email->cc($cc_user);
    }
    $CI->email->cc($cc);
    $CI->email->cc('admin2@basilrl.com');
    $CI->email->subject($sub);
    $CI->email->message($message);
    //added by sangeeta 9 april
    if ($attachment_path) {
        if (is_array($attachment_path)) {
            for ($i = 0; $i < count($attachment_path); $i++) {
                $CI->email->attach($attachment_path[$i]);
            }
        } else {
            $CI->email->attach($attachment_path);
        }
    } //end
    $bool = $CI->email->send();
    //  $bool=true;
    if ($attachment_path != '' || $attachment_path != NULL) {

        unlink($attachment_path);
    }

    if ($bool) {
        return true;
    } else {
        show_error($CI->email->print_debugger());
    }
}

// function send_acknowledgement_mail($to = NULL, $from = NULL, $cc = NULL, $bcc = NULL, $msg = NULL, $sub = NULL, $attachment_file = NULL, $attachment_path = NULL, $report = false) {
//     // echo $msg; die;
//     $CI = &get_instance();
//     $CI->load->library('email');

//     $message = $msg;

//     // print_r($message);die;
//     if (is_array($to) && count($to) > 1)
//         $to_user = implode(',', $to); // convert email array to string
//     else
//         $to_user = $to;

//     if (is_array($cc) && count($cc) > 1)
//         $cc_user = implode(',', $cc); // convert email array to string
//     else
//         $cc_user = $cc;

//     if (is_array($bcc) && count($bcc) > 1)
//         $bcc_user = implode(',', $bcc); // convert email array to string
//     else
//         $bcc_user = $bcc;

//     $config['protocol'] = PROTOCOL;
//     $config['smtp_host'] = HOST;
//     $config['smtp_user'] = USER;
//     $config['smtp_pass'] = PASS;
//     $config['smtp_port'] = PORT;
//     $config['newline'] = "\r\n";
//     $config['smtp_crypto'] = CRYPTO;
//     $config['charset'] = 'utf-8';
//     $config['newline'] = "\r\n";
//     $config['mailtype'] = 'html';
//     $CI->email->initialize($config);
//     $CI->email->from(FROM, 'BASIL');
//     if ($report) {
//         $CI->email->to($to_user);
//     } else {
//         $CI->email->to($to_user);
//     }
//     if ($cc_user) {
//         $CI->email->cc($cc_user);
//     }
//     if ($bcc_user) {
//         $CI->email->cc($bcc_user);
//     }
//     $CI->email->cc($cc);
//     $CI->email->bcc($bcc);
//     $CI->email->subject($sub);
//     $CI->email->message($message);
//     //added by sangeeta 9 april
//     if ($attachment_path) {
//         if (is_array($attachment_path)) {
//             for ($i = 0; $i < count($attachment_path); $i++) {
//                 $CI->email->attach($attachment_path[$i]);
//             }
//         } else {
//             $CI->email->attach($attachment_path);
//         }
//     } //end
//    // $bool = $CI->email->send();
//     $bool=true;
//     if ($attachment_path != '' || $attachment_path != NULL) {

//         unlink($attachment_path);
//     }

//     if ($bool) {
//         // echo '<pre>'; print_r($CI->email->print_debugger()); die;
//         return true;
//     } else {
//         show_error($CI->email->print_debugger());
//     }
// }

function send_acknowledgement_mail($to = NULL, $from = NULL, $cc = NULL, $bcc = NULL, $msg = NULL, $sub = NULL, $attachment_file = NULL, $attachment_path = NULL, $report = false) {
    // echo $msg; die;
    $CI = &get_instance();
    $CI->load->library('email');

    $message = $msg;

    // print_r($message);die;
    if (is_array($to) && count($to) > 1)
        $to_user = implode(',', $to); // convert email array to string
    else
        $to_user = $to;

    if (is_array($cc) && count($cc) > 1)
        $cc_user = implode(',', $cc); // convert email array to string
    else
        $cc_user = $cc;

    if (is_array($bcc) && count($bcc) > 1)
        $bcc_user = implode(',', $bcc); // convert email array to string
    else
        $bcc_user = $bcc;

    $config['protocol'] = PROTOCOL;
    $config['smtp_host'] = HOST;
    $config['smtp_user'] = USER;
    $config['smtp_pass'] = PASS;
    $config['smtp_port'] = PORT;
    $config['newline'] = "\r\n";
    $config['smtp_crypto'] = CRYPTO;
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $CI->email->initialize($config);
    $CI->email->from(FROM, 'BASIL');
    if ($report) {
        $CI->email->to($to_user);
    } else {
        $CI->email->to($to_user);
    }
    if ($cc_user) {
        $CI->email->cc($cc_user);
    }
    if ($bcc_user) {
        $CI->email->cc($bcc_user);
    }
    $CI->email->cc($cc);
    $CI->email->bcc($bcc);
    $CI->email->subject($sub);
    $CI->email->message($message);
    //added by sangeeta 9 april
    if ($attachment_path) {
        if (is_array($attachment_path)) {
            for ($i = 0; $i < count($attachment_path); $i++) {
                $CI->email->attach($attachment_path[$i]);
            }
        } else {
            $CI->email->attach($attachment_path);
        }
    } //end
    $bool = $CI->email->send();

    // $bool=true;
    if ($attachment_path != '' || $attachment_path != NULL) {

        unlink($attachment_path);
    }

    if ($bool) {
        return true;
    } else {
        show_error($CI->email->print_debugger());
    }
}

function send_proforma_mail($to = NULL, $from = NULL, $cc = NULL, $bcc = NULL, $msg = NULL, $sub = NULL, $attachment_file = NULL, $attachment_path = NULL, $report = false) {
    $CI = &get_instance();
    $CI->load->library('email');

    $message = $msg;

    // print_r($message);die;
    if (is_array($to) && count($to) > 1)
        $to_user = implode(',', $to); // convert email array to string
    else
        $to_user = $to;

    if (is_array($cc) && count($cc) > 1)
        $cc_user = implode(',', $cc); // convert email array to string
    else
        $cc_user = $cc;

    if (is_array($bcc) && count($bcc) > 1)
        $bcc_user = implode(',', $bcc); // convert email array to string
    else
        $bcc_user = $bcc;

    $config['protocol'] = PROTOCOL;
    $config['smtp_host'] = HOST;
    $config['smtp_user'] = USER;
    $config['smtp_pass'] = PASS;
    $config['smtp_port'] = PORT;
    $config['newline'] = "\r\n";
    $config['smtp_crypto'] = CRYPTO;
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $CI->email->initialize($config);
    $CI->email->from(FROM, 'BASIL');
    if ($report) {
        $CI->email->to($to_user);
    } else {
        $CI->email->to($to_user);
    }
    if ($cc_user) {
        $CI->email->cc($cc_user);
    }
    if ($bcc_user) {
        $CI->email->cc($bcc_user);
    }
    $CI->email->cc($cc);
    $CI->email->bcc($bcc);
    $CI->email->subject($sub);
    $CI->email->message($message);
   
    if ($attachment_path) {
        if (is_array($attachment_path)) {
            for ($i = 0; $i < count($attachment_path); $i++) {
                $CI->email->attach($attachment_path[$i]);
            }
        } else {
            $CI->email->attach($attachment_path);
        }
    } //end
   $bool = $CI->email->send();
    //  $bool=true;
    if ($attachment_path != '' || $attachment_path != NULL) {

        // unlink($attachment_path);
    }

    if ($bool) {
        return true;
    } else {
        show_error($CI->email->print_debugger());
    }
}

function send_sample_report($to, $cc, $bcc, $subject, $body) {
    $CI = &get_instance();
    $CI->load->library('email');
    $config['protocol'] = PROTOCOL;
    $config['smtp_host'] = HOST;
    $config['smtp_user'] = USER;
    $config['smtp_pass'] = PASS;
    $config['smtp_port'] = PORT;
    $config['newline'] = "\r\n";
    $config['smtp_crypto'] = CRYPTO;
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $CI->email->initialize($config);
    $CI->email->from(FROM, 'BASIL');
    $CI->email->to('admin2@basilrl.com');
    $CI->email->cc('admin2@basilrl.com');
    $CI->email->bcc('admin2@basilrl.com');
    $CI->email->subject($subject);
    $CI->email->message($body);
   $bool = $CI->email->send();
    //  $bool=true;
    if ($bool) {
        return true;
    } else {
        show_error($CI->email->print_debugger());
    }
}

function sanitizeFileName($filename) {

    //  	$dangerous_characters = array(" ", '"', "'", "&", "/", "\\", "?", "#");
    $dangerous_characters = array("?", ' ', "[", "]", "/", "\\", "=", "<", ">", ":", ";", ", ", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", chr(0));
    return str_replace($dangerous_characters, '_', $filename);
}

function exist_val($val, $permission) {
    if (is_array($permission)) {
        if (!in_array($val, $permission)) {
            return FALSE;
        } else {
            return TRUE;
        }
    } else {
        if ($val != $permission) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

function amount_to_word($number) {
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
        } else
            $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : 'Zero';
    return $result . "Rupees  " . $points . " Paise";
}

function numberToWords($numberstring, $basic_unit, $fractional_unit) {
    // echo $numberstring; die;
    $str_arr = explode('.', $numberstring);
    // print_r($str_arr); die;
    $number = $b = str_replace(',', '', $str_arr[0]);
    $arraynum = array_map('intval', str_split($str_arr[1]));

    $no = $number;
    $point = $str_arr[1];
    $hundred = null;
    $digits_1 = strlen($no);
    $point_1 = strlen($point);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
        } else
            $str[] = null;
    }
    $str = array_reverse($str);
    $result = '';
    $result .= implode('', $str);
    $result .= $basic_unit . ' ';
    $i = 0;
    while ($i < $point_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($point % $divider);
        $point = floor($point / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str1)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str1[0]) ? ' and ' : null;
            $str1 [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
        } else
            $str1[] = null;
    }

    $str1 = array_reverse($str1);

    if (count($str1) > 0 && $str1[0] != '') {

        $result .= implode('', $str1);
        $result .= $fractional_unit . ' ';
    }
    return $result . 'Only';
}

function getS3Urlpath($path) {
    $firstfivechar = substr($path, 0, 5);

    if ($firstfivechar == 's3://') {
        $lasturl = str_replace("s3://" . BUCKETNAME, "", $path);
        $s3Url = 'https://' . BUCKETNAME . '.s3.ap-south-1.amazonaws.com' . $lasturl;
        return $s3Url;
    } else
        return $path;
}

function numberOfDayPassed() {
    date_default_timezone_set('Asia/Dhaka');
    $startDate = date("Y-01-00");
    $currentDate = date("Y-m-d");
    $diff = strtotime($currentDate) - strtotime($startDate);

    $dateDiff = floor($diff / 86400);
    // $dayNumber = $dateDiff + 1;
    return $dateDiff;
}

function send_to_report_approval($to, $cc, $bcc, $subject, $body) {
    $CI = &get_instance();
    $CI->load->library('email');
    $config['protocol'] = PROTOCOL;
    $config['smtp_host'] = HOST;
    $config['smtp_user'] = USER;
    $config['smtp_pass'] = PASS;
    $config['smtp_port'] = PORT;
    $config['newline'] = "\r\n";
    $config['smtp_crypto'] = CRYPTO;
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $CI->email->initialize($config);
    $CI->email->from(FROM, 'BASIL');
    $CI->email->to($to);
    $CI->email->cc('admin2@basilrl.com');
    
    $CI->email->subject($subject);
    $CI->email->message($body);
    $bool = $CI->email->send();
    // $bool=true;
    if ($bool) {
        return true;
    } else {
        show_error($CI->email->print_debugger());
    }
}

function send_mail_while_Release_to_Client($to = NULL, $from = NULL, $cc = NULL , $bcc = NULL, $msg = NULL, $sub = NULL, $attachment_file = NULL, $attachment_path = NULL, $report = false) {
    // echo $msg; die;
    $CI = &get_instance();
    $CI->load->library('email');

    $message = $msg;

    // print_r($message);die;
    if (is_array($to) && count($to) > 1)
        $to_user = implode(',', $to); // convert email array to string
    else
        $to_user = $to;

    if (is_array($cc) && count($cc) > 1)
        $cc_user = implode(',', $cc); // convert email array to string
    else
        $cc_user = $cc;
    
    if (is_array($bcc) && count($bcc) > 1)
        $bcc_user = implode(',', $bcc); // convert email array to string
    else
        $bcc_user = $bcc;


    $config['protocol'] = PROTOCOL;
    $config['smtp_host'] = HOST;
    $config['smtp_user'] = USER;
    $config['smtp_pass'] = PASS;
    $config['smtp_port'] = PORT;
    $config['newline'] = "\r\n";
    $config['smtp_crypto'] = CRYPTO;
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $CI->email->initialize($config);
    $CI->email->from(FROM, 'BASIL');
    if ($report) {
        $CI->email->to($to_user);
    } else {
        $CI->email->to($to_user);
    }
    if ($cc_user) {
        $CI->email->cc($cc_user);
    }
    /* added by millan on 26-07-2021  */
    if ($bcc_user) {
        $CI->email->bcc($bcc_user);
    }
    /* added by millan on 26-07-2021  */
    $CI->email->cc($cc);
    $CI->email->subject($sub);
    $CI->email->message($message);


    // print_r($attachment_file);die;
    $CI->email->attach($attachment_file);
    $bool = $CI->email->send();
    // $bool=true;
    if ($bool) {
        return true;
    } else {
        show_error($CI->email->print_debugger());
    }
}

function exist_val_multiple($match_array, $permission) {
    foreach ($match_array as $value) {
        if (in_array($value, $permission)) {
            return TRUE;
            break;
        }
    }
    return false;
}

function change_time($time,$timezone)
{
    $time = new DateTime($time,new DateTimeZone('UTC'));
    $laTimezone = new DateTimeZone( 'Asia/Kolkata' );
    $time->setTimeZone( $laTimezone );
    return $time->format( 'Y-m-d H:i:s' );
}
 function getS3Url2($path) {
    // if(substr($path, 0, 5) == 's3://'){
    // // $lasturl = str_replace("s3://" . BUCKETNAME, "", $path);
    // $lasturl=
    // $s3Url = 'https://'  . 's3.ap-south-1.amazonaws.com' . $lasturl;
    // return $s3Url;
    // } else {
    //   return $path;  
    // }
    $lasturl=str_replace("s3:","",$path,$i);
    return 'https://'  . 's3.ap-south-1.amazonaws.com' . $lasturl;
}
function send_Image_Database($image_Name)
{
    $newurl=str_replace("https://s3.ap-south-1.amazonaws.com","s3:/",$image_Name,$i);   
    // echo "<pre>";
    // print_r($newurl); die;
    return $newurl;
}

function pre_r($array)
{
    echo '<pre>';
    print_r(($array));
}

// Added by CHANDAN --08-06-2022
function getParameterForCateogry($record_finding_id, $categoryrow)
{
    $CI = &get_instance();
    $CI->db->select('*');
    $CI->db->from('record_finding_parameters_body');
    $CI->db->where(['record_finding_id' => $record_finding_id, 'is_deleted' => 0, 'category' => $categoryrow->category]);
    $CI->db->order_by('priority_order', 'asc');
    $result =  $CI->db->get();
    return ($result->num_rows() > 0) ? $result->result() : NULL;
}

function getPartsOnRecordFinding($columnName, $sample_reg_id, $sample_test_id)
{
    $CI = &get_instance();
    $query = $CI->db->select('sample_part_id')
        ->group_start()
        ->where('sample_test_sample_reg_id', $sample_reg_id)
        ->where('sample_test_id', $sample_test_id)
        ->group_end()
        ->get('sample_test');
    $parts_id = explode(',', $query->row_array()['sample_part_id']);
    if ($columnName == 'part_name') {
        $CI->db->select('group_concat(parts.part_name) as parts');
    } else {
        $CI->db->select('group_concat(parts.parts_desc) as parts');
    }
    $CI->db->where_in('part_id', $parts_id);
    $part_name_query = $CI->db->get('parts');
    return ($part_name_query->num_rows() > 0) ? $part_name_query->row()->parts : NULL;
}
// End....
?>
