<?php

error_reporting(0);
//session_start();
//if (!isset($_SESSION['USER_ID']) || ($_SESSION['USER_ID'] == '')) {
//    header("location:login.php");
//    exit();
//}


require_once('session/check_user.php');

?>

<?php
    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT COUNT(stat) AS catcount FROM category WHERE stat=0 ");
    $stmt->execute(); 
    $resultcatcount = $stmt->fetch(PDO::FETCH_ASSOC);

    //echo $resultcatcount['catcount'];


    $stmt = $conn->prepare("SELECT * FROM category ");
    $stmt->execute();
    $category = $stmt->fetchAll();/**/


    //echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

   if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['jump'])){
        $ttype = $_POST['ttype'];
        $loca1 = $_POST['loca1'];
        $loca2 = $_POST['loca2'];

       // if($loca1 == "" || $ttype == ""){ echo "<script>window.location='user_typhoon.php'</script>"; }else{
            $_SESSION['dtype'] = $ttype;
            $_SESSION['typhoonname'] = $loca1;
            if($loca2 == "FORM"){
                if($u_mun=="barcelona"){
                    echo "<script>window.location='user_entry.php'</script>";
                }elseif($u_mun=="bulan"){
                    echo "<script>window.location='user_entry_bulan.php'</script>";
                }elseif($u_mun=="bulusan"){
                    echo "<script>window.location='user_entry_bulusan.php'</script>";
                }elseif($u_mun=="casiguran"){
                    echo "<script>window.location='user_entry_casiguran.php'</script>";
                }elseif($u_mun=="castilla"){
                    echo "<script>window.location='user_entry_castilla.php'</script>";
                }elseif($u_mun=="donsol"){
                    echo "<script>window.location='user_entry_donsol.php'</script>";
                }elseif($u_mun=="gubat"){
                    echo "<script>window.location='user_entry_gubat.php'</script>";
                }elseif($u_mun=="irosin"){
                    echo "<script>window.location='user_entry_irosin.php'</script>";
                }elseif($u_mun=="juban"){
                    echo "<script>window.location='user_entry_juban.php'</script>";
                }elseif($u_mun=="magallanes"){
                    echo "<script>window.location='user_entry_magallanes.php'</script>";
                }elseif($u_mun=="matnog"){
                    echo "<script>window.location='user_entry_matnog.php'</script>";
                }elseif($u_mun=="pilar"){
                    echo "<script>window.location='user_entry_pilar.php'</script>";
                }elseif($u_mun=="prieto diaz"){
                    echo "<script>window.location='user_entry_prietodiaz.php'</script>";
                }elseif($u_mun=="sta. magdalena"){
                    echo "<script>window.location='user_entry_stamagdalena.php'</script>";
                }elseif($u_mun=="sorsogon"){
                    echo "<script>window.location='user_entry_sorsogoncity.php'</script>";
                }
            }elseif($loca2 == "ATTACH FILE"){
                echo "<script>window.location='typhoon_attachfile.php'</script>";
            }elseif($loca2 == "DAMAGE ASSESSMENT REPORT"){
                echo "<script>window.location='damage_assess.php'</script>";
            }
       // }

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
        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">


        <?php include('include/head_tab.php')?>
        <?php include('logs.php')?>




        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <script type="text/javascript">

            var data1="";
            var f = "";
            function request(){
                var req = $.ajax({
                        type:"post",
                        url:"user_home_ajax.php",
                        data:{data:"Hello World"}
                    });

                    req.done(function(data){
                        console.log("Request successful! ==== "+data);
                        if (data=="good working"){ data1="" ; }else{
                        data1 = "4";  
                        }

                    });

            };
            request();
            var autoloadaa=setInterval(function(num){
                    if(data1=="4"){
                        request();  
                    }else{     
                    }  
            },1000);
                
              
        </script> -->



        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <script>
           /* var autoloadaa=setInterval(function(num){

                <?php
                    $user_ids=$u_user_id;
                ?>

                var today = new Date();
                var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                var dateTime = date+' '+time;
                /*console.log(dateTime);*/
                /*var user_id = "<?php echo $user_ids; ?>";
                var user_dura = "";
                $.ajax({
                    method: "POST",
                    url: "logs.php",
                    data: { "user_id": user_id, "user_in": dateTime, "user_out": dateTime, "user_dura": user_dura},
                })
                /*.done(function( msg ) {
                    alert( "Response: " + msg );
                });*/

            /*},1000); ;*/
        </script>

        
    </head>

    <!--<style>
    .minimiy{
        display:none;
    }
    .minimiy1{
        width:100%;
        padding-left:30px;
    }

    .minimin{
        display:block;
    }
    .minimin1{
        padding-left:30px;
    }
    </style>-->

      <!--<body data-sa-theme="3">-->

        <style>
         /*   .effects{ 
           background: radial-gradient(circle, #193937, #10312F, #092524, #071F1F, #061B1A, #041211, #041413, #071f1f);

          background-image: url(data:image/png;base64,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);
           
        background-color:#1D1E20;
             } 
  

             body {background:#000000;background-image: url(data:image/png;base64,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);}
   
   background-color:#1A1D21;background-image: url(data:image/png;base64,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
  */    
  </style>
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


                       <!--<li style="margin-bottom:1px; margin-top:25px; text-align:center"> <a href="javascript:void(0)" id="minim"><i style="padding-left:5px;padding-right:5px;padding-top:6px;padding-bottom:6px; border-radius:50%;" class="zmdi zmdi-chevron-left btn-light"></i></a> </li>
                    
                    <script>
                        $( document ).ready(function() {
                            $( "#minim" ).click(function() {
                        // $( this ).slideLeft();
                            //alert("click");

                                $( ".sidebar" ).addClass( " minimiy" );
                                $( ".content" ).addClass( " minimiy1" );

                            });
                        });
                    </script>-->
                    </ul>
                </div>
            </aside>
            

           
</div>

            <section class="content">
                <div class="content__inner">
                <h5>REPORTING</h5>

              <!--  <label for=""><?=$_COOKIE['user']?></label> --> 

             




               <!---     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                <link rel="stylesheet" href="/resources/demos/style.css">
                <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                <script>
                $( function() {

                    $("#dialog").hide();
                    setTimeout(function(){
                        $("#dialog").show();
                    }, 4000);
                    
                  

                   
                } );
                </script>

                <div id="dialog" title="Basic dialog">
                
                <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
                </div>





            <div id="div1">
1
                </div>
                <div id="div2">
2
                </div>
                <div id="div3">
3
                </div>


                <script>
                var Index = 1;

                window.setInterval(ChangeVisibleDiv, 2000);

                function ChangeVisibleDiv()
                {
                    var nextIndex = Index + 1;
                    var prevIndex = Index - 1;
                    //reset on 4, not 3 because then the 3rd div would never be faded out.
                    if(Index == 4)
                        nextIndex = 1;
                    // opposite story here as above
                    if(Index == 1)
                        prevIndex = 3;

                    $(("#div" + prevIndex)).css('opacity',0);
                    $(("#div" + nextIndex)).css('opacity',0);
                    $(("#div" + Index)).css('opacity',1);

                    Index++;

                    //reset on 4, not 3 because then the 3rd div would never be faded out.
                    if(Index == 4)
                        Index = 1;

                }
                </script>-->






              

                <header class="content__title">       
                </header>
                  
                    <div class="contacts row" >


                    <style>
                            .mbt:hover{
                                border-bottom:2px solid grey !important;
                            }
                            .cont {
                                position: relative;
                            }

                            /*.topright {
                            position: absolute;
                            top: 0px;
                            right: 32px;
                            font-size: 10px;
                            color:white;
                        
                            padding-left:5px;
                            padding-right:5px;

                            border-radius: 0px 0px 0px 8px;
                            }*/
                            .topright {
                                position: absolute;
                                top: 0px;
                                right: 0px;
                                font-size: 10px;
                                color:white;
                            
                                padding-left:5px;
                                padding-right:5px;

                                border-radius: 0px 0px 0px 8px;
                            }
                            .topright:hover {
                                text-decoration:underline;
                                color:#f3f0f0;
                                background-color:green !important;/**/
                                border-left:2px solid #5C5C5C;
                                border-right:2px solid #5C5C5C;
                                border-bottom:2px solid #5C5C5C;

                            }
                            .imag:hover{
                                -webkit-border-radius: 50%;
                                -moz-border-radius: 50%;
                                border-radius: 50%;
                                -webkit-box-shadow: 0px 0px 5px 0px #808080;
                                -moz-box-shadow:    0px 0px 5px 0px #808080;
                                box-shadow:         0px 0px 5px 0px #808080;
                            }

                        </style>


                    <?php
                        require_once('config/main_tiles_con.php');
                         $conn = new mysqli($servername, $username, $password, $dbname);
                         if ($conn->connect_error) {
                             die("Connection failed: " . $conn->connect_error);
                         } 
                         $sql = "SELECT * FROM type_category";
                         $result = $conn->query($sql);
                         if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                         ?>
                         
                            <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
                               <div class="contacts__item cont">
                                 <a class="contacts__img">
                                    <!-- <img src="img/category/typhoon.png" alt=""> -->
                                    <img class="imag" src="img/category/<?=$row['ttype']?>1.png"  style="width:116px; height:116px;" >
                                 </a>
                               <div class="contacts__info">
                                    <strong style="font-size:11px; text-shadow:0 1px 0 black;"><?php echo $row['ttype'];?></strong>
                               </div>
                                  <style>
                                      .contacts__btn{  background-color:rgba(0,0,0,0.2); 
                                            position: relative;
                                            display: inline-block;
                                            border-radius:10px;
                                            padding:6px;
                                       }
                                      .contacts__btn:hover{  background-color:rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.4);  }
                                      .contacts__btn .badge {
                                            position: absolute;
                                            top: -5px;
                                            right: -5px;
                                            padding: 0px 0px;
                                            border-radius: 50%;
                                            background-color: red;
                                            color: white;
                                       }
                                  </style>
                                  <?php if($resultcatcount['catcount']==0){ ?>
                                  <button class="contacts__btn"><a style="color:white; font-size: 10px; font-weight: bold;" href="session_var/var_dtype.php?dtype=<?=$row['ttype']?>">View Record</a></button>
                                  <?php }else{?>
                                    <button class="contacts__btn"><a style="color:white; font-size: 10px; font-weight: bold;" href="session_var/var_dtype.php?dtype=<?=$row['ttype']?>">View Record <!--&nbsp;<span class="badge" style=" border: 1px solid maroon; border-radius: 50%; padding-left: 3px;  padding-right: 3px;  padding-top: 2px;  padding-bottom: 1px; background-color: red;"><?=$resultcatcount['catcount']?></span>--> </a></button>
                                  

                                 
                                  <?php } ?>

                            <a href="javascript:void(0);" class="topright" data-toggle="modal" data-target="#modal-default<?=$row['tid']?>" data-cid="<?=$row['cid']?>"  data-tname="<?=$row['category']?>" style="background-color:#5C5C5C; text-shadow:0 1px 0 black;" title="Jump/Skip to">⤤⤤</a>
                                   <!--  <a href="#" class="topright1" data-toggle="modal" data-target="#modal-edit" data-cid="<?=$row['cid']?>" data-tname="<?=$row['category']?>" data-daterangefrom="<?=$row['daterange']?>" data-remarksedit="<?=$row['remarks']?>" style="background-color:#98C510;">&nbsp;EDIT</a>
-->






                        <div class="modal fade" id="modal-default<?=$row['tid']?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="user_home.php" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title pull-left">JUMP TO - <?=$row['ttype']?> </h5>
                                        </div>
                                          <div class="modal-body" style="">

                                            <input type="hidden" name="ttype" value="<?=$row['ttype']?>">

                                            <div class="input-group" style="">
                                              <select name="loca1" style="width:100%; padding:4px; background-color:rgba(0,0,0,0); color:#777878; font-weight:bold; border:1px solid #3A3A3A; margin-bottom:13px;">
                                                <?php
                                                    foreach($category as $cate){
                                                        if($cate['ttype'] == $row['ttype'] ){
                                                            if($cate['muni'] == $_SESSION['u_mun'] && $row['ttype'] == "ACTIVITY REPORT"){ ?>
                                                              <option value="<?=$cate['category']?>"> <?=$cate['category']?> </option>     <?php 
                                                            }elseif($cate['muni'] == "All" || $cate['muni'] == $_SESSION['u_mun']){        ?>
                                                              <option value="<?=$cate['category']?>"> <?=$cate['category']?> </option>     <?php   
                                                            }
                                                        }
                                                    }
                                                ?>
                                              </select>
                                            </div>
                                            <div class="input-group" style="">
                                              <select name="loca2" style="width:100%; padding:4px; background-color:rgba(0,0,0,0); color:#777878; font-weight:bold; border:1px solid #3A3A3A;">
                                                <?php 
                                                    if($row['ttype'] == "ACTIVITY REPORT"){  ?>
                                                      <option value="ATTACH FILE">ATTACH FILE</option>  <?php 
                                                    }else{ ?>
                                                      <option value="FORM"><?=$row['ttype']?> FORM</option>
                                                      <option value="ATTACH FILE">ATTACH FILE</option>
                                                      <option value="DAMAGE ASSESSMENT REPORT">DAMAGE ASSESSMENT REPORT</option>  <?php 
                                                    }
                                                ?>
                                              </select>
                                            </div>      
                                          </div>
                                        <div class="modal-footer"> 
                                            <button type="button" class="btn btn-link mbt" data-dismiss="modal" style="background-color:#161818">Cancel</button>
                                            <button type="submit" class="btn btn-link mbt" name="jump" id="jump" style="background-color:green;">Jump</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                     








                            </div>
                            



                        </div>
                        
                        <?php 
                         }
                         } else {
                           
                         }
                            $conn->close();
                         ?>

                        
                        
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
        <script src="vendors/bower_components/select2/dist/js/select2.full.min.js"></script><!---->
        <script src="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <!-- <script src="vendors/bower_components/autosize/dist/autosize.min.js"></script>

        App functions and actions -->
        <script src="js/app.min.js"></script>
    </body>


</html>