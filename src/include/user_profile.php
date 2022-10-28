<div class="scrollbar-inner">
    <div class="user">
    <div class="user__info" data-toggle="dropdown">
        <!-- <img class="user__img" src="img/favicon.png" alt="">
        <div>-->
        <?php
            $email1= $_SESSION['u_email'];

            if ($_SESSION['u_mun']=="cgs sorsogon"){
                require_once('config/main_tiles_con.php');
            }else{
                require_once('config/main_tiles_con.php');
            }
            
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            $sql = "SELECT * FROM users WHERE user_email='".$email1."'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                    <!--<img class="user__img"--> <?php //echo '<img  src="data:image/jpeg;base64,'.base64_encode($row['user_image']).'"  width="116px" height="116px" />';
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