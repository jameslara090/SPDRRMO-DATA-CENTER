<?php
session_start();

// store session data
//if (isset($_SESSION['id']))
//$_SESSION['id'] = $_SESSION['id']; // or if you have any algo.

//admin
if (isset($_SESSION['u_user_id'])){
            $_SESSION['u_user_id'] = $_SESSION['u_user_id'];
}
if (isset($_SESSION['u_email'])){
			$_SESSION['u_email'] = $_SESSION['u_email'];
}
if (isset($_SESSION['u_utype'])){
			$_SESSION['u_utype'] = $_SESSION['u_utype'];
}
if (isset($_SESSION['u_mun'])){
			$_SESSION['u_mun']= $_SESSION['u_mun'];
}
if (isset($_SESSION['typhoonname'])){
			$_SESSION["typhoonname"] = $_SESSION["typhoonname"];
}
if (isset($_SESSION['dtype'])){
			$_SESSION["dtype"] = $_SESSION["dtype"];
}

echo "good working";
//$u_user_id = $_SESSION['u_user_id'];
//$u_email = $_SESSION['u_email'];
//$u_utype = $_SESSION['u_utype'];
//$u_mun = $_SESSION['u_mun'];

//$_SESSION["dtype"] = $_SESSION["dtype"];



//users
//$_SESSION['u_user_id'] = $_SESSION['u_user_id'];
//$_SESSION['u_email'] = $_SESSION['u_email'];
//$_SESSION['u_utype'] = $_SESSION['u_utype'];
//$_SESSION['u_mun']= $_SESSION['u_mun'];

//$_SESSION["typhoonname"]=$_GET['name'];
//$_SESSION["dtype"]=$_GET['dtype'];
?>