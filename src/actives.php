<?php

require_once('session/check_user.php');

require_once('config/main_tiles_con.php');
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 

require_once('config/dbcon.php');
$conn1 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



               

$tot=0;
$tot1=0;
$tot2=0;
$tot3=0;
$tot4=0;
$tot5=0;
$tot6=0;
$tot7=0;
$tot8=0;
$tot9=0;
$tot10=0;
$tot11=0;
$tot12=0;
$tot13=0;
$tot14=0;
$tot15=0;



$sql_mun = "SELECT * FROM users_log WHERE user_id='".$u_user_id."'  ";
$result_mun = $conn->query($sql_mun);
if ($result_mun->num_rows > 0) {
     while($row_mun = $result_mun->fetch_assoc()) {

            date_default_timezone_set('Asia/Manila');
            $dates = date('Y-n-j G:i:s A');
            $nowd=explode(' ',$dates);
            $nowd1=explode(':',$nowd[1]);

            $date=$nowd[0];
            $hour=intval($nowd1[0]);
            $min=intval($nowd1[1]);
            $sec=intval($nowd1[2]);
            $fdate = $date." ".$hour.":".$min;
               
            //bulusan
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt,SUBSTRING_INDEX(user_out,':',2) as d FROM users_log WHERE user_id='8' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot+=$cnt['cnt'];

            //echo $cnt['d'];
            //sta mag
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='9' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active' ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot1+=$cnt['cnt'];
            //gubat
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='10' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot2+=$cnt['cnt'];
            //irosin
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='15' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot3+=$cnt['cnt'];
            //juban
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='16' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot4+=$cnt['cnt'];
            //city
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='22' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot5+=$cnt['cnt'];
            //barcelona
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='23' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot6+=$cnt['cnt'];
            //casiguran
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='24' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot7+=$cnt['cnt'];
            //bulan
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='25' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot8+=$cnt['cnt'];
            //castilla
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='26' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot9+=$cnt['cnt'];
            //donsol
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='27' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot10+=$cnt['cnt'];
            //magallanes
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='28' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot11+=$cnt['cnt'];
            //matnog
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='29' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot12+=$cnt['cnt'];
            //pilar
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='30' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot13+=$cnt['cnt'];
            //prieto diaz
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='31' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot14+=$cnt['cnt'];

            //cgs sorsogon
            $stmt = $conn1->prepare("SELECT COUNT(iid) AS cnt FROM users_log WHERE user_id='32' and SUBSTRING_INDEX(user_out,':',2)='".$fdate."' and stat='Active'  ");
            $stmt->execute(); 
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC);
            $tot15+=$cnt['cnt'];
           
  
     }
}


//echo " = ".$notifcount['notifcount']." = ".$notifcount2['notifcount2']." = ".$notifcount_cgs['notifcount']." = ".$notifcount2_cgs['notifcount2'];

          


$numUsers = $tot.":".$tot1.":".$tot2.":".$tot3.":".$tot4.":".$tot5.":".$tot6.":".$tot7.":".$tot8.":".$tot9.":".$tot10.":".$tot11.":".$tot12.":".$tot13.":".$tot14.":".$tot15;
$cont= $numUsers;
if ($cont == "0") {
                     echo  0;   
}else{
                     echo  $cont;
     } 
?> 