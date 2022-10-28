<?php


require_once('../config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$iid1=$_GET['iid'];

$stmt = $conn->prepare("SELECT * FROM ftopic WHERE iid='".$iid1."' ");
$stmt->execute();
$result_h = $stmt->fetch(PDO::FETCH_ASSOC);

try{
	$file=$result_h['filepath'];
	$files = glob($file);
	$name = explode('/', $result_h['filepath']);
	$fol="/img_ftopic/";
	$ec = str_replace('\\','/',dirname(__FILE__, 1)).$fol.$name[2];

	
	$f =str_replace('/ajax','',$ec);

	///echo "  ===================  ".str_replace('\ajax','',$f);

	
	
	unlink($f);

	$stmt = $conn->prepare("DELETE FROM ftopic WHERE iid='".$iid1."' ");
	$stmt->execute();
	echo "success"; 

} catch (Exception $e){  }

        

?>