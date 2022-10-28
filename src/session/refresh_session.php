<?php
session_start();

// store session data
//if (isset($_SESSION['id']))
//$_SESSION['id'] = $_SESSION['id']; // or if you have any algo.

//admin
if (isset($_SESSION['user_id'])){
            $_SESSION['user_id'] = $_SESSION['user_id'];
}
if (isset($_SESSION['email'])){
			$_SESSION['email'] = $_SESSION['email'];
}
if (isset($_SESSION['utype'])){
			$_SESSION['utype'] = $_SESSION['utype'];
}
if (isset($_SESSION['mun'])){
			$_SESSION['mun']= $_SESSION['mun'];
}
if (isset($_SESSION['admin_typhoonname'])){
			$_SESSION["admin_typhoonname"] = $_SESSION["admin_typhoonname"];
}
if (isset($_SESSION['admin_dtype'])){
			$_SESSION["admin_dtype"] = $_SESSION["admin_dtype"];
}
if (isset($_SESSION['admin_mun'])){
			$_SESSION["admin_mun"] = $_SESSION["admin_mun"];
}

echo "good working";
//$user_id = $_SESSION['user_id'];
//$email = $_SESSION['email'];
//$utype = $_SESSION['utype'];
//$mun = $_SESSION['mun'];

//$_SESSION["dtype"] = $_SESSION["dtype"];




//users
//$_SESSION['u_user_id'] = $_SESSION['u_user_id'];
//$_SESSION['u_email'] = $_SESSION['u_email'];
//$_SESSION['u_utype'] = $_SESSION['u_utype'];
//$_SESSION['u_mun']= $_SESSION['u_mun'];
?>