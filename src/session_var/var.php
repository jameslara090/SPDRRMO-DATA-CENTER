<?php

session_start();
$_SESSION["typhoonname"]=$_GET['name'];

//echo $_SESSION["typhoonname"];


if ($_SESSION['u_mun']=="cgs sorsogon"){
    echo "<script>window.location='../cgs_report.php'</script>";
}else{
    echo "<script>window.location='../user_reportfor.php'</script>";
}
?>