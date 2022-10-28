
<?php
    error_reporting(0);

    require_once('session/check_user.php');

    $tname=$_SESSION['typhoonname'];

    if ($tname != "" && $u_mun=="cgs sorsogon"){
    }else{
        header("location:cgs_home.php");
    }
    


    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  

    //filter search check
    $filter=$_GET['trig'];  $filters="";  if($filter =="fil"){  $filters="1";  }else{  $filters="";  }
    //end
    if ($filters=="1"){
        if ($_POST['tdatetime']=="All" || $_POST['tdatetime']==""){
            $stmt = $conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."' order by asof DESC, cid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();

            echo "<script>window.location='cgs_entry.php'</script>";
        }else{
            $stmt = $conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."' and SUBSTRING_INDEX(asof,' ',1)='".$_POST['tdatetime']."' order by asof DESC, cid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();

        }
    }else{
        $stmt = $conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."' order by asof DESC, cid ASC");
        $stmt->execute(); 
        $resulttf = $stmt->fetchAll();

    }
    

    $stmt = $conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."' and cid='".$_GET['id']."' ");
    $stmt->execute(); 
    $resulttfselect = $stmt->fetch(PDO::FETCH_ASSOC);
    
    

    //populate combobox delete filter
    $stmt = $conn->prepare("SELECT DISTINCT asof FROM cgs_record WHERE typhoon_name='".$tname."'  order by asof DESC ");
    $stmt->execute(); 
    $resulttfdatedeletefilter = $stmt->fetchAll();
    //end


    //populate combobox filter by
    //$stmt = $conn->prepare("SELECT DISTINCT tdatetime FROM typhoon_form  order by tdatetime DESC ");
    $stmt=$conn->prepare("SELECT * FROM cgs_record WHERE typhoon_name='".$tname."'    GROUP BY SUBSTRING_INDEX(asof,' ',1) order by SUBSTRING_INDEX(asof,' ',1) DESC ");
    $stmt->execute(); 
    $resulttfdateall = $stmt->fetchAll();
    //end

    //open close colapse
    $update=$_GET['id'];  $updatestat="";  if($update>0){  $updatestat="-in";  }else{  $updatestat="";  }
    //end

    
    



    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['update'])){
            try{
                    $stmt = $conn->prepare("UPDATE cgs_record SET cgs_sub=:cgs_sub, n_port=:n_port, ns_passenger=:ns_passenger, ns_roll_bus=:ns_roll_bus, ns_roll_truck=:ns_roll_truck, ns_roll_car=:ns_roll_car, ns_vessel=:ns_vessel, ns_mbca=:ns_mbca, ts_vessel=:ts_vessel, ts_mbca=:ts_mbca, remarks=:remarks WHERE cid=:cid");
                    $stmt->bindParam(':cgs_sub', $_POST['cgs_sub']);
                    $stmt->bindParam(':n_port', $_POST['n_port']);
                    $stmt->bindParam(':ns_passenger', $_POST['ns_passenger']);
                    $stmt->bindParam(':ns_roll_bus', $_POST['ns_roll_bus']);
                    $stmt->bindParam(':ns_roll_truck', $_POST['ns_roll_truck']);
                    $stmt->bindParam(':ns_roll_car', $_POST['ns_roll_car']);
                    $stmt->bindParam(':ns_vessel', $_POST['ns_vessel']);
                    $stmt->bindParam(':ns_mbca', $_POST['ns_mbca']);
                    $stmt->bindParam(':ts_vessel', $_POST['ts_vessel']);
                    $stmt->bindParam(':ts_mbca', $_POST['ts_mbca']);
                    $stmt->bindParam(':remarks', $_POST['remarks']);
                    $stmt->bindParam(':cid', $_GET['id']);
                    $stmt->execute();
                    if ($num < 1){  echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully updated : ( ".$_POST['cgs_sub']." )  '); window.location='cgs_entry.php'</script>";
                    }else{  echo "<script>alert('✔ Successfully updated to : ( ".$_POST['cgs_sub']." )  '); window.location='cgs_entry.php'</script>";  }

                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
                }
        }
        if(isset($_POST['delete'])){
            try{
                    $stmt = $conn->prepare("DELETE FROM cgs_record WHERE asof=:tdatetime and typhoon_name='".$tname."' ");
                    $stmt->bindParam(':tdatetime', $_POST['tdatetime']);
                    $stmt->execute();
                    echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Deleted : ( ".$_POST['tdatetime']." ) Record '); window.location='cgs_entry.php'</script>";
                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
                }
        }
        if(isset($_POST['add'])){
            $mun=$u_mun;
            $num="0";  if ($_POST['cgs_sub'] == "" && $_POST['n_port'] == "" && $_POST['ns_passenger'] < 1 && $_POST['ns_roll_bus'] < 1  && $_POST['ns_roll_truck'] < 1 && $_POST['ns_roll_car'] < 1 && $_POST['ns_vessel'] < 1 && $_POST['ns_mbca'] < 1 && $_POST['ts_vessel'] < 1 && $_POST['ts_mbca'] < 1  && $_POST['remarks'] == "" )    { $num="0"; }else{ $num="1"; }
            $num1="0";  if ($_POST['cgs_sub1'] == "" && $_POST['n_port1'] == "" && $_POST['ns_passenger1'] < 1 && $_POST['ns_roll_bus1'] < 1  && $_POST['ns_roll_truck1'] < 1 && $_POST['ns_roll_car1'] < 1 && $_POST['ns_vessel1'] < 1 && $_POST['ns_mbca1'] < 1 && $_POST['ts_vessel1'] < 1 && $_POST['ts_mbca1'] < 1  && $_POST['remarks'] == "" )    { $num1="0"; }else{ $num1="1"; }
            $num2="0";  if ($_POST['cgs_sub2'] == "" && $_POST['n_port2'] == "" && $_POST['ns_passenger2'] < 1 && $_POST['ns_roll_bus2'] < 1  && $_POST['ns_roll_truck2'] < 1 && $_POST['ns_roll_car2'] < 1 && $_POST['ns_vessel2'] < 1 && $_POST['ns_mbca2'] < 1 && $_POST['ts_vessel2'] < 1 && $_POST['ts_mbca2'] < 1  && $_POST['remarks'] == "" )    { $num2="0"; }else{ $num2="1"; }
            $num3="0";  if ($_POST['cgs_sub3'] == "" && $_POST['n_port3'] == "" && $_POST['ns_passenger3'] < 1 && $_POST['ns_roll_bus3'] < 1  && $_POST['ns_roll_truck3'] < 1 && $_POST['ns_roll_car3'] < 1 && $_POST['ns_vessel3'] < 1 && $_POST['ns_mbca3'] < 1 && $_POST['ts_vessel3'] < 1 && $_POST['ts_mbca3'] < 1  && $_POST['remarks'] == "" )    { $num3="0"; }else{ $num3="1"; }
            $num4="0";  if ($_POST['cgs_sub4'] == "" && $_POST['n_port4'] == "" && $_POST['ns_passenger4'] < 1 && $_POST['ns_roll_bus4'] < 1  && $_POST['ns_roll_truck4'] < 1 && $_POST['ns_roll_car4'] < 1 && $_POST['ns_vessel4'] < 1 && $_POST['ns_mbca4'] < 1 && $_POST['ts_vessel4'] < 1 && $_POST['ts_mbca4'] < 1  && $_POST['remarks'] == "" )    { $num4="0"; }else{ $num4="1"; }
            
            //echo " 1 ".$num." 2 ".$num1." 3 ".$num2." 4 ".$num3." 5 ".$num4;
            try{
              if($_POST['asof'] == ""){ } else{
                if($num > 0){
                    $stmt = $conn->prepare("INSERT INTO `cgs_record`(`typhoon_name`, `asof`, `cgs_main`, `cgs_sub`, `n_port`, `ns_passenger`, `ns_roll_bus`, `ns_roll_truck`, `ns_roll_car`, `ns_vessel`, `ns_mbca`, `ts_vessel`, `ts_mbca`, `remarks`) VALUES (:typhoon_name, :asof, :cgs_main, :cgs_sub, :n_port, :ns_passenger, :ns_roll_bus, :ns_roll_truck, :ns_roll_car, :ns_vessel, :ns_mbca, :ts_vessel, :ts_mbca, :remarks)");
                    $stmt->bindParam(':typhoon_name', $tname);
                    $stmt->bindParam(':asof', $_POST['asof']);
                    $stmt->bindParam(':cgs_main', $_POST['cgs_main']);
                    $stmt->bindParam(':cgs_sub', $_POST['cgs_sub']);
                    $stmt->bindParam(':n_port', $_POST['n_port']);
                    $stmt->bindParam(':ns_passenger', $_POST['ns_passenger']);
                    $stmt->bindParam(':ns_roll_bus', $_POST['ns_roll_bus']);
                    $stmt->bindParam(':ns_roll_truck', $_POST['ns_roll_truck']);
                    $stmt->bindParam(':ns_roll_car', $_POST['ns_roll_car']);
                    $stmt->bindParam(':ns_vessel', $_POST['ns_vessel']);
                    $stmt->bindParam(':ns_mbca', $_POST['ns_mbca']);
                    $stmt->bindParam(':ts_vessel', $_POST['ts_vessel']);
                    $stmt->bindParam(':ts_mbca', $_POST['ts_mbca']);
                    $stmt->bindParam(':remarks', $_POST['remarks']);
                    $stmt->execute();
                }else{}
                if($num1 > 0){
                    $stmt = $conn->prepare("INSERT INTO `cgs_record`(`typhoon_name`, `asof`, `cgs_main`, `cgs_sub`, `n_port`, `ns_passenger`, `ns_roll_bus`, `ns_roll_truck`, `ns_roll_car`, `ns_vessel`, `ns_mbca`, `ts_vessel`, `ts_mbca`, `remarks`) VALUES (:typhoon_name1, :asof1, :cgs_main1, :cgs_sub1, :n_port1, :ns_passenger1, :ns_roll_bus1, :ns_roll_truck1, :ns_roll_car1, :ns_vessel1, :ns_mbca1, :ts_vessel1, :ts_mbca1, :remarks1)");
                    $stmt->bindParam(':typhoon_name1', $tname);
                    $stmt->bindParam(':asof1', $_POST['asof']);
                    $stmt->bindParam(':cgs_main1', $_POST['cgs_main1']);
                    $stmt->bindParam(':cgs_sub1', $_POST['cgs_sub1']);
                    $stmt->bindParam(':n_port1', $_POST['n_port1']);
                    $stmt->bindParam(':ns_passenger1', $_POST['ns_passenger1']);
                    $stmt->bindParam(':ns_roll_bus1', $_POST['ns_roll_bus1']);
                    $stmt->bindParam(':ns_roll_truck1', $_POST['ns_roll_truck1']);
                    $stmt->bindParam(':ns_roll_car1', $_POST['ns_roll_car1']);
                    $stmt->bindParam(':ns_vessel1', $_POST['ns_vessel1']);
                    $stmt->bindParam(':ns_mbca1', $_POST['ns_mbca1']);
                    $stmt->bindParam(':ts_vessel1', $_POST['ts_vessel1']);
                    $stmt->bindParam(':ts_mbca1', $_POST['ts_mbca1']);
                    $stmt->bindParam(':remarks1', $_POST['remarks']);
                    $stmt->execute();
                }else{}
                if($num2 > 0){
                    $stmt = $conn->prepare("INSERT INTO `cgs_record`(`typhoon_name`, `asof`, `cgs_main`, `cgs_sub`, `n_port`, `ns_passenger`, `ns_roll_bus`, `ns_roll_truck`, `ns_roll_car`, `ns_vessel`, `ns_mbca`, `ts_vessel`, `ts_mbca`, `remarks`) VALUES (:typhoon_name2, :asof2, :cgs_main2, :cgs_sub2, :n_port2, :ns_passenger2, :ns_roll_bus2, :ns_roll_truck2, :ns_roll_car2, :ns_vessel2, :ns_mbca2, :ts_vessel2, :ts_mbca2, :remarks2)");
                    $stmt->bindParam(':typhoon_name2', $tname);
                    $stmt->bindParam(':asof2', $_POST['asof']);
                    $stmt->bindParam(':cgs_main2', $_POST['cgs_main2']);
                    $stmt->bindParam(':cgs_sub2', $_POST['cgs_sub2']);
                    $stmt->bindParam(':n_port2', $_POST['n_port2']);
                    $stmt->bindParam(':ns_passenger2', $_POST['ns_passenger2']);
                    $stmt->bindParam(':ns_roll_bus2', $_POST['ns_roll_bus2']);
                    $stmt->bindParam(':ns_roll_truck2', $_POST['ns_roll_truck2']);
                    $stmt->bindParam(':ns_roll_car2', $_POST['ns_roll_car2']);
                    $stmt->bindParam(':ns_vessel2', $_POST['ns_vessel2']);
                    $stmt->bindParam(':ns_mbca2', $_POST['ns_mbca2']);
                    $stmt->bindParam(':ts_vessel2', $_POST['ts_vessel2']);
                    $stmt->bindParam(':ts_mbca2', $_POST['ts_mbca2']);
                    $stmt->bindParam(':remarks2', $_POST['remarks']);
                    $stmt->execute();
                }else{}
                if($num3 > 0){
                    $stmt = $conn->prepare("INSERT INTO `cgs_record`(`typhoon_name`, `asof`, `cgs_main`, `cgs_sub`, `n_port`, `ns_passenger`, `ns_roll_bus`, `ns_roll_truck`, `ns_roll_car`, `ns_vessel`, `ns_mbca`, `ts_vessel`, `ts_mbca`, `remarks`) VALUES (:typhoon_name3, :asof3, :cgs_main3, :cgs_sub3, :n_port3, :ns_passenger3, :ns_roll_bus3, :ns_roll_truck3, :ns_roll_car3, :ns_vessel3, :ns_mbca3, :ts_vessel3, :ts_mbca3, :remarks3)");
                    $stmt->bindParam(':typhoon_name3', $tname);
                    $stmt->bindParam(':asof3', $_POST['asof']);
                    $stmt->bindParam(':cgs_main3', $_POST['cgs_main3']);
                    $stmt->bindParam(':cgs_sub3', $_POST['cgs_sub3']);
                    $stmt->bindParam(':n_port3', $_POST['n_port3']);
                    $stmt->bindParam(':ns_passenger3', $_POST['ns_passenger3']);
                    $stmt->bindParam(':ns_roll_bus3', $_POST['ns_roll_bus3']);
                    $stmt->bindParam(':ns_roll_truck3', $_POST['ns_roll_truck3']);
                    $stmt->bindParam(':ns_roll_car3', $_POST['ns_roll_car3']);
                    $stmt->bindParam(':ns_vessel3', $_POST['ns_vessel3']);
                    $stmt->bindParam(':ns_mbca3', $_POST['ns_mbca3']);
                    $stmt->bindParam(':ts_vessel3', $_POST['ts_vessel3']);
                    $stmt->bindParam(':ts_mbca3', $_POST['ts_mbca3']);
                    $stmt->bindParam(':remarks3', $_POST['remarks']);
                    $stmt->execute();
                }else{}
                if($num4 > 0){
                    $stmt = $conn->prepare("INSERT INTO `cgs_record`(`typhoon_name`, `asof`, `cgs_main`, `cgs_sub`, `n_port`, `ns_passenger`, `ns_roll_bus`, `ns_roll_truck`, `ns_roll_car`, `ns_vessel`, `ns_mbca`, `ts_vessel`, `ts_mbca`, `remarks`) VALUES (:typhoon_name4, :asof4, :cgs_main4, :cgs_sub4, :n_port4, :ns_passenger4, :ns_roll_bus4, :ns_roll_truck4, :ns_roll_car4, :ns_vessel4, :ns_mbca4, :ts_vessel4, :ts_mbca4, :remarks4)");
                    $stmt->bindParam(':typhoon_name4', $tname);
                    $stmt->bindParam(':asof4', $_POST['asof']);
                    $stmt->bindParam(':cgs_main4', $_POST['cgs_main4']);
                    $stmt->bindParam(':cgs_sub4', $_POST['cgs_sub4']);
                    $stmt->bindParam(':n_port4', $_POST['n_port4']);
                    $stmt->bindParam(':ns_passenger4', $_POST['ns_passenger4']);
                    $stmt->bindParam(':ns_roll_bus4', $_POST['ns_roll_bus4']);
                    $stmt->bindParam(':ns_roll_truck4', $_POST['ns_roll_truck4']);
                    $stmt->bindParam(':ns_roll_car4', $_POST['ns_roll_car4']);
                    $stmt->bindParam(':ns_vessel4', $_POST['ns_vessel4']);
                    $stmt->bindParam(':ns_mbca4', $_POST['ns_mbca4']);
                    $stmt->bindParam(':ts_vessel4', $_POST['ts_vessel4']);
                    $stmt->bindParam(':ts_mbca4', $_POST['ts_mbca4']);
                    $stmt->bindParam(':remarks4', $_POST['remarks']);
                    $stmt->execute();
                }else{}


                echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Added '); window.location='cgs_entry.php'</script>";
              }
                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {   echo 'Previous Error: '.$e->getMessage() . "<br/>";  }
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

        <?php include('include/head_tab.php')?>

        <?php include('logs.php')?>

        



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
            <?php include('include/user_header.php');?>
            <aside class="sidebar">
                <div class="scrollbar-inner">
                   <?php include('include/user_profile.php')?>
                </div>
                    <ul class="navigation">
                       <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="cgs_home.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                       <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    </ul>
                </div>
            </aside>


            <section class="content">

                   <div class="row" style="margin-bottom:20px;">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="cgs_home.php">Typhoon  </a>  &nbsp;⟶&nbsp;
                      <a href="cgs_report.php">Report to:  </a>  &nbsp;⟶&nbsp;
                      <label>Manage Coast Guard Report For: <?php echo $_SESSION["typhoonname"]; ?></label>
                   </div>

                <div class="row" style=" padding:15px;  padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:15px;">
                
                


                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"  >
                                <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                    <div class="panel-heading" role="tab" id="headingOne" >
                                        <h4 class="panel-title">
                                           <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size:12px;"> ADD RECORD </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                          <form action="cgs_entry.php" method="post">
                                            <div class="row" style=" padding:0px; padding-bottom:10px; padding-top:10px;">
                                                    <div class="col-sm-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp;&nbsp;As of:&nbsp;</span>
                                                            <div class="form-group">
                                                                <input style="border:1px solid #406362;" name="asof" id="myText" onchange="success()" type="text" class="form-control datetime-picker" placeholder="Click here to select Date and Time">
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script type="text/javascript">
                                                        function success() {
                                                            var tag=document.getElementById("add");
                                                            if(document.getElementById("myText").value==="") {
                                                                document.getElementById('add').disabled = true;
                                                            } else { 
                                                                tag.style.background="green";
                                                                document.getElementById('add').disabled = false;
                                                            }
                                                        }
                                                    </script>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="table" class="table" >
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center;">Coast Guard Station</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Coast Guard Sub Station</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Name of Port</th>
                                                            <th colspan="6" rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Number of Stranded/s</th>
                                                            <th colspan="2" rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Taking Shelter</th>
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
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="cgs_main" value="Sorsogon" style="width:140px; height:100%; margin:0px; text-align:center; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="cgs_sub" id="" style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Bulan">Bulan</option> <option value="Castilla">Castilla</option>  <option value="Donsol">Donsol</option> <option value="Matnog">Matnog</option>  <option value="Pilar">Pilar</option>         </select> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="n_port"  style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_passenger"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_bus"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number"   name="ns_roll_truck"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_car"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_vessel"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_mbca"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_vessel"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_mbca"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="cgs_main1" value="Sorsogon" style="width:140px; height:100%; margin:0px;  text-align:center; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="cgs_sub1" id="" style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Bulan">Bulan</option> <option value="Castilla">Castilla</option>  <option value="Donsol">Donsol</option> <option value="Matnog">Matnog</option>  <option value="Pilar">Pilar</option>         </select> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="n_port1"  style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_passenger1"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_bus1"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number"   name="ns_roll_truck1"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_car1"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_vessel1"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_mbca1"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_vessel1"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_mbca1"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="cgs_main2" value="Sorsogon" style="width:140px; height:100%; margin:0px;  text-align:center; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="cgs_sub2" id="" style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Bulan">Bulan</option> <option value="Castilla">Castilla</option>  <option value="Donsol">Donsol</option> <option value="Matnog">Matnog</option>  <option value="Pilar">Pilar</option>         </select> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="n_port2"  style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_passenger2"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_bus2"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number"   name="ns_roll_truck2"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_car2"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_vessel2"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_mbca2"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_vessel2"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_mbca2"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="cgs_main3" value="Sorsogon" style="width:140px; height:100%; margin:0px; text-align:center; padding-left:8px; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="cgs_sub3" id="" style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Bulan">Bulan</option> <option value="Castilla">Castilla</option>  <option value="Donsol">Donsol</option> <option value="Matnog">Matnog</option>  <option value="Pilar">Pilar</option>         </select> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="n_port3"  style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_passenger3"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_bus3"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number"   name="ns_roll_truck3"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_car3"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_vessel3"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_mbca3"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_vessel3"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_mbca3"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="cgs_main4" value="Sorsogon" style="width:140px; height:100%; margin:0px;  text-align:center; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="cgs_sub4" id="" style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Bulan">Bulan</option> <option value="Castilla">Castilla</option>  <option value="Donsol">Donsol</option> <option value="Matnog">Matnog</option>  <option value="Pilar">Pilar</option>         </select> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="n_port4"  style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_passenger4"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_bus4"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number"   name="ns_roll_truck4"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_car4"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_vessel4"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_mbca4"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_vessel4"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_mbca4"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="input-group" style="margin-bottom: 20px;">
                                                <div class="form-group">
                                                    <label>Remarks</label>
                                                    <textarea  style="padding:0px;" class="form-control textarea-autosize"  name="remarks"  placeholder="Remarks here..."></textarea>
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom:15px;">
                                               <div class="col-sm-3"><button title="Save to Database (Note: Date & Time Required to { enable this Save Button } )" type="submit" name="add" id="add" disabled > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SAVE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Add" href="cgs_entry.php"> CANCEL</a>  </div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                            </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>



                                <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                    <div class="panel-heading" role="tab" id="headingFour">
                                        <h4 class="panel-title">
                                           <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="font-size:12px;"> DELETE FILTER </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                        <div class="panel-body">
                                         <form action="cgs_entry.php" method="post" style=" width:500px;">
                                            <div class="row" style=" padding:0px; padding-bottom:10px; padding-top:10px; padding-left:15px;">
                                                <div class="input-group">
                                                    <div class="form-group">
                                                    <select class="select2"  name="tdatetime" style="width:300px;" >
                                                        <option value="norecord" disabled selected >Select Date & Time to Delete</option>
                                                        <?php foreach($resulttfdatedeletefilter as $popdate1){?>
                                                           <option value="<?=$popdate1['asof']?>" ><?=$popdate1['asof']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    </div>
                                                    <span style=" vertical-align : middle;text-align:center; padding:5px;  margin-right:15px;">  <button title="Confirm Delete" type="submit" name="delete" style="heigth:100%;" > &nbsp;&nbsp;&nbsp; DELETE &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Decampment" href="cgs_entry.php"> CANCEL</a>  </span>
                                                </div>
                                            </div>
                                          </form>
                                        </div>
                                    </div>
                                </div>



                                <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse<?php echo $updatestat;?>" role="tabpanel" aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                         <form action="cgs_entry.php?id=<?=$resulttfselect['cid']?>" method="post">
                                            <div class="row" style=" padding:0px; padding-bottom:10px; padding-top:10px;">
                                                    <div class="col-sm-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> &nbsp;&nbsp;&nbsp;As of:&nbsp;</span>
                                                            <div class="form-group">
                                                                <input type="text" name="tdatetime" value="<?=$resulttfselect['asof']?>" style="width:120px; height:100%; margin:0px; background-color:rgba(0,0,0,0); border:1px solid #3d5859; border-left:none; color:#a8b6b5;" readonly> <input type="hidden" name="tid" value="<?=$resulttfselect['tid']?>" style="width:50px; height:100%; margin:0px; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="table" class="table" >
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center;">Coast Guard Station</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Coast Guard Sub Station</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Name of Port</th>
                                                            <th colspan="6" rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Number of Stranded/s</th>
                                                            <th colspan="2" rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Taking Shelter</th>
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
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="cgs_main" value="Sorsogon" style="width:140px; height:100%; margin:0px; text-align:center; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="cgs_sub"   id="" style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="<?=$resulttfselect['cgs_sub']?>"><?=$resulttfselect['cgs_sub']?></option> <option value="Bulan">Bulan</option> <option value="Castilla">Castilla</option>  <option value="Donsol">Donsol</option> <option value="Matnog">Matnog</option>  <option value="Pilar">Pilar</option>         </select> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="n_port"   value="<?=$resulttfselect['n_port']?>"  style="width:157px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_passenger"  value="<?=$resulttfselect['ns_passenger']?>" style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_bus"   value="<?=$resulttfselect['ns_roll_bus']?>"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number"   name="ns_roll_truck"  value="<?=$resulttfselect['ns_roll_truck']?>" style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_roll_car"  value="<?=$resulttfselect['ns_roll_car']?>"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_vessel"  value="<?=$resulttfselect['ns_vessel']?>"  style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ns_mbca" value="<?=$resulttfselect['ns_mbca']?>" style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_vessel" value="<?=$resulttfselect['ts_vessel']?>" style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="ts_mbca" value="<?=$resulttfselect['ts_mbca']?>" style="width:95px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="input-group" style="margin-bottom: 20px;">
                                                <div class="form-group">
                                                    <label>Remarks</label>
                                                    <textarea  style="padding:0px;" class="form-control textarea-autosize"  name="remarks"  placeholder="Remarks here..." ><?=$resulttfselect['remarks']?></textarea>
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom:15px;">
                                               <div class="col-sm-3"><button title="Save Changes" type="submit" name="update" > &nbsp;&nbsp;&nbsp; SAVE CHANGES &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Edit" href="cgs_entry.php"> CANCEL</a>  </div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                            </div>
                                          
                                          </form>

                                        </div>



                                    </div>
                                </div>




                            </div>




                </div>
                
                <div class="card">
                    <div class="card-body">
                        <!--<h4 class="card-title">Record</h4>-->


                        <form action="cgs_entry.php?trig=fil" method="post" style=" width:250px; height:33px; padding:0px; margin:0px;">
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
                                    <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">&nbsp;</th>
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
                                 $num="1";
                                 foreach ($resulttf as $valuetf) 
                                 {
                                    if ($num=="6"){
                                        $num="1";
                                    }else{}    
                                ?>
                                    <tr style="border:1px solid #2b4c4a;">
                                        <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                            <a title="Edit ( <?=$valuetf['asof']?> )" href="cgs_entry.php?id=<?=$valuetf['cid']?>" style="padding-left:5px;padding-right:5px; border-radius:50px;" ><i class="zmdi zmdi-edit" ></i></a>
                                        </td>
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
                                $num++; } ?>
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