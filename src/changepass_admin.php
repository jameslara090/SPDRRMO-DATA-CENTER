<?php

//session_start();
//if (!isset($_SESSION['USER_ID']) || ($_SESSION['USER_ID'] == '')) {
//    header("location:login.php");
 //   exit();
//}

require_once('session/check_admin.php');

require_once('config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt = $conn->prepare("SELECT * FROM users WHERE user_id='$user_id' ");
$stmt->execute(); 
$resultlogo = $stmt->fetch(PDO::FETCH_ASSOC);

//$datestart=date('Y-m-d');
//echo date('Y-m-d',strtotime('+30 days',strtotime($datestart))) . PHP_EOL;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['save'])){
        try{
            if($resultlogo['user_pass']==$_POST['user_pass_old'] && $_POST['user_pass_new'] != ""){
                $stmt = $conn->prepare("UPDATE users SET user_email='".$_POST['user_email']."', user_pass='".$_POST['user_pass_new']."', usertype='".$utype."', name='".$_POST['name']."'  WHERE user_id='".$user_id."' ");
                $stmt->execute();
                echo "<script>alert('✔ Successfully Updated'); window.location='logout.php'</script>";
            }else{
                echo "<script>alert('Please check your old password (incorrect)! or New Password (Empty)!'); window.location='changepass_admin.php'</script>";
            }
            
            }
            catch (Exception $e){ echo $e->getMessage() . "<br/>";while($e = $e->getPrevious()) {echo 'Previous Error: '.$e->getMessage() . "<br/>";}
            }
    }
    if(isset($_POST['cancel'])){
        echo "<script>window.location='changepass_admin.php'</script>";
    }
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

            <?php include('include/header.php')?>
            
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
                        <li style="margin-bottom:1px; "> <a style="padding:0px; margin:0px;" href="index2.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="add_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;<i class="zmdi zmdi-accounts-add"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add User</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="situation.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Situation Report</button> </a> </li>
                        <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="changepass_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    
                        <li style="margin-bottom:1px; margin-top:15px;"> <a style="padding:0px; margin:0px;" href="forum_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-comments"></i> &nbsp;&nbsp;&nbsp;&nbsp;Forum / Suggestions</button> </a> </li>
                    </ul>
                </div>
            </aside>

           
</div>

            <section class="content">
                <div class="content__inner">
                 <!--<h5>MANAGE YOUR ACCOUNT</h5>-->
                 <header class="content__title">
                   <!-- <h1>Users</h1>   -->
                 </header>

               


                
                    <!--<div class="row" style=" margin-top:15px; margin-bottom:20px; padding-left:10px;">
                        <form action="4-upload.php" method="post" enctype="multipart/form-data">-->
                            
                      <!--  <div class="" style=" width:100px; text-align:left; margin-left:10px; margin-top:20px; background-color:blue; ">
                            <img  id="blah" name="blah" src="" alt="User Logo" />
                        </div>
                        <div class=" " style=" padding-top:10px;  padding:10px; margin-bottom:15px; background-color:green;">
                            <input  type="file" name="upFile" id="upFile" accept=".png,.gif,.jpg,.webp"  onchange="readURL(this);"  required>
                        </div>
                         <div class="col-md-3" style=" padding-top:10px;">
                            <input type="submit" name="submit" value="Upload Image">
                        </div>

                        </form>-->
                        
                        <script>
                           /* function readURL(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                    
                                reader.onload = function (e) {
                                    $('#blah')
                                        .attr('src', e.target.result)
                                        .width(100)
                                        .height(100);
                                };
                    
                                reader.readAsDataURL(input.files[0]);
                            }
                            }*/
                        </script>

                    <!--<div class="row" style="background-color:rgba(0,0,0,0.2); text-align:center; padding:10px;">
                    <img  id="blah" name="blah" src="" alt="User Logo" /> &nbsp;&nbsp;
                    <input  type="file" name="upFile" id="upFile" accept=".png,.gif,.jpg,.webp"  onchange="readURL(this);"  required>
                    </div>
                                    


                   <div class="row" style="background-color:rgba(0,0,0,0.1);">
                        <div class="col-md-3" style="padding:0px;">
                        <label for="" style="margin:0px;margin-top:9px;">User Email</label>
                        <input type="text"   name="user_email" placeholder="user email"  style="width:100%; height:28px; margin:0px; text-align:left; background-color:rgba(0,0,0,0.2); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5;" >
                        </div>
                        <div class="col-md-3" style="padding:0px;">
                        <label for="" style="margin:0px;margin-top:9px;">User Password</label>
                        <input type="text"   name="user_pass"  placeholder="user password" style="width:100%; height:28px; margin:0px; text-align:left; background-color:rgba(0,0,0,0.2); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5;" >
                        </div>
                        <div class="col-md-3" style="padding:0px;">
                        <label for="" style="margin:0px;margin-top:9px;">Name</label>
                        <input type="text"   name="name" placeholder="name"  style="width:100%; height:28px; margin:0px; text-align:left; background-color:rgba(0,0,0,0.2); border:2px solid rgba(0,0,0,0.1); color:#a8b6b5;" >
                        </div>
                        <div class="col-md-3" style="padding:0px;">
                        <button style="margin-top:30px;" title="Save to Database" type="submit" name="add" id="add"  > Save Changes </button>
                        </div>
                   </div> -->


                   <style>
                    .center {
                    margin: auto;
                    width: 400px;;
                    /*border: 3px solid #73AD21;*/
                    padding: 30px;
                    }
                    </style>
                   <form action="changepass_admin.php" method="post">
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