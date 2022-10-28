<?php
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



    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id='".$u_user_id."' ");
    $stmt->execute();
    $curr_user = $stmt->fetch(PDO::FETCH_ASSOC);
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

        <!-- Vendor styles -->
        <link rel="stylesheet" href="vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="vendors/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="vendors/bower_components/lightgallery/dist/css/lightgallery.min.css">


        <script type="text/javascript" src="toastr/jquery-1.6.4.min.js"></script>
        <!--FOR TOAST MESSAGE-->
        <link href="toastr/toastr.css" rel="stylesheet"/>
        <script type="text/javascript" src="toastr/toastr.js"></script>
        <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>-->
        <!--END TOAST-->


        <?php include('include/head_tab.php')?>
        <?php include('logs.php')?>


        




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
      
    <body data-sa-theme="3">
        <main class="main">
            <?php include('include/user_header.php');?>
            <aside class="sidebar">
                <div class="scrollbar-inner">
                    <?php include('include/user_profile.php')?>
                </div>
                    <ul class="navigation">
                       <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="user_home.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>   
                       <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>

                       <li style="margin-bottom:1px; border-left:3px solid green; margin-top:15px;"> <a style="padding:0px; margin:0px;" href="forum.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-comments"></i> &nbsp;&nbsp;&nbsp;&nbsp;Forum / Suggestions</button> </a> </li>
                    </ul>
                </div>
            </aside>

           
</div>

            <section class="content">

           


            <?php

           //-- echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

           require_once('config/dbcon.php');
           $conn9 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
           $conn9->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $stmt = $conn9->prepare("SELECT * FROM type_category ");
            $stmt->execute();
            $res = $stmt->fetchAll();

            $stmt = $conn9->prepare("SELECT DISTINCT * FROM category ");
            $stmt->execute();
             $res1 = $stmt->fetchAll();
            foreach ($res as $val){
              //--  echo "  >> ".$val['ttype']." <<  ";
                    foreach($res1 as $val1){
                        if ($val1['ttype'] == $val['ttype']){
                          //--  echo $val1['ttype'].$val1['cid'];
                        }
                    }
            }


            ?>

            <div class="row" >
            <div class="col-12 col-lg-12" >


          <!-- <?php // ini_set('session.gc_maxlifetime',172800); 
            //$times = ini_get("session.gc_maxlifetime");
           ?>

            <label for="" style="background-color:green;"><?=$times?></label>-->

            <a href="" class="btn btn-sm btn-info mt-1" style="margin-bottom:10px;" data-toggle="modal" data-target="#modal-large"> Add New Topic</a>

            <style>
                #outer {
                    resize: both;
                    overflow: auto;
                }
            </style>

							<div class="card" style=" padding:2px;">
								<div class="card-body h-100" id="outer" style=" padding:4px; padding-bottom:30px;">
									<!--<div class="media">
										<img src="img/avatars/avatar-5.jpg" width="56" height="56" class="rounded-circle mr-3" alt="Ashley Briggs">
										<div class="media-body">
											<small class="float-right text-navy">5m ago</small>
											<p class="mb-2"><strong>Ashley Briggs</strong></p>
											<p>Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit
												vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus.</p>

											<div class="row no-gutters mt-1 lightbox photos">
												<a href="img/photos/download.jpg" style="padding:0px; margin:0px;">
                                                  <div class="col-12 lightbox__item photos__item" style="padding:0px; margin:0px;">
													<img src="img/photos/download.jpg" class="img-fluid pr-1" alt="Image name">
												  </div>
                                                </a>
												<div class="col-6">
													<img src="img/photos/unsplash-3.jpg" class="img-fluid pl-1" alt="Unsplash">
												</div>
											</div>

											<small class="text-muted">Today 7:51 pm</small><br />
											<a href="#" class="btn btn-sm btn-danger mt-1"><i class="feather-sm" data-feather="heart"></i> Like</a>

											<div class="media mt-3">
												<a class="pr-2" href="#">
                <img src="img/avatars/avatar-4.jpg" width="36" height="36" class="rounded-circle mr-2" alt="Stacie Hall">
              </a>
												<div class="media-body">
													<p class="text-muted" style="margin:0px; margin-bottom:8px;">
														<strong>Stacie Hall</strong>: Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed aliquam ultrices
														mauris. Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris.
													</p>
                                                    <span style="background-color:#28a745; padding-left:5px; padding-right:5px; border-radius:5px;">
                                                      <a href="javascript:void(0)" class="mt-1" style="color:white;"><i class="zmdi zmdi-thumb-up"></i> &nbsp;<span style="color:white;">3</span></a>
                                                    </span>
                                                   
                                                   
                                                    <div class="input-group" style="margin-top: 15px;">
                                                        <div class="form-group" style=" padding:0px;margin:0px; margin-right:20px;">
                                                        
                                                        <textarea class="form-control textarea-autosize"  name="remarksedit" id="remarksedit"  placeholder="Comment" style=" padding:0px; padding-top:8px; padding-bottom:4px; " rows="1" title="Comment"></textarea>
                                                        <i class="form-group__bar" style="padding:0px; margin:0px;"></i> 
                                                        </div>
                                                            <style>
                                                                .hove:hover{
                                                                    color:white;
                                                                }
                                                            </style>
                                                            <div class="row" style="background-color:#28A745; float:right; margin-right:1px; padding:5px; border-radius:6px; text-align:center;">
                                                              <a href="" style="display: flex; flex-direction: column; justify-content: center; text-align: center; color:white;"> Submit </a>
                                                            </div>
                                                    </div>
                                                    
                                                        
                                                        <a href="">
                                                           <div class="icon-toggle" style="background-color:green;">
                                                                <input type="checkbox">
                                                                <i class="zmdi zmdi-thumb-up"></i>
                                                            </div>
                                                        </a>
                                                       
												</div>
											</div>

										</div>
									</div>

									<hr />-->
                                    <style>
                                        .hove:hover{
                                            color:white;
                                        }

                                        img {
                                          /*  -webkit-box-shadow: -2px 2px 4px -1px rgba(0,0,0,0.75);
                                            -moz-box-shadow: -2px 2px 4px -1px rgba(0,0,0,0.75);
                                             box-shadow: -2px 2px 4px -1px rgba(0,0,0,0.75);*/

                                                -webkit-box-shadow: -1px 0px 6px 1px rgba(0,0,0,0.44);
                                                -moz-box-shadow: -1px 0px 6px 1px rgba(0,0,0,0.44);
                                                box-shadow: -1px 0px 6px 1px rgba(0,0,0,0.44);
                                        }
                                        .rtopic_like{
                                               -webkit-box-shadow: -1px 1px 2px 0px rgba(0,0,0,0.44);
                                                -moz-box-shadow: -1px 1px 2px 0px rgba(0,0,0,0.44);
                                                box-shadow: -1px 1px 2px 0px rgba(0,0,0,0.44);
                                        }
                                        #myBtn {
                                            display: none;
                                            position: fixed;
                                            bottom: 10px;
                                            right: 10px;
                                            z-index: 99;
                                            font-size: 18px;
                                            border: none;
                                            outline: none;
                                            background-color: red;
                                            color: white;
                                            cursor: pointer;
                                            padding-right:8px;
                                            padding-left:8px;
                                            padding-top:3px;
                                            padding-bottom:3px;
                                            border-radius: 15px;
                                            border:4px solid rgba(0,0,0,0.2);
                                        }

                                        #myBtn:hover {
                                            background-color: #555;
                                        }
                                        
                                        
                                        #myBtnr {
                                            display: none;
                                            position: fixed;
                                            bottom: 10px;
                                            right: 60px;
                                            z-index: 99;
                                            font-size: 18px;
                                            border: none;
                                            outline: none;
                                            background-color: red;
                                            color: white;
                                            cursor: pointer;
                                            padding-right:8px;
                                            padding-left:8px;
                                            padding-top:3px;
                                            padding-bottom:3px;
                                            border-radius: 15px;
                                            border:4px solid rgba(0,0,0,0.2);
                                        }

                                        #myBtna:hover {
                                            background-color: #555;
                                        }
                                        
                                        #myBtna {
                                            display: none;
                                            position: fixed;
                                            bottom: 10px;
                                            right: 110px;
                                            z-index: 99;
                                            font-size: 18px;
                                            border: none;
                                            outline: none;
                                            background-color: green;
                                            color: white;
                                            cursor: pointer;
                                            padding-right:8px;
                                            padding-left:8px;
                                            padding-top:3px;
                                            padding-bottom:3px;
                                            border-radius: 15px;
                                            border:4px solid rgba(0,0,0,0.2);
                                        }

                                        #myBtnr:hover {
                                            background-color: #555;
                                        }
                                    </style>
                                    
                                      <button onclick="topFunction2()" id="myBtna" title="Add New Topic">&nbsp;+&nbsp;</button>
                                      <button onclick="topFunction1()" id="myBtnr" title="Reload this Page">↻</button>
                                      
                                      <button onclick="topFunction()" id="myBtn" title="Go to top"> ⇧ </button>
                                      

                                    <?php

                                    require_once('config/dbcon.php');
                                    $connf = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                    $connf->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                                    $stmt = $connf->prepare("SELECT * FROM ftopic order by iid DESC");
                                    $stmt->execute();
                                    $resf = $stmt->fetchAll();

                                    $stmt = $connf->prepare("SELECT DISTINCT * FROM rtopic ");
                                    $stmt->execute();
                                    $resf1 = $stmt->fetchAll();

                                    

                                    //foreach ($res as $val){
                                    //  echo "  >> ".$val['ttype']." <<  ";
                                    

                                           // foreach($res1 as $val1){
                                            //    if ($val1['ttype'] == $val['ttype']){
                                                //  echo $val1['ttype'].$val1['cid'];
                                            //    }
                                           // }

                                        
                                   // }

                                foreach ($resf as $val){
                                ?>
                                    <div class="media">
										<img src="img/logo/<?=$val['mun']?>.png" width="46" height="46" class="rounded-circle mr-3" alt="<?=$val['mun']?>">
										<div class="media-body">
											<!--<small class="float-right text-navy">5m ago</small>-->
                                            <div class="del_ftopic">
                                <small class="float-right text-navy"><?=$val['addeddate']?> <?php if($val['mun']==strtoupper($curr_user['name'])){?>&nbsp;&nbsp;<a id="<?=$val['iid']?>">❌</a><?php } ?></small>
                                            </div>
											<p class="mb-2" style="pointer-events:none"><strong><?=$val['mun']?></strong></p>
											<p style="pointer-events:none"><?=$val['content']?></p>

                                            <?php if($val['filename']!=""){ ?>

											<div class="row no-gutters mt-1 lightbox photos">
												<a id="image_focus" href="img_ftopic/<?=$val['filename']?>" style="padding:0px; margin:0px;">
                                                  <div class="col-12 lightbox__item photos__item" style="padding:0px; margin:0px;">
													<img src="img_ftopic/<?=$val['filename']?>" class="img-fluid pr-1" alt="<?=$val['filename']?>">
												  </div>
                                                </a>
											</div>
                                            <?php } 
                                            
                                            $stmt = $connf->prepare("SELECT * FROM users WHERE user_id='".$_SESSION['u_user_id']."' ");
                                            $stmt->execute(); 
                                            $res_u1 = $stmt->fetch(PDO::FETCH_ASSOC);

                                            $stmt = $connf->prepare("SELECT COUNT(mun) as ftopic_like FROM ftopic_like WHERE ftopic='".$val['iid']."' and mun='".$res_u1['name']."' ");
                                            $stmt->execute();
                                            $ftopic_like = $stmt->fetch(PDO::FETCH_ASSOC);

                                            $stmt = $connf->prepare("SELECT COUNT(mun) as ftopic_like FROM ftopic_like WHERE ftopic='".$val['iid']."'   ");
                                            $stmt->execute();
                                            $ftopic_like_all = $stmt->fetch(PDO::FETCH_ASSOC);

                                            $ftopic_like_all_count ="";
                                            if($ftopic_like['ftopic_like'] > 0){
                                                $ftopic_like_all_count = $ftopic_like_all['ftopic_like'] - 1;
                                            }else{
                                                $ftopic_like_all_count = $ftopic_like_all['ftopic_like'];
                                            }

                                            $ftopic_like_count_like = "";
                                            if($ftopic_like['ftopic_like'] > 0 && $ftopic_like_all_count > 0){
                                                $ftopic_like_count_like = "👍🏻 "."You and ".$ftopic_like_all_count." others";
                                            }elseif($ftopic_like['ftopic_like'] > 0 && $ftopic_like_all_count == 0){
                                                $ftopic_like_count_like = "👍🏻 ".strtoupper($res_u1['name']);
                                            }elseif($ftopic_like['ftopic_like'] == 0 && $ftopic_like_all_count == 0){
                                                $ftopic_like_count_like = " 👍🏻 ";
                                            }else{
                                                $ftopic_like_count_like = "👍🏻 ".$ftopic_like_all_count;
                                            }

                                          
                                            ?>

											<!--<small class="text-muted">Today 7:51 pm</small><br />-->
                                            <div class="ftopic_like" style="">
											  <a id="<?=$val['iid']?>" class="btn btn-sm btn-danger mt-1" style="margin-bottom:8px; margin-left:1px;" title="Like"><span><?=$ftopic_like_count_like?></span></a>
                                            </div>
                                           <?php 
                                           $stmt = $connf->prepare("SELECT DISTINCT COUNT(iid) as CNT,ftopic FROM rtopic WHERE ftopic='".$val['iid']."' ");
                                           $stmt->execute();
                                           $resf2 = $stmt->fetch(PDO::FETCH_ASSOC);


                                           if ($resf2['CNT']>'0'){
                                           ?>
                                           
                                           <style>
                                               .inner{
                                                  float: left; display: inline; width:100%; word-break: break-all; word-wrap: break-word;
                                                }
                                           </style>

                                           <div class="inner" style="background-color:rgba(0,0,0,0.2); padding:10px; padding-top:1px; padding-bottom:0px; ">
                                           <?php 
                                           foreach($resf1 as $val1){
                                               if($val1['ftopic']==$val['iid']){
                                           ?>
											<div class="media mt-3">
												<a class="pr-2" href="javascript:void(0)">
                                                   <img src="img/logo/<?=$val1['mun']?>.png" width="36" height="36" class="rounded-circle mr-2" alt="<?=$val1['mun']?>">
                                                </a>
												<div class="media-body">
                                                  <?php 
                                                 
                                                   if($val1['mun']==strtoupper($curr_user['name'])){?>
                                                    <div class="del_rtopic">
                                                      <small class="float-right text-navy"> &nbsp;&nbsp;<a id="<?=$val1['iid']?>">❌</a></small>
                                                    </div>
                                                  <?php } ?>
													<p class="text-muted" style="margin:0px; margin-bottom:8px; padding:0px; >
														<strong style="padding:0px; color:#667D94; font-size:12px; vertical-align: bottom;"><?=$val1['mun']?></strong><br><span style="color:#667D94; font-size:10px; margin:0px; padding:0px; vertical-align: top; height:13px;display:inline-block; overflow: hidden;"><?=$val1['addeddate']?></span> <br><span id="cont" style="margin:0px;padding:0px; vertical-align: top;"><?=$val1['content']?></span>
													</p>



                                                    <?php
                                                        $stmt = $connf->prepare("SELECT COUNT(mun) as rtopic_like FROM rtopic_like WHERE ftopic='".$val['iid']."' and rtopic='".$val1['iid']."' and mun='".$res_u1['name']."' ");
                                                        $stmt->execute();
                                                        $rtopic_like = $stmt->fetch(PDO::FETCH_ASSOC);
            
                                                        $stmt = $connf->prepare("SELECT COUNT(mun) as rtopic_like FROM rtopic_like WHERE ftopic='".$val['iid']."'  and rtopic='".$val1['iid']."'  ");
                                                        $stmt->execute();
                                                        $rtopic_like_all = $stmt->fetch(PDO::FETCH_ASSOC);


                                                        $rtopic_like_all_count ="";
                                                        if($rtopic_like['rtopic_like'] > 0){
                                                            $rtopic_like_all_count = $rtopic_like_all['rtopic_like'] - 1;
                                                        }else{
                                                            $rtopic_like_all_count = $rtopic_like_all['rtopic_like'];
                                                        }

                                                        $rtopic_like_count_like = "";
                                                        if($rtopic_like['rtopic_like'] > 0 && $rtopic_like_all_count > 0){
                                                            $rtopic_like_count_like = "👍🏻 "."You and ".$rtopic_like_all_count." others";
                                                        }elseif($rtopic_like['rtopic_like'] > 0 && $rtopic_like_all_count == 0){
                                                            $rtopic_like_count_like = "👍🏻 ".strtoupper($res_u1['name']);
                                                        }elseif($rtopic_like['rtopic_like'] == 0 && $rtopic_like_all_count == 0){
                                                            $rtopic_like_count_like = " 👍🏻 ";
                                                        }else{
                                                            $rtopic_like_count_like = "👍🏻 ".$rtopic_like_all_count;
                                                        }


                                                    ?>
                                                    <span class="rtopic_like" style=" padding-left:5px; padding-right:5px; border-radius:5px; background-color:#0c2f2d;">
                                                      <a id="<?=$val1['ftopic']?>/<?=$val1['iid']?>" class="mt-1" style="color:white; background-color:#0f3434; border-radius:5px; font-size:11px;" title="Like"><?=$rtopic_like_count_like?></a>
                                                    </span>
                                                   
                                                   
                                                    <div style=" border-bottom:1px solid rgba(0,0,0,0.2); margin-top:3px;"></div>
                                                       
												</div>
											</div>
                                            <?php } ?>
                                           <?php } ?>
                                               </div> <label style="font-size:1px; margin:0px; padding:0px;">&nbsp;</label>
                                            <?php } ?> 
                                                    
                                                    <div class="input-group" style="margin-top: 5px; margin-left:0px; background-color:rgba(0,0,0,0.2); border-radius:5px; padding-left:5px;">
                                                        <div class="form-group" style=" padding:0px;margin:0px; margin-right:20px;">
                                                        
                                                        <textarea class="form-control textarea-autosize"  name="remarksedit" id="input<?=$val['iid']?>"  placeholder="Comment" style=" padding:0px; padding-top:8px; padding-bottom:4px;" rows="1" title="Comment"></textarea>
                                                        <i class="form-group__bar" style="padding:0px; margin:0px;"></i>
                                                        </div>
                                                           
                                                            <div class="row" style="background-color:rgba(0,0,0,0.3); outline: 2px solid rgba(0,0,0,0.2);  outline-offset: -4px; float:right; margin-right:1px; padding:5px; border-radius:6px; text-align:center;">
                                                              <a id="<?=$val['iid']?>" style="display: flex; flex-direction: column; justify-content: center; text-align: center; color:white;"> &nbsp;&nbsp;Submit&nbsp;&nbsp; </a>
                                                            </div>
                                                    </div>

										</div>
									</div>
									<hr style="margin-top:5px;">
                                <?php } ?>

                                <?php
                                require_once('config/dbcon.php');
                                $connuser = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $connuser->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $connuser->prepare("SELECT * FROM users WHERE user_id='".$_SESSION['u_user_id']."' ");
                                $stmt->execute(); 
                                $res_u = $stmt->fetch(PDO::FETCH_ASSOC);

                                //echo strtoupper($res_u['name']);
                                $user = strtoupper($res_u['name']);

                                date_default_timezone_set('Asia/Manila');
			                    $dates = date('n/j/Y g:iA');

                                ?>

                                <script>
                                   function toasterOptions() {
                                        toastr.options = {
                                            "closeButton": false,
                                            "debug": false,
                                            "newestOnTop": false,
                                            "progressBar": true,
                                            "preventDuplicates": true,
                                            "onclick": null,
                                            "showDuration": "100",
                                            "hideDuration": "1000",
                                            "timeOut": "3000",
                                            "extendedTimeOut": "1000",
                                            "showEasing": "swing",
                                            "hideEasing": "linear",
                                            "showMethod": "show",
                                            "hideMethod": "hide"
                                        }
                                    };


                                    
                                    // function loadDoc() {
                                    // var xhttp = new XMLHttpRequest();
                                    // xhttp.onreadystatechange = function() {
                                    //     if (this.readyState == 4 && this.status == 200) {
                                    //     //document.getElementById("demo").innerHTML = this.responseText;

                                    //     if(this.responseText=="success"){
                                    //             toasterOptions();
                                    //             toastr.success('SUCCESS MESSAGE!');
                                    //     }else{
                                    //             toasterOptions();
                                    //             toastr.warning('DANGER MESSAGE!');
                                    //     }



                                    //     }
                                    // };
                                    // xhttp.open("POST", "save.php?var1=bar ipsum&var2="+$('#post_text').val(), true);
                                    // xhttp.send();
                                    // }

 
                                    $('div.input-group a').click(function() {
                                        var ftopic = $(this).attr('id');
                                        var mun = "<?php echo $user ?>";
                                        var content = $('#input'+ftopic).val();
                                        var addeddate = "<?php echo $dates ?>";
                                       // alert(ftopic+" | "+mun+ " | " +content+ " | " +addeddate);
                                        //alert("mun "+mun);



                                        var xhttp = new XMLHttpRequest();
                                        xhttp.onreadystatechange = function() {
                                            if (this.readyState == 4 && this.status == 200) {
                                            //document.getElementById("demo").innerHTML = this.responseText;

                                            if(this.responseText=="success"){
                                                    toasterOptions();
                                                    toastr.success('SUCCESS SAVED COMMENT!');

                                                    setTimeout(function(){
                                                        localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                        location.reload();   
                                                    }, 1000);
                                            }else if(this.responseText=="nodata"){
                                                    toasterOptions();
                                                    toastr.info('INPUT REQUIRED');                                        
                                            }else{
                                                    toasterOptions();
                                                    toastr.warning('ERROR YOUR COMMENT NOT SAVED TRY AGAIN!');
                                            }



                                            }
                                        };
                                        xhttp.open("POST", "ajax/rtopic.php?ftopic="+ftopic+"&mun="+mun+"&content="+content+"&addeddate="+addeddate+"&utype=user", true);
                                        xhttp.send();
                                    });



                                    $('div.ftopic_like a').click(function() {
                                         var ftopic_like = $(this).attr('id');
                                         var mun = "<?php echo $user ?>";
                                         var xhttp = new XMLHttpRequest();
                                         xhttp.onreadystatechange = function() {
                                             if (this.readyState == 4 && this.status == 200) {
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();              
                                             }else{
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();   
                                             } 
                                         };
                                         xhttp.open("POST", "ajax/ftopic_like.php?ftopic_like="+ftopic_like+"&mun="+mun+"&utype=user", true);
                                         xhttp.send();
                                    });


                                    $('span.rtopic_like a').click(function() {
                                         var rtopic_like = $(this).attr('id');
                                         var mun = "<?php echo $user ?>";
                                         var xhttp = new XMLHttpRequest();
                                         xhttp.onreadystatechange = function() {
                                             if (this.readyState == 4 && this.status == 200) {
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();              
                                             }else{
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();   
                                             } 
                                         };
                                         xhttp.open("POST", "ajax/rtopic_like.php?rtopic_like="+rtopic_like+"&mun="+mun+"&utype=user", true);
                                         xhttp.send();
                                    });

                                    $('div.del_ftopic a').click(function() {
                                        var del_ftopic1 = $(this).attr('id');
                                        var xhttp = new XMLHttpRequest();
                                         xhttp.onreadystatechange = function() {
                                             if (this.readyState == 4 && this.status == 200) {
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();              
                                             }else{
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();   
                                             } 
                                         };
                                         xhttp.open("POST", "ajax/del_ftopic.php?iid="+del_ftopic1+"&utype=user", true);
                                         xhttp.send();
                                    });
                                    $('div.del_rtopic a').click(function() {
                                        var del_rtopic = $(this).attr('id');
                                        //alert(del_rtopic);
                                        var xhttp = new XMLHttpRequest();
                                         xhttp.onreadystatechange = function() {
                                             if (this.readyState == 4 && this.status == 200) {
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();              
                                             }else{
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();   
                                             } 
                                         };
                                         xhttp.open("POST", "ajax/del_rtopic.php?iid="+del_rtopic+"&utype=user", true);
                                         xhttp.send();
                                    });



                                   
                                  
                                    //localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                    //location.reload();
                                    //toasterOptions();
                                    //toastr.success('SUCCESS MESSAGE!');
                                </script>


                                <!--<a  id="loadpos">eryer</a>-->

                               <!-- <?php echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>'; ?>-->
									<!--<div class="media">
										<img src="img/avatars/avatar.jpg" width="56" height="56" class="rounded-circle mr-3" alt="Chris Wood">
										<div class="media-body">
											<small class="float-right text-navy">30m ago</small>
											<p class="mb-2"><strong>Chris Wood</strong></p>
											<p>
												Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus
												pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
											</p>
											<small class="text-muted">Today 7:21 pm</small><br />
											<a href="#" class="btn btn-sm btn-danger mt-1"><i class="feather-sm" data-feather="heart"></i> Like</a>
										</div>
									</div>

									<hr />
									<div class="media">
										<img src="img/avatars/avatar-2.jpg" width="56" height="56" class="rounded-circle mr-3" alt="Carl Jenkinsh">
										<div class="media-body">
											<small class="float-right text-navy">3h ago</small>
											<p class="mb-2"><strong>Carl Jenkinsh</strong></p>

											<img src="img/photos/unsplash-1.jpg" class="img-fluid" alt="Unsplash">

											<small class="text-muted">Today 5:12 pm</small><br />
											<a href="#" class="btn btn-sm btn-danger mt-1"><i class="feather-sm" data-feather="heart"></i> Like</a>

											<div class="media mt-3">
												<a class="pr-2" href="#">
                <img src="img/avatars/avatar-4.jpg" width="36" height="36" class="rounded-circle mr-2" alt="Stacie Hall">
              </a>
												<div class="media-body">
													<p class="text-muted">
														<strong>Stacie Hall</strong>: Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed aliquam ultrices
														mauris. Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris.
													</p>
												</div>
											</div>
										</div>
									</div>

									<hr />
									<div class="media">
										<img src="img/avatars/avatar-5.jpg" width="56" height="56" class="rounded-circle mr-3" alt="Ashley Briggs">
										<div class="media-body">
											<small class="float-right text-navy">4h ago</small>
											<p class="mb-2"><strong>Ashley Briggs</strong></p>
											<p>
												Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus
												pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
											</p>
											<small class="text-muted">Today 4:21 pm</small><br />
											<a href="#" class="btn btn-sm btn-danger mt-1"><i class="feather-sm" data-feather="heart"></i> Like</a>
										</div>
									</div>-->
								</div>
							</div>
						</div>
            </div>




                        <div class="modal fade" id="modal-large" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!--<form class="form-inline" method="POST" action="upload_file/upload_file.php" enctype="multipart/form-data">-->
                                    <form method="POST" action="upload_file/upload_ftopic.php?utype=user" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title pull-left">ADD NEW TOPIC</h5>
                                    </div>
                                        <div class="modal-body" style="">
                                                <div class="input-group" style="margin-top: 14px;">
                                                    <div class="form-group" style=" padding:0px;margin:0px;">
                                                    <textarea class="form-control textarea-autosize"  name="details" id="details" placeholder="Details" style=" padding:8px; border:1px solid #242525;" rows="2"></textarea>
                                                    <i class="form-group__bar" style="padding:0px; margin:0px;"></i>
                                                    </div>
                                                </div>
                                                <div class="input-group" style="margin-top: 14px;">Image only</div>
                                                <div class="input-group" style="">
                                                    <div class="form-group" style=" padding:0px;margin:0px;">
                                                      <input class="form-control" id="file" type="file" name="upload" accept="image/png,image/jpeg,image/jpg" />
                                                    </div>
                                                </div>
                                               
                                        </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-link" name="submit" id="submit" disabled>Add Topic</button>
                                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                                    </div>
                                    </form>

                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            $('#details').on('input', function() {


                                           
                                                var tag=document.getElementById("submit");
                                                if(document.getElementById("details").value==="") {
                                                    tag.style.background="transparent";
                                                    document.getElementById('submit').disabled = true;
                                                } else { 
                                                    tag.style.background="green";
                                                    document.getElementById('submit').disabled = false;
                                                }
                                            });
                                        });
                                    </script>

                                </div>
                            </div>
                        </div>


                        <script type="text/javascript">
                            window.scrollTo(localStorage.scrollPos || 0, 0);

                            $('#loadpos').click(function(){

                            // setTimeout(function(){
                                //Saves the current scroll position before reloading the page.
                                        // localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                        // location.reload();
                            // }, 3000);

                             

                            });
                        </script>


                        <script>
                            var mybutton = document.getElementById("myBtn");
                            var mybutton1 = document.getElementById("myBtnr");
                            var mybutton2 = document.getElementById("myBtna");
                            window.onscroll = function() {scrollFunction()};
                            function scrollFunction() {
                            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                                mybutton.style.display = "block";
                                mybutton1.style.display = "block";
                                mybutton2.style.display = "block";
                            } else {
                                mybutton.style.display = "none";
                                mybutton1.style.display = "none";
                                mybutton2.style.display = "none";
                            }
                            }
                            function topFunction() {
                                document.body.scrollTop = 0;
                                document.documentElement.scrollTop = 0;
                            }
                            
                            function topFunction1() {
                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                location.reload(); 
                            }
                            
                            function topFunction2() {
                               
                            $("#modal-large").modal();
  
                            }
                            
                            
                          
                        </script>
                        
                        
                        <script>
                            $(document).ready(function(){
                                    var timeoutID;
                                   
  

                                    function setup() {
                                        this.addEventListener("mousemove", resetTimer, false);
                                        this.addEventListener("mousedown", resetTimer, false);
                                        this.addEventListener("keypress", resetTimer, false);
                                        this.addEventListener("DOMMouseScroll", resetTimer, false);
                                        this.addEventListener("mousewheel", resetTimer, false);
                                        this.addEventListener("touchmove", resetTimer, false);
                                        this.addEventListener("MSPointerMove", resetTimer, false);
                                        startTimer();
                                    }
                                    setup();
                                    function startTimer() {
                                        timeoutID = window.setTimeout(goInactive, 30000);
                                    }
                                    function resetTimer(e) {
                                        window.clearTimeout(timeoutID);
                                        goActive();
                                    }
                                    function goInactive() {                                      
                                            var file = "";
                                            var textarea = "";
                                            var focusin_file = $('#modal-large').is(':visible');
                                            var textarea_focus = $("textarea").is(":focus");
                                            var urlimg = window.location.href;
                                            var showimg = urlimg.includes("#lg=1&slide=0");
                                           
                                            $('textarea').each(
                                                function(index){  
                                                    var input = $(this);
                                                    if(input.val() != ""){
                                                    textarea = "1";
                                                    }
                                                }
                                            );
                                            file = $("#file").val();

                                            if (file == "" && textarea == "" && focusin_file == false && textarea_focus == false && showimg == false){
                                                localStorage.setItem('scrollPos', window.pageYOffset || document.documentElement.scrollTop);
                                                location.reload();  
                                            }
                                    }
                                    function goActive() {
                                        startTimer();
                                    }
                            });
                            
                            /*$("p").click(function(){
                              alert("The paragraph was clicked.");
                            });*/
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
        <script src="vendors/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <!-- Vendors -->
        <script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="vendors/bower_components/popper.js/dist/umd/popper.min.js"></script>
        <script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <!-- Light Gallery -->
        <script src="vendors/bower_components/lightgallery/dist/js/lightgallery-all.min.js"></script>
       
       <!--textarea autosize-->
        <script src="vendors/bower_components/autosize/dist/autosize.min.js"></script>

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
    </body>


</html>