<?php
//Mysql

class conversion_report{

  public function __construct(){
    $this->db_host = "localhost";
    $this->db_user = "root";
    $this->db_password = "nekoten1";
    $this->db_name = "conversion";
    // $this->conn = mysqli_connect($this->db_host,$this->db_user,$this->db_password,$this->db_name);
  }

  public function report_save(){
  $conn = mysqli_connect($this->db_host,$this->db_user,$this->db_password,$this->db_name);
  // print_r($this->conn) ;
  //check
  $ip_check = "/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/";
  $url_check = "/shop/index.php/Main";
  $buy_check = "/shop/index.php/Main/buyController";

  //day
  $today = date("Ymd");

  $day = date("Ymd",strtotime($today."-1 day"));


  $week_day = date('w',strtotime($day));


  if($week_day == 0){
    $day = date("Ymd",strtotime($day."-2 day"));
  }
  else if($week_day == 6){
    $day = date("Ymd",strtotime($day."-1 day"));
  }


  $ip_array = array();
  $date;
  $uu;
  $pv;
  $conversion;

  $count = 0;

  echo $day."<br />";

  //file open
  $fo = file('/var/log/httpd/access_log.'."$day");


  //uu
  $ip_array = array();

  foreach($fo as $value){

    $ip_temp = explode(" - -",$value);

      if($ip_temp[0] != "::1"){

        preg_match($ip_check,$ip_temp[0],$temp_array);
        $ip_array[$count] = $temp_array[0];
      }

    $count++;

  }

  $ip_data = array_unique($ip_array);


  $uu = sizeof($ip_data);
  echo "uu:".$uu."<br />";

  $url_data = array();
  $buy_data = array();

  //pv
  $count2 = 0;

  foreach($fo as $value){
    $fo_array = explode(" ",$value);

    if(strpos($fo_array[6],$url_check) !== false){
      $url_data[$count2] = $fo_array[6];

    }

    if(strpos($fo_array[6],$buy_check) !== false){
      $buy_data[$count2] = $fo_array[6];
    }

    $count2++;

  }

  $pv = sizeof($url_data);

  $buy = sizeof($buy_data);

  echo "pv:".$pv."<br />";

  //conversion
  if($buy == 0){
      $conversion = 0;
  }
  else{
    $conversion = $buy/$pv*100;
  }

  echo "conversion:".$conversion."%";

  //DB save
  $query = "INSERT INTO conversion VALUES ('$day','$uu','$pv','$buy','$conversion')";

  $conn->query($query);
  }

}

$conversion_report = new conversion_report;
$conversion_report->report_save();

?>
