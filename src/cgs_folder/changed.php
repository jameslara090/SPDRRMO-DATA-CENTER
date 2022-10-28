<?php


require_once('../session/check_user.php');

require_once('../config/dbcon.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt = $conn->prepare("SELECT * FROM users WHERE user_id='$u_user_id' ");
$stmt->execute(); 
$resultlogo = $stmt->fetch(PDO::FETCH_ASSOC);

//$datestart=date('Y-m-d');
//echo date('Y-m-d',strtotime('+30 days',strtotime($datestart))) . PHP_EOL;

//if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['save'])){
        try{
            if($resultlogo['user_pass']==$_POST['user_pass_old'] && $_POST['user_pass_new'] != ""){
                $stmt = $conn->prepare("UPDATE users SET user_email='".$_POST['user_email']."', user_pass='".$_POST['user_pass_new']."', usertype='".$u_utype."', name='".$_POST['name']."'  WHERE user_id='".$u_user_id."' ");
                $stmt->execute();
                echo "<script>alert('✔ Successfully Updated'); window.location='../logout_user.php'</script>";
            }else{
                echo "<script>alert('Please check your old password (incorrect)! or New Password (Empty)!'); window.location='changepass_user.php'</script>";
            }
            
            }
            catch (Exception $e){ echo $e->getMessage() . "<br/>";while($e = $e->getPrevious()) {echo 'Previous Error: '.$e->getMessage() . "<br/>";}
            }
    }
    if(isset($_POST['cancel'])){
        echo "<script>window.location='changepass_user.php'</script>";
    }
//}

?>