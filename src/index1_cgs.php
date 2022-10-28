<?php

require_once('session/check_admin.php');

require_once('config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT DISTINCT asof,notif FROM cgs_record WHERE typhoon_name='".$_SESSION["admin_typhoonname"]."'  order by asof DESC");
$stmt->execute(); 
$resulttf = $stmt->fetchAll();


$stmt = $conn->prepare("SELECT filename,addeddate,remarks,notif FROM cgs_attachfile WHERE typhoon_name='".$_SESSION["admin_typhoonname"]."' and mun='".$_SESSION["admin_mun"]."' order by addeddate DESC");
$stmt->execute(); 
$resultfile = $stmt->fetchAll();
    

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

        <link rel="stylesheet" href="demo/css/demo.css">

        <?php // include('include/head_tab.php')?>

        <link rel="icon" type="image/png" href="img/icon.png" sizes="96x96">
        <title>DISASTER REPORT ( LDRRMO / SPDRRMC )</title>
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <style>
            .contacts__btn1{  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
            .contacts__btn1 .badge1 {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
            .abtn1{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
            .abtn1:hover{ color:white; }
        </style>


        

        <?php include('include/head_tab.php')?>

        
    </head>
      
    <body data-sa-theme="3">
        <main class="main">

           <?php include('include/header.php')?>

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

                    

                    <div class="scrollbar-inner">
                       <?php include('include/profile.php')?>
                    </div>

                    <ul class="navigation">
                    <li style="margin-bottom:1px; border-left:3px solid green; "> <a style="padding:0px; margin:0px;" href="index2.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="add_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;<i class="zmdi zmdi-accounts-add"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add User</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="situation.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Situation Report</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    </ul>
                </div>
            </aside>

           

            <section class="content">
                <div class="content__inner">

                   <div class="row" style="margin-bottom:20px;">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="index2.php">Users  </a>  &nbsp;>&nbsp;
                      <a href="view_report.php">[&nbsp; <?php echo $_SESSION["admin_mun"];?> &nbsp;] &nbsp;Typhoon  </a>  &nbsp;>&nbsp;
                      <label>Report List for: <?php echo $_SESSION["admin_typhoonname"];?></label>
                   </div>
                   

                    <div class="row todo">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="toolbar toolbar--inner">
                                   

                                    <!--<div class="actions">
                                        <a href="list.php" class="actions__item zmdi zmdi-view-headline" title="View All Record in <?=$_SESSION['admin_typhoonname']?>"></a>
                                    </div>-->

                                   
                                </div>

                                <div class="listview listview--bordered">
                                    <?php
                                     foreach($resulttf as $value){
                                    ?>
                                    <div class="listview__item" style=" padding-left:30px; padding-top:8px;padding-bottom:8px;">
                                     <?php
                                     if ($value['notif']=="0"){
                                     ?>
                                      <input type="checkbox" name="" onclick="return false">
                                     <?php 
                                     }else{
                                     ?>
                                      <input type="checkbox" name="" onclick="return false" checked>
                                     <?php
                                     }
                                     ?>
                                        <!--<a href="list.php?trig=date&value=<?=$value['tdatetime']?>" class="custom-control custom-control--char" title="View Record in <?=$value['tdatetime']?>">-->
                                        <a href="list_cgs.php" class="custom-control custom-control--char" title="View Record in <?=$value['asof']?>">
                                            <div class="todo__info" style="">
                                                <span>  <img src="img/form.png" width="15px" heigth="10px" alt=""> CGS REPORT</span>
                                                <small> &nbsp;&nbsp; ( <?=$value['asof']?> ) </small>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    


                                     <?php
                                     foreach($resultfile as $value1){
                                    ?>
                                    <div class="listview__item" style=" padding-left:30px; padding-top:8px;padding-bottom:8px;">
                                     <?php
                                     if ($value1['notif']=="0"){
                                     ?>
                                      <input type="checkbox" name="" onclick="return false">
                                     <?php 
                                     }else{
                                     ?>
                                      <input type="checkbox" name="" onclick="return false" checked>
                                     <?php
                                     }
                                     ?>
                                        <!--<a href="attach.php?trig=date&value=<?=$value1['addeddate']?>" class="custom-control custom-control--char" title="View Record in <?=$value1['addeddate']?>">-->
                                        <a href="attach_cgs.php" class="custom-control custom-control--char" title="View Record in <?=$value1['addeddate']?>">
                                            <div class="todo__info" style="">
                                                <?php $fname = explode('_-', $value1['filename']); ?>
                                                <span>  <img src="img/attach.png" width="15px" heigth="10px" alt=""> ATTACH FILE ( <?=$fname[2]?> )</span>
                                                <small> &nbsp;&nbsp; ( <?=$value1['addeddate']?> )  &nbsp; &nbsp;<?=$value1['remarks']?></small>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                    }
                                    ?>

                                    
                                </div>
                            </div>
                        </div>

                        
                    </div>

                   

                   
                </div>

                <footer class="footer hidden-xs-down">
                    

                    
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