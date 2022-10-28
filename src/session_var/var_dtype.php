<?php

session_start();
$_SESSION["dtype"]=$_GET['dtype'];

//echo $_SESSION["typhoonname"];


    echo "<script>window.location='../user_typhoon.php'</script>";

?>