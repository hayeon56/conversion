<?php

class conversion_mail{

  public function __construct(){
    $this->db_host = "localhost";
    $this->db_user = "root";
    $this->db_password = "nekoten1";
    $this->db_name = "conversion";
    // $this->conn = mysqli_connect($this->db_host,$this->db_user,$this->db_password,$this->db_name);
  }

  public function send_mail(){

      $conn = mysqli_connect($this->db_host,$this->db_user,$this->db_password,$this->db_name);

      $today = date("Ymd");

      $day = date("Ymd",strtotime($today."-1 day"));


      $week_day = date('w',strtotime($day));

      if($week_day == 0){
        $day = date("Ymd",strtotime($day."-2 day"));
      }
      else if($week_day == 6){
        $day = date("Ymd",strtotime($day."-1 day"));
      }

      $query = "SELECT * FROM conversion WHERE date=$day";

      if($result = $conn->query($query)){
        while($row = $result->fetch_assoc()){
          $content = '<body>
                      <table border="1">
                        <tr>
                          <th>日付</th>
                            <td>'.$row["date"].'</td>
                        </tr>
                        <tr>
                          <th>uu</th>
                            <td>'.$row["uu"].'</td>
                        </tr>
                        <tr>
                          <th>pv</th>
                            <td>'.$row["pv"].'</td>
                        </tr>
                        <tr>
                         <th>受注数</th>
                          <td>'.$row["buy"].'</td>
                        </tr>
                        <tr>
                          <th>cvr</th>
                            <td>'.$row["conversion"].'</td>
                        </tr>
                        </table>
                        </body>';
        }
        $result->close();
      }

      echo $content;
      //
      // $mailto = "h-lee@estore.co.jp,
      // takai@estore.co.jp,
      // y-ito@estore.co.jp,
      // f-maeda@estore.co.jp,
      // yagi@estore.co.jp,
      // kumamimi@estore.co.jp,
      // m-park@estore.co.jp,
      // mi-kim@estore.co.jp";

      $mailto = "h-lee@estore.co.jp";
      $mailFrom = "yeoneeeeeee@naver.com";
      $subject = "「音楽やさん」アクセスレポート";
      $additional_headers = "Content-Type:text/html;";
      $result = mail($mailto, $subject, $content,$additional_headers);

      if($result){
        echo "mail success";
      }
      else{
        error_log($result,0);
        echo $result;
        echo "mail fail";
      }
    }
}

$conversion_mail = new conversion_mail();
$conversion_mail->send_mail();



?>
