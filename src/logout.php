<?php
// Initialize the session
//session_start();
 
// Unset all of the session variables
//$_SESSION = array();
 
// Destroy the session.
//session_destroy();
 
// Redirect to login page
//header("location: login.php");

//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

session_start();


require_once('session/check_admin.php');
require_once('config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare("UPDATE users_log SET stat = 'Offline' WHERE user_id='".$_SESSION['user_id']."'   ");   
$stmt->execute();


unset($_SESSION['user_id']);
unset($_SESSION['email']);
unset($_SESSION['utype']);
unset($_SESSION['mun']);

unset($_SESSION["admin_typhoonname"]);
unset($_SESSION["admin_mun"]);

header("location:login.php");
//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';


exit;
?>