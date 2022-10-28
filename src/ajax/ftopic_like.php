<?php


require_once('../config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$ftopic_like=$_GET['ftopic_like'];
$mun=$_GET['mun'];

        
        $stmt = $conn->prepare("SELECT COUNT(mun) as ftopic_like FROM ftopic_like WHERE ftopic='".$ftopic_like."' and mun='".$mun."' ");
        $stmt->execute();
        $ftopic_like_count = $stmt->fetch(PDO::FETCH_ASSOC);
try{
        if($ftopic_like_count['ftopic_like'] > 0){
                $stmt = $conn->prepare("DELETE FROM ftopic_like WHERE ftopic='".$ftopic_like."' and mun='".$mun."' ");
                $stmt->execute();
        }else{
                $stmt = $conn->prepare("INSERT INTO `ftopic_like`(`ftopic`,`mun`) VALUES ('".$ftopic_like."','".$mun."')");
                $stmt->execute();
                echo "success"; 
        }
}catch (Exception $e){  }
	

?>