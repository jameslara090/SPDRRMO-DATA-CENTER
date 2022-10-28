<?php


require_once('../session/check_user.php');

require_once('../config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt = $conn->prepare("SELECT * FROM users WHERE user_id='$u_user_id' ");
$stmt->execute(); 
$resultlogo = $stmt->fetch(PDO::FETCH_ASSOC);

//$datestart=date('Y-m-d');
//echo date('Y-m-d',strtotime('+30 days',strtotime($datestart))) . PHP_EOL;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['save1'])){
        try{
            if($resultlogo['user_pass']==$_POST['user_pass_old'] && $_POST['user_pass_new'] != ""){
                $stmt = $conn->prepare("UPDATE users SET user_email='".$_POST['user_email']."', user_pass='".$_POST['user_pass_new']."', usertype='".$u_utype."', name='".$_POST['name']."'  WHERE user_id='".$u_user_id."' ");
                $stmt->execute();
                echo "<script>alert('✔ Successfully Updated'); window.location='logout_user.php'</script>";
            }else{
                echo "<script>alert('Please check your old password (incorrect)! or New Password (Empty)!'); window.location='changepass_user.php'</script>";
            }
            
            }
            catch (Exception $e){ echo $e->getMessage() . "<br/>";while($e = $e->getPrevious()) {echo 'Previous Error: '.$e->getMessage() . "<br/>";}
            }
    }
    if(isset($_POST['cancel'])){
        echo "<script>window.location='changepass_user.php'</script>";
    }
}

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

            <?php include('../include/user_header.php')?>

            <aside class="sidebar">
                <div class="scrollbar-inner">
                  <?php include('../include/user_profile.php')?>
                </div>
                    <ul class="navigation">
                        <li style="margin-bottom:1px; "> <a style="padding:0px; margin:0px;" href="index.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                        <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="changepass_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    </ul>
                </div>
            </aside>

           
</div>

            <section class="content">
                <div class="content__inner">
                 <header class="content__title">
                 </header>

                


                   <style>
                    .center {
                    margin: auto;
                    width: 400px;;
                    padding: 30px;
                    }
                    </style>
                   <form action="changed.php" method="post">
                   <div class="row" style="">
                   
                    <div class="center" style="background-color:rgba(0,0,0,0.2); text-align:center;" >

                        <div style="float:center;">
                          <img class="user__img" <?php  echo '<img  src="data:image/jpeg;base64,'.base64_encode($resultlogo['user_image']).'"  width="116px" height="116px" />';?>
                        </div>

                        <div style="float:center; padding:20px;padding-bottom:10px;">Change your Account Information</div>
                        <div style="float:center; padding:0px;padding-bottom:15px;"><code>Note: If you click " Save Changes " bellow it will logout automatically to make changes!</code></div>
                            <div class="input-group">
                              <span style="width:130px; height:28px; margin:0px; text-align:center; background-color:rgba(0,0,0,0.3); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5; margin:3px; padding:0px; vertical-align : middle;text-align:right; font-size:11px; padding-top:4px; margin-right:0px;  padding-right:4px;">New Email : </span>
                              <input type="text"   name="user_email"  value="<?=$resultlogo['user_email']?>"  style="width:100%; height:28px; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5; margin:3px; margin-left:0px;" >
                            </div>
                            <!--<div class="input-group">
                              <span style="width:130px; height:28px; margin:0px; text-align:center; background-color:rgba(0,0,0,0.3); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5; margin:3px; padding:0px; vertical-align : middle;text-align:right; font-size:11px; padding-top:4px; margin-right:0px;  padding-right:4px;">New Name : </span>-->
                              <input type="hidden"   name="name"   value="<?=$resultlogo['name']?>"  style="width:100%; height:28px; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5; margin:3px; margin-left:0px;" >
                            <!--</div>-->
                            <div class="input-group">
                              <span style="width:130px; height:28px; margin:0px; text-align:center; background-color:rgba(0,0,0,0.3); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5; margin:3px; padding:0px; vertical-align : middle;text-align:right; font-size:11px; padding-top:4px; margin-right:0px; padding-right:4px; ">Old Password : </span>
                              <input type="password"   name="user_pass_old"    style="width:100%; height:28px; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5; margin:3px; margin-left:0px;" >
                            </div>
                            <div class="input-group">
                              <span style="width:130px; height:28px; margin:0px; text-align:center; background-color:rgba(0,0,0,0.3); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5; margin:3px; padding:0px; vertical-align : middle;text-align:right; font-size:11px; padding-top:4px; margin-right:0px; padding-right:4px; ">New Password : </span>
                              <input type="password"   name="user_pass_new"    style="width:100%; height:28px; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5; margin:3px; margin-left:0px;">
                            </div>

                            <button style="margin-top:20px; float:right; margin-left:5px;" title="Save to Database" type="submit" name="save" id="save"  > Save Changes </button>
                            <button style="margin-top:20px; float:right;" title="Cancel" type="submit" name="cancel" id="cancel"  > Cancel </button>
                    </div>
                   
                   </div>  
                   </form>
                </div>

               
                

                <footer class="footer hidden-xs-down">

                    
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