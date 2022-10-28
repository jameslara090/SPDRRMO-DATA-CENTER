 
<?php
    error_reporting(0);

    require_once('session/check_user.php');

    $tname=$_SESSION['typhoonname'];

    $dtype=$_SESSION['dtype'];
    if (!isset($_SESSION['dtype']) || ($_SESSION['dtype'] == '')) {
        header("location:user_home.php");
        exit();
    }

    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  
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
                       <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="user_home.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                       <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
                    
                       <li style="margin-bottom:1px; margin-top:15px;"> <a style="padding:0px; margin:0px;" href="forum.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-comments"></i> &nbsp;&nbsp;&nbsp;&nbsp;Forum / Suggestions</button> </a> </li>
                    </ul>
                </div>
            </aside>


            <section class="content">

                   <div class="row" style="margin-bottom:20px;">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="user_home.php">Reporting  </a>  &nbsp;⟶&nbsp;
                      <a href="user_typhoon.php"><?php echo ucfirst(strtolower($_SESSION['dtype']));?>  </a>  &nbsp;⟶&nbsp;
                      <a href="user_reportfor.php">Report to:  </a>  &nbsp;⟶&nbsp;
                      <label>Manage Attach File: <?php echo $_SESSION["typhoonname"]; ?></label>
                   </div>


                   <div class="row" style=" padding:15px;  padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:15px;">
                     
                     
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"  >
                            <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                <div class="panel-heading" role="tab" id="headingOne" >
                                    <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size:12px;"> UPLOAD FILE </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                   <form class="form-inline" method="POST" action="upload_file/upload_file.php" enctype="multipart/form-data">
                                        
                                        <div class="table-responsive">
                                            <table id="table" class="table" style="margin-top:20px;">
                                            <thead>
                                                <tr>
                                                    <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:10px; width:250px;">SELECT FILE</th>
                                                    <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:left; padding-left:3px; width:350px;">REMARKS</th>
                                                    
                                                </tr>

                                            </thead>
                                            <tbody>

                                        
                                                <tr style="border:1px solid #2b4c4a;">
                                                    <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; padding-left:8px; border-left:none; color:#a8b6b5;">  <input class="form-control" type="file" name="upload"/> </td>
                                                    <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5; "><input style="width:100%;" class="form-control" type="text" name="remarks" placeholder="Remarks"/></td>
                                                
                                                </tr>
                                        
                                            </tbody>
                                        </table>
                                        </div>
                                        
                                        <div class="col-sm-12" style=" padding-left:10px; margin-bottom:15px;"><button title="Upload Online" type="submit" name="submit" id="add"  > &nbsp; UPLOAD &nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:10px; padding-right:10px; color:white;" title="Cancel Upload" href="typhoon_attachfile.php"> CANCEL</a>  </div>
                                        
                                     

                                        </form>

                                    </div>
                                </div>
                            </div>

                    </div>
                   
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
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">&nbsp;</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">File Name</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">File Size</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:left; padding-left:8px;">Date Added</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">Time Added</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">Remarks</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:left; padding-left:8px;">Status</th>
                                    </tr>

                                </thead>
                                <tbody>
                               
                                <?php
                                    require 'config/typhoon_attachfile_con.php';
                                    $row = $conn->query("SELECT * FROM `typhoon_attachfile` WHERE dtype='".$dtype."' and typhoon_name='".$tname."' and mun='".$u_mun."' ORDER BY tid DESC") or die(mysqli_error());
                                    while($fetch = $row->fetch_array()){
                
                                    $dates=explode(" ",$fetch['addeddate']);
                                ?>
                                    <tr style="border:1px solid #2b4c4a;">
                                    <?php 
                                        $name = explode('/', $fetch['filepath']);

                                        $fname = explode('_-', $fetch['filename']);
                                    ?> 
                                        <td style="padding:0px;width:37px;"><a title="Delete File" href="upload_file/delete_typhoon.php?file=<?php echo $fetch['filepath']?>&id=<?php echo $fetch['tid']?>&name=<?php echo $name[2]?>" class="btn btn-danger"><i class="zmdi zmdi-delete"></i></a></td>
                                        <td style="padding:0px;width:33px;"><a title="Download File" href="upload_file/download.php?file=<?php echo $name[2]?>" class="btn btn-primary"><i class="zmdi zmdi-download"></i></a></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px;"><?php echo $fname[2]?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; color:#a8b6b5; padding-left:8px;"><?php echo $fetch['filesize']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?php echo $dates[0]?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?php echo $dates[1]." ".$dates[2]?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:lect; padding-left:8px; color:#a8b6b5;"><?php echo $fetch['remarks']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:lect; padding-left:8px; color:#a8b6b5;"><?php if($fetch['notif'] > "0"){ echo"File/Downloaded"; }else{ echo "Not Seen"; } ?></td>
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