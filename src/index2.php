<?php

//session_start();
//if (!isset($_SESSION['USER_ID']) || ($_SESSION['USER_ID'] == '')) {
//    header("location:login.php");
 //   exit();
//}

require_once('session/check_admin.php');

require_once('config/dbcon.php');
$conn1 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$datestart=date('Y-m-d');
//echo date('Y-m-d',strtotime('+30 days',strtotime($datestart))) . PHP_EOL;

//$checksum = checksum_table("cgs_attachfile");

//$checksum1 = checksum_table("drrms.cgs_record");

//echo $checksum1." = ".$checksum;

     date_default_timezone_set('Asia/Manila');
     $dates = date('Y-n-j G:i:s A');
     $nowd=explode(' ',$dates);
     $nowd1=explode(':',$nowd[1]);

     $date=$nowd[0];
     $hour=intval($nowd1[0]);
     $min=intval($nowd1[1]);
     $sec=intval($nowd1[2]);

//echo $date." ".$hour.":".$min.":".$sec;\



//echo gethostname();


//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['save'])){
        try{
            $stmt=$conn1->prepare("UPDATE cnt SET num='".$_POST['cnt']."' ");
            $stmt->execute();
            }
            catch (Exception $e){
            }
    }
    if(isset($_POST['reset'])){
        try{
            $stmt=$conn1->prepare("UPDATE cnt SET num='100' ");
            $stmt->execute();
            }
            catch (Exception $e){
            }
    }
    if(isset($_POST['stop'])){
        try{
            $stmt=$conn1->prepare("UPDATE cnt SET num='none' ");
            $stmt->execute();
            }
            catch (Exception $e){
            }
    }
}


$stmt = $conn1->prepare("SELECT * FROM cnt ");
$stmt->execute(); 
$cnt = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
    

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta http-equiv="refresh" content="<?=$cnt['num']?>"/>
    
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="vendors/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="vendors/bower_components/select2/dist/css/select2.min.css">
         <link rel="stylesheet" href="css/custom.css">
        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">

        <?php //include('include/head_tab.php')?>

        <link rel="icon" type="image/png" href="img/icon.png" sizes="96x96">
        <!--style="border:4px datted green;"-->
        <title>DISASTER REPORT ( LDRRMO / SPDRRMC )</title>
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <style>
            .contacts__btn1{  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
            .contacts__btn1 .badge1 {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
            .abtn1{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
            .abtn1:hover{ color:white; }
        </style>
        <script>
           /* var autoloadaa=setInterval(function(num){
                $.get("notify.php", function(data){
                    var count = data;
                    document.title ="DISASTER REPORT ( LDRRMO / SPDRRMC )";
                    if (count > 0){
                        console.log(document.title = "(" + $.trim(count) + ") " + document.title);
                        $('#notifyme_val').text(count); 
                        $("#notifyme").show();
                    }else{
                        console.log(document.title = document.title);
                        $("#notifyme").hide();
                    }
                });

            },1000); ;*/
        </script>
        
    </head>
      
    <body data-sa-theme="3">

    <!--<body oncontextmenu="return false;">-->
    <script language="JavaScript">
    /* window.onload = function() {  document.addEventListener("contextmenu", function(e){ e.preventDefault();  }, false); document.addEventListener("keydown", function(e) { if (e.ctrlKey && e.shiftKey && e.keyCode == 73) { disabledEvent(e); } if (e.ctrlKey && e.shiftKey && e.keyCode == 74) { disabledEvent(e); } if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) { disabledEvent(e); } if (e.ctrlKey && e.keyCode == 85) { disabledEvent(e); } if (event.keyCode == 123) { disabledEvent(e); } }, false); function disabledEvent(e){ if (e.stopPropagation){ e.stopPropagation();  } else if (window.event){ window.event.cancelBubble = true; } e.preventDefault(); return false; }  };
    */

    /*window.onload = function() {  document.addEventListener("contextmenu", function(e){ e.preventDefault();  }, false); document.addEventListener("keydown", function(e) { if (e.ctrlKey && e.shiftKey && e.keyCode == 73) { disabledEvent(e); } if (e.ctrlKey && e.shiftKey && e.keyCode == 74) { disabledEvent(e); } if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) { disabledEvent(e); } if (e.ctrlKey && e.keyCode == 85) { disabledEvent(e); } if (event.keyCode == 123) { disabledEvent(e); } }, false); function disabledEvent(e){ if (e.stopPropagation){ e.stopPropagation();  } else if (window.event){ window.event.cancelBubble = true; } e.preventDefault(); return false; }  };
    document.onkeyup = function(e) {
        if (e.ctrlKey && e.which == 77) {
            alert("Ctrlu + Mu shortcut combination was pressed");
        }
    };*/

    </script>





        <main class="main">



            <?php include('include/header.php')?>
            
            <?php include('logs_2.php')?>

            <script>
            var seconds = 0;
                var autoloadaa=setInterval(function(num){
                        var count = <?php echo $cont;?>;
                        document.title ="DISASTER REPORT ( LDRRMO / SPDRRMC )";
                        if (count > 0){                          
                            console.log(document.title = "🔴 (" + $.trim(count) + ") " + document.title);
                        }else{                                  
                            console.log(document.title = document.title);
                        }
                        seconds = seconds + 1;
                        if($("#cnt").val()){
                        $("#counter").text(seconds + " ");
                        }
                },1000); ;
            </script>


          

            <aside class="sidebar">
                <div class="scrollbar-inner">
                  <?php include('include/profile.php')?>
                </div>
                    <ul class="navigation">
                        <li style="margin-bottom:1px; border-left:3px solid green; "> <a style="padding:0px; margin:0px;" href="index2.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="add_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;<i class="zmdi zmdi-accounts-add"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add User</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="situation.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Situation Report</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    
                        <li style="margin-bottom:1px; margin-top:15px;"> <a style="padding:0px; margin:0px;" href="forum_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-comments"></i> &nbsp;&nbsp;&nbsp;&nbsp;Forum / Suggestions</button> </a> </li>
                    </ul>
                </div>
            </aside>

           
</div>

            <section class="content">

            <?php
            //echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
            ?>
                <div class="content__inner">

                <h5 style="margin-bottom:15px; ">
                 <form action="index2.php" method="post">   
                    <div style=" text-align:right; font-size:12px; color:grey;">
                    🕑<span id="counter"></span><input name="cnt" id="cnt" style="text-align:center; width:50px; background-color:rgba(0,0,0,0.1); border:1px dotted  rgba(0,0,0,0.5); color:grey;" title="Count <?=$cnt['num']?> to refresh this page" type="number" value="<?=$cnt['num']?>"> 
                       <button type="submit" name="save" title="Save Changes" >Save</button>
                       <button  type="submit" name="reset"  title="Reset to Default (100) count"> <i class="zmdi zmdi-time-restore-setting"></i> </button>
                       <button  type="submit" name="stop"  title=" Set without counter"> <i class="zmdi zmdi-timer-off"></i> </button> 
                    </div>   
                    <code style="font-family:Arial;">MDRRMO's</code>
                 </form>
                </h5> 


                    <div class="contacts row">
                       
                        <?php
                        require_once('config/main_tiles_con.php');
                         $conn = new mysqli($servername, $username, $password, $dbname);
                         if ($conn->connect_error) {
                             die("Connection failed: " . $conn->connect_error);
                         } 
                         $sql = "SELECT * FROM users WHERE usertype='user1' and name != '-CGS SORSOGON' order by user_email ASC ";
                         $result = $conn->query($sql);
                         if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $exp = explode("-", $row['name']);
                                $lowercase_name = strtolower($exp[1]);

                                $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(tdatetime)) AS notifcount FROM typhoon_form WHERE notif=0 and mun='".$lowercase_name."' ");
                                $stmt->execute(); 
                                $notifcount = $stmt->fetch(PDO::FETCH_ASSOC);

                                $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(addeddate)) AS notifcount2 FROM typhoon_attachfile WHERE notif=0 and mun='".$lowercase_name."' ");
                                $stmt->execute(); 
                                $notifcount2 = $stmt->fetch(PDO::FETCH_ASSOC);

                                $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(d_re)) AS notifcount3 FROM 1pod WHERE notif=0 and mun='".$lowercase_name."' ");
                                $stmt->execute(); 
                                $notifcount3 = $stmt->fetch(PDO::FETCH_ASSOC);

                                $tot=$notifcount['notifcount']+$notifcount2['notifcount2'] + $notifcount3['notifcount3'];

                         ?>

                                  <style>
                                       /* .imag{
                                            width: 200px;
                                            height: 200px;
                                            border-radius: 100px;
                                            background-color: #ccc;
                                            animation: glow 1s infinite alternate;
                                        }
                                        @keyframes glow {
                                            from {
                                                box-shadow: 0 0 4px -4px #2db730;
                                            }
                                            to {
                                                box-shadow: 0 0 4px 4px #2db730;
                                            }
                                        }*/


                                        .imag{
                                            background-color:#fff;
                                            
                                        }
                                        .imag.active {

                                            -webkit-box-shadow: 0 0 10px #03bb5f;
                                            -moz-box-shadow: 0 0 10px #03bb5f;
                                                    box-shadow: 0 0 10px #03bb5f;

                                                    
                                        }

                                        .imag:hover{
                                            -webkit-border-radius: 50%;
                                            -moz-border-radius: 50%;
                                            border-radius: 50%;
                                            -webkit-box-shadow: 0px 0px 10px 0px #808080;
                                            -moz-box-shadow:    0px 0px 10px 0px #808080;
                                            box-shadow:         0px 0px 10px 0px #808080;
                                        }




                                  </style>

                            <div class="col-xl-2 col-lg-3 col-sm-4 col-6">

                            <?php //echo $row['name'];?>
                               <div class="contacts__item">
                                  <a  class="contacts__img">
                                      <?php // echo '<img class="imag" id="'.$row['user_id'].'" src="data:image/jpeg;base64,'.base64_encode($row['user_image']).'"  width="116px" height="116px" >';
                                      ?>

                                      <img class="imag" src="img/logo/<?=$row['name']?>.png"  style="width:116px; height:116px;" >
                                  </a>
                                  <!--<label for=""><?=$row['user_id']?></label>-->
                               <div class="contacts__info">
                                    <strong style="font-size:11px;"><?php echo $row['name'];?></strong>
                                    <small style="font-size:10px;"><?php echo $row['user_email'];?></small>
                               </div>
                                  <style>
                                      .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                      .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                      .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                      .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                      .abtn:hover{ color:white; }
                                  </style>
                                  <?php
                                   if ($tot > 0){
                                  ?>
                                  <button class="contacts__btn"><a class="abtn"  href="session_var/var_admin_mun.php?mun=<?=$lowercase_name?>">View Report&nbsp;<span class="badge" style=" border: 1px solid maroon; border-radius: 50%; padding-left: 3px;  padding-right: 3px;  padding-top: 2px;  padding-bottom: 1px; background-color: red;"><?php echo $tot;?></span></a></button>
                                  <?php
                                  }else{
                                  ?>
                                  <button class="contacts__btn"><a class="abtn"  href="session_var/var_admin_mun.php?mun=<?=$lowercase_name?>">View Report</a></button>
                                  <?php
                                  }
                                  ?>
                            </div>
                        </div>
                        <?php 
                         }
                         } else {
                           
                         }
                            $conn->close();
                         ?>

                       





                        
                        </div>
                    </div>


                </div>



              <!--cgs sorsogon-->
                <div class="content__inner">
                 <h5 style="margin-bottom:15px;"><code style="font-family:Arial;">CGS SORSOGON</code></h5>
                <div class="contacts row">
                        <?php
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
                                //$lowercase_name = strtolower($exp[1]);
                                $lowercase_name = "cgs sorsogon";

                                $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(asof)) AS notifcount FROM cgs_record WHERE notif=0 ");
                                $stmt->execute(); 
                                $notifcount_cgs = $stmt->fetch(PDO::FETCH_ASSOC);

                                $stmt = $conn1->prepare("SELECT COUNT(DISTINCT(addeddate)) AS notifcount2 FROM cgs_attachfile WHERE notif=0  ");
                                $stmt->execute(); 
                                $notifcount2_cgs = $stmt->fetch(PDO::FETCH_ASSOC);

                                //$tot_cgs=$notifcount_cgs['notifcount'];
                                $tot_cgs=$notifcount_cgs['notifcount']+$notifcount2_cgs['notifcount2'];

                              

                         ?>
                            <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                               <div class="contacts__item">
                                  <a  class="contacts__img">
                                      <?php // echo '<img class="imag" id="'.$row['user_id'].'" src="data:image/jpeg;base64,'.base64_encode($row['user_image']).'"  width="116px" height="116px" />';
                                      ?>

                                      <img class="imag" src="img/logo/<?=$row['name']?>.png"  style="width:116px; height:116px;" >

                                  </a>
                                
                               <div class="contacts__info">
                                    <strong style="font-size:11px;"><?php echo $row['name'];?></strong>
                                    <small style="font-size:10px;"><?php echo $row['user_email'];?></small>
                               </div>
                                  <style>
                                      .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                      .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                      .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                      .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                      .abtn:hover{ color:white; }
                                  </style>
                                  <?php
                                   if ($tot_cgs > 0){
                                  ?>
                                  <button class="contacts__btn"><a class="abtn"  href="session_var/var_admin_mun.php?mun=<?=$lowercase_name?>">View Report&nbsp;<span class="badge" style=" border: 1px solid maroon; border-radius: 50%; padding-left: 3px;  padding-right: 3px;  padding-top: 2px;  padding-bottom: 1px; background-color: red;"><?php echo $tot_cgs;?></span></a></button>
                                  <?php
                                  }else{
                                  ?>
                                  <button class="contacts__btn"><a class="abtn"  href="session_var/var_admin_mun.php?mun=<?=$lowercase_name?>">View Report</a></button>
                                  <?php
                                  }
                                  ?>
                            </div>
                        </div>
                        <?php 
                         }
                         } else {
                           
                         }
                            $conn->close();
                         ?>

                       





                        
                        </div>
                    </div>
                    </div>


                     <!--END cgs sorsogon-->






                

                <footer class="footer hidden-xs-down">
                    <!--<p>© CopCoder. All rights reserved 2019</p>-->

                    
                </footer>
            </section>
        </main>

      
        <!-- Vendors -->
        <script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="vendors/bower_components/popper.js/dist/umd/popper.min.js"></script>
        <script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="vendors/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
        
    </body>


</html>