<?php
   
  // ini_set('session.gc_maxlifetime',172800); 
   
   error_reporting(0);
   
   include("config/login_config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['email']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT * FROM users WHERE user_email = '$myusername' and user_pass = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      //$active = $row['active'];
      

      $count = mysqli_num_rows($result);

      $exp = explode("-", $row['name']);
      $lowercase_name = strtolower($exp[1]);

      $id=$row['user_id'];
      $email=$_POST['email'];
      $utype=$row['usertype'];



        require_once('config/dbcon.php');
        $conn2 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        date_default_timezone_set('Asia/Manila');
        $dates = date('Y-n-j G:i:s A');
        $nowd=explode(' ',$dates);
        $nowd1=explode(':',$nowd[1]);

        $date=$nowd[0];
        $hour=intval($nowd1[0]);
        $min=intval($nowd1[1]);
        $sec=intval($nowd1[2]);
        $fdate = $date." ".$hour.":".$min.":".$sec;
        
        require('UserInfo.php');
        $ip = UserInfo::get_ip();
        $device = UserInfo::get_device();
        $devicename = gethostname();
        $os = UserInfo::get_os();
        $browser = UserInfo::get_browser();
 
 
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1 and $row['usertype']=="Administrator") {
         //session_register("USER_ID");
         
         $_SESSION['user_id'] = $id;
         $_SESSION['email'] = $email;
         $_SESSION['utype'] = $utype;
         $_SESSION['mun']= $lowercase_name;

         if($exp[0]=="PDRRMO2_IROSIN"){
            $stmt = $conn2->prepare("UPDATE users_log SET user_in='".$fdate."', stat = 'Active', user_ip = '".$ip."', user_device = '".$device."', user_devicename = '".$devicename."', user_os = '".$os."', user_browser = '".$browser."' WHERE user_id='".$id."'   ");   
            $stmt->execute();
         }
         header("location: index2.php");

      }elseif($count == 1 and $row['usertype']=="user1" && $lowercase_name=="cgs sorsogon") {
        
        $stmt = $conn2->prepare("UPDATE users_log SET user_in='".$fdate."', stat = 'Active', user_ip = '".$ip."', user_device = '".$device."', user_devicename = '".$devicename."', user_os = '".$os."', user_browser = '".$browser."' WHERE user_id='".$id."'   ");   
        $stmt->execute();

        $_SESSION['u_user_id'] = $id;
        $_SESSION['u_email'] = $email;
        $_SESSION['u_utype'] = $utype;
        $_SESSION['u_mun']= $lowercase_name;
        
        header("location: cgs_home.php");

      }else {

        $stmt = $conn2->prepare("UPDATE users_log SET user_in='".$fdate."', stat = 'Active', user_ip = '".$ip."', user_device = '".$device."', user_devicename = '".$devicename."', user_os = '".$os."', user_browser = '".$browser."' WHERE user_id='".$id."'   ");   
        $stmt->execute();



        $_SESSION['u_user_id'] = $id;
        $_SESSION['u_email'] = $email;
        $_SESSION['u_utype'] = $utype;
        $_SESSION['u_mun']= $lowercase_name;
       
        header("location: user_home.php");

      }
   }

   //echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

   //echo "hey PDF_set_text_matrix()";


?>

<!DOCTYPE html>
<html lang="en">
    

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="vendors/bower_components/animate.css/animate.min.css">

        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">

        <?php include('include/head_tab.php')?>


      

       

    </head>

    <title>DRRMS-Sorsogon</title>

    <body data-sa-theme="3">
        <div class="login">

        <style>
            .shadow {
                box-shadow: 0 8px 28px -6px black;
            }
            .shadowbtn {
                box-shadow: 0 8px 12px -6px black;
            }
        </style>

            <!-- Login -->
            <div class="login__block active shadow" id="l-login">
                <div class="login__block__header">
                    <i><img src="img/favicon1.png" alt="" srcset="" height="80" width="80"> </i>
                   <label class="form-control-lg">Sign in</label>

                    <!--<div class="actions actions--inverse login__block__actions">
                        <div class="dropdown">
                            <i data-toggle="dropdown" class="zmdi zmdi-more-vert actions__item"></i>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" data-sa-action="login-switch" data-sa-target="#l-register" href="#">Create an account</a>
                                <a class="dropdown-item" data-sa-action="login-switch" data-sa-target="#l-forget-password" href="#">Forgot password?</a>
                            </div>
                        </div>
                    </div>-->
                </div>


                <style>
                        input:-webkit-autofill,
                        input:-webkit-autofill:hover, 
                        input:-webkit-autofill:focus
                        input:-webkit-autofill, 
                        textarea:-webkit-autofill,
                        textarea:-webkit-autofill:hover
                        textarea:-webkit-autofill:focus,
                        select:-webkit-autofill,
                        select:-webkit-autofill:hover,
                        select:-webkit-autofill:focus {
                            border:none !important;
                            -webkit-text-fill-color: inherit !important;
                            -webkit-box-shadow: 0 0 0px 1000px rgba(0,0,0,0.1) inset;
                            -webkit-text-fill-color: #fff !important;
                            transition: background-color 5000s ease-in-out 0s;
                        }
                </style>

                <form action="login.php" method="POST">
                <div class="login__block__body">
                    <div class="form-group">
                    <input type="text" name="email" class="form-control form-control-lg text-center" placeholder="Email Address">
                                <i class="form-group__bar"></i>
                    </div>
                   
                                       

                    <div class="form-group">
                        <input type="password" class="form-control text-center form-control-lg" name="password"  placeholder="Password">
                        <i class="form-group__bar"></i>
                    </div>
                    <button name="login" title="Login" class="btn btn--icon login__block__btn shadowbtn"><i class="zmdi zmdi-long-arrow-right"></i></button>

                   
                </div>

                </form>

                <button  name="loginback" type="submit" class="btn btn--icon login__block__btn"><a title="Back to official website"  style="color:white;" href="http://spdrrmo.org.ph"><i class="zmdi zmdi-long-arrow-left"></a></i></button>

        
            </div>

            <!-- Register -->
            <div class="login__block" id="l-register">
                <div class="login__block__header">
                    <i class="zmdi zmdi-account-circle"></i>
                    Create an account

                    <div class="actions actions--inverse login__block__actions">
                        <div class="dropdown">
                            <i data-toggle="dropdown" class="zmdi zmdi-more-vert actions__item"></i>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" data-sa-action="login-switch" data-sa-target="#l-login" href="#">Already have an account?</a>
                                <a class="dropdown-item" data-sa-action="login-switch" data-sa-target="#l-forget-password" href="#">Forgot password?</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="login__block__body">
                    <div class="form-group">
                        <input type="text" class="form-control text-center" placeholder="Name">
                    </div>

                    <div class="form-group form-group--centered">
                        <input type="text" class="form-control text-center" placeholder="Email Address">
                    </div>

                    <div class="form-group form-group--centered">
                        <input type="password" class="form-control text-center" placeholder="Password">
                    </div>

                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Accept the license agreement</span>
                        </label>
                    </div>

                    <a href="index.html" class="btn btn--icon login__block__btn"><i class="zmdi zmdi-plus"></i></a>
                </div>
            </div>

            <!-- Forgot Password -->
            <div class="login__block" id="l-forget-password">
                <div class="login__block__header">
                    <i class="zmdi zmdi-account-circle"></i>
                    Forgot Password?

                    <div class="actions actions--inverse login__block__actions">
                        <div class="dropdown">
                            <i data-toggle="dropdown" class="zmdi zmdi-more-vert actions__item"></i>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" data-sa-action="login-switch" data-sa-target="#l-login" href="#">Already have an account?</a>
                                <a class="dropdown-item" data-sa-action="login-switch" data-sa-target="#l-register" href="#">Create an account</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="login__block__body">
                    <p class="mb-5">Lorem ipsum dolor fringilla enim feugiat commodo sed ac lacus.</p>

                    <div class="form-group">
                        <input type="text" class="form-control text-center" placeholder="Email Address">
                    </div>

                    <a href="index.html" class="btn btn--icon login__block__btn"><i class="zmdi zmdi-check"></i></a>
                </div>
            </div>
        </div>

        <!--<div class="footerr"><style type="text/css">.footerr{ padding-top: 0px; padding-bottom: 0px; position: fixed; left: 0;bottom: 0; width: 100%;color: white; text-align: right; font-size: 14px;font-weight: normal; height: 18px;}.pad{  padding-right: 5px;  font-size: 10px;color: #dddddd; } </style> <label class="pad">Developed by: CoPCoder <code>Mark.J.Dy Colaboration with Wiljames Lara</code>  </label>  </div>
        -->

        
        <!-- Vendors -->
        <script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="vendors/bower_components/popper.js/dist/umd/popper.min.js"></script>
        <script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
    </body>


</html>