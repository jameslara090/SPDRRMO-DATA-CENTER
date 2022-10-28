<?php

require_once('session/check_user.php');

    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


     //date_default_timezone_set('Asia/Manila');
     //$dates = date('Y-n-j g:i:s A');

     date_default_timezone_set('Asia/Manila');
     $dates = date('Y-n-j G:i:s A');
     $nowd=explode(' ',$dates);
     $nowd1=explode(':',$nowd[1]);

     $date=$nowd[0];
     $hour=intval($nowd1[0]);
     $min=intval($nowd1[1]);
     $sec=intval($nowd1[2]);

//echo $date." ".$hour.":".$min.":".$sec;\



     //$nowd=$dates;
     $nowd_now=$date." ".$hour.":".$min.":".$sec;

     $comp=explode($nowd_now,' ');
     $comp_f=$comp[0];
     $stmt = $conn->prepare("SELECT iid,COUNT(user_in) AS dt,user_in FROM users_log  WHERE user_id='".$u_user_id."' and SUBSTRING_INDEX(user_in,' ',0) = '".$comp_f."' ");   
     $stmt->execute();
     $profi = $stmt->fetch(PDO::FETCH_ASSOC);

     //$stmt = $conn->prepare("SELECT iid,COUNT(SUBSTRING_INDEX(user_in,' ',0)) as dat, user_out FROM users_log  WHERE user_id='".$u_user_id."' and SUBSTRING_INDEX(user_in,' ',0) = '".$comp_f."' ");   
     //$stmt->execute();
     //$profi1 = $stmt->fetch(PDO::FETCH_ASSOC);

     $dura =$profi['dt'];

     //$dat=$profi1['dat'];

     //echo "ery ".$dat;

     


     if ($dura==22){
        
        //     $base=$_POST['user_in'];
        //     $dates = $_POST['user_out'];

        //     $date1 = strtotime($base); 
        //      $date2 = strtotime($dates); 
        //      $diff = abs($date2 - $date1); 

        //      $years = floor($diff / (365*60*60*24)); 
        //      $months = floor(($diff - $years * 365*60*60*24) 
        //                                  / (30*60*60*24)); 
        //      $days = floor(($diff - $years * 365*60*60*24 - 
        //                  $months*30*60*60*24)/ (60*60*24));
        //      $hours = floor(($diff - $years * 365*60*60*24 
        //                  - $months*30*60*60*24 - $days*60*60*24) 
        //                                              / (60*60)); 
        //      $minutes = floor(($diff - $years * 365*60*60*24 
        //              - $months*30*60*60*24 - $days*60*60*24 
        //                              - $hours*60*60)/ 60);
        //      $seconds = floor(($diff - $years * 365*60*60*24 
        //              - $months*30*60*60*24 - $days*60*60*24 
        //                      - $hours*60*60 - $minutes*60));

        //      $dura='0 second';
        //      if($hours != 0 && $minutes != 0 ){
        //          $dura=$hours."hr ".$minutes."min".$seconds."sec";
        //      }elseif($hours == 0 && $minutes != 0){
        //          $dura=$minutes."min ".$seconds."sec";
        //      }elseif($hours == 0 && $minutes == 0 && $seconds != 0){
        //          $dura=$seconds."sec";
        //      }

        // $stmt = $conn->prepare("INSERT INTO `users_log`(`user_id`,`user_in`,`user_out`,`user_dura`)   
        // VALUES ('".$_POST['user_id']."', '".$_POST['user_in']."', '".$_POST['user_out']."', '".$dura."' )");
        // $stmt->execute();
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


            $stmt = $conn->prepare("UPDATE users_log SET user_out = '".$nowd_now."',user_dura = '".$dura."',stat = 'Active' WHERE user_id='".$u_user_id."' and user_in = '".$base."'     ");   
            $stmt->execute();

       
     }

    

    
    // $dates = $_POST['user_out'];

    // $base=$_POST['user_out'];

    // $date1 = strtotime($base); 
    // $date2 = strtotime($dates); 
    // $diff = abs($date2 - $date1); 

    // $years = floor($diff / (365*60*60*24)); 
    // $months = floor(($diff - $years * 365*60*60*24) 
    //                             / (30*60*60*24)); 
    // $days = floor(($diff - $years * 365*60*60*24 - 
    //             $months*30*60*60*24)/ (60*60*24));
    // $hours = floor(($diff - $years * 365*60*60*24 
    //             - $months*30*60*60*24 - $days*60*60*24) 
    //                                         / (60*60)); 
    // $minutes = floor(($diff - $years * 365*60*60*24 
    //         - $months*30*60*60*24 - $days*60*60*24 
    //                         - $hours*60*60)/ 60);
    // $seconds = floor(($diff - $years * 365*60*60*24 
    //         - $months*30*60*60*24 - $days*60*60*24 
    //                 - $hours*60*60 - $minutes*60));

    // $dura=0;
    // if($hours != 0 && $minutes != 0 ){
    //     $dura=$hours." hr and ".$minutes;
    // }elseif($hours == 0 && $minutes != 0){
    //     $dura=$minutes." min and ".$seconds;
    // }




?>