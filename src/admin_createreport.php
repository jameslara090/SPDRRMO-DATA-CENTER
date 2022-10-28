

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
        <link rel="stylesheet" href="vendors/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="vendors/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="vendors/bower_components/nouislider/distribute/nouislider.min.css">
        <link rel="stylesheet" href="vendors/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css">
        <link rel="stylesheet" href="vendors/bower_components/trumbowyg/dist/ui/trumbowyg.min.css">
        <link rel="stylesheet" href="vendors/bower_components/rateYo/min/jquery.rateyo.min.css">

        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">

        <!-- Demo only -->
        <link rel="stylesheet" href="demo/css/demo.css">
       
       

      
        
    </head>
      
    <body data-sa-theme="3">
        <main class="main">
            <div class="page-loader">
                <div class="page-loader__spinner">
                    <svg viewBox="25 25 50 50">
                        <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                    </svg>
                </div>
            </div>

            <header class="header">
                <div class="navigation-trigger hidden-xl-up" data-sa-action="aside-open" data-sa-target=".sidebar">
                    <i class="zmdi zmdi-menu"></i>
                </div>

                <div class="logo hidden-sm-down">
                    <h1><a href="index1.php">SPDRRMO Data Center</a></h1>
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

                    <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="top-nav__notify"><i class="zmdi zmdi-account-circle"></i></a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu--block">
                        <div class="dropdown-header">
                            My Profile

                           
                        </div>

                        

                        <div class="listview listview--hover">

                        <a href="#" class="listview__item">
                                <img src="img/Male User_50px.png" class="listview__img" alt="">

                                <div class="listview__content">
                                    <div class="listview__heading h5">
                                        spdrrmo-sorsogon@ymail.com
                                    </div>
                                    
                                </div>
                            </a>

                      
                            <a href="#" class="listview__item">
                                <img src="img/Edit File_48px.png" class="listview__img" alt="">

                                <div class="listview__content">
                                    <div class="listview__heading h5">
                                         Edit Profile
                                    </div>
                                    
                                </div>
                            </a>

                           
                         

                           

                           

                            <a href="#" class="view-more">Logout</a>
                        </div>
                    </div>
                </li>
                   
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

       

            <section class="content">
                <div class="content__inner">
                <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Create Report</h4>
                            

                            <form action="" method="POST">

                             <div class="row">
                             <div class="col-sm-4">
                                    <label>Date</label>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                        <div class="form-group">
                                            <input type="text" required="" class="form-control date-picker" placeholder="Date Happened">
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                </div>
                             </div>
                            <div class="form-group">
                                <textarea class="form-control" required="" rows="15" name="report" placeholder="Type text ..."></textarea>
                                <i class="form-group__bar"></i>
                            </div>

                            <br>
                            <div class="form-group">
                            <button type="submit" class="btn btn-light btn--icon-text"><i class="zmdi zmdi-arrow-forward"></i>Deliver</button>
                                
                            </div>
                            </form>
                           

                            
                        </div>
                    </div>

                   

                   

                    <a class="btn btn-light btn--action btn--fixed zmdi zmdi-plus" href="create_report.php" ></a>
                </div>

                <footer class="footer hidden-xs-down">
                    <p>© CopCoder. All rights reserved.</p>

                    
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


        <script src="vendors/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script src="vendors/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="vendors/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="vendors/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        <script src="vendors/bower_components/nouislider/distribute/nouislider.min.js"></script>
        <script src="vendors/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <script src="vendors/bower_components/trumbowyg/dist/trumbowyg.min.js"></script>
        <script src="vendors/bower_components/rateYo/min/jquery.rateyo.min.js"></script>
        <script src="vendors/bower_components/jquery-text-counter/textcounter.min.js"></script>
        <script src="vendors/bower_components/autosize/dist/autosize.min.js"></script>

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
    </body>


</html>