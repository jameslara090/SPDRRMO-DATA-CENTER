


<header class="header">
    <div class="navigation-trigger hidden-xl-up" data-sa-action="aside-open" data-sa-target=".sidebar">
        <i class="zmdi zmdi-menu"></i>
    </div>

    <style>
    #pare.parent {
        position: relative;
        height: 200px;
        border: 3px solid red;
    }
    #pare.chil {
        position: relative;
        width: 50%;
        bottom: 5px;
        border: 3px solid #8AC007;
    } 
    </style>

    <div class="logo hidden-sm-down">
        <!--<h1 id="pare"><a  href="index2.php">DISASTER REPORT ( LDRRMO / SPDRRMC )</a></h1><span id="chil">ewt</span>-->

        <style type='text/css'>
            #header {
            position: relative;
            /* min-height: 150px;*/
            display: flex;
            }
            #header-content {
            position: absolute;
            bottom: 0;
            right: 0;

            }
            #header, #header * {
            /* background: rgba(40, 40, 100, 0.25);*/
            }




            #hov{
                border-bottom: 1px solid #453886;
            background-position: 0 100%;
            background-repeat: repeat-x;
            background-size: 3px 3px;
            color: #000;
            text-decoration: none;
            }
            #hov:hover {
                border-bottom: none;
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg id='squiggle-link' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:ev='http://www.w3.org/2001/xml-events' viewBox='0 0 20 4'%3E%3Cstyle type='text/css'%3E.squiggle{animation:shift .3s linear infinite;}@keyframes shift {from {transform:translateX(0);}to {transform:translateX(-20px);}}%3C/style%3E%3Cpath fill='none' stroke='%23453886' stroke-width='2' class='squiggle' d='M0,3.5 c 5,0,5,-3,10,-3 s 5,3,10,3 c 5,0,5,-3,10,-3 s 5,3,10,3'/%3E%3C/svg%3E");
            background-position: 0 100%;
            background-size: auto 6px;
            background-repeat: repeat-x;
            text-decoration: none;
            align-items: center;
            }
        </style>

        <div id="header">
        <a  href="index2.php" id="hov" ><h5 style=" margin-bottom:13px; font-weight:bold;">DISASTER REPORT &nbsp;&nbsp;&nbsp;&nbsp;</h5>
              <div id="header-content" style="font-size:9px; font-style:italic; color:white; margin-bottom:3px; ">↪&nbsp; LDRRMO / SPDRRMC &nbsp;↩</div></a>
        </div>

        <!--DISASTER REPORT ( LDRRMO / SPDRRMC )-->

    </div>
    

    

    <form class="search">
        <!--  <div class="search__inner">
            <input type="text" class="search__text" placeholder="Search for people, files, documents...">
            <i class="zmdi zmdi-search search__helper" data-sa-action="search-close"></i>
        </div>-->
    </form>
    <div class="clock hidden-md-down" >
        <div class="time">
            <span class="time__hours"></span>
            <span class="time__min"></span>
            <span class="time__sec"></span>
        </div>
    </div>

    <ul class="top-nav">
        <!--<li class="hidden-xl-up"><a href="#" data-sa-action="search-open"><i class="zmdi zmdi-search"></i></a></li>-->





        <?php
                
                require_once('config/main_tiles_con.php');
                $conn5 = new mysqli($servername, $username, $password, $dbname);
                if ($conn5->connect_error) {
                    die("Connection failed: " . $conn5->connect_error);
                } 

                require_once('config/dbcon.php');
                $conn6 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn6->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



                            

                $tot=0;
                $tot_cgs=0;



                $sql_mun = "SELECT * FROM users WHERE usertype='user1' order by user_email ASC ";
                $result_mun = $conn5->query($sql_mun);
                if ($result_mun->num_rows > 0) {
                    while($row_mun = $result_mun->fetch_assoc()) {
                        $exp_mun = explode("-", $row_mun['name']);
                        $lowercase_name_mun = strtolower($exp_mun[1]);

                        

                            $stmt = $conn6->prepare("SELECT COUNT(DISTINCT(tdatetime)) AS notifcount FROM typhoon_form WHERE notif=0 and mun='".$lowercase_name_mun."' ");
                            $stmt->execute(); 
                            $notifcount = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn6->prepare("SELECT COUNT(DISTINCT(addeddate)) AS notifcount2 FROM typhoon_attachfile WHERE notif=0 and mun='".$lowercase_name_mun."' ");
                            $stmt->execute(); 
                            $notifcount2 = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn6->prepare("SELECT COUNT(DISTINCT(d_re)) AS notifcount3 FROM 1pod WHERE notif=0 and mun='".$lowercase_name_mun."' ");
                            $stmt->execute(); 
                            $notifcount3 = $stmt->fetch(PDO::FETCH_ASSOC);

                            $tot+=$notifcount['notifcount']+$notifcount2['notifcount2']+$notifcount3['notifcount3'];
                
                    }
                }

                require_once('config/main_tiles_con.php');
                $conn5 = new mysqli($servername, $username, $password, $dbname);
                if ($conn5->connect_error) {
                    die("Connection failed: " . $conn5->connect_error);
                } 

                $sql = "SELECT * FROM users WHERE usertype='user1' and name='-CGS SORSOGON' order by user_email ASC ";
                $result = $conn5->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $exp = explode("-", $row['name']);
                        $lowercase_name = "cgs sorsogon";

                            $stmt = $conn6->prepare("SELECT COUNT(DISTINCT(asof)) AS notifcount FROM cgs_record WHERE notif=0 ");
                            $stmt->execute(); 
                            $notifcount_cgs = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $conn6->prepare("SELECT COUNT(DISTINCT(addeddate)) AS notifcount2 FROM cgs_attachfile WHERE notif=0  ");
                            $stmt->execute(); 
                            $notifcount2_cgs = $stmt->fetch(PDO::FETCH_ASSOC);

                            $tot_cgs+=$notifcount_cgs['notifcount']+$notifcount2_cgs['notifcount2'];
                    
                    }
                }


                //echo " = ".$notifcount['notifcount']." = ".$notifcount2['notifcount2']." = ".$notifcount_cgs['notifcount']." = ".$notifcount2_cgs['notifcount2'];

                        


                $numUsers = $tot + $tot_cgs;
                $cont= $numUsers;
                
                $noti="none";
                if ($cont>0){
                    $noti="inline-block";
                }else{
                    $noti="none";
                }



                ?>




        
        <li class="dropdown top-nav__notifications" id="notifyme" style="display:<?=$noti?>;">
            <a href="index2.php" class="top-nav__notify abtn1" style=" padding:3px; margin:0px; text-align:center;">
            <i class="zmdi zmdi-notifications contacts__btn1" style=" text-align:center;  margin-top:2px; width:100%; height:30px; "><span class="badge1" style=" margin:0px; font-size:11px; border: 1px solid maroon; border-radius: 50%; padding-left: 6px;  padding-right: 6px;  padding-top: 0px;  padding-bottom: 0px; background-color: red; " id="notifyme_val"> <?=$cont?></span> </i>
        </a>


        <li class="dropdown top-nav__notifications">
            <a href="logout.php" class="top-nav__notify">
            <i class="zmdi zmdi-power"></i>
        </a>

        
        
    </li>
    </ul>



</header>