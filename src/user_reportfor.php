
<?php
    require_once('session/check_user.php');

    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //populate combobox filter by
    $tname=$_SESSION["typhoonname"];
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT(tdatetime)) AS tdatetimecount FROM typhoon_form WHERE typhoon_name='".$tname."' and mun='".$u_mun."' ");
    $stmt->execute(); 
    $resulttfdateall = $stmt->fetch(PDO::FETCH_ASSOC);
    //echo $resulttfdateall['tdatetimecount'];
    //end
    
    
    //upload
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT(filepath)) AS filecount FROM typhoon_attachfile WHERE typhoon_name='".$tname."' and mun='".$u_mun."' ");
    $stmt->execute(); 
    $resultupload = $stmt->fetch(PDO::FETCH_ASSOC);
    //end

    //upload
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT(d_re)) AS damcount FROM 1pod WHERE tname='".$tname."' and mun='".$u_mun."' ");
    $stmt->execute(); 
    $resultdam = $stmt->fetch(PDO::FETCH_ASSOC);
    //end
    
    
     //attach files

    $stmt = $conn->prepare("SELECT * FROM attach_files_dam WHERE dtype='".$_SESSION['dtype']."' and typhoon_name='".$_SESSION["typhoonname"]."' and mun='sorsogon' order by tid ASC");
    $stmt->execute(); 
    $res_attach = $stmt->fetchAll();

    $stmt = $conn->prepare("SELECT COUNT(tid) AS cnt,SUM(tid) AS reccount,remarks FROM attach_files_dam WHERE dtype='".$_SESSION['dtype']."' and typhoon_name='".$_SESSION["typhoonname"]."' and mun='sorsogon' order by tid ASC");
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
        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">
        
         <link rel="stylesheet" href="vendors/bower_components/lightgallery/dist/css/lightgallery.min.css">
         

        <?php include('include/head_tab.php')?>
        <?php include('logs.php')?>

        
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
                <div class="content__inner"> 
                   <div class="row">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="user_home.php">Reporting  </a>  &nbsp;⟶&nbsp;
                      <a href="user_typhoon.php"><?php echo ucfirst(strtolower($_SESSION['dtype']));?>  </a>  &nbsp;⟶&nbsp;
                      <label>Report For: <?php echo $_SESSION["typhoonname"]; ?></label>
                   </div>
                    
                    <header class="content__title">
                    </header>

                

                    
                    <div class="contacts row">

                    <?php if ($_SESSION['dtype'] !="ACTIVITY REPORT"){?>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item">

                                <div class="contacts__info">
                                    <strong style="font-size:16px; background-color:rgba(0,0,0,0.2); margin-bottom:10px;" title="<?=$_SESSION['dtype']?> FORM"><span ><?=$_SESSION['dtype']?> FORM</span></strong>
                                    <small>Total Reported: <?=$resulttfdateall['tdatetimecount']?></small>
                                </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <?php
                                if($u_mun=="barcelona"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="bulan"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_bulan.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="bulusan"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_bulusan.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="casiguran"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_casiguran.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="castilla"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_castilla.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="donsol"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_donsol.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="gubat"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_gubat.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="irosin"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_irosin.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="juban"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_juban.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="magallanes"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_magallanes.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="matnog"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_matnog.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="pilar"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_pilar.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="prieto diaz"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_prietodiaz.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="sta. magdalena"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_stamagdalena.php">Manage&nbsp;</a></button>
                                <?php
                                }elseif($u_mun=="sorsogon"){
                                ?>
                                     <button class="contacts__btn"><a class="abtn"  href="user_entry_sorsogoncity.php">Manage&nbsp;</a></button>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                     <?php } ?>


                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item">

                                <div class="contacts__info">
                                    <strong style="font-size:16px; background-color:rgba(0,0,0,0.2); margin-bottom:10px;"><span >ATTACH FILE</span></strong>
                                    <small>Uploaded File: <?=$resultupload['filecount']?></small>
                                </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <button class="contacts__btn"><a class="abtn"  href="typhoon_attachfile.php">Manage&nbsp;</a></button>
                                
                            </div>
                        </div>


                    </div>
                            
                    

                    <hr>
                    <div>&nbsp;</div><div>&nbsp;</div>
                    <?php if ($_SESSION['dtype'] !="ACTIVITY REPORT"){?>
                    <div class="contacts row">
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                            <div class="contacts__item">

                                <div class="contacts__info">
                                    <strong style="font-size:16px; background-color:rgba(0,0,0,0.2); margin-bottom:10px;" title="DAMAGE ASSESSMENT REPORT"><span > &nbsp;DAMAGE ASSESSMENT REPORT</span></strong>
                                    <small>Total Reported: <?=$resultdam['damcount']?></small>
                                </div>
                                <style>
                                    .contacts__btn{  background-color:rgba(0,0,0,0.2);  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
                                    .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                    .contacts__btn .badge {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
                                    .abtn{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
                                    .abtn:hover{ color:white; }
                                </style>
                                <button class="contacts__btn"><a class="abtn"  href="damage_assess.php">Manage&nbsp;</a></button>
                                
                            </div>
                        </div>
                    </div>

                    <?php } ?>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                                        <?php
                                            $dam="";
                                            if($res_attach_count['cnt'] > "0"){
                                                $dam=$res_attach_count['remarks'];
                                            }
                                            
                                            if($res_attach_count['reccount'] > "0"){
                                        ?>
                                        <div style=" margin-top:20px;">ATTACH ( <strong style="color:#559BE5;"><?=$dam?></strong> )</div>

                                        <div style="" style="height:850px;">
                                                <div class="row lightbox photos" >
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
                                                            $row = $conn->query("SELECT * FROM `attach_files_dam` WHERE dtype='".$_SESSION['dtype']."' and typhoon_name='".$_SESSION["typhoonname"]."' and mun='sorsogon' order by tid ASC") or die(mysqli_error());
                                                            while($fetch = $row->fetch_array()){
                                                            $dates=explode(" ",$fetch['addeddate']);
                                                                //$img = "attach_files_dam/".$fetch['filename'];
                                                                //if(!@exif_imagetype($img)){
                                                        ?>
                                                            <tr style="border:1px solid #2b4c4a; font-size:14px;">
                                                            <?php 
                                                                $name = explode('/', $fetch['filepath']);
                                                                $fname = explode('_-', $fetch['filename']);
                                                            ?> 
                                                                <td style="padding:0px;width:33px;"><a title="Download File" href="upload_file/download_attach_files_dam.php?file=<?php echo $name[2]?>" class="btn btn-primary"><i class="zmdi zmdi-download"></i></a></td>
                                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px;"><?php echo $fname[2]?></td>
                                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px;"><?php echo $fetch['filesize']?></td>
                                                                <!--<td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:lect; padding-left:8px; color:#a8b6b5;"><?php echo $fetch['remarks']?></td>-->
                                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?php echo $dates[0]?></td>
                                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?php echo $dates[1]." ".$dates[2]?></td>
                                                        <?php
                                                               // } 
                                                        } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <?php
                                            }
                                        ?>
                                        
                                        
                                        
                                        
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