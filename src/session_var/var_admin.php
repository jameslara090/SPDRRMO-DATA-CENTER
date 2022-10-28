<?php

session_start();
$_SESSION["admin_typhoonname"]=$_GET['name'];
$_SESSION["admin_dtype"]=$_GET['dtype'];
if ($_SESSION["admin_mun"]=="cgs sorsogon"){
    echo "<script>window.location='../index1_cgs.php'</script>";
}else{

//echo $_SESSION["typhoonname"];

echo "<script>window.location='../index1.php'</script>";
}

?>