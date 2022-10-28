
<?php
    error_reporting(0);

    require_once('session/check_admin.php');
    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $conn->prepare("SELECT * FROM category order by cid DESC");
    $stmt->execute(); 
    $resulttf = $stmt->fetchAll();

    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];

    $dr = "_";
    if($date1 == "" && $date2 == ""){
        $dr = "_";
    }else{
        $dr = $date1." / ".$date2;
    }
    //echo $date1." / ".$date2;

    //echo $dr;

$ct="";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['del'])){
            try{
                $stmt = $conn->prepare("DELETE FROM category WHERE cid='".$_GET['id']."' ");
                $stmt->execute();
                echo "<script>alert('✔ Successfully deleted'); window.location='situation.php'</script>";
                }
                catch (Exception $e){ echo $e->getMessage() . "<br/>";while($e = $e->getPrevious()) {echo 'Previous Error: '.$e->getMessage() . "<br/>";}
                }
        }
        if(isset($_POST['edit'])){
            $stmt = $conn->prepare("SELECT * FROM category  WHERE cid='".$_GET['id']."' ");
            $stmt->execute();
            $res_ct= $stmt->fetch(PDO::FETCH_ASSOC);
            
            $ct=explode(" / ",$res_ct['daterange']);


           // echo $ct;
           //open close colapse
            $update=$_GET['id'];  $updatestat="";  if($update>0){  $updatestat="-in";  }else{  $updatestat="";  }
            //end

        }
        if(isset($_POST['update']))
        {
        try{
                $dater=$_POST['date1_edit']." / ".$_POST['date2_edit'];
                $stmt=$conn->prepare("UPDATE category SET daterange='".$dater."' WHERE cid='".$_GET['id']."' ");
                $stmt->execute();
                echo "<script>window.location='situation.php'</script>";
                }
                catch (Exception $e){
                echo $e->getMessage() . "<br/>";
                while($e = $e->getPrevious()) {
                echo 'Previous Error: '.$e->getMessage() . "<br/>";
                }
                }
        }
        if(isset($_POST['add'])){
            try{
                $stmt = $conn->prepare("INSERT INTO `category`(`muni`, `ttype`, `category`, `daterange`, `remarks`, `stat`) VALUES ('All', :ttype, :category, '".$dr."', '".$_POST['remarks']."', '0')");
                $stmt->bindParam(':ttype', $_POST['ttype']);
                $stmt->bindParam(':category', $_POST['category']);
                $stmt->execute();
                echo "<script>alert('✔ Successfully Added'); window.location='situation.php'</script>";
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
                        <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="add_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;<i class="zmdi zmdi-accounts-add"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add User</button> </a> </li>
                        <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="situation.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Situation Report</button> </a> </li>
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
                               <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size:12px;"> ADD SITUATION </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                              <form action="situation.php" method="post">
                                
                                <div class="table-responsive">
                                    <table id="table" class="table">
                                    <thead>
                                        <tr>
                                            <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:10px; width:180px;">TYPE ⟶ FROM</th>
                                            <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:left; padding-left:8px; width:150px;">DETAIL ⟶ TO</th>
                                            
                                        </tr>

                                    </thead>
                                    <tbody>


                                   
                                        <tr style="border:1px solid #2b4c4a;">
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; padding-left:8px; border-left:none; color:#a8b6b5;"><select     title="Category"          name="ttype" id="" style="width:100%; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0); border:none; color:#fff;">    <option value="TYPHOON">TYPHOON</option> <!-- <option value="EARTHQUAKE">EARTHQUAKE</option>  <option value="VOLCANIC">VOLCANIC</option> <option value="LANDSLIDE">LANDSLIDE</option> <option value="TSUNAMI">TSUNAMI</option> <option value="FLOODING">FLOODING</option> -->  </select></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="category"  title="Typhoon Name"  style="width:230px; height:100%; margin:0px; text-align:left; background-color:rgba(0,0,0,0); border:none; color:#a8b6b5;" placeholder="" ></td>
                                          
                                        </tr>

                                        <tr style="border:1px solid #2b4c4a;">
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; padding-left:8px; border-left:none; color:#a8b6b5;"><input type="date"  title="From"  name="date1"  style="width:230px; height:100%; margin:0px; text-align:left; background-color:rgba(0,0,0,0); border:none; color:#a8b6b5;" ></td>
                                            <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="date"   name="date2"  title="To"  style="width:230px; height:100%; margin:0px; text-align:left; background-color:rgba(0,0,0,0); border:none; color:#a8b6b5;" ></td>
                                          
                                        </tr>

                                        <tr style="border:1px solid #2b4c4a;" >
                                            <td rowspan="1" colspan="2"  style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; padding-left:8px; border-left:none; color:#a8b6b5;"><input type="text" title="Remarks"  name="remarks"  style="width:100%; height:100%; margin:0px; text-align:left; background-color:rgba(0,0,0,0); border:none; color:#a8b6b5;" ></td>
                                           
                                          
                                        </tr>
                                   
                                    </tbody>
                                </table>
                                </div>
                                <div class="row" style="margin-bottom:15px; margin-top:20px;">
                                   <div class="col-sm-12"><button title="Save to Database" type="submit" name="add" id="add"  > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SAVE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Add" href="situation.php"> CANCEL</a>  </div>
                                   
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
                                         <form action="situation.php?id=<?=$_GET['id']?>" method="post">
                                           
                                           <div class="table-responsive" style="margin-bottom:8px;">

                                           <input type="text"  title="From"  name="date1_edit" value="<?=$ct[0]?>"  style="width:130px; height:100%; margin:0px; text-align:left; background-color:rgba(0,0,0,0);  border:1px groove white; color:#a8b6b5; padding-left:5px;" >
                                            <input type="text"   name="date2_edit"  title="To" value="<?=$ct[1]?>" style="width:130px; height:100%; margin:0px; text-align:left; background-color:rgba(0,0,0,0); border:1px groove white; color:#a8b6b5; padding-left:5px;" >
                                            <button title="Save Changes" type="submit" name="update" >  SAVE CHANGES </button>  
                                            <a style="background-color:orange; padding:4px; padding-left:10px; padding-right:10px; color:white;" title="Cancel Edit" href="situation.php"> CANCEL</a>

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
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center; width:10px;">&nbsp;</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:4px; vertical-align : middle;text-align:left; width:125px;">TYPE</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:left; padding-left:8px; width:150px;">DETAIL</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;  width:250px;">DATE OCCURENCE</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;  width:250px;">REMARKS</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;  ">DATE ADDED</th>
                                    </tr>

                                </thead>
                                <tbody>

                                <?php
                                
                                 $num="1";
                                 foreach ($resulttf as $valuetf) 
                                 {
                                     $dis="disabled";
                                     $col="grey";
                                     if($valuetf['ttype']=="TYPHOON"){
                                         $dis="";
                                         $col="white";
                                     }else{
                                         $dis="disabled";
                                         $col="#4a4949";
                                     }
                                
                                ?>
                                    <tr style="border:1px solid #2b4c4a;">
                                       <form action="situation.php?id=<?=$valuetf['cid']?>" method="post">
                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                                <button title="Delete <?=$valuetf['ttype']." ".$valuetf['category']?>" id="hov" name="del" add="del" type="submit" style="padding-left:7px;padding-right:7px; border-radius:50px; background-color:rgba(0,0,0,0); color:white; border:none;" ><i class="zmdi zmdi-delete" ></i></button>
                                            </td>
                                        </form>
                                        <form action="situation.php?id=<?=$valuetf['cid']?>" method="post">
                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                                <button title="Edit <?=$valuetf['ttype']." ".$valuetf['category']?>" id="hov" name="edit" add="edit" type="submit" style="padding-left:7px;padding-right:7px; border-radius:50px; background-color:rgba(0,0,0,0); color:white; border:none;" <?=$dis?> ><i style="color:<?=$col?>;" class="zmdi zmdi-edit" ></i></button>
                                            </td>
                                        </form>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; border-left:none; color:#a8b6b5;"><?=$valuetf['ttype']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?=$valuetf['category']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px; "><?=$valuetf['daterange']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px; " title="<?=$valuetf['remarks']?>"><?=$valuetf['remarks']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px; "><?=$valuetf['cDate']?></td>
                                         
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