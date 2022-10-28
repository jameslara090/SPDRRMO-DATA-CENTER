
<?php
    error_reporting(0);

    require_once('session/check_admin.php');

    $tname=$_SESSION["admin_typhoonname"];

    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    //filter search check

    $filter=$_GET['trig'];  
    $filters="";  
    if($filter =="fil"){  
        $filters="1";  
    }elseif($filter =="date"){  
        $filters="date";  
    }else{
        $filters="";
    }
    
    //end
    if ($filters=="1"){
        if ($_POST['tdatetime']=="All" || $_POST['tdatetime']==""){
            $stmt = $conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."'  order by asof DESC, cid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();
            echo "<script>window.location='list_cgs.php'</script>";
        }else{
            $stmt = $conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."' and SUBSTRING_INDEX(asof,' ',1)='".$_POST['tdatetime']."'  order by asof DESC, cid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();
        }
    }elseif($filters=="date"){
            $stmt = $conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."' and asof='".$_GET['value']."'  order by asof DESC, cid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();

            try{
                $stmt = $conn->prepare("UPDATE cgs_record SET notif=1 WHERE asof=:dateval ");
                $stmt->bindParam(':dateval', $_GET['value']);
                $stmt->execute();
            }
            catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
            }
    }else{
            $stmt = $conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."'  order by asof DESC, cid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();

            try{
                $stmt = $conn->prepare("UPDATE cgs_record SET notif=1 WHERE typhoon_name='".$tname."'  ");
                $stmt->execute();
            }
            catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
            }
    }
    
    
    //populate combobox filter by
    //$stmt = $conn->prepare("SELECT DISTINCT tdatetime FROM cgs_record  order by tdatetime DESC ");
    $stmt=$conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."'  GROUP BY SUBSTRING_INDEX(asof,' ',1) order by SUBSTRING_INDEX(asof,' ',1) DESC ");
    $stmt->execute(); 
    $resulttfdateall = $stmt->fetchAll();
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


        <link rel="stylesheet" href="demo/css/demo.css">

        <?php // include('include/head_tab.php')?>

        <link rel="icon" type="image/png" href="img/icon.png" sizes="96x96">
        <title>DISASTER REPORT ( LDRRMO / SPDRRMC )</title>
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <style>
            .contacts__btn1{  position: relative;  display: inline-block;  border-radius:10px; padding:4px; }
            .contacts__btn1 .badge1 {  position: absolute; top: -5px; right: -5px; padding: 0px 0px; border-radius: 50%; background-color: red; color: white; }
            .abtn1{ color:#eae9ea; font-size: 10px; font-weight: bold; padding:4px; }
            .abtn1:hover{ color:white; }
        </style>


        <?php //include('include/head_tab.php')?>

        



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
                    </ul>
                </div>
            </aside>


            <section class="content">

                   <div class="row" style="margin-bottom:20px;">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="index2.php">Users  </a>  &nbsp;>&nbsp;
                      <a href="view_report.php">[&nbsp; <?php echo $_SESSION["admin_mun"];?> &nbsp;] &nbsp;Typhoon  </a>  &nbsp;>&nbsp;
                      <a href="index1_cgs.php">Report List for: <?php echo $_SESSION["admin_typhoonname"];?> </a>  &nbsp;>&nbsp;
                      <label>List</label>
                   </div>

                
                            
                
                <div class="card">
                    <div class="card-body">
                        <!--<h4 class="card-title">Record</h4>-->

                        <?php
                        //if($filters==""){
                        ?>
                        <form action="list_cgs.php?trig=fil" method="post" style=" width:250px; height:33px; padding:0px; margin:0px;">
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
                                              $exp = explode(" ", $popdateall['asof']);
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
                       // }else{
                       // }

                        ?>


                        
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
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center;">Coast Guard Station</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; padding-left:8px; vertical-align : middle;text-align:left;">Coast Guard Sub Station</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Name of Port</th>
                                        <th colspan="6" rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Number of Stranded/s</th>
                                        <th colspan="2" rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Taking Shelter</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Weather / Remarks</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">As of</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Passenger/s</th>
                                        <th colspan="3" rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Rolling Cargo</th>
                                        <th rowspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Vessel</th>
                                        <th rowspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Mbca</th>
                                        <th rowspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Vessel</th>
                                        <th rowspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Mbca</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Bus</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Trucks</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Car</th>
                                    </tr>

                                </thead>
                                <tbody>


                                <?php
                                
                                
                                 foreach ($resulttf as $valuetf) 
                                 {
                                    
                                   
                                ?>
                                    <tr style="border:1px solid #2b4c4a;">
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['cgs_main']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?=$valuetf['cgs_sub']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['n_port']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['ns_passenger']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['ns_roll_bus']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['ns_roll_truck']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['ns_roll_car']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['ns_vessel']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['ns_mbca']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['ts_vessel']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['ts_mbca']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['remarks']?></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['asof']?></td>
                                    </tr>
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