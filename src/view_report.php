<?php

error_reporting(0);

require_once('session/check_admin.php');

require_once('config/dbcon.php');
$conn2 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$tname=$_GET['name'];
//check
$check=$_GET['check'];
$checkresult=""; 
if($check =="1"){ 
    $checkresult="1";  
}elseif($check=="2"){
    $checkresult="2";
}else{  
    $checkresult=""; 
}



//end
if ($checkresult==1){
    try{
        $stmt = $conn2->prepare("UPDATE category SET stat=1 WHERE category=:tname");
        $stmt->bindParam(':tname', $_GET['name']);
        $stmt->execute();
        echo "<script>window.location='view_report.php'</script>";
    }
    catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
    }
}elseif ($checkresult==2){
    try{
        $stmt = $conn2->prepare("UPDATE category SET stat=0 WHERE category=:tname2");
        $stmt->bindParam(':tname2', $_GET['name']);
        $stmt->execute();
        echo "<script>window.location='view_report.php'</script>";
    }
    catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
    }
}else{
    echo $checkresult;
}



?>


<!DOCTYPE html>
<html lang="en">
    

<head>
        <meta charset="utf-8">
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
        <title>DISASTER REPORT ( LDRRMO / SPDRRMC )</title>
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <style>
            .contacts__btn1{  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
            .contacts__btn1 .badge1 {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
            .abtn1{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
            .abtn1:hover{ color:white; }
        </style>
       

        
    </head>
      
    <body data-sa-theme="3">
        <main class="main">
            <?php  include('include/header.php');?>
            
            <?php include('logs_2.php')?>

            <script>
                var autoloadaa=setInterval(function(num){
                        var count = <?php echo $cont;?>;
                        document.title ="DISASTER REPORT ( LDRRMO / SPDRRMC )";
                        if (count > 0){
                            console.log(document.title = "(" + $.trim(count) + ") " + document.title);
                        }else{
                            console.log(document.title = document.title);
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


            <section class="content">
                <div class="content__inner"> 
                   <div class="row">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="index2.php">Users  </a>  &nbsp;⟶&nbsp;&nbsp;
                     <!-- <label>[&nbsp; <?php echo $_SESSION["admin_mun"];?> &nbsp;] &nbsp;Typhoon</label> -->
                     <label><?php echo $_SESSION["admin_mun"];?></label>
                   </div>
                    
                    <header class="content__title" style="border-bottom:1px solid rgba(0,0,0,0.1); padding:0px;text-align:right;">FILTER: &nbsp;
                     
                       <select name="" id="filter">
                           <option value="ALL">ALL HAZARD</option> 
                           <option value="TYPHOON">TYPHOON</option> 
                           <option value="EARTHQUAKE">EARTHQUAKE</option> 
                           <option value="VOLCANIC">VOLCANIC</option> 
                           <option value="LANDSLIDE">LANDSLIDE</option> 
                           <option value="TSUNAMI">TSUNAMI</option> 
                           <option value="FLOODING">FLOODING</option>
                           <option value="ACTIVITYREPORT">ACTIVITY REPORT</option>  
                       </select>
                    </header>
                    <script src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
                    <script>
                        $(function() {
                            $('#filter').change(function(){
                                if($('#filter').val() == 'TYPHOON') {
                                    $('div#TYPHOON').toggle(true);
                                    $('div#EARTHQUAKE').toggle(false);
                                    $('div#VOLCANIC').toggle(false);
                                    $('div#LANDSLIDE').toggle(false);
                                    $('div#TSUNAMI').toggle(false);
                                    $('div#FLOODING').toggle(false);
                                    $('div#ACTIVITYREPORT').toggle(false);
                                }else if($('#filter').val() == 'EARTHQUAKE') {
                                    $('div#TYPHOON').toggle(false);
                                    $('div#EARTHQUAKE').toggle(true);
                                    $('div#VOLCANIC').toggle(false);
                                    $('div#LANDSLIDE').toggle(false);
                                    $('div#TSUNAMI').toggle(false);
                                    $('div#FLOODING').toggle(false);
                                    $('div#ACTIVITYREPORT').toggle(false);
                                }else if($('#filter').val() == 'VOLCANIC') {
                                    $('div#TYPHOON').toggle(false);
                                    $('div#EARTHQUAKE').toggle(false);
                                    $('div#VOLCANIC').toggle(true);
                                    $('div#LANDSLIDE').toggle(false);
                                    $('div#TSUNAMI').toggle(false);
                                    $('div#FLOODING').toggle(false);
                                    $('div#ACTIVITYREPORT').toggle(false);
                                }else if($('#filter').val() == 'LANDSLIDE') {
                                    $('div#TYPHOON').toggle(false);
                                    $('div#EARTHQUAKE').toggle(false);
                                    $('div#VOLCANIC').toggle(false);
                                    $('div#LANDSLIDE').toggle(true);
                                    $('div#TSUNAMI').toggle(false);
                                    $('div#FLOODING').toggle(false);
                                    $('div#ACTIVITYREPORT').toggle(false);
                                }else if($('#filter').val() == 'TSUNAMI') {
                                    $('div#TYPHOON').toggle(false);
                                    $('div#EARTHQUAKE').toggle(false);
                                    $('div#VOLCANIC').toggle(false);
                                    $('div#LANDSLIDE').toggle(false);
                                    $('div#TSUNAMI').toggle(true);
                                    $('div#FLOODING').toggle(false);
                                    $('div#ACTIVITYREPORT').toggle(false);
                                }else if($('#filter').val() == 'FLOODING') {
                                    $('div#TYPHOON').toggle(false);
                                    $('div#EARTHQUAKE').toggle(false);
                                    $('div#VOLCANIC').toggle(false);
                                    $('div#LANDSLIDE').toggle(false);
                                    $('div#TSUNAMI').toggle(false);
                                    $('div#FLOODING').toggle(true);
                                    $('div#ACTIVITYREPORT').toggle(false);
                                }else if($('#filter').val() == 'ACTIVITYREPORT') {
                                    $('div#TYPHOON').toggle(false);
                                    $('div#EARTHQUAKE').toggle(false);
                                    $('div#VOLCANIC').toggle(false);
                                    $('div#LANDSLIDE').toggle(false);
                                    $('div#TSUNAMI').toggle(false);
                                    $('div#FLOODING').toggle(false);
                                    $('div#ACTIVITYREPORT').toggle(true);
                                }else{
                                    $('div#TYPHOON').toggle(true);
                                    $('div#EARTHQUAKE').toggle(true);
                                    $('div#VOLCANIC').toggle(true);
                                    $('div#LANDSLIDE').toggle(true);
                                    $('div#TSUNAMI').toggle(true);
                                    $('div#FLOODING').toggle(true);
                                    $('div#ACTIVITYREPORT').toggle(true);
                                }
                            });
                        });
                    </script>


                <div class="contacts row">
                    <?php
                        require_once('config/main_tiles_con.php');
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        } 
                        $sql = "SELECT * FROM category WHERE stat='0' and muni='".$_SESSION['admin_mun']."' or muni='All'  order by cid DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {

                          if($_SESSION["admin_mun"]=="cgs sorsogon"){

                            $stmt = $conn2->prepare("SELECT COUNT(DISTINCT(asof)) AS notifcount,typhoon_name FROM cgs_record WHERE notif=0 and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcount = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(addeddate) AS notifcountdate,typhoon_name FROM cgs_attachfile WHERE notif=0 and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountdate = $stmt->fetch(PDO::FETCH_ASSOC);


                            $stmt = $conn2->prepare("SELECT COUNT(DISTINCT(asof)) AS notifcountreported,typhoon_name FROM cgs_record WHERE  typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreported = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(addeddate) AS notifcountreporteddate,typhoon_name FROM cgs_attachfile WHERE  typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreporteddate = $stmt->fetch(PDO::FETCH_ASSOC);

                            $totrep=$notifcountreported['notifcountreported']+$notifcountreporteddate['notifcountreporteddate'];
                            $notifcounttot=$notifcount['notifcount']+$notifcountdate['notifcountdate'];

                          }else{
                            $stmt = $conn2->prepare("SELECT COUNT(DISTINCT(tdatetime)) AS notifcount,typhoon_name FROM typhoon_form WHERE notif=0 and mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcount = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(addeddate) AS notifcountdate,typhoon_name FROM typhoon_attachfile WHERE notif=0 and mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountdate = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(d_re) AS notifcountdate FROM 1pod WHERE notif=0 and mun='".$_SESSION["admin_mun"]."' and tname='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountdate_dam = $stmt->fetch(PDO::FETCH_ASSOC);




                            $stmt = $conn2->prepare("SELECT COUNT(DISTINCT(tdatetime)) AS notifcountreported,typhoon_name FROM typhoon_form WHERE mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreported = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(addeddate) AS notifcountreporteddate,typhoon_name FROM typhoon_attachfile WHERE mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreporteddate = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(d_re) AS notifcountreporteddate FROM 1pod WHERE mun='".$_SESSION["admin_mun"]."' and tname='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreporteddate_dam = $stmt->fetch(PDO::FETCH_ASSOC);

                            $totrep=$notifcountreported['notifcountreported']+$notifcountreporteddate['notifcountreporteddate']+$notifcountreporteddate_dam['notifcountreporteddate'];
                            $notifcounttot=$notifcount['notifcount']+$notifcountdate['notifcountdate']+$notifcountdate_dam['notifcountdate'];
                          }
                        ?>
                       
                         <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item" id="<?=str_replace(' ','',$row['ttype'])?>">
                                <a  class="contacts__img">
                                    <img src="img/category/<?=$row['ttype']?>1.png" alt="" title="<?=$row['ttype']?>">
                                </a>
                            <div class="contacts__info">
                            <style>
                            #d{
                                border:1px ridge #081111;
                            }
                            </style>
                                <!--<strong style="font-size:11px;">  <span style=""> <a href="view_report.php?name=<?=$row['category']?>&check=1"> <i class="zmdi zmdi-check-square"></i> </a> </span>&nbsp;  <?php echo $row['category'];?></strong>-->
                                        <?php if($row['ttype']=="TYPHOON" || $row['ttype']=="ACTIVITY REPORT"){ ?>
                                          <strong style="font-size:11px;" title="<?=$row['ttype']?>"  data-toggle="popover" data-placement="top" data-html="true" data-content=" <span style=font-size:11px;><span style=color:green;>Title:</span> <?=$row['category'].'<br><span style=color:green;>Date:</span> '.$row['daterange'].'<br><span style=color:green;>Remarks:</span> <textarea id=d wrap=off cols=37 rows=8 style=background-color:rgba(0,0,0,0);color:white; readonly >'.$row['remarks'].'</textarea>' ?></span>  "    > <?=$row['category'].'<br>'.$row['daterange'].'  '.$row['remarks']?> </strong>
                                        <?php }else{ ?>
                                          <strong style="font-size:11px;" title="<?=$row['ttype']?>"  data-toggle="popover" data-placement="top" data-html="true" data-content=" <span style=font-size:11px;>Date: <?=$row['category'].' / '.$row['daterange'].'<br>Remarks: '.$row['remarks']?></span>  "     > <?=$row['category'].' / '.$row['daterange'].'<br>'.$row['remarks']?> </strong>
                                        <?php } ?>

                                <small style="font-size:10px;">Total Reported: <?=$totrep?></small>
                            </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <?php
                                if ( $notifcounttot > 0){
                                ?>
                                   <button class="contacts__btn"><a class="abtn"  href="session_var/var_admin.php?name=<?=$row['category']?>&dtype=<?=$row['ttype']?>">View Report&nbsp;<span class="badge" style=" border: 1px solid maroon; border-radius: 50%; padding-left: 3px;  padding-right: 3px;  padding-top: 2px;  padding-bottom: 1px; background-color: red;"><?=$notifcounttot?></span></a></button>
                                <?php }else{ ?>
                                   <button class="contacts__btn"><a class="abtn"  href="session_var/var_admin.php?name=<?=$row['category']?>&dtype=<?=$row['ttype']?>">View Report</a></button>
                                <?php } ?>
                           </div>
                         </div>
                        
                    <?php 
                        }
                        } else {
                        
                        }
                        $conn->close();
                        ?>
                </div>
                <hr>
                <div>&nbsp;</div><div>&nbsp;</div>


                <div class="contacts row">
                    <?php
                        require_once('config/main_tiles_con.php');
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        } 
                        $sql = "SELECT * FROM category WHERE stat='1' order by cid DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
 
                         if($_SESSION["admin_mun"]=="cgs sorsogon"){
                            $stmt = $conn2->prepare("SELECT COUNT(DISTINCT(asof)) AS notifcount2,typhoon_name FROM cgs_record WHERE notif=0 and  typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcount2 = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(addeddate) AS notifcount2date,typhoon_name FROM cgs_attachfile WHERE notif=0 and mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcount2date = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(DISTINCT(asof)) AS notifcountreported2,typhoon_name FROM cgs_record WHERE  typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreported2 = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(addeddate) AS notifcountreported2date,typhoon_name FROM cgs_attachfile WHERE mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreported2date = $stmt->fetch(PDO::FETCH_ASSOC);

                            $totrep2=$notifcountreported2['notifcountreported2']+$notifcountreported2date['notifcountreported2date'];
                            $notifcount4=$notifcount2['notifcount2']+$notifcount2date['notifcount2date'];
                         }else{
                            $stmt = $conn2->prepare("SELECT COUNT(DISTINCT(tdatetime)) AS notifcount2,typhoon_name FROM typhoon_form WHERE notif=0 and mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcount2 = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(addeddate) AS notifcount2date,typhoon_name FROM typhoon_attachfile WHERE notif=0 and mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcount2date = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(d_re) AS notifcount2date FROM 1pod WHERE notif=0 and mun='".$_SESSION["admin_mun"]."' and tname='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcount2date_dam = $stmt->fetch(PDO::FETCH_ASSOC);



                            $stmt = $conn2->prepare("SELECT COUNT(DISTINCT(tdatetime)) AS notifcountreported2,typhoon_name FROM typhoon_form WHERE mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreported2 = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(addeddate) AS notifcountreported2date,typhoon_name FROM typhoon_attachfile WHERE mun='".$_SESSION["admin_mun"]."' and typhoon_name='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreported2date = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn2->prepare("SELECT COUNT(d_re) AS notifcountreported2date FROM 1pod WHERE mun='".$_SESSION["admin_mun"]."' and tname='".$row['category']."' ");
                            $stmt->execute(); 
                            $notifcountreported2date_dam = $stmt->fetch(PDO::FETCH_ASSOC);

                            $totrep2=$notifcountreported2['notifcountreported2']+$notifcountreported2date['notifcountreported2date']+$notifcountreported2date_dam['notifcountreported2date'];
                            $notifcount4=$notifcount2['notifcount2']+$notifcount2date['notifcount2date']+$notifcount2date_dam['notifcount2date'];
                         }

                         
                        ?>
                       
                         <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item">
                                <a href="#" class="contacts__img">
                                    <img src="img/category/typhoon.png" alt=""  title="<?=$row['ttype']?>">
                                </a>
                            <div class="contacts__info">
                                <strong style="font-size:11px;"><span style=""> <a href="view_report.php?name=<?=$row['category']?>&check=2"> <i class="zmdi zmdi-minus-square"></i> </a> </span>&nbsp;<?php echo $row['category'];?></strong>
                                <small style="font-size:10px;">Total Reported: <?=$totrep2?></small>
                            </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <?php
                                if ($notifcount4 > 0){
                                ?>
                                   <button class="contacts__btn"><a class="abtn"  href="session_var/var_admin.php?name=<?=$row['category']?>">View Report&nbsp;<span class="badge" style=" border: 1px solid maroon; border-radius: 50%; padding-left: 3px;  padding-right: 3px;  padding-top: 2px;  padding-bottom: 1px; background-color: red;"><?=$notifcount4?></span></a></button>
                                <?php }else{ ?>
                                   <button class="contacts__btn"><a class="abtn"  href="session_var/var_admin.php?name=<?=$row['category']?>">View Report</a></button>
                                <?php } ?>
                           </div>
                         </div>
                        
                    <?php 
                        }
                        } else {
                        
                        }
                        $conn->close();
                        ?>
                </div>





                    <!--<div class="contacts row">
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item">
                                <a href="#" class="contacts__img">
                                    <img src="img/category/typhoon.png" alt="">
                                </a>

                                <div class="contacts__info">
                                    <strong>BAGYONG USMAN</strong>
                                    <small>number of report: 4</small>
                                </div>

                                <button class="contacts__btn"><a style="color:white; font-size: 10px; font-weight: bold;  padding-left: 10px; padding-right: 10px;  padding-bottom: 7px; padding-top: 7px; border-top: 1px solid #234543" href="index1.php">View Report&nbsp;<span class="badge" style="border: 1px solid maroon; border-radius: 50%; padding-left: 3px;  padding-right: 3px;  padding-top: 2px;  padding-bottom: 1px; background-color: red;">1</span></a></button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="contacts row">
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item">
                                <a href="#" class="contacts__img">
                                    <img src="img/category/typhoon.png" alt="">
                                </a>

                                <div class="contacts__info">
                                    <strong>BAGYONG USMAN</strong>
                                    <small>number of report: 4</small>
                                </div>

                                <button class="contacts__btn"><a style="color:white; font-size: 10px; font-weight: bold;  padding-left: 10px; padding-right: 10px;  padding-bottom: 7px; padding-top: 7px; border-top: 1px solid #234543" href="index1.php">View Report&nbsp;<span class="badge" style="border: 1px solid maroon; border-radius: 50%; padding-left: 3px;  padding-right: 3px;  padding-top: 2px;  padding-bottom: 1px; background-color: red;">1</span></a></button>
                            </div>
                        </div>
                    </div>-->






                    </div>
                </div>
          



             






                

                <footer class="footer hidden-xs-down">
                    <!--<p>© CopCoder. All rights reserved.</p>-->

                    
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