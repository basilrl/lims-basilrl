<?php
 
/**
 * Description of CountrySync
 *
 * @author Abhishek
 */
class CountrySync extends MY_Controller{
    
    public function index(){
        $this->load->library('excel');
//        $inputFileName =   "public/bc365sync/Countries_Regions NAV.CPS 2021-07-16T11_19_14.xlsx";
//        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
//        $reader = PHPExcel_IOFactory::createReader($inputFileType);
//        $reader->setReadDataOnly(true);
//        $objPHPExcel = $reader->load($inputFileName);
// 
//        $sheet = $objPHPExcel->getActiveSheet();    
//        $highestRow = $sheet->getHighestRow();
//        $highestColumn = $sheet->getHighestColumn();
//        $country_name = array('United Arab Emirates','Austria','Australia','Belgium','Bulgaria','Brunei Darussalam','Brazil','Canada','Switzerland','China','Costa Rica','Cyprus','Czechia','Germany','Denmark','Algeria','Estonia','Greece','Spain','Finland','Fiji Islands','France','Great Britain','Croatia','Hungary','Indonesia','Ireland','India','Iceland','Italy','Japan','Kenya','Lithuania','Luxembourg','Latvia','Morocco','Montenegro','Malta','Mexico','Malaysia','Mozambique','Nigeria','Nothern Ireland','Netherlands','Norway','New Zealand','Philippines','Poland','Portugal','Romania','Serbia','Russia','Saudi Arabia','Solomon Islands','Sweden','Singapore','Slovenia','Slovakia','Swaziland','Thailand','Tunisia','Turkey','Tanzania','Uganda','USA','Vanuatu','Samoa');
//        $country_code =array('AE','AT','AU','BE','BG','BN','BR','CA','CH','CN','CR','CY','CZ','DE','DK','DZ','EE','EL','ES','FI','FJ','FR','GB','HR','HU','ID','IE','IN','IS','IT','JP','KE','LT','LU','LV','MA','ME','MT','MX','MY','MZ','NG','NI','NL','NO','NZ','PH','PL','PT','RO','RS','RU','SA','SB','SE','SG','SI','SK','SZ','TH','TN','TR','TZ','UG','US','VU','WS');
        $countries = array(array('United Arab Emirates','AE'),array('Austria','AT'),array('Australia','AU'),array('Belgium','BE'),array('Bulgaria','BG'),array('Brunei Darussalam','BN'),array('Brazil','BR'),array('Canada','CA'),array('Switzerland','CH'),array('China','CN'),array('Costa Rica','CR'),array('Cyprus','CY'),array('Czechia','CZ'),array('Germany','DE'),array('Denmark','DK'),array('Algeria','DZ'),array('Estonia','EE'),array('Greece','EL'),array('Spain','ES'),array('Finland','FI'),array('Fiji Islands','FJ'),array('France','FR'),array('Great Britain','GB'),array('Croatia','HR'),array('Hungary','HU'),array('Indonesia','ID'),array('Ireland','IE'),array('India','IN'),array('Iceland','IS'),array('Italy','IT'),array('Japan','JP'),array('Kenya','KE'),array('Lithuania','LT'),array('Luxembourg','LU'),array('Latvia','LV'),array('Morocco','MA'),array('Montenegro','ME'),array('Malta','MT'),array('Mexico','MX'),array('Malaysia','MY'),array('Mozambique','MZ'),array('Nigeria','NG'),array('Nothern Ireland','NI'),array('Netherlands','NL'),array('Norway','NO'),array('New Zealand','NZ'),array('Philippines','PH'),array('Poland','PL'),array('Portugal','PT'),array('Romania','RO'),array('Serbia','RS'),array('Russia','RU'),array('Saudi Arabia','SA'),array('Solomon Islands','SB'),array('Sweden','SE'),array('Singapore','SG'),array('Slovenia','SI'),array('Slovakia','SK'),array('Swaziland','SZ'),array('Thailand','TH'),array('Tunisia','TN'),array('Turkey','TR'),array('Tanzania','TZ'),array('Uganda','UG'),array('USA','US'),array('Vanuatu','VU'),array('Samoa','WS'));
//        echo "<pre>"; print_r($countries);die;
        
foreach ($countries as $k=>$country){
//    echo $country[0]."-". $country[1]."<br>";
    $this->_syncCountry($country[0], $country[1]);
}
//   die;     
//        for ($row = 2; $row < $highestRow; $row++) {
//            $country_name = trim($sheet->getCell('B' . $row)->getValue());
//            $country_code = trim($sheet->getCell('A' . $row)->getValue());
//            echo "array('".$country_name."','".$country_code."'),";
////            $this->_syncCountry($country_name, $country_code);
//        }
//      die;
//    echo "<pre>";  print_r($data);die;
        echo "Country Sync Completed!";

    }

    private function _syncCountry($country_name, $country_code) {
       $country = $this->db->get_where('mst_country', ['country_name' => $country_name]); 
        if($country->num_rows() > 0){
            $existingCountry =$country->row_array(); 
            $this->db->update('mst_country', ['bc365_county_id' => $country_code], ['country_id' => $existingCountry['country_id']]);
        }else{
            $data = array(
                'country_name' => $country_name, 
                'country_code' =>$country_code , 
                'bc365_county_id' => $country_code,
                'created_by' => $this->admin_id(),
                'created_on' => date('Y-m-d H:i:s')
              );
            $this->db->insert('mst_country', $data);
        }
    }

}
