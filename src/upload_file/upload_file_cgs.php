<?php
   // require_once 'config\typhoon_attachfile_con.php';
   
   require_once '../config/typhoon_attachfile_con.php';
   
   //require_once('../session/check_user.php');
   //in add kulang above is the default
   session_start();
   $u_user_id = $_SESSION['u_user_id'];
   $u_email = $_SESSION['u_email'];
   $u_utype = $_SESSION['u_utype'];
   $u_mun = $_SESSION['u_mun'];
   //end
   
	if(ISSET($_POST['submit'])){
		if($_FILES['upload']['name'] != "") {
			$file = $_FILES['upload'];
			
			$file_name = $file['name'];
			
			$file_name = str_replace('_-','',$file['name']);
			
			$file_temp = $file['tmp_name'];
			//$name = explode('.', $file_name);

            $tname=$_SESSION["typhoonname"];
			$lowercase_tname = strtolower($tname);
			$removespace_tname = str_replace(' ', '', $lowercase_tname);
			$removespace_umun = str_replace(' ', '', $_SESSION["u_mun"]);
			
			$letters = array_merge(range('a','z'),range('A','Z'));
			$ran=$letters[rand(5,51)].$letters[rand(0,51)].$letters[rand(10,51)].$letters[rand(20,51)].$letters[rand(4,51)].$letters[rand(2,51)].$letters[rand(26,51)].$letters[rand(21,51)].$letters[rand(1,51)].$letters[rand(7,51)].$letters[rand(15,51)];


			$add_filename=$removespace_umun."_-".$removespace_tname.$ran."__-".$file_name;
														   

			//$name = $file_name;
			$name = $add_filename;

			//$path = "../attach_files/".$file_name;
			$path = "../attach_files_cgs/".$add_filename;

			$remarks = $_POST['remarks'];
            
            date_default_timezone_set('Asia/Manila');
            $dates = date('Y-m-d h:i:s A');
            
            
			
			//$conn->query("INSERT INTO `file` VALUES('', '$name[0]', '$path')") or die(mysqli_error());
		//$conn->query("INSERT INTO `cgs_attachfile` VALUES('','$tname','$u_mun', '$add_filename', '$path','$remarks','$dates','0')") or die(mysqli_error());
			
			
			require_once('../config/dbcon.php');
            $conni = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conni->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            try{
                $stmt = $conni->prepare("INSERT INTO `cgs_attachfile`(`typhoon_name`,`mun`,`filename`,`filepath`,`remarks`,`addeddate`,`notif`) VALUES ('".$tname."','".$u_mun."', '".$add_filename."', '".$path."','".$remarks."','".$dates."','0')");
                $stmt->execute();
            }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
            }
                
                
                
                
			
			move_uploaded_file($file_temp, $path);
			header("location:../cgs_attachfile.php");
			
		}else{
			echo "<script>alert('Required Field!')</script>";
			echo "<script>window.location='../cgs_attachfile.php'</script>";
		}
	}
?>