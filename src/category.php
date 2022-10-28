<?php
session_start();
//Check whether the session variable SESS_MEMBER_ID is present or not
if (!isset($_SESSION['USER_ID']) || ($_SESSION['USER_ID'] == '')) {
    header("location:login.php");
    exit();
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

        
    </head>
      
    <body data-sa-theme="3">
        <main class="main">
            <!--<div class="page-loader">
                <div class="page-loader__spinner">
                    <svg viewBox="25 25 50 50">
                        <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                    </svg>
                </div>
            </div>-->

            <header class="header">
                <div class="navigation-trigger hidden-xl-up" data-sa-action="aside-open" data-sa-target=".sidebar">
                    <i class="zmdi zmdi-menu"></i>
                </div>

                <div class="logo hidden-sm-down">
                    <h1><a href="index2.php"><center>SPDRRMO Data Center</center></a></h1>
                </div>

                <form class="search">
                    <div class="search__inner">
                        <input type="text" class="search__text" placeholder="Search for people, files, documents...">
                        <i class="zmdi zmdi-search search__helper" data-sa-action="search-close"></i>
                    </div>
                </form>
                <div class="clock hidden-md-down">
                    <div class="time">
                        <span class="time__hours"></span>
                        <span class="time__min"></span>
                        <span class="time__sec"></span>
                    </div>
                </div>

                <ul class="top-nav">
                    <li class="hidden-xl-up"><a href="#" data-sa-action="search-open"><i class="zmdi zmdi-search"></i></a></li>

                    

                   
                    <li class="dropdown top-nav__notifications">
                    <a href="logout.php" class="top-nav__notify">
                        <i class="zmdi zmdi-power"></i>
                    </a>
                  
                </li>
                </ul>

              
            </header>

            <aside class="sidebar">
                <div class="scrollbar-inner">

                    

                    <div class="scrollbar-inner">

                    <div class="user">
                    <div class="user__info" data-toggle="dropdown">
                    <?php
                          $email1= $_SESSION['email'];
                          require_once('config/main_tiles_con.php');
                          $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                               die("Connection failed: " . $conn->connect_error);
                            } 
                          $sql = "SELECT * FROM users WHERE user_email='".$email1."'";
                          $result = $conn->query($sql);
                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                               ?>

                                 <img class="user__img" <?php  echo '<img  src="data:image/jpeg;base64,'.base64_encode($row['user_image']).'"  width="116px" height="116px" />';
                                      ?>
                               <div>
                               <div class="user__name"><?php echo $row['name'];?></div>
                               <div class="user__email"><?php echo $row['user_email'];?></div>
                            <?php
                            }
                          } else {
                          }
                            $conn->close();
                       ?>
                    </div>

                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">View Profile</a>
                        <a class="dropdown-item" href="#">Settings</a>
                        <a class="dropdown-item" href="#">Logout</a>
                    </div>
                </div>

                    <ul class="navigation">
                    <li class="navigation__active h5"><a href="index2.php"><i><img class="user__img" src="img/Home_100px.png" alt=""></i>Home</a></li>
                      

                       

                       
                    </ul>
                </div>
            </aside>

           
</div>

            <section class="content">
                <div class="content__inner">
                <header class="content__title">
                        <h1>Users</h1>

                       
                    </header>


                    <div class="contacts row">
                    <?php
                        require_once('config/main_tiles_con.php');
                         $conn = new mysqli($servername, $username, $password, $dbname);
                         if ($conn->connect_error) {
                             die("Connection failed: " . $conn->connect_error);
                         } 
                         $sql = "SELECT * FROM type_category";
                         $result = $conn->query($sql);
                         if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                         ?>
                            <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                               <div class="contacts__item">
                                 <a href="#" class="contacts__img">
                                     <img src="img/category/typhoon.png" alt="">
                                 </a>
                               <div class="contacts__info">
                                    <strong style="font-size:11px;"><?php echo $row['ttype'];?></strong>
                               </div>
                                  <style>
                                      .contacts__btn{  background-color:rgba(0,0,0,0.2); 
                                            position: relative;
                                            display: inline-block;
                                            border-radius:10px;
                                            padding:6px;
                                       }
                                      .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                      .contacts__btn .badge {
                                            position: absolute;
                                            top: -5px;
                                            right: -5px;
                                            padding: 0px 0px;
                                            border-radius: 50%;
                                            background-color: red;
                                            color: white;
                                       }
                                  </style>
                                  <button class="contacts__btn"><a style="color:white; font-size: 10px; font-weight: bold;" href="category.php">View Report&nbsp;<span class="badge" style=" border: 1px solid maroon; border-radius: 50%; padding-left: 3px;  padding-right: 3px;  padding-top: 2px;  padding-bottom: 1px; background-color: red;">5</span></a></button>
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