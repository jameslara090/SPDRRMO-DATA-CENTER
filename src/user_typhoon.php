<?php

require_once('session/check_user.php');

require_once('config/dbcon.php');
$connr = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$connr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conna = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conna->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['subm'])){
        if($_SESSION['dtype'] != "ACTIVITY REPORT"){
            $rem = $_POST['remarks'];
            if($rem==""){
                $rem="N/A";
            }
            $stmt = $connr->prepare("INSERT INTO `category`(`muni`, `ttype`, `category`, `daterange`, `remarks`,`stat`) 
            VALUES ('".$u_mun."', '".$_SESSION['dtype']."', '".$_POST['newr']."', '".$_POST['newrto']."', '".$rem."','0')");
            $stmt->execute();
            header("location:user_typhoon.php");
        }else{
            $title=$_POST['title'];
            $range=$_POST['newr1'];
            $range1=$_POST['newr2'];
            $datrange="";
            if ($range1 == ""){
                $datrange =$range;
            }else{
                $datrange =$range." / ".$range1;
            }

            if($title == ""){
                $title="N/A";
            }
            $rem1 = $_POST['actremarks'];
            if($rem1==""){
                $rem1="N/A";
            }
            $stmt = $connr->prepare("INSERT INTO `category`(`muni`, `ttype`, `category`, `daterange`, `remarks`,`stat`) 
            VALUES ('".$u_mun."', '".$_SESSION['dtype']."', '".$title."', '".$datrange."', '".$rem1."','0')");
            $stmt->execute();
            header("location:user_typhoon.php");
        }
    }
    if(isset($_POST['del'])){
        try{
            $muni = $u_mun;
            $dtype = $_SESSION['dtype'];
            $tname = $_POST['tname'];
            $stmt = $conna->prepare("DELETE FROM typhoon_form WHERE dtype='".$dtype."' and typhoon_name='".$tname."' and mun='".$muni."' ");
            $stmt->execute();


            $stmt = $conna->prepare("SELECT * FROM typhoon_attachfile WHERE dtype='".$dtype."' and typhoon_name='".$tname."' and mun='".$muni."' ");
            $stmt->execute();
            $af = $stmt->fetchAll();
            $files="";
            foreach($af as $f){
                $file=$f['filepath'];
                $id =$f['tid'];
                $files = glob($file); // get all file names
                $name = explode('/', $f['filepath']);
                $fol="/attach_files/";
                $ec = str_replace('\\','/',dirname(__FILE__)).$fol.$name[2];
                //echo $ec;
                unlink($ec);
               
                $stmt = $conna->prepare("DELETE FROM typhoon_attachfile WHERE tid='".$id."' and dtype='".$dtype."' and typhoon_name='".$tname."' and mun='".$muni."' ");
                $stmt->execute();
            }

            

            $stmt = $conna->prepare("DELETE FROM 1pod WHERE dtype='".$dtype."' and tname='".$tname."' and mun='".$muni."' ");
            $stmt->execute();



            $stmt = $conna->prepare("DELETE FROM category WHERE cid='".$_POST['cid']."' ");
            $stmt->execute();
            echo "<script>window.location='user_typhoon.php'</script>";
            }
            catch (Exception $e){
            echo $e->getMessage() . "<br/>";
            while($e = $e->getPrevious()) {
            echo 'Previous Error: '.$e->getMessage() . "<br/>";
            }
            }
    }
    if(isset($_POST['edit1'])){
        try{
            $cid=$_POST['cid'];
            $tname=$_POST['tname'];
            $daterangefrom=$_POST['daterangefrom'];
            $remarksedit=$_POST['remarksedit'];

            $stmt = $conna->prepare("UPDATE category SET daterange='".$daterangefrom."', remarks='".$remarksedit."' WHERE cid='".$cid."' and category='".$tname."' and muni='".$u_mun."' ");
            $stmt->execute();
            echo "<script>window.location='user_typhoon.php'</script>";
            }
            catch (Exception $e){
            echo $e->getMessage() . "<br/>";
            while($e = $e->getPrevious()) {
            echo 'Previous Error: '.$e->getMessage() . "<br/>";
            }
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

        <?php include('include/head_tab.php')?>
        <?php include('logs.php')?>

       
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

        
    </head>
      
    <body data-sa-theme="3">
        <main class="main">
            <?php include('include/user_header.php');?>
            <aside class="sidebar">
                <div class="scrollbar-inner">
                   <?php include('include/user_profile.php')?>
                </div>
                    <ul class="navigation">
                       <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="user_home.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                       <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    
                       <li style="margin-bottom:1px; margin-top:15px;"> <a style="padding:0px; margin:0px;" href="forum.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-comments"></i> &nbsp;&nbsp;&nbsp;&nbsp;Forum / Suggestions</button> </a> </li>
                    </ul>
                </div>
            </aside>


            <section class="content">

            <?php

            //$_SESSION['dtype']=$_GET['dtype'];
           // echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

            ?>
                <div class="content__inner"> 
                   <div class="row">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="user_home.php">Reporting  </a>  &nbsp;⟶&nbsp;
                      <label> <?php echo ucfirst(strtolower($_SESSION['dtype']));?> </label>
                   </div>
                    
                    <header class="content__title">
                    </header>

                <div class="contacts row">


                      <!-- adding tile -->
                      <?php if ($_SESSION['dtype']=="TYPHOON"){}else{?>
                         <div class="col-xl-2 col-lg-3 col-sm-4 col-6" >
                            <div class="contacts__item">
                                <a class="contacts__img"  data-toggle="modal" data-target="#modal-default" title="Add New Report">
                                    <img src="img/category/add/<?=$_SESSION['dtype']?>.png" alt="">
                                </a>
                            <div class="contacts__info">
                                <strong style="font-size:11px;"> &nbsp; </strong>
                                <small style="font-size:10px;"> &nbsp; </small>
                            </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                               <label for="" style="margin-top:5px;">&nbsp;</label>
                           </div>
                         </div>
                      <?php } ?>
                      

                        <div class="modal fade" id="modal-default" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="user_typhoon.php" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title pull-left">NEW REPORT FOR [ <?=$_SESSION['dtype']?> ]</h5>
                                    </div>
                                        <div class="modal-body" style="">
                                          <?php if($_SESSION['dtype'] != "ACTIVITY REPORT"){ ?>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp;&nbsp;From:&nbsp;</span>
                                                    <div class="form-group">
                                                        <input style="border:1px solid #3a3a3a;" name="newr" id="myText" onchange="success()" type="text" class="form-control datetime-picker" placeholder="Date and Time">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp;&nbsp;To:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                    <div class="form-group">
                                                        <input style="border:1px solid #3a3a3a;" name="newrto" id="myText" onchange="success()" type="text" class="form-control datetime-picker" placeholder="Date and Time">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                                <div class="input-group" style="margin-top: 14px;">
                                                    <div class="form-group" style=" padding:0px;margin:0px;">
                                                    <textarea class="form-control textarea-autosize"  name="remarks"  placeholder="Remarks" style=" padding:8px; border:1px solid #242525;" rows="2"></textarea>
                                                    <i class="form-group__bar" style="padding:0px; margin:0px;"></i>
                                                    </div>
                                                </div> 
                                          <?php }else{ ?>
                                                <div class="input-group" style="margin-bottom: 14px;">
                                                    <div class="form-group" style=" padding:0px;margin:0px;">
                                                    <textarea class="form-control textarea-autosize"  name="title"  placeholder="Title" style=" padding:8px; border:1px solid #242525;" rows="1"></textarea>
                                                    <i class="form-group__bar" style="padding:0px; margin:0px;"></i>
                                                    </div>
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp;&nbsp;From:&nbsp;</span>
                                                    <div class="form-group">
                                                        <input style="border:1px solid #3a3a3a;" name="newr1" id="myText" onchange="success()" type="text" class="form-control datetime-picker" placeholder="Date and Time">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div> 
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To:&nbsp;</span>
                                                    <div class="form-group">
                                                        <input style="border:1px solid #3a3a3a;" name="newr2" id="myText" onchange="success()" type="text" class="form-control datetime-picker" placeholder="Date and Time">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                                <div class="input-group" style="margin-top: 14px;">
                                                    <div class="form-group" style=" padding:0px;margin:0px;">
                                                    <textarea class="form-control textarea-autosize"  name="actremarks"  placeholder="Remarks" style=" padding:8px; border:1px solid #242525;" rows="2"></textarea>
                                                    <i class="form-group__bar" style="padding:0px; margin:0px;"></i>
                                                    </div>
                                                </div>
                                          <?php } ?>  
                                        </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-link" name="subm" id="subm" disabled>Add Report</button>
                                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                        <script type="text/javascript">
                            function success() {
                                var tag=document.getElementById("subm");
                                if(document.getElementById("myText").value==="") {
                                    document.getElementById('subm').disabled = true;
                                } else { 
                                    tag.style.background="green";
                                    document.getElementById('subm').disabled = false;
                                }
                            }
                        </script>
                      <!-- end adding tile -->
                          




                    <?php
                        require_once('config/main_tiles_con.php');
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        if ($_SESSION['dtype']=="TYPHOON"){
                            $sql = "SELECT * FROM category WHERE stat='0' and muni='All' and ttype='".$_SESSION['dtype']."' order by cid DESC";
                        } else{
                            $sql = "SELECT * FROM category WHERE stat='0' and muni='".$_SESSION['u_mun']."' and ttype='".$_SESSION['dtype']."'  order by cid DESC";
                        }
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                        ?>
                       
                         <div class="col-xl-2 col-lg-3 col-sm-4 col-6" >
                         <style>
                            .cont {
                            position: relative;
                            }

                            .topright {
                            position: absolute;
                            top: 0px;
                            right: 32px;
                            font-size: 10px;
                            color:white;
                        
                            padding-left:5px;
                            padding-right:5px;

                            border-radius: 0px 0px 0px 8px;
                            }
                            .topright:hover {
                                text-decoration:underline;
                                color:#f3f0f0;
                            }

                            .topright1 {
                            position: absolute;
                            top: 0px;
                            right: 0px;
                            font-size: 10px;
                            color:white;
                            
                            padding-left:5px;
                            padding-right:5px;

                            border-radius: 0px 8px 0px 0px;
                            }
                            .topright1:hover {
                                text-decoration:underline;
                                color:#f3f0f0;
                            }

                        </style>
                            <div class="contacts__item cont"  style="">
                                <a  class="contacts__img">
                                    <img src="img/category/<?=$row['ttype']?>1.png" alt="">
                                </a>
                            <div class="contacts__info">


                                <style>
                                    #d{
                                        border:1px ridge #081111;
                                    }
                                </style>
                                
                                <?php if($_SESSION['dtype'] == "TYPHOON" || $_SESSION['dtype'] == "ACTIVITY REPORT"){ ?>
                                    <strong style="font-size:11px;" data-toggle="popover" data-placement="top" data-html="true" data-content=" <span style=font-size:11px;><span style=color:green;>Title:</span> <?=$row['category']?></span> "><?php echo $row['category'];?></strong>
                                <?php }else{ 
                                    if($row['daterange'] == ""){
                                    ?>
                                    <strong style="font-size:11px;" data-toggle="popover" data-placement="top" data-html="true" data-content=" <span style=font-size:11px;>Title: <?=$row['category']?></span> "><?php echo $row['category'];?></strong>
                                <?php }else{ ?>
                                    <strong style="font-size:11px;" data-toggle="popover" data-placement="top" data-html="true" data-content=" <span style=font-size:11px;>Date: <?=$row['category']." / ".$row['daterange']?></span> "><?php echo $row['category']." / ".$row['daterange'];?></strong>
                                <?php } } ?>



                                <?php if($_SESSION['dtype'] == "TYPHOON" || $_SESSION['dtype'] == "ACTIVITY REPORT"){ ?>
                                  <small style="font-size:10px;" data-toggle="popover" data-placement="top" data-html="true" data-content=" <span style=font-size:11px;><span style=color:green;>Date:</span> <?=$row['daterange']."<br><span style=color:green;>Remarks:</span> <textarea id=d wrap=off cols=37 rows=8 style=background-color:rgba(0,0,0,0);color:white; readonly >".$row['remarks'].'</textarea>' ?></span> " ><?php echo $row['daterange']." ".$row['remarks'];?></small>
                                <?php }else{ ?>
                                  <small style="font-size:10px;" data-toggle="popover" data-placement="top" data-html="true" data-content="<span style=font-size:11px;>Remarks: <?=$row['remarks']?></span> " ><?php echo $row['remarks'];?></small>
                                <?php } ?>
                            </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <button class="contacts__btn"><a class="abtn"  href="session_var/var.php?name=<?=$row['category']?>">Manage Record&nbsp;</a></button>


                                <?php if($_SESSION['dtype'] !="TYPHOON"){?>
                                    <a href="#" class="topright" data-toggle="modal" data-target="#modal-confirm" data-cid="<?=$row['cid']?>"  data-tname="<?=$row['category']?>" style="background-color:#5C5C5C;">DELETE</a>
                                    <a href="#" class="topright1" data-toggle="modal" data-target="#modal-edit" data-cid="<?=$row['cid']?>" data-tname="<?=$row['category']?>" data-daterangefrom="<?=$row['daterange']?>" data-remarksedit="<?=$row['remarks']?>" style="background-color:#98C510;">&nbsp;EDIT</a>


                                    <!-- Small -->
                                    <div class="modal fade" id="modal-confirm" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                            <form  action="user_typhoon.php" method="post">
                                                <div class="modal-header" style=" padding:15px; text-align:center;">
                                                    <h5 class="modal-title pull-left" style="text-align:center;">Confirm Delete</h5>
                                                </div>
                                                <div class="modal-body" style=" padding:15px;">
                                                    Are you sure you want to delete this record ? <br>
                                                    <input type="hidden" name="cid" value="">
                                                    <input type="hidden" name="tname" value="">
                                                </div>
                                                <div class="modal-footer" style=" padding:15px;">
                                                    <button type="submit" name="del" class="btn btn-link" style="background-color:#5C5C5C;">Delete</button>
                                                    <button type="submit" class="btn btn-link btn-light" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-edit" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                            <form  action="user_typhoon.php" method="post">
                                                <div class="modal-header" style=" padding:15px; text-align:center;">
                                                    <h5 class="modal-title pull-left" style="text-align:center;">Edit</h5>
                                                </div>
                                                <div class="modal-body" style=" padding:15px;">
                                                   <!-- Are you sure you want to delete this record ? <br>-->
                                                    <input type="hidden" name="cid" value="">
                                                   <!-- <input type="text" name="tname" value="">-->

                                                    <div class="input-group" style="margin-bottom: 1px;">
                                                        <div class="form-group">
                                                            <input style="" name="tname"  id="tname" value="" id="myText" type="text" class="form-control" placeholder="Date" title="Title readonly" readonly>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                    <div class="input-group">
                                                        <div class="form-group">
                                                            <input style="border:1px solid #3a3a3a;" name="daterangefrom"  id="daterangefrom" value="" id="myText" type="text" class="form-control" placeholder="Date" title="Date">
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                    <div class="input-group" style="margin-top: 1px;">
                                                        <div class="form-group" style=" padding:0px;margin:0px;">
                                                        <textarea class="form-control textarea-autosize"  name="remarksedit" id="remarksedit"  placeholder="Remarks" style=" padding:2px; border:1px solid #242525;" rows="2" title="Remarks"></textarea>
                                                        <i class="form-group__bar" style="padding:0px; margin:0px;"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer" style=" padding:15px;">
                                                    <button type="submit" name="edit1" id="edit1" class="btn btn-link" style="background-color:#5C5C5C;" >SAVE CHANGES</button>
                                                    <button type="submit" class="btn btn-link btn-light" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                    
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
                        $sql = "SELECT * FROM category WHERE stat='1' order by cid DESC ";
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
                                <strong style="font-size:11px;"><?php echo $row['category'];?></strong>
                            </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <button class="contacts__btn"><a class="abtn"  href="session_var/var.php?name=<?=$row['category']?>">Manage Record&nbsp;</a></button>
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



        <script>
            $('#modal-confirm').on('show.bs.modal', function(e) {
            var rid = $(e.relatedTarget).data('cid');
                $(e.currentTarget).find('input[name="cid"]').val(rid);
            var tn = $(e.relatedTarget).data('tname');
                $(e.currentTarget).find('input[name="tname"]').val(tn);
            });
            $('#modal-edit').on('show.bs.modal', function(e) {
            var rid = $(e.relatedTarget).data('cid');
                $(e.currentTarget).find('input[name="cid"]').val(rid);
            var tn = $(e.relatedTarget).data('tname');
                $(e.currentTarget).find('input[name="tname"]').val(tn);
            var dr = $(e.relatedTarget).data('daterangefrom');
                $(e.currentTarget).find('input[name="daterangefrom"]').val(dr);
            var re = $(e.relatedTarget).data('remarksedit');
               $('#remarksedit').val(re);
            });
        </script>

    </body>


</html>