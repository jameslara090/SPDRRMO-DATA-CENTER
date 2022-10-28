<?php
//for irosin
    require_once('session/check_admin.php');
    require_once('config/dbcon.php');
    $conn_2 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     date_default_timezone_set('Asia/Manila');
     $dates = date('Y-n-j G:i:s A');
     $nowd=explode(' ',$dates);
     $nowd1=explode(':',$nowd[1]);
     $date=$nowd[0];
     $hour=intval($nowd1[0]);
     $min=intval($nowd1[1]);
     $sec=intval($nowd1[2]);
     $nowd_now=$date." ".$hour.":".$min.":".$sec;

     $comp=explode($nowd_now,' ');
     $comp_f=$comp[0];
     $stmt = $conn_2->prepare("SELECT iid,COUNT(user_in) AS dt,user_in FROM users_log  WHERE user_id='".$_SESSION['user_id']."' and SUBSTRING_INDEX(user_in,' ',0) = '".$comp_f."' ");   
     $stmt->execute();
     $profi = $stmt->fetch(PDO::FETCH_ASSOC);
     $dura =$profi['dt'];
    $stmt = $conn_2->prepare("SELECT * FROM users WHERE user_id='".$_SESSION['user_id']."' ");
    $stmt->execute(); 
    $resulttfselect = $stmt->fetch(PDO::FETCH_ASSOC);
    $exp = explode("-", $resulttfselect['name']);
        if($exp[0]=="PDRRMO2_IROSIN"){
                if ($dura==22){
                }else{
                        $base=$profi['user_in'];
                        $dates = $nowd_now;
            
                        $date1 = strtotime($base); 
                        $date2 = strtotime($dates); 
                        $diff = abs($date2 - $date1); 
            
                        $years = floor($diff / (365*60*60*24)); 
                        $months = floor(($diff - $years * 365*60*60*24) 
                                                    / (30*60*60*24)); 
                        $days = floor(($diff - $years * 365*60*60*24 - 
                                    $months*30*60*60*24)/ (60*60*24));
                        $hours = floor(($diff - $years * 365*60*60*24 
                                    - $months*30*60*60*24 - $days*60*60*24) 
                                                                / (60*60)); 
                        $minutes = floor(($diff - $years * 365*60*60*24 
                                - $months*30*60*60*24 - $days*60*60*24 
                                                - $hours*60*60)/ 60);
                        $seconds = floor(($diff - $years * 365*60*60*24 
                                - $months*30*60*60*24 - $days*60*60*24 
                                        - $hours*60*60 - $minutes*60));
            
                        $dura='0 second';
                        if($hours != 0 && $minutes != 0 ){
                            $dura=$hours."hr ".$minutes."min ".$seconds."sec";
                        }elseif($hours == 0 && $minutes != 0){
                            $dura=$minutes."min ".$seconds."sec";
                        }elseif($hours == 0 && $minutes == 0 && $seconds != 0){
                            $dura=$seconds."sec";
                        }
            
                        $stmt = $conn_2->prepare("UPDATE users_log SET user_out = '".$nowd_now."',user_dura = '".$dura."',stat = 'Active' WHERE user_id='".$_SESSION['user_id']."' and user_in = '".$base."'     ");   
                        $stmt->execute();
                }
        }
//for irosin end
?>