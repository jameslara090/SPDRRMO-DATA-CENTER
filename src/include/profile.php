<div class="scrollbar-inner">
    <div class="user">
    <div class="user__info" data-toggle="dropdown">
        <!-- <img class="user__img" src="img/favicon.png" alt="">
        <div>-->
        <?php
            $email1= $_SESSION['email'];
            require_once('config/main_tiles_con.php');
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            $sql = "SELECT * FROM users WHERE user_email='".$email1."'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                   <!-- <img class="user__img" --> <?php // echo '<img  src="data:image/jpeg;base64,'.base64_encode($row['user_image']).'"  width="116px" height="116px" />';
                        ?>
                        <img class="user__img" src="img/logo/<?=$row['name']?>.png"  style="width:39px; height:39px;" >
                <div>
                <div class="user__name"><?php echo $row['name'];?></div>
                <div class="user__email"><?php echo $row['user_email'];?></div>
            <?php
            }
            } else {
            }
            $conn->close();
        ?>

            
        </div>
    </div>

        <!--<div class="dropdown-menu">
            <a class="dropdown-item" href="#">View Profile</a>
            <a class="dropdown-item" href="#">Settings</a>
            <a class="dropdown-item" href="#">Logout</a>
        </div>-->
        <!--<li class="navigation__active h5"><a href="#"><i><img class="user__img" src="img/Home_100px.png" alt=""></i>Home</a></li>
                    <li class="navigation__active h5"><a href="admin_createreport.php"><i><img class="user__img" src="img/Hospital_100px.png" alt=""></i>Add User</a></li>
                    <li class="navigation__active h5"><a  href="admin_createreport.php"><i><img style="background-color:green;" class="user__img" src="img/Versions_100px.png" alt="" ></i>Situation Report</a></li>-->
