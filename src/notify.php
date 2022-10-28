<?php


require_once('config/main_tiles_con.php');
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 

require_once('config/dbcon.php');
$conn1 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



               

$tot=0;
$tot_cgs=0;



$sql_mun = "SELECT * FROM users WHERE usertype='user1' order by user_email ASC ";
$result_mun = $conn->query($sql_mun);
if ($result_mun->num_rows > 0) {
     while($row_mun = $result_mun->fetch_assoc()) {
          $exp_mun = explode("-", $row_mun['name']);
          $lowercase_name_mun = strtolower($exp_mun[1]);

          

               $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(tdatetime)) AS notifcount FROM typhoon_form WHERE notif=0 and mun='".$lowercase_name_mun."' ");
               $stmt->execute(); 
               $notifcount = $stmt->fetch(PDO::FETCH_ASSOC);

               $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(addeddate)) AS notifcount2 FROM typhoon_attachfile WHERE notif=0 and mun='".$lowercase_name_mun."' ");
               $stmt->execute(); 
               $notifcount2 = $stmt->fetch(PDO::FETCH_ASSOC);

               $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(d_re)) AS notifcount3 FROM 1pod WHERE notif=0 and mun='".$lowercase_name_mun."' ");
               $stmt->execute(); 
               $notifcount3 = $stmt->fetch(PDO::FETCH_ASSOC);

               $tot+=$notifcount['notifcount']+$notifcount2['notifcount2']+$notifcount3['notifcount3'];
  
     }
}

require_once('config/main_tiles_con.php');
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM users WHERE usertype='user1' and name='-CGS SORSOGON' order by user_email ASC ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {
          $exp = explode("-", $row['name']);
          $lowercase_name = "cgs sorsogon";

               $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(asof)) AS notifcount FROM cgs_record WHERE notif=0 ");
               $stmt->execute(); 
               $notifcount_cgs = $stmt->fetch(PDO::FETCH_ASSOC);

               $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(addeddate)) AS notifcount2 FROM cgs_attachfile WHERE notif=0  ");
               $stmt->execute(); 
               $notifcount2_cgs = $stmt->fetch(PDO::FETCH_ASSOC);

               $tot_cgs+=$notifcount_cgs['notifcount']+$notifcount2_cgs['notifcount2'];
     
     }
}


//echo " = ".$notifcount['notifcount']." = ".$notifcount2['notifcount2']." = ".$notifcount_cgs['notifcount']." = ".$notifcount2_cgs['notifcount2'];

          


$numUsers = $tot + $tot_cgs;
$cont= $numUsers;
if ($cont == "0") {
                     echo  "";   
}else{
                     echo  $cont;
     } 
?> 