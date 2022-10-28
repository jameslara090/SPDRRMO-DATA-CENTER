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

   $dtype=$_SESSION['dtype'];
    if (!isset($_SESSION['dtype']) || ($_SESSION['dtype'] == '')) {
        header("location:user_home.php");
        exit();
    }
   
	if(ISSET($_POST['submit'])){
		if($_FILES['upload']['name'] != "") {
			$file = $_FILES['upload'];
			
			$file_name = $file['name'];
			
			$file_name = str_replace('_-','',$file['name']);
			
			$file_temp = $file['tmp_name'];
			//$name = explode('.', $file_name);
			
			$filesize=$_FILES['upload']['size'];
			function formatSizeUnits($bytes)
			{
					if ($bytes >= 1073741824){ $bytes = number_format($bytes / 1073741824, 2) . ' GB'; }
					elseif ($bytes >= 1048576) { $bytes = number_format($bytes / 1048576, 2) . ' MB'; }
					elseif ($bytes >= 1024) { $bytes = number_format($bytes / 1024, 2) . ' KB'; }
					elseif ($bytes > 1) { $bytes = $bytes . ' bytes'; }
					elseif ($bytes == 1) { $bytes = $bytes . ' byte'; }
					else { $bytes = '0 bytes'; } 
					return $bytes;
			}
			$filesize=formatSizeUnits($filesize);

			date_default_timezone_set('Asia/Manila');
			$dates = date('Y-m-d h:i:s A');
			
			$dates_up = date('YMdhA_SF');
			
			$letters = array_merge(range('a','z'),range('A','Z'));
			$ran=$letters[rand(5,51)].$letters[rand(0,51)].$letters[rand(10,51)].$letters[rand(20,51)].$letters[rand(4,51)].$letters[rand(2,51)].$letters[rand(26,51)].$letters[rand(21,51)].$letters[rand(1,51)].$letters[rand(7,51)].$letters[rand(15,51)];

            $tname=$_SESSION["typhoonname"];
			$lowercase_tname = strtolower($tname);
		   
			if($_SESSION['dtype']=="TYPHOON"){
			  $removespace_tname = str_replace(' ', '', $lowercase_tname);
			}else{
			  $re = str_replace('-', '', $lowercase_tname);
			  $ref=explode(' ',$re);
			  $removespace_tname = $_SESSION['dtype'].str_replace(' ', '', $ref[0]).'-'.$dates_up;
			}

			$removespace_umun = str_replace(' ', '', $_SESSION["u_mun"]);
			//$has= password_hash("hash");
			$add_filename=$removespace_umun."_-".$removespace_tname."-".$dates_up.$ran."__-".$file_name;
														   

			//$name = $file_name;
			$name = $add_filename;

			//$path = "../attach_files/".$file_name;
			$path = "../attach_files/".$add_filename;

			$remarks = $_POST['remarks'];
            
            
            
            
			
			//$conn->query("INSERT INTO `file` VALUES('', '$name[0]', '$path')") or die(mysqli_error());
			
		//	$conn->query("INSERT INTO `typhoon_attachfile` VALUES('','$dtype','$tname','$u_mun', '$add_filename', '$path','$remarks','$dates','0')") or die(mysqli_error());
			
			
			require_once('../config/dbcon.php');
            $conni = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conni->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            try{
                $stmt = $conni->prepare("INSERT INTO `typhoon_attachfile`(`dtype`,`typhoon_name`,`mun`,`filename`,`filepath`,`filesize`,`remarks`,`addeddate`,`notif`) VALUES ('".$dtype."','".$tname."','".$u_mun."', '".$add_filename."', '".$path."','".$filesize."','".$remarks."','".$dates."','0')");
                $stmt->execute();
            }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
            }
			
			move_uploaded_file($file_temp, $path);
			header("location:../typhoon_attachfile.php");
			
		}else{
			echo "<script>alert('Required Field!')</script>";
			echo "<script>window.location='../typhoon_attachfile.php'</script>";
		}
	}
?>