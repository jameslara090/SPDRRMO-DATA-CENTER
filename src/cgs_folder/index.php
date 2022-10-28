<?php

require_once('../session/check_user.php');

?>


<!DOCTYPE html>
<html lang="en">
    

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="../vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="../vendors/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="../vendors/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="../vendors/bower_components/select2/dist/css/select2.min.css">
         <link rel="stylesheet" href="../css/custom.css">
        <!-- App styles -->
        <link rel="stylesheet" href="../css/app.min.css">

        <?php include('../include/head_tab.php')?>

    
    </head>
      
    <body data-sa-theme="3">
        <main class="main">
            <?php include('../include/user_header.php');?>
            <aside class="sidebar">
                <div class="scrollbar-inner">
                   <?php include('../include/user_profile.php')?>
                </div>
                    <ul class="navigation">
                       <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="index.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                       <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    </ul>
                </div>
            </aside>


            <section class="content">
                <div class="content__inner"> 
                   <div class="row">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <!--<a href="user_home.php">Reporting  </a>  &nbsp;>&nbsp;-->
                      <label>Typhoon</label>
                   </div>
                    
                    <header class="content__title">
                    </header>

                <div class="contacts row">
                    <?php
                        require_once('../config/main_tiles_con.php');
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        } 
                        $sql = "SELECT * FROM category WHERE stat='0' order by cid DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                        ?>
                       
                         <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item">
                                <a href="#" class="contacts__img">
                                    <img src="../img/category/typhoon.png" alt="">
                                </a>
                            <div class="contacts__info">
                                <strong style="font-size:11px;"><?php echo $row['category'];?></strong>
                            </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <button class="contacts__btn"><a class="abtn"  href="../session_var/var.php?name=<?=$row['category']?>">Manage Record&nbsp;</a></button>
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
                        require_once('../config/main_tiles_con.php');
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        } 
                        $sql = "SELECT * FROM category WHERE stat='1' order by cid DESC ";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                        ?>
                       
                         <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item">
                                <a href="#" class="contacts__img">
                                    <img src="../img/category/typhoon.png" alt="">
                                </a>
                            <div class="contacts__info">
                                <strong style="font-size:11px;"><?php echo $row['category'];?></strong>
                            </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <button class="contacts__btn"><a class="abtn"  href="../session_var/var.php?name=<?=$row['category']?>">Manage Record&nbsp;</a></button>
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
          



             






                

                <footer class="footer hidden-xs-down">
                    <!--<p>© CopCoder. All rights reserved.</p>-->

                    
                </footer>
            </section>
           
        </main>

      
        <!-- Vendors -->
        <script src="../vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="../vendors/bower_components/popper.js/dist/umd/popper.min.js"></script>
        <script src="../vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="../vendors/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="../vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="../vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <!-- App functions and actions -->
        <script src="../js/app.min.js"></script>
    </body>


</html>