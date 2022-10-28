<?php

// require_once('config/dbcon.php');
// $conn1 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $stmt = $conn1->prepare("SELECT t1.tid,t1.remarks as p1,t2.remarks as p2,t3.remarks as p3,
// t4.remarks as p4,
// t5.remarks as p5
// FROM table1 t1,table2 t2,table3 t3,table4 t4,table5 t5 where t2.tid=t1.tid and t3.tid=t1.tid and t4.tid=t1.tid and t5.tid=t1.tid;");


// $stmt->execute(); 
// $result =$stmt->fetchAll(); //$stmt->fetch(PDO::FETCH_ASSOC);
// $i=1;
//   foreach ($result as $value){
//    echo "<div>".$i."  ".$value['p1']."    _______________________    "."  ".$value['p2']."    _______________________     "."  ".$value['p3']."    _______________________     "."  ".$value['p4']."    _______________________     "."  ".$value['p5']."<br></div>";
   
//   $i++;}
 
?>




<?php
    error_reporting(0);

    require_once('session/check_admin.php');

    $tname=$_SESSION['admin_typhoonname'];

    //if ($tname != "" && $mun=="sta. magdalena"){
    //}else{
    //    header("location:user_typhoon.php");
    //}
    


    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    //$stmt = $conn->prepare("SELECT MAX(iid) AS max FROM 1pod");
    //$stmt->execute(); 
    //$id = $stmt->fetch(PDO::FETCH_ASSOC);

    ////echo $id['max']+1;
    //$uid=$id['max']+1;

    $muni = $_SESSION['admin_mun'];

    

    if($mun=="sta. magdalena"){
      $stmt = $conn->prepare("SELECT * FROM barangay WHERE b_municipality='SANTA MAGDALENA' order by b_barangay ASC");
      $stmt->execute(); 
      $result_brgy = $stmt->fetchAll();
    }else{
      $resStr = strtoupper($muni); 
      $stmt = $conn->prepare("SELECT * FROM barangay WHERE b_municipality='".$resStr."' order by b_barangay ASC");
      $stmt->execute(); 
      $result_brgy = $stmt->fetchAll();
    }

    
    //echo '<pre>'; print_r($result_brgy); echo '</pre>';
     
    //echo "<script>alert(".$result_brgy."); window.location='damage_assess.php'</script>";


    

//===============================================SELECT UPDATE===============================================================================================================================


    $stmt = $conn->prepare("SELECT * FROM 1pod  WHERE tname='".$tname."' and mun='".$muni."' and iid = '".$_GET['id']."' ");   
    $stmt->execute();
    $profi = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 2aa  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' ");   
    $stmt->execute();
    $areaaf = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 3pa  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' ");   
    $stmt->execute();
    $popaf = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 4pd  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' ");   
    $stmt->execute();
    $popdi = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 5c  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' ");   
    $stmt->execute();
    $cas = $stmt->fetch(PDO::FETCH_ASSOC);

    //damage prop
    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Houses' ");   
    $stmt->execute();
    $dam = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'School Bldgs' ");   
    $stmt->execute();
    $dam1 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Govt Offices' ");   
    $stmt->execute();
    $dam2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Public Markets' ");   
    $stmt->execute();
    $dam3 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Flood Control' ");   
    $stmt->execute();
    $dam4 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Commercial Facilities' ");   
    $stmt->execute();
    $dam5 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Others' ");   
    $stmt->execute();
    $dam6 = $stmt->fetch(PDO::FETCH_ASSOC);
    //tourism


    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Resorts' ");   
    $stmt->execute();
    $dam7 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Hotels' ");   
    $stmt->execute();
    $dam8 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Restaurants' ");   
    $stmt->execute();
    $dam9 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Tourism Assisted Center' ");   
    $stmt->execute();
    $dam10 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Theme Parks' ");   
    $stmt->execute();
    $dam11 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Visitors Centers' ");   
    $stmt->execute();
    $dam12 = $stmt->fetch(PDO::FETCH_ASSOC);
    //end damage prop


    //damage lifelines
    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'National' and dloc = 'Roads' ");
    $stmt->execute();
    $daml = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Provincial' and dloc = 'Roads' ");
    $stmt->execute();
    $daml1 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Municipality' and dloc = 'Roads' ");
    $stmt->execute();
    $daml2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'City' and dloc = 'Roads' ");
    $stmt->execute();
    $daml3 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Barangay' and dloc = 'Roads' ");
    $stmt->execute();
    $daml4 = $stmt->fetch(PDO::FETCH_ASSOC);


    //bridges
    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Bailey' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml5 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Concrete' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml6 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Wooden' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml7 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Suspension' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml8 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Railways' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml9 = $stmt->fetch(PDO::FETCH_ASSOC);

    //communication
    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Communication Facilities' and dlist = 'PLDT' ");
    $stmt->execute();
    $daml10 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Communication Facilities' and dlist = 'Bayan Tel' ");
    $stmt->execute();
    $daml11 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Communication Facilities' and dlist = 'Cell Sites' ");
    $stmt->execute();
    $daml12 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and dtype = 'Communication Facilities' and dlist = 'Repeaters' ");
    $stmt->execute();
    $daml13 = $stmt->fetch(PDO::FETCH_ASSOC);
    //end damage lifelines


    //agriculture
    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Rice'  ");
    $stmt->execute();
    $agri = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Corn'  ");
    $stmt->execute();
    $agri1 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Vegetables'  ");
    $stmt->execute();
    $agri2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Root Crops'  ");
    $stmt->execute();
    $agri3 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Fruit Trees'  ");
    $stmt->execute();
    $agri4 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Bananas'  ");
    $stmt->execute();
    $agri5 = $stmt->fetch(PDO::FETCH_ASSOC);


    //fisheries
    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Fisheries' and alist = 'Animals'  ");
    $stmt->execute();
    $agri6 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Fisheries' and alist = 'Farm'  ");
    $stmt->execute();
    $agri7 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."' and ctype = 'Fisheries' and alist = 'Poultry & Fowls'  ");
    $stmt->execute();
    $agri8 = $stmt->fetch(PDO::FETCH_ASSOC);
    //end agriculture


     //local actions
    $stmt = $conn->prepare("SELECT * FROM 9la  WHERE tname='".$tname."' and mun='".$muni."' and uid = '".$_GET['id']."'  ");
    $stmt->execute();
    $loca = $stmt->fetch(PDO::FETCH_ASSOC);


    //==================================================END SELECT UPDATE============================================================================================================================


    //echo $areaaf['brgyaf'];
    

    

    
    



     
        

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
        

        



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
          $("tr").hover(function(){
              $(this).css("background-color", "rgba(0,0,0,0.2)");
              }, function(){
              $(this).css("background-color", "rgba(0,0,0,0)");
          });

          $('#prt').click(function(){
            window.print();
          });
          $('#prt').hover(function(){
            $(this).css("background-color", "green");
            }, function(){
            $(this).css("background-color", "rgba(0,0,0,0)");
          });
          $('#prt1').hover(function(){
            $(this).css("background-color", "green");
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
                    <li><a  id="prt1" href="dam_admin_view.php"  style="background-color:rgba(0,0,0,0.2); width:60px; height:20px; float:right; text-align:center; padding:0px; border:1px solid grey ">Back</a> </li>
                    <li><a  id="prt"  style="background-color:rgba(0,0,0,0.2); width:60px; height:20px; float:right; text-align:center; padding:0px; border:1px solid grey ">Print</a> </li>
                    </ul>
                </div>
            </aside>


            <section class="content">

                  

             
                
                <div class="card">
                                                <div class="card-body" style="padding:0px;">
                                                    <div class="tab-container" style=" margin:0px;">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                              <li class="nav-item"  >
                                                                  <a class="nav-link" data-toggle="tab" href="#summarye" role="tab" style="border-bottom: 1px solid rgba(0,0,0,0.1);">DAMAGE ASSESSMENT REPORT OF ( <?=strtoupper($muni)?> )</a>
                                                              </li>
                                                        </ul>

                                                        <div class="tab-content">
                                                              <div class="tab-pane active fade show" id="summarye" role="tabpanel" style="padding-left:5px; padding-right:5px; width:1000px; ">




                                                              <label for="" style=" color:#a6bbbb; margin-top:5px; margin-bottom:5px;" > <code style="color:#a6bbbb;"> A. PROFILE OF DISASTER</code></label>

                                                                 


                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);   ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">Profile of Disaster</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">Type of Disaster / Emergency :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$profi['t_em']?>"  name="t_em1"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="" value="TYPHOON"  >
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom:4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Date and Time of Occurrence :</span>
                                                                                      <div class="form-group">
                                                                                          <input style=" padding:6px;  color:white;  border-left:none; border-bottom: 1px solid rgba(0,0,0,0.1);" value="<?=$profi['d_oc']?>" name="d_oc1" id="myText" onchange="success()" type="text" class="form-control datetime-picker"  placeholder="" >
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px;">
                                                                                        <span class="input-group-addon" style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Source of Report :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$profi['s_r']?>"  name="s_r1" style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="" >
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>


                                                                        </div>
                                                                    </div>


                                                                <label for="" style=" color:#a6bbbb;  margin-bottom:5px; margin-top:10px;" > <code style="color:#a6bbbb;"> B. SUMMARY OF THE EFFECTS (AS OF REPORTING TIME)</code></label>
                                                             

                                                                <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
                                                                <script>
                                                                        $(document).ready(function(){
                                                                              $("#selecteditem1").select2();
                                                                              $("#s_all1").click(function(){
                                                                                  if($("#s_all1").is(':checked') ){
                                                                                      $("#selecteditem1 > option").prop("selected","selected");
                                                                                      $("#selecteditem1").trigger("change");
                                                                                  }else{
                                                                                      $("#selecteditem1 > option").removeAttr("selected");
                                                                                      $("#selecteditem1").trigger("change");
                                                                                  }
                                                                              });
                                                                              $('#selecteditem1').select2({closeOnSelect: false});                                                   
                                                                        });
                                                                </script>

                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);   ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">1. Areas Affected</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;">Barangay Affected :  </span>
                                                                                    <div class="form-group">
                                                                                        <select class="select2"   id="selecteditem1" style="width:100%;  border-bottom: 1px solid rgba(0,0,0,0.1);" id="brgys" name="brgyaf2[]"  multiple >
                                                                                            <!--<option value="all"  >1. all</option>-->
                                                                                            <?php $i=1;
                                                                                                  foreach($result_brgy as $val){
                                                                                            ?>
                                                                                                     <option value="<?=$val['b_barangay']?>"  ><?=$val['b_barangay']?></option>
                                                                                            <?php $i++;} ?>

                                                                                            <?php 
                                                                                              $data =  $areaaf['brgyaf'];
                                                                                              $array =  explode(',', $data);
                                                                                              foreach ($array as $each1) {
                                                                                              ?>
                                                                                                    <script>
                                                                                                      var value = ['<?php print_r($each1);?>'];
                                                                                                      el = document.getElementById("selecteditem1");
                                                                                                        for (var j = 0; j < value.length; j++) {
                                                                                                          for (var i = 0; i < el.length; i++) {
                                                                                                            if (el[i].innerHTML == value[j]) {
                                                                                                              el[i].selected = true;
                                                                                                            }
                                                                                                        }
                                                                                                      }

                                                                                                    
                                                                                                  </script>

                                                                                            <?php } ?>

                                                                                        </select>

                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>


                                                                        </div>
                                                                    </div>

                                                                <div class="card-demo" style="width:323px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px;">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">2. Population Affected ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Families :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control" value="<?=number_format($popaf['fams'])?>"  name="fams3"  style=" border-left:none; padding:6px;   color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Persons :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control" value="<?=number_format($popaf['pers'])?>"  name="pers3" style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-demo" style="width:324px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">3. Population Displaced ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Families :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control" value="<?=number_format($popdi['fams'])?>" name="fams4"  style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Persons :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=number_format($popdi['pers'])?>"  name="pers4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Infants: 0-1 year old :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=number_format($popdi['infa'])?>"  name="infa4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Children: 2-12 years old :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=number_format($popdi['child'])?>"  name="child4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Adolescents: 13-17 year :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=number_format($popdi['adol'])?>"  name="adol4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Adults 18 years old and above :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=number_format($popdi['adul'])?>"  name="adul4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                 </div>

                                                                 <div class="card-demo" style="width:324px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">4. Casualties ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Dead :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=number_format($cas['dead'])?>"  name="dead5"  style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Injured :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=number_format($cas['injur'])?>"  name="injur5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Missing :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=number_format($cas['miss'])?>"  name="miss5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                 </div>

                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">5. Damaged Properties ( Structures )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                <div class="row">
                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;" > &nbsp;&nbsp; A. Breakdown</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Totally</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Patially</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Est. Cost</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Houses</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam['tota'])?>" name="tota6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=number_format($dam['par'])?>"  name="par6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?="₱ ".number_format($dam['est'],2)?>"  name="est6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">School Bldgs.</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=number_format($dam1['tota'])?>"  name="tota6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=number_format($dam1['par'])?>"  name="par6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?="₱ ".number_format($dam1['est'],2)?>"  name="est6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Gov't Offices</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=number_format($dam2['tota'])?>"  name="tota6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=number_format($dam2['par'])?>"  name="par6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?="₱ ".number_format($dam2['est'],2)?>"  name="est6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Public Markets</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam3['tota'])?>" name="tota6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam3['par'])?>" name="par6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam3['est'],2)?>" name="est6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Flood Control (Sea wall, dikes, dams, levees, irrigation system)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam4['tota'])?>" name="tota6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam4['par'])?>" name="par6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam4['est'])?>" name="est6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Commercial Facilities (Factories/mall, stores, supermarkets)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam5['tota'])?>" name="tota6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam5['par'])?>" name="par6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam5['est'],2)?>" name="est6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Other (Specify)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam6['tota'])?>" name="tota6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam6['par'])?>" name="par6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam6['est'],2)?>" name="est6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; B. Tourism Facilities</label>

                                                                         <br> <label for="" style=" color:#7e8889; " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. Privately owned:</label>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Resorts</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam7['tota'])?>" name="tota6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam7['par'])?>" name="par6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam7['est'],2)?>" name="est6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Hotels</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam8['tota'])?>" name="tota6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam8['par'])?>" name="par6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam8['est'],2)?>" name="est6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Restaurants</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam9['tota'])?>" name="tota6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam9['par'])?>" name="par6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam9['est'],2)?>" name="est6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <label for="" style=" color:#7e8889; " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Government owned:</label>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Tourism Assisted Center</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam10['tota'])?>" name="tota6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam10['par'])?>" name="par6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam10['est'],2)?>" name="est6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Theme Parks</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam11['tota'])?>" name="tota6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam11['par'])?>" name="par6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam11['est'],2)?>" name="est6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Visitor Centers</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam12['tota'])?>" name="tota6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($dam12['par'])?>" name="par6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($dam12['est'],2)?>" name="est6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                
                                                                               





                                                                        </div>
                                                                   
                                                                 </div></div>












                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">6. Damaged Lifelines</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                              <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 6.1 Transportation Facilities</label>
                                                                                <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >Location <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ▸ Roads:</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Passable/Not Passable</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Number</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Cost</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">National</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_1" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml['dopt']?>"><?=$daml['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml['num'])?>" name="num7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml['cost'],2)?>" name="cost7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Provincial</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_2" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml1['dopt']?>"><?=$daml1['dopt']?></option> 
                                                                                                        <option  value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml1['num'])?>" name="num7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml1['cost'],2)?>" name="cost7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Municipal</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_3" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml2['dopt']?>"><?=$daml2['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml2['num'])?>" name="num7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml2['cost'],2)?>" name="cost7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">City</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_4" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml3['dopt']?>"><?=$daml3['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml3['num'])?>" name="num7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml3['cost'],2)?>" name="cost7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Barangay</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_5" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml4['dopt']?>"><?=$daml4['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml4['num'])?>" name="num7_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml4['cost'],2)?>" name="cost7_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">▸ Bridges:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>  Bailey </label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_6" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml5['dopt']?>"><?=$daml5['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml5['num'])?>" name="num7_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml5['cost'],2)?>" name="cost7_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Concrete</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_7"  data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml6['dopt']?>"><?=$daml6['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml6['num'])?>" name="num7_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml6['cost'],2)?>" name="cost7_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Wooden</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_8" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml7['dopt']?>"><?=$daml7['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml7['num'])?>" name="num7_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml7['cost'],2)?>" name="cost7_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Suspension</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_9" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml8['dopt']?>"><?=$daml8['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml8['num'])?>" name="num7_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml8['cost'],2)?>" name="cost7_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Railways</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_10" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml9['dopt']?>"><?=$daml9['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml9['num'])?>" name="num7_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml9['cost'],2)?>" name="cost7_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 6.2 Communication Facilities</label>

                                                                                <div class="row">
                                                                                      <div class="col-sm-2 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >&nbsp;</label>
                                                                                      </div>

                                                                                      <div class="col-sm-4 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >Location</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Optional/Not Not</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Number</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Cost</label>
                                                                                      </div>
                                                                                </div>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">PLDT</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml10['dloc']?>" name="dloc7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_11" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml10['dopt']?>"><?=$daml10['dopt']?></option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml10['num'])?>" name="num7_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml10['cost'],2)?>" name="cost7_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Bayan Tel</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml11['dloc']?>" name="dloc7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_12" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml11['dopt']?>"><?=$daml11['dopt']?></option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml11['num'])?>" name="num7_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml11['cost'],2)?>" name="cost7_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Cell Sites</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml12['dloc']?>" name="dloc7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_13" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml12['dopt']?>"><?=$daml12['dopt']?></option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml12['num'])?>" name="num7_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml12['cost'],2)?>" name="cost7_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Repeaters</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml13['dloc']?>" name="dloc7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1); margin-bottom:44px;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_14" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml13['dopt']?>"><?=$daml13['dopt']?></option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=number_format($daml13['num'])?>" name="num7_14" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($daml13['cost'],2)?>" name="cost7_14" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                        </div>
                                                                   
                                                                 </div></div>


















                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">7. Agriculture</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                              <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 7.1 Crops</label>
                                                                                <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" > </label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Areas Damaged (No. of Hectares)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">(Metric Tons)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Lossess (Peso Value)</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Rice</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri['hect']?>" name="hect8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri['metr']?>" name="metr8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri['loss'],2)?>" name="loss8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Corn</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri1['hect']?>" name="hect8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri1['metr']?>" name="metr8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri1['loss'],2)?>" name="loss8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Vegetables</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri2['hect']?>" name="hect8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri2['metr']?>" name="metr8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri2['loss'],2)?>" name="loss8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Root Crops</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri3['hect']?>" name="hect8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri3['metr']?>" name="metr8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri3['loss'],2)?>" name="loss8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Fruit Trees</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri4['hect']?>" name="hect8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri4['metr']?>" name="metr8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri4['loss'],2)?>" name="loss8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Bananas</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri5['hect']?>"  name="hect8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri5['metr']?>" name="metr8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri5['loss'],2)?>" name="loss8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                               



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 7.2 Fisheries</label>

                                                                                 <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" > </label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Areas Damaged (No. of Heads)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Peso Value</label>
                                                                                      </div>
                                                                                </div>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Animals</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri6['hect']?>" name="hect8_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri6['loss'],2)?>" name="loss8_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Farm</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri7['hect']?>" name="hect8_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri7['loss'],2)?>" name="loss8_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                       
                                                                                       
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Poultry & Fowls</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri8['hect']?>" name="hect8_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?="₱ ".number_format($agri8['loss'],2)?>" name="loss8_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                       
                                                                                        </div>
                                                                                </div>









                                                                        </div>
                                                                   
                                                                 </div></div>




                                                           <label for="" style=" color:#a6bbbb;  margin-bottom:5px; margin-top:10px; margin-left:4px;" > <code style="color:#a6bbbb;"> C. LOCAL ACTIONS</code></label>



                                                            
                                                                 <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1); margin-left:3px;   margin-right:3px;  ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">Local Actions</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">1. Emergency Responders Involved :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['resp']?>" name="resp9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">2. Assets Deployed :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['asse']?>" name="asse9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>
                                                                                 
                                                                                 <label for="" style="color:#7e8889;"> &nbsp;&nbsp;&nbsp; 3. Number of affected population Served</label>
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Families :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['na_f']?>" name="na_f9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <label for="" style="color:#7e8889;"> &nbsp;&nbsp;&nbsp; 4. Number of Displaced population Served</label>
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Families :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['nd_f']?>" name="nd_f9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Persons :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['nd_p']?>" name="nd_p9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Infants :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control" value="<?=$loca['nd_i']?>" name="nd_i9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Children :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control" value="<?=$loca['nd_c']?>" name="nd_c9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adults :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control" value="<?=$loca['nd_a']?>" name="nd_a9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>


                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">5. Extent of Local Assistance :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['ext']?>" name="ext9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold; border-bottom: 1px solid rgba(0,0,0,0.1);" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>


                                                                                  
                                                                                


                                                                        </div>
                                                                    </div>
                                                                 






                                                              </div>
                                                             
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                    





                <script>
                        $(document).ready(function(){
                            window.print();
                        });
                </script>
             






                

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