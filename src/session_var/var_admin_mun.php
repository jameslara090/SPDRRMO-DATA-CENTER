<?php

session_start();
$_SESSION["admin_mun"]=$_GET['mun'];

//echo $_SESSION["typhoonname"];

echo "<script>window.location='../view_report.php'</script>";

?>