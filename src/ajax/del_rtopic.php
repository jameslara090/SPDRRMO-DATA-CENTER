<?php


require_once('../config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$iid1=$_GET['iid'];

try{
	$stmt = $conn->prepare("DELETE FROM rtopic WHERE iid='".$iid1."' ");
	$stmt->execute();

	$stmt = $conn->prepare("DELETE FROM rtopic_like WHERE rtopic='".$iid1."' ");
	$stmt->execute();
	echo "success"; 
} catch (Exception $e){  }

        

?>