<?php

session_start();

require_once('session/check_user.php');
require_once('config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare("UPDATE users_log SET stat = 'Offline' WHERE user_id='".$u_user_id."'   ");   
$stmt->execute();

unset($_SESSION['u_user_id']);
unset($_SESSION['u_email']);
unset($_SESSION['u_utype']);
unset($_SESSION['u_mun']);

unset($_SESSION["typhoonname"]);

header("location:login.php");
//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';



exit;

?>