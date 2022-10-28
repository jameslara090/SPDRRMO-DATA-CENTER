<?php
   // require_once 'config\typhoon_attachfile_con.php';
   
   session_start();
   require_once '../config/typhoon_attachfile_con.php';
   
   //require_once('../session/check_user.php');

   //$dtype=$_SESSION['dtype'];
   // if (!isset($_SESSION['dtype']) || ($_SESSION['dtype'] == '')) {
    //    header("location:user_home.php");
    //    exit();
    //}
   
	if(ISSET($_POST['submit'])){

		    $utype_val = $_GET['utype'];
		    $users = "";
		    $loc ="";
		    if ($utype_val=="user") {
		    	$users = $_SESSION['u_user_id'];
		    	$loc = "forum";
		    }else if($utype_val =="admin"){
		    	$users = $_SESSION['user_id'];
		    	$loc = "forum_admin";
		    }
		
			$file = $_FILES['upload'];
			
			$file_name = $file['name'];
			
			$file_name = str_replace('_-','',$file['name']);
			
			$file_temp = $file['tmp_name'];
			//$name = explode('.', $file_name);

			date_default_timezone_set('Asia/Manila');
			$dates = date('M-d-Y ⟶ h:i A');
			
			//$dates_up = date('YMdhA');
			$dates_up = date('Y-M-d_h-i-s_A_');


			require_once('../config/dbcon.php');
		    $connuser = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		    $connuser->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $stmt = $connuser->prepare("SELECT * FROM users WHERE user_id='".$users."' ");
		    $stmt->execute(); 
		    $res_u = $stmt->fetch(PDO::FETCH_ASSOC);

		    $user = strtoupper($res_u['name']);

	    if($_FILES['upload']['name'] != "") {
	        
	        
	        $letters = array_merge(range('a','z'),range('A','Z'));
			$ran=$letters[rand(5,51)].$letters[rand(0,51)].$letters[rand(10,51)].$letters[rand(20,51)].$letters[rand(4,51)].$letters[rand(2,51)].$letters[rand(26,51)].$letters[rand(21,51)].$letters[rand(1,51)].$letters[rand(7,51)].$letters[rand(15,51)];


		      $add_filename = $user."-".$dates_up.$ran."_".$file_name;
		    



            //$tname=$_SESSION["typhoonname"];
			//$lowercase_tname = strtolower($tname);
		   
			//if($_SESSION['dtype']=="TYPHOON"){
			//  $removespace_tname = str_replace(' ', '', $lowercase_tname);
			//}else{
			//  $re = str_replace('-', '', $lowercase_tname);
			//  $ref=explode(' ',$re);
			//  $removespace_tname = $_SESSION['dtype'].str_replace(' ', '', $ref[0]).'-'.$dates_up;
			//}

			//$removespace_umun = str_replace(' ', '', $_SESSION["u_mun"]);
			//$add_filename=$removespace_umun."_-".$removespace_tname."__-".$file_name;
														   

			//$name = $file_name;
			$name = $add_filename;

			//$path = "../attach_files/".$file_name;
			$path = "../img_ftopic/".$add_filename;
        }
			$details = $_POST['details'];
            
            
            
            
			
			//$conn->query("INSERT INTO `file` VALUES('', '$name[0]', '$path')") or die(mysqli_error());
	//	$conn->query("INSERT INTO `ftopic` VALUES('','$user','$details', '$add_filename', '$path','$dates','')") or die(mysqli_error());
	
	

			
			require_once('../config/dbcon.php');
            $conni = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conni->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            try{
                $stmt = $conni->prepare("INSERT INTO `ftopic`(`mun`,`content`,`filename`,`filepath`,`addeddate`) VALUES ('".$user."','".$details."', '".$add_filename."', '".$path."','".$dates."')");
                $stmt->execute();
            }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
            }
                
                
			
			move_uploaded_file($file_temp, $path);
			header("location:../".$loc.".php");
			
		
	}
?>