<?php

require_once('session/check_admin.php');

require_once('config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT DISTINCT tdatetime,notif FROM typhoon_form WHERE typhoon_name='".$_SESSION["admin_typhoonname"]."' and mun='".$_SESSION["admin_mun"]."' order by tdatetime DESC");
$stmt->execute(); 
$resulttf = $stmt->fetchAll();


$stmt = $conn->prepare("SELECT filename,addeddate,remarks,notif FROM typhoon_attachfile WHERE typhoon_name='".$_SESSION["admin_typhoonname"]."' and mun='".$_SESSION["admin_mun"]."' order by addeddate DESC");
$stmt->execute(); 
$resultfile = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT * FROM 1pod WHERE tname='".$_SESSION["admin_typhoonname"]."' and mun='".$_SESSION["admin_mun"]."' order by d_re DESC");
$stmt->execute(); 
$resultdam = $stmt->fetchAll();







$stmt = $conn->prepare("SELECT DISTINCT notif,COUNT(DISTINCT(tdatetime)) AS noti FROM typhoon_form WHERE typhoon_name='".$_SESSION["admin_typhoonname"]."' and mun='".$_SESSION["admin_mun"]."' and notif=0 order by tdatetime DESC");
$stmt->execute(); 
$resulttfno =  $stmt->fetch(PDO::FETCH_ASSOC);
    


$stmt = $conn->prepare("SELECT filename,addeddate,remarks,COUNT(notif) AS noti1 FROM typhoon_attachfile WHERE typhoon_name='".$_SESSION["admin_typhoonname"]."' and mun='".$_SESSION["admin_mun"]."' and notif=0 order by addeddate DESC");
$stmt->execute(); 
$resultfileno =  $stmt->fetch(PDO::FETCH_ASSOC);
    

$stmt = $conn->prepare("SELECT t_em,d_re,COUNT(notif) AS noti2 FROM 1pod WHERE tname='".$_SESSION["admin_typhoonname"]."' and mun='".$_SESSION["admin_mun"]."' and notif=0 order by d_re DESC");
$stmt->execute(); 
$resultdamno =  $stmt->fetch(PDO::FETCH_ASSOC);

$notif = $resulttfno['noti'];
$notif1 = $resultfileno['noti1'];
$notif2 = $resultdamno['noti2'];
    


//attach files

$stmt = $conn->prepare("SELECT * FROM attach_files_dam WHERE dtype='".$_SESSION['admin_dtype']."' and typhoon_name='".$_SESSION["admin_typhoonname"]."' and mun='sorsogon' order by tid ASC");
$stmt->execute(); 
$res_attach = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT COUNT(tid) AS cnt,SUM(tid) AS reccount,remarks FROM attach_files_dam WHERE dtype='".$_SESSION['admin_dtype']."' and typhoon_name='".$_SESSION["admin_typhoonname"]."' and mun='sorsogon' order by tid ASC");
$stmt->execute(); 
$res_attach_count =  $stmt->fetch(PDO::FETCH_ASSOC);

//end 

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
         
         <link rel="stylesheet" href="vendors/bower_components/lightgallery/dist/css/lightgallery.min.css">
         
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

                   <div class="row" style="margin-bottom:20px;">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="index2.php">Users  </a>  &nbsp;⟶
                      <a href="view_report.php">&nbsp; <?php echo $_SESSION["admin_mun"];?> &nbsp;  </a>  ⟶&nbsp;&nbsp;
                     <!--<a href="view_report.php"><?php echo $_SESSION["admin_mun"];?>  </a>  &nbsp;>&nbsp;-->
                      <label><?=$_SESSION['admin_dtype']?> <?php echo $_SESSION["admin_typhoonname"];?></label>
                   </div>
                   

                    <div class="row todo">
                        <div class="col-md-12">
                            <div class="card">
                           
                                <div class="toolbar toolbar--inner" style="">
                                    <label style=" margin:0px; padding:0px;">NEW REPORT [ <?=$_SESSION['admin_dtype']?> FORM <code><?=$notif?></code> &nbsp;&nbsp; ATTACH FILE <code><?=$notif1?></code> &nbsp;&nbsp; DAMAGED REPORT <code><?=$notif2?></code> ]</label>

                                    <!--<div class="actions">
                                        <a href="list.php" class="actions__item zmdi zmdi-view-headline" title="View All Record in <?=$_SESSION['admin_typhoonname']?>"></a>
                                    </div>-->

                                   
                                </div>

                                <div class="listview listview--bordered">
                                    <?php
                                    $chan="rgba(0,0,0,0)";
                                     foreach($resulttf as $value){
                                        if($value['notif']==0){
                                            $chan="#1c5175";
                                        }else{
                                            $chan="rgba(0,0,0,0)";
                                        }
                                    ?>
                                    <div class="listview__item" style=" padding-left:30px; padding-top:8px;padding-bottom:8px; background-color:<?=$chan?>">
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
                                        <a href="list.php" class="custom-control custom-control--char" title="View Record in <?=$value['tdatetime']?>">
                                            <div class="todo__info" style="">
                                                <span>  <img src="img/form.png" width="15px" heigth="10px" alt=""> TYPHOON FORM</span>
                                                <small> &nbsp;&nbsp; ( <?=$value['tdatetime']?> ) </small>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    
                                    <div style="background-color:rgba(0,0,0,0.2); height:10px;"> </div>

                                    <?php
                                    
                                     foreach($resultfile as $value1){
                                         if($value1['notif']==0){
                                             $chan="#1c5175";
                                         }else{
                                             $chan="rgba(0,0,0,0)";
                                         }
                                    ?>
                                    <div class="listview__item" style=" padding-left:30px; padding-top:8px;padding-bottom:8px; background-color:<?=$chan?>">
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
                                        <a href="attach.php" class="custom-control custom-control--char" title="View Record in <?=$value1['addeddate']?>">
                                            <div class="todo__info" style="">
                                                <?php
                                                $fname = explode('_-', $value1['filename']);
                                                ?>
                                                <span>  <img src="img/attach.png" width="15px" heigth="10px" alt=""> ATTACH FILE ( <?=$fname[2]?> )</span>
                                                <small> &nbsp;&nbsp; ( <?=$value1['addeddate']?> )  &nbsp; &nbsp;<?=$value1['remarks']?></small>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <div style="background-color:rgba(0,0,0,0.2); height:10px;"></div>


                                    <?php
                                     foreach($resultdam as $value1){
                                        if($value1['notif']==0){
                                            $chan="#1c5175";
                                        }else{
                                            $chan="rgba(0,0,0,0)";
                                        }
                                    ?>
                                    <div class="listview__item" style=" padding-left:30px; padding-top:8px;padding-bottom:8px; background-color:<?=$chan?> ">
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
                                        <a href="dam_admin_view.php" class="custom-control custom-control--char" title="View Record in <?=$value1['d_re']?>">
                                            <div class="todo__info" style="">
                                                <span>  <img src="img/damage.png" width="13px" heigth="9px" alt=""> DAMAGED REPORT ( <?=$value1['t_em']?> )</span>
                                                <small> &nbsp;&nbsp; ( <?=$value1['d_re']?> ) </small>
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
                    
                    
                    
                    
                    
                    
                                    <?php
                                     $dam="";
                                     if($res_attach_count['cnt'] > "0"){
                                         $dam=$res_attach_count['remarks'];
                                     }
                                    ?>

                                    <div class="row" style=" padding:15px;  padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:15px;">
                                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"  >
                                                    <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                                        <div class="panel-heading" role="tab" id="headingOne" >
                                                            <h4 class="panel-title">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size:12px;"> ADD ATTACHMENT </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                            <div class="panel-body">
                                                                <form action="upload_file/upload_attach_files_dam.php" autocomplete="off" method="post" enctype="multipart/form-data">
                                                                    <input type="text"   name="name" autocomplete="off" autofill="off" value="<?=$dam?>" style="width:390px; height:100%; margin-top:10px; border-left:none;border-right:none;border-top:none; background-color:rgba(0,0,0,0);  color:#a8b6b5;" >
                                                                    <input type="hidden"   name="countdata" value="<?=$res_attach_count['cnt']?>">    
                                                                        <div class="" style=" width:100px; text-align:center; margin-left:10px; margin-top:20px; ">
                                                                            <img  id="blah" name="blah" src="" alt="No thumbnail if none image format" />
                                                                        </div>
                                                                        <div class=" " style=" padding-top:10px;  padding:10px; margin-bottom:15px;">
                                                                            <input  type="file" name="upload" id="upload"  onchange="readURL(this);"  required>
                                                                        </div>
                                                                        <script>
                                                                            function readURL(input) {
                                                                                if (input.files && input.files[0]) {
                                                                                    var reader = new FileReader();
                                                                        
                                                                                    reader.onload = function (e) {
                                                                                        $('#blah')
                                                                                            .attr('src', e.target.result)
                                                                                            .width(350)
                                                                                            .height(250);
                                                                                    };
                                                                                    reader.readAsDataURL(input.files[0]);
                                                                                }
                                                                            }
                                                                        </script>
                                                                      <div class="row" style="margin-bottom:15px; margin-top:20px;">
                                                                       <div class="col-sm-12"><button title="Save to Database" type="submit" name="submit" id="submit"  > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SAVE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Add" href="index1.php"> CANCEL</a>  </div>
                                                                      </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                    </div>





                                    <?php
                                    if($res_attach_count['reccount'] > "0"){
                                    ?>
                                    <div style=" margin-top:20px;">ATTACH ( <strong style="color:#559BE5;"><?=$dam?></strong> )</div>

                                        <div style="" style="height:850px;" class="">
                                                <div class="row lightbox photos cont" >
                                                        <?php  foreach($res_attach as $val){
                                                                $img = "attach_files_dam/".$val['filename'];
                                                                if(@exif_imagetype($img)){ ?>
                                                                    <a href="attach_files_dam/<?=$val['filename']?>" class="col-md-2 col-4" style="height:179px;">
                                                                        <div class="lightbox__item photos__item">
                                                                            <img  style="border: 2px solid rgba(0,0,0,0.1); border-radius: 4px; padding: 1px;" src="attach_files_dam/<?=$val['filename']?>" alt="<?=$val['filename']?>" width="100%" height="100%" />
                                                                        </div>
                                                                    </a>  <?php  }  }  ?>
                                                </div>
                                        </div>
                                        <div style="background-color:green; margin-bottom:4px;"></div>

                                        <div class="table-responsive">
                                            <style type="text/css">
                                                .tbtn{  background-color: Transparent;  background-repeat:no-repeat;  border: none;  cursor:pointer;  overflow: hidden;   outline:none;  }  .tbtn:hover{  border: 1px solid Transparent; } .tbtnicon{  color:#6c757d;  }  .tbtnicon:hover{  color:black;  }
                                            </style>
                                            <table class="table">
                                                <thead>
                                                    <tr style=" font-size:14px;">
                                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">&nbsp;</th>
                                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">&nbsp;</th>
                                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">File Name</th>
                                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">File Size</th>
                                                        <!--<th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">Remarks</th>-->
                                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:left; padding-left:8px;">Date Added</th>
                                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">Time Added</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <?php
                                                            require 'config/typhoon_attachfile_con.php';
                                                            $row = $conn->query("SELECT * FROM `attach_files_dam` WHERE dtype='".$_SESSION['admin_dtype']."' and typhoon_name='".$_SESSION["admin_typhoonname"]."' and mun='sorsogon' order by tid ASC") or die(mysqli_error());
                                                            while($fetch = $row->fetch_array()){
                                                            $dates=explode(" ",$fetch['addeddate']);
                                                        ?>
                                                            <tr style="border:1px solid #2b4c4a; font-size:14px;">
                                                            <?php 
                                                                $name = explode('/', $fetch['filepath']);
                                                                $fname = explode('_-', $fetch['filename']);
                                                            ?> 
                                                                <td style="padding:0px;width:37px;"><a style="height:100%;" title="Delete File" href="upload_file/delete_attach_files_dam.php?file=<?php echo $fetch['filepath']?>&id=<?php echo $fetch['tid']?>&name=<?php echo $name[2]?>" class="btn btn-danger"><i class="zmdi zmdi-delete"></i></a></td>
                                                                <td style="padding:0px;width:33px;"><a title="Download File" href="upload_file/download_attach_files_dam.php?file=<?php echo $name[2]?>" class="btn btn-primary"><i class="zmdi zmdi-download"></i></a></td>
                                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px;"><?php echo $fname[2]?></td>
                                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px;"><?php echo $fetch['filesize']?></td>
                                                                <!--<td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:lect; padding-left:8px; color:#a8b6b5;"><?php echo $fetch['remarks']?></td>-->
                                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?php echo $dates[0]?></td>
                                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?php echo $dates[1]." ".$dates[2]?></td>
                                                        <?php
                                                        } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                         }
                                        ?>
                                        
                                        

                   

                   
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
        
        
        <!-- Light Gallery -->
         <script src="vendors/bower_components/lightgallery/dist/js/lightgallery-all.min.js"></script>
         

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
        
        
        
        <script>
        $(document).ready(function(){
        $("tr").hover(function(){
            $(this).css("background-color", "rgba(0,0,0,0.2)");
            }, function(){
            $(this).css("background-color", "rgba(0,0,0,0)");
        });
        });
        </script>
        
        
    </body>


</html>