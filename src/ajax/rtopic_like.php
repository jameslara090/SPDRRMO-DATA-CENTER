<?php


require_once('../config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$topic_like=explode('/',$_GET['rtopic_like']);
$ftopic = $topic_like[0];
$rtopic = $topic_like[1];
$mun=$_GET['mun'];
try{
        
        $stmt = $conn->prepare("SELECT COUNT(mun) as rtopic_like FROM rtopic_like WHERE ftopic='".$ftopic."' and rtopic='".$rtopic."' and mun='".$mun."' ");
        $stmt->execute();
        $rtopic_like_count = $stmt->fetch(PDO::FETCH_ASSOC);

        if($rtopic_like_count['rtopic_like'] > 0){
                $stmt = $conn->prepare("DELETE FROM rtopic_like WHERE ftopic='".$ftopic."' and rtopic='".$rtopic."' and  mun='".$mun."' ");
                $stmt->execute();
        }else{
                $stmt = $conn->prepare("INSERT INTO `rtopic_like`(`ftopic`,`rtopic`,`mun`) VALUES ('".$ftopic."','".$rtopic."','".$mun."')");
                $stmt->execute();
                echo "success"; 
        }
}catch (Exception $e){ }
	

?>