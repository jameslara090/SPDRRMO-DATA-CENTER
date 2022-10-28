



<header class="header">
    <div class="navigation-trigger hidden-xl-up" data-sa-action="aside-open" data-sa-target=".sidebar">
        <i class="zmdi zmdi-menu"></i>
    </div>
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


    <div class="logo hidden-sm-down">
        <?php
        if ($_SESSION['u_mun'] == "cgs sorsogon"){?>
            
           

        <div id="header">
        <a  href="cgs_home.php" id="hov" ><h5 style=" margin-bottom:13px; font-weight:bold;">DISASTER REPORT &nbsp;&nbsp;&nbsp;&nbsp;</h5>
              <div id="header-content" style="font-size:9px; font-style:italic; color:white; margin-bottom:3px; ">↪&nbsp; LDRRMO / SPDRRMC &nbsp;↩</div></a>
        </div>

          
        <?php } else { ?>
            
            <div id="header">
        <a  href="user_home.php" id="hov" ><h5 style=" margin-bottom:13px; font-weight:bold;">DISASTER REPORT &nbsp;&nbsp;&nbsp;&nbsp;</h5>
              <div id="header-content" style="font-size:9px; font-style:italic; color:white; margin-bottom:3px; ">↪&nbsp; LDRRMO / SPDRRMC &nbsp;↩</div></a>
        </div>

        <?php }
        ?>
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

        <li class="dropdown top-nav__notifications">

        <?php
            if ($_SESSION['u_mun'] == "cgs sorsogon"){?>
                <a href="logout_user.php" class="top-nav__notify">
                    <i class="zmdi zmdi-power"></i>
                </a>
               
            <?php } else { ?>

                <a href="logout_user.php" class="top-nav__notify">
                    <i class="zmdi zmdi-power"></i>
                </a>
               
           <?php }
        ?>
       
        
    </li>
    </ul>

</header>