
<?php
    error_reporting(0);

    require_once('session/check_admin.php');

    $tname=$_SESSION["admin_typhoonname"];

    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    
    //end
    
    $stmt = $conn->prepare("SELECT * FROM typhoon_attachfile WHERE typhoon_name='".$tname."' and mun='".$_SESSION["admin_mun"]."' order by addeddate DESC");
    $stmt->execute(); 
    $resulttf = $stmt->fetchAll();

    try{
        $stmt = $conn->prepare("UPDATE typhoon_attachfile SET notif=1 WHERE typhoon_name='".$tname."' and mun='".$_SESSION["admin_mun"]."' ");
        $stmt->execute();
    }
    catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
    }
    
    
    
    //populate combobox filter by
    //$stmt = $conn->prepare("SELECT DISTINCT tdatetime FROM typhoon_form  order by tdatetime DESC ");
    $stmt=$conn->prepare("SELECT * FROM typhoon_form WHERE typhoon_name='".$tname."' and mun='".$_SESSION["admin_mun"]."' GROUP BY SUBSTRING_INDEX(tdatetime,' ',1) order by SUBSTRING_INDEX(tdatetime,' ',1) DESC ");
    $stmt->execute(); 
    $resulttfdateall = $stmt->fetchAll();
    //end

    $brgycount="";
    if($_SESSION['admin_mun']=="barcelona"){
        $brgycount="25";
    }elseif($_SESSION['admin_mun']=="bulan"){
        $brgycount="63";
    }elseif($_SESSION['admin_mun']=="bulusan"){
        $brgycount="24";
    }elseif($_SESSION['admin_mun']=="casiguran"){
        $brgycount="25";
    }elseif($_SESSION['admin_mun']=="castilla"){
        $brgycount="34";
    }elseif($_SESSION['admin_mun']=="donsol"){
        $brgycount="52";
    }elseif($_SESSION['admin_mun']=="gubat"){
        $brgycount="42";
    }elseif($_SESSION['admin_mun']=="irosin"){
        $brgycount="28";
    }elseif($_SESSION['admin_mun']=="juban"){
        $brgycount="25";
    }elseif($_SESSION['admin_mun']=="magallanes"){
        $brgycount="34";
    }elseif($_SESSION['admin_mun']=="matnog"){
        $brgycount="40";
    }elseif($_SESSION['admin_mun']=="pilar"){
        $brgycount="49";
    }elseif($_SESSION['admin_mun']=="prieto diaz"){
        $brgycount="23";
    }elseif($_SESSION['admin_mun']=="sta. magdalena"){
        $brgycount="14";
    }elseif($_SESSION['admin_mun']=="sorsogon"){
        $brgycount="64";
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

        <?php// include('include/head_tab.php')?>


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
                    <li style="margin-bottom:1px; border-left:3px solid green; "> <a style="padding:0px; margin:0px;" href="index2.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="add_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;<i class="zmdi zmdi-accounts-add"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add User</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="situation.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Situation Report</button> </a> </li>
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    
                        <li style="margin-bottom:1px; margin-top:15px;"> <a style="padding:0px; margin:0px;" href="forum_admin.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-comments"></i> &nbsp;&nbsp;&nbsp;&nbsp;Forum / Suggestions</button> </a> </li>
                    </ul>
                </div>
            </aside>


            <section class="content">

                   <div class="row" style="margin-bottom:20px;">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="index2.php">Users  </a>  &nbsp;⟶
                      <a href="view_report.php">&nbsp; <?php echo $_SESSION["admin_mun"];?> &nbsp; </a>  ⟶&nbsp;&nbsp;
                      <a href="index1.php"><?=$_SESSION['admin_dtype']?>  <?php echo $_SESSION["admin_typhoonname"];?> </a>  &nbsp;&nbsp;⟶&nbsp;&nbsp;
                      <label>File Uploaded</label>
                   </div>

                
                            
                
                <div class="card">
                    <div class="card-body">
                       
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
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">&nbsp;</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">File Name</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">File Size</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:left; padding-left:8px;">Date Added</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">Time Added</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">Remarks</th>
                                    </tr>

                                </thead>
                                <tbody>
                               
                                <?php
                                    require 'config/typhoon_attachfile_con.php';
                                    $mun=$_SESSION["admin_mun"];
                                    $row = $conn->query("SELECT * FROM `typhoon_attachfile` WHERE typhoon_name='".$tname."' and mun='".$mun."' ORDER BY tid DESC") or die(mysqli_error());
                                    while($fetch = $row->fetch_array()){
                
                                    $dates=explode(" ",$fetch['addeddate']);
                                ?>
                                    <tr style="border:1px solid #2b4c4a;">
                                    <?php 
                                        $name = explode('/', $fetch['filepath']);
                                        $fname = explode('_-', $fetch['filename']);
                                    ?>
                                        <td style="padding:0px;width:33px;"><a title="Download" href="upload_file/download.php?file=<?php echo $name[2]?>" class="btn btn-primary"><i class="zmdi zmdi-download"></i></a></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px;"><?php echo $fname[2]?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px;"><?php echo $fetch['filesize']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?php echo $dates[0]?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?php echo $dates[1]." ".$dates[2]?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:lect; padding-left:8px; color:#a8b6b5;"><?php echo $fetch['remarks']?></td>
                                <?php 
                                 } ?>
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