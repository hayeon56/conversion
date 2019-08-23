<?php

$conversion_mail = new conversion_mail();
$content = $conversion_mail->send_mail();
echo $content."<br>";

require "./PHPMailer-5.2.27/PHPMailerAutoload.php";
$mail = new PHPMailer(true);
$mail->IsSMTP();

$mail->SMTPDebug  = 2;

$mail->Host = "smtp.naver.com";

$mail->SMTPAuth = true;
$mail->Port = 465;
$mail->SMTPSecure = "ssl";
$mail->Username = "yeoneeeeeee@naver.com";
$mail->Password = "";
$mail->SetFrom('yeoneeeeeee@naver.com', '');
$mail->AddAddress('h-lee@estore.co.jp', 'YOU');
// $mail->AddAddress('takai@estore.co.jp', 'YOU');
// $mail->AddAddress('y-ito@estore.co.jp', 'YOU');
// $mail->AddAddress('f-maeda@estore.co.jp', 'YOU');
// $mail->AddAddress('yagi@estore.co.jp', 'YOU');
// $mail->AddAddress('kumamimi@estore.co.jp', 'YOU');
// $mail->AddAddress('m-park@estore.co.jp', 'YOU');
// $mail->AddAddress('mi-kim@estore.co.jp', 'YOU');
$mail->Subject = 'CVR Report';
$mail->MsgHTML($content);
$mail->Send();


class conversion_mail{

  public function __construct(){
    $this->db_host = "localhost";
    $this->db_user = "root";
    $this->db_password = "";
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
                      <p>「音楽やさん」アクセスレポート</p>
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

      return $content;

    }
}





?>
