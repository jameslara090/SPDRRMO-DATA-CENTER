<?php


require_once('../config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$ftopic=$_GET['ftopic'];
$mun=$_GET['mun'];
$content=$_GET['content'];
$addeddate=$_GET['addeddate'];
try{
    if ($content!=""){
        $stmt = $conn->prepare("INSERT INTO `rtopic`(`ftopic`,`mun`,`content`,`addeddate`) VALUES ('".$ftopic."','".$mun."','".$content."','".$addeddate."')");
        $stmt->execute();
        echo "success";
    }else{
        echo "nodata";
    }
}
	catch (Exception $e){ echo $e->getMessage() . "<br/>";while($e = $e->getPrevious()) {echo 'Previous Error: '.$e->getMessage() . "<br/>";}
}
	

?>