
<?php
    error_reporting(0);

    require_once('session/check_admin.php');
    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $conn->prepare("SELECT * FROM users WHERE usertype='user1' order by user_email ASC");
    $stmt->execute(); 
    $resulttf = $stmt->fetchAll();


    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['del'])){
            try{
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id='".$_GET['id']."' ");
                $stmt->execute();
                echo "<script>alert('✔ Successfully deleted'); window.location='add_user.php'</script>";
                }
                catch (Exception $e){ echo $e->getMessage() . "<br/>";while($e = $e->getPrevious()) {echo 'Previous Error: '.$e->getMessage() . "<br/>";}
                }
        }
        if(isset($_POST['add'])){
            try{
                require "config/add_user.php";
                if ($_FILES['upFile']['size']==0) { die("No file selected"); }
                if ($_FILES['upFile']['size']==512000) { die("Large Image"); }
                if (exif_imagetype($_FILES['upFile']['tmp_name'])===false) { die("Not an image"); }
                $pdo = new PDO(
                "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, 
                DB_USER, DB_PASSWORD, 
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false]
                );
                $rem="";
                try {
                    $stmt = $pdo->prepare("INSERT INTO `users` (`name`, `user_image`) VALUES (?, ?)");
                    $stmt->execute([$_FILES["upFile"]["name"], file_get_contents($_FILES['upFile']['tmp_name'])]);
                    $imgname =[$_FILES["upFile"]["name"], file_get_contents($_FILES['upFile']['tmp_name'])];
                    $rem=$imgname[0];
                } catch (Exception $ex) {
                    echo "ERROR - " . $ex->getMessage();
                    die();
                    $rem="";
                }

                //echo "rem || ".$rem;
                //echo '<pre>'; print_r([$_FILES["upFile"]["name"], file_get_contents($_FILES['upFile']['tmp_name'])]); echo '</pre>';

                
                //echo "  ||  ".$rem;

                if($rem==""){
                }else{
                    $stmt = $conn->prepare("UPDATE users SET user_email='".$_POST['user_email']."', user_pass='".$_POST['user_pass']."', usertype='".$_POST['user_type']."', name='".$_POST['name']."', status=0 WHERE name='".$rem."' ");
                    $stmt->execute();

                    $stmt = $conn->prepare("DELETE FROM users WHERE user_email='' and user_pass='' ");
                    $stmt->execute();
                    echo "<script>alert('✔ Successfully Added'); window.location='add_user.php'</script>";
                }

                
                }
                catch (Exception $e){ echo $e->getMessage() . "<br/>";while($e = $e->getPrevious()) {echo 'Previous Error: '.$e->getMessage() . "<br/>";}
                }
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

         <!--for date and time-->
        <link rel="stylesheet" href="vendors/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="vendors/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="vendors/bower_components/nouislider/distribute/nouislider.min.css">
        <link rel="stylesheet" href="vendors/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css">
        <link rel="stylesheet" href="vendors/bower_components/trumbowyg/dist/ui/trumbowyg.min.css">
        <link rel="stylesheet" href="vendors/bower_components/rateYo/min/jquery.rateyo.min.css">
        <!--for date and time-->


        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">

        <link rel="stylesheet" href="demo/css/demo.css">

       <!--for image upload-->
        <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
        <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
        <!--for image upload-->


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
      



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
        $("tr").hover(function(){
            $(this).css("background-color", "rgba(0,0,0,0.2)");
            }, function(){
            $(this).css("background-color", "rgba(0,0,0,0)");
        });
        });
        </script>


        
    </head>
      
    <body data-sa-theme="3">
        <main class="main">
            <?php include('include/header.php');?>
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
                        <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="add_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;<i class="zmdi zmdi-accounts-add"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add User</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="situation.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Situation Report</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    
                        <li style="margin-bottom:1px; margin-top:15px;"> <a style="padding:0px; margin:0px;" href="forum_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-comments"></i> &nbsp;&nbsp;&nbsp;&nbsp;Forum / Suggestions</button> </a> </li>
                    </ul>
                </div>
            </aside>


            <section class="content">



               
            <div class="row" style=" padding:15px;  padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:15px;">
                
                


                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"  >
                    <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                        <div class="panel-heading" role="tab" id="headingOne" >
                            <h4 class="panel-title">
                               <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size:12px;"> ADD USER </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                              <form action="add_user.php" autocomplete="off" method="post" enctype="multipart/form-data">

                               <!--<div class="row" style=" margin-top:15px; margin-bottom:20px; padding-left:10px;">
                                  <form action="4-upload.php" method="post" enctype="multipart/form-data">-->
                                        
                                    <div class="" style=" width:100px; text-align:center; margin-left:10px; margin-top:20px; ">
                                        <img  id="blah" name="blah" src="" alt="User Logo" />
                                    </div>
                                    <div class=" " style=" padding-top:10px;  padding:10px; margin-bottom:15px;">
                                        <input  type="file" name="upFile" id="upFile" accept=".png,.gif,.jpg,.webp"  onchange="readURL(this);"  required>
                                    </div>
                                    <!-- <div class="col-md-3" style=" padding-top:10px;">
                                        <input type="submit" name="submit" value="Upload Image">
                                    </div>

                                   </form>-->
                                    
                                    <script>
                                        function readURL(input) {
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
                                        }
                                    </script>
                              <!--</div>-->
                                
                                <div class="table-responsive">
                                    <table id="table" class="table">
                                    <thead>
                                        <tr>
                                            <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:10px; width:180px;">USER TYPE</th>
                                            <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center; padding-left:8px; width:150px;">USER EMAIL</th>
                                            <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center; padding-left:8px; width:150px;">USER PASSWORD</th>
                                            <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center; padding-left:8px; width:150px;">NAME</th>
                                            
                                        </tr>

                                    </thead>
                                    <tbody>

                                   
                                        <tr style="border:1px solid #2b4c4a;">
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; padding-left:8px; border-left:none; color:#a8b6b5;"><select              name="user_type" id="" style="width:100%; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0); border:none; color:#fff;">    <option value="user1">USER</option> <option value="Administrator">ADMIN</option>  </select></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="user_email"  autocomplete="off" autofill="off" style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0); border:none; color:#a8b6b5;" ></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="password"    name="user_pass" autocomplete="off" autofill="off"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0); border:none; color:#a8b6b5;" ></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="name" autocomplete="off" autofill="off" style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0); border:none; color:#a8b6b5;" ></td>
                                          
                                        </tr>
                                   
                                    </tbody>
                                </table>
                                </div>
                                <div class="row" style="margin-bottom:15px; margin-top:20px;">
                                   <div class="col-sm-12"><button title="Save to Database" type="submit" name="add" id="add"  > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SAVE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Add" href="add_user.php"> CANCEL</a>  </div>
                                   
                                </div>

                                </form>

                            </div>
                        </div>
                    </div>

               </div>
               </div>


            
      
                <div class="card">
                    <div class="card-body">
                        <!--<h4 class="card-title">Record</h4>

                        <?php
                        if($filters==""){
                        ?>
                        <form action="list.php?trig=fil" method="post" style=" width:250px; height:33px; padding:0px; margin:0px;">
                            <div class="row" style=" padding:0px; margin:0px;">
                               <div class="col-sm-9" style="padding:0px; margin:0px;" >
                                    <div class="form-group" style="margin:0px; height:25px; padding:0px;">
                                       <select class="select2"  name="tdatetime" style="background-color:rgba(0,0,0,0); color:rgba(0,0,0,0); border:none; height:25px;">
                                          <?php if($_POST['tdatetime'] != ""){ ?>
                                          <option  value="<?=$_POST['tdatetime']?>" disabled selected ><?=$_POST['tdatetime']?></option>
                                          <?php }else{ ?>
                                            <option value="" disabled selected >Select Filter</option>
                                          <?php } ?>
                                          <?php foreach($resulttfdateall as $popdateall){
                                              $exp = explode(" ", $popdateall['tdatetime']);
                                          ?>
                                          <option value="<?=$exp[0]?>" ><?=$exp[0]?></option>
                                          <?php } ?>
                                         <option value="All" >View All Record</option>
                                       </select>
                                    </div>
                                </div>
                                <div class="col-sm-3" style=" padding:0px; margin:0px;">
                                    <div class="form-group" style=" margin-top:7px;">
                                        <button style="border-radius:7px; background-color:rgba(0,0,0,0.1);"  title="Show Record" type="submit" name="filtersearch" >🔍</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php
                        }else{
                        }

                        ?>-->


                        
                        <div class="table-responsive">
                            <style type="text/css">
                                .tbtn{
                                    background-color: Transparent;
                                    background-repeat:no-repeat;
                                    border: none;
                                    cursor:pointer;
                                    overflow: hidden;
                                    outline:none;
                                    }
                                    .tbtn:hover{
                                    border: 1px solid Transparent;
                                    }
                                    .tbtnicon{
                                    color:#6c757d;
                                    }
                                    .tbtnicon:hover{
                                    color:black;
                                    }
                            </style>
                            <table id="data-table" class="table">
                                <thead>
                                    <tr>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center; width:10px;">&nbsp;</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:4px; vertical-align : middle;text-align:center; width:300px;">USER EMAIL</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center; padding-left:8px; width:300px;">NAME</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">USER LOGO</th>
                                    </tr>

                                </thead>
                                <tbody>

                                <?php
                                
                                 $num="1";
                                 foreach ($resulttf as $valuetf) 
                                 {
                                
                                ?>
                                    <tr style="border:1px solid #2b4c4a;">
                                        <form action="add_user.php?id=<?=$valuetf['user_id']?>" method="post">
                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                                <button title="Delete <?=$valuetf['name']?>" name="del" add="del" type="submit" style="padding-left:7px;padding-right:7px; border-radius:50px; background-color:rgba(0,0,0,0); color:white; border:none;" ><i class="zmdi zmdi-delete" ></i></button>
                                            </td>
                                        </form>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; padding-left:8px; border-left:none; color:#a8b6b5;"><?=$valuetf['user_email']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['name']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;">

                                          
                                       <!-- <img class="user__img"--> <?php // echo '<img  src="data:image/jpeg;base64,'.base64_encode($valuetf['user_image']).'"  width="140px" height="140px" />';?> 
                                        
                                       <img class="user__img" src="img/logo/<?=$valuetf['name']?>.png"  style="width:39px; height:39px;" >

                                        </td>
                                         
                                    </tr>
                                <?php 
                                 $num++;} ?>
                                </tbody>
                            </table>
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
        <script src="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        

        <!-- Vendors: Data tables -->
        <script src="vendors/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="vendors/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>


        <!--for date and time-->
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
        <!--for date and time-->

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
    </body>


</html>