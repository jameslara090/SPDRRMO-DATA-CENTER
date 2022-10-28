<?php

//require_once('../session/check_admin.php');
//in add kulang above is the default
   session_start();
   $u_user_id = $_SESSION['user_id'];
   $u_email = $_SESSION['email'];
   $u_utype = $_SESSION['utype'];
   $u_mun = $_SESSION['mun'];
   //end
   
require_once('../config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$file=$_GET['file'];
$name=$_GET['name'];
 
    $files = glob($file); // get all file names
    foreach($files as $file){ // iterate files
       
    if(is_file($file))
        unlink($file); // delete file

       
   }


    //echo dirname(__DIR__, 1).$file;

   
        $stmt = $conn->prepare("DELETE FROM attach_files_dam WHERE tid='".$_GET['id']."' ");
        $stmt->execute();
  
        echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Deleted '); window.location='../index1.php'</script>";
   

//}else{
   // echo "<script>alert('  ✉ MESSAGE \\n  🚫 Erro: File not Found ".$filename." '); window.location='../cgs_attachfile.php'</script>";
//}


?>