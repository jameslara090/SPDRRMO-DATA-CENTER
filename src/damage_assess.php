<?php

// require_once('config/dbcon.php');
// $conn1 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $stmt = $conn1->prepare("SELECT t1.tid,t1.remarks as p1,t2.remarks as p2,t3.remarks as p3,
// t4.remarks as p4,
// t5.remarks as p5
// FROM table1 t1,table2 t2,table3 t3,table4 t4,table5 t5 where t2.tid=t1.tid and t3.tid=t1.tid and t4.tid=t1.tid and t5.tid=t1.tid;");


// $stmt->execute(); 
// $result =$stmt->fetchAll(); //$stmt->fetch(PDO::FETCH_ASSOC);
// $i=1;
//   foreach ($result as $value){
//    echo "<div>".$i."  ".$value['p1']."    _______________________    "."  ".$value['p2']."    _______________________     "."  ".$value['p3']."    _______________________     "."  ".$value['p4']."    _______________________     "."  ".$value['p5']."<br></div>";
   
//   $i++;}
 
?>




<?php
    error_reporting(0);

    require_once('session/check_user.php');

    $tname=$_SESSION['typhoonname'];

    $dtype=$_SESSION['dtype'];
    if (!isset($_SESSION['dtype']) || ($_SESSION['dtype'] == '')) {
        header("location:user_home.php");
        exit();
    }

    //if ($tname != "" && $u_mun=="sta. magdalena"){
    //}else{
    //    header("location:user_typhoon.php");
    //}
    


    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    //$stmt = $conn->prepare("SELECT MAX(iid) AS max FROM 1pod");
    //$stmt->execute(); 
    //$id = $stmt->fetch(PDO::FETCH_ASSOC);

    ////echo $id['max']+1;
    //$uid=$id['max']+1;

    

    if($u_mun=="sta. magdalena"){
      $stmt = $conn->prepare("SELECT * FROM barangay WHERE b_municipality='SANTA MAGDALENA' order by b_barangay ASC");
      $stmt->execute(); 
      $result_brgy = $stmt->fetchAll();
    }else{
      $resStr = strtoupper($u_mun); 
      $stmt = $conn->prepare("SELECT * FROM barangay WHERE b_municipality='".$resStr."' order by b_barangay ASC");
      $stmt->execute(); 
      $result_brgy = $stmt->fetchAll();
    }

    
    //echo '<pre>'; print_r($result_brgy); echo '</pre>';
     
    //echo "<script>alert(".$result_brgy."); window.location='damage_assess.php'</script>";


    $stmt = $conn->prepare("SELECT * FROM 1pod WHERE dtype='".$dtype."' and tname='".$tname."' and mun='".$u_mun."' order by d_re DESC");
    $stmt->execute(); 
    $result_rec = $stmt->fetchAll();


    

    

  

    
    $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and mun='".$u_mun."' order by tdatetime DESC, tid ASC");
    $stmt->execute(); 
    $resulttf = $stmt->fetchAll();
   
    

    $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and tid='".$_GET['id']."' ");
    $stmt->execute(); 
    $resulttfselect = $stmt->fetch(PDO::FETCH_ASSOC);
    
    

    //open close colapse
    $update=$_GET['id'];  $updatestat="";  if($update>0){  $updatestat="-in";  }else{  $updatestat="";  }
    //end

    //open close colapse
    $del=$_GET['del'];  
    $delstat="";  
    if($del>0){  
      $delstat=$_GET['del'];  
    }else{  
      $delstat="";  
    }
    //end
    //open edit
    // $id=$_GET['id'];  
    // $upstat="";  
    // if($id>0){  
    //   $upstat=$_GET['id'];  
    // }else{  
    //   $upstat="";  
    // }
    //end


//===============================================SELECT UPDATE===============================================================================================================================


    $stmt = $conn->prepare("SELECT * FROM 1pod  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and iid = '".$_GET['id']."' ");   
    $stmt->execute();
    $profi = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 2aa  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' ");   
    $stmt->execute();
    $areaaf = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 3pa  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' ");   
    $stmt->execute();
    $popaf = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 4pd  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' ");   
    $stmt->execute();
    $popdi = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 5c  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' ");   
    $stmt->execute();
    $cas = $stmt->fetch(PDO::FETCH_ASSOC);

    //damage prop
    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Houses' ");   
    $stmt->execute();
    $dam = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'School Bldgs' ");   
    $stmt->execute();
    $dam1 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Govt Offices' ");   
    $stmt->execute();
    $dam2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Public Markets' ");   
    $stmt->execute();
    $dam3 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Flood Control' ");   
    $stmt->execute();
    $dam4 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Commercial Facilities' ");   
    $stmt->execute();
    $dam5 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Structures' and dprop = 'Others' ");   
    $stmt->execute();
    $dam6 = $stmt->fetch(PDO::FETCH_ASSOC);
    //tourism


    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Resorts' ");   
    $stmt->execute();
    $dam7 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Hotels' ");   
    $stmt->execute();
    $dam8 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Restaurants' ");   
    $stmt->execute();
    $dam9 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Tourism Assisted Center' ");   
    $stmt->execute();
    $dam10 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Theme Parks' ");   
    $stmt->execute();
    $dam11 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 6dp  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Tourism Facilities' and dprop = 'Visitors Centers' ");   
    $stmt->execute();
    $dam12 = $stmt->fetch(PDO::FETCH_ASSOC);
    //end damage prop


    //damage lifelines
    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'National' and dloc = 'Roads' ");
    $stmt->execute();
    $daml = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Provincial' and dloc = 'Roads' ");
    $stmt->execute();
    $daml1 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Municipality' and dloc = 'Roads' ");
    $stmt->execute();
    $daml2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'City' and dloc = 'Roads' ");
    $stmt->execute();
    $daml3 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Barangay' and dloc = 'Roads' ");
    $stmt->execute();
    $daml4 = $stmt->fetch(PDO::FETCH_ASSOC);


    //bridges
    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Bailey' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml5 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Concrete' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml6 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Wooden' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml7 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Suspension' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml8 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Transportation Facilities' and dlist = 'Railways' and dloc = 'Bridges' ");
    $stmt->execute();
    $daml9 = $stmt->fetch(PDO::FETCH_ASSOC);

    //communication
    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Communication Facilities' and dlist = 'PLDT' ");
    $stmt->execute();
    $daml10 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Communication Facilities' and dlist = 'Bayan Tel' ");
    $stmt->execute();
    $daml11 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Communication Facilities' and dlist = 'Cell Sites' ");
    $stmt->execute();
    $daml12 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 7dl  WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dtype = 'Communication Facilities' and dlist = 'Repeaters' ");
    $stmt->execute();
    $daml13 = $stmt->fetch(PDO::FETCH_ASSOC);
    //end damage lifelines


    //agriculture
    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Rice'  ");
    $stmt->execute();
    $agri = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Corn'  ");
    $stmt->execute();
    $agri1 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Vegetables'  ");
    $stmt->execute();
    $agri2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Root Crops'  ");
    $stmt->execute();
    $agri3 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Fruit Trees'  ");
    $stmt->execute();
    $agri4 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE  dtype='".$dtype."' and tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Crops' and alist = 'Bananas'  ");
    $stmt->execute();
    $agri5 = $stmt->fetch(PDO::FETCH_ASSOC);


    //fisheries
    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Fisheries' and alist = 'Animals'  ");
    $stmt->execute();
    $agri6 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Fisheries' and alist = 'Farm'  ");
    $stmt->execute();
    $agri7 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM 8a  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and ctype = 'Fisheries' and alist = 'Poultry & Fowls'  ");
    $stmt->execute();
    $agri8 = $stmt->fetch(PDO::FETCH_ASSOC);
    //end agriculture


     //local actions
    $stmt = $conn->prepare("SELECT * FROM 9la  WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."'  ");
    $stmt->execute();
    $loca = $stmt->fetch(PDO::FETCH_ASSOC);


    //==================================================END SELECT UPDATE============================================================================================================================


    //echo $areaaf['brgyaf'];
    

    

    
    



      if($delstat > 0){
          try{
                  $stmt = $conn->prepare("DELETE FROM 1pod WHERE iid='".$delstat."' and mun='".$u_mun."' and dtype='".$dtype."' and  tname='".$tname."' ");
                  $stmt->execute();
                  echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Deleted  '); window.location='damage_assess.php'</script>";
              }
              catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
              }
      }
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['add'])){
            $mun=$u_mun;
           
            try{
              if($_POST['d_oc1']==""){ } else{
                date_default_timezone_set('Asia/Manila');
                $dates = date('Y-m-d h:i:s A');

                $stmt = $conn->prepare("INSERT INTO `1pod`(`dtype`,`tname`,`mun`,`t_em`,`d_oc`,`s_r`,`d_re`)   
                VALUES ('".$dtype."','".$tname."', '".$u_mun."', '".$_POST['t_em1']."', '".$_POST['d_oc1']."', '".$_POST['s_r1']."', '".$dates."')");
                $stmt->execute();

                $stmt = $conn->prepare("SELECT MAX(iid) AS max FROM 1pod");
                $stmt->execute(); 
                $id = $stmt->fetch(PDO::FETCH_ASSOC);
                $uid=$id['max'];

                $brgysval = implode(',', $_POST['brgyaf2']);
                //echo  $brgysval." - ".$uid;

                $stmt = $conn->prepare("INSERT INTO `2aa`(`uid`,`dtype`,`tname`,`mun`,`brgyaf`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', '".$brgysval."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `3pa`(`uid`,`dtype`,`tname`,`mun`,`fams`,`pers`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', '".$_POST['fams3']."', '".$_POST['pers3']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `4pd`(`uid`,`dtype`,`tname`,`mun`,`fams`,`pers`,`infa`,`child`,`adol`,`adul`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', '".$_POST['fams4']."', '".$_POST['pers4']."', '".$_POST['infa4']."', '".$_POST['child4']."', '".$_POST['adol4']."', '".$_POST['adul4']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `5c`(`uid`,`dtype`,`tname`,`mun`,`dead`,`injur`,`miss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', '".$_POST['dead5']."', '".$_POST['injur5']."', '".$_POST['miss5']."')");
                $stmt->execute();
                
                //STRUCTURES
                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', '', 'Damaged Properties', 'Structures', 'Houses', '".$_POST['tota6_1']."', '".$_POST['par6_1']."', '".$_POST['est6_1']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  '', 'Damaged Properties', 'Structures', 'School Bldgs', '".$_POST['tota6_2']."', '".$_POST['par6_2']."', '".$_POST['est6_2']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  '', 'Damaged Properties', 'Structures', 'Govt Offices', '".$_POST['tota6_3']."', '".$_POST['par6_3']."', '".$_POST['est6_3']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  '', 'Damaged Properties', 'Structures', 'Public Markets', '".$_POST['tota6_4']."', '".$_POST['par6_4']."', '".$_POST['est6_4']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', '',  'Damaged Properties', 'Structures', 'Flood Control', '".$_POST['tota6_5']."', '".$_POST['par6_5']."', '".$_POST['est6_5']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  '', 'Damaged Properties', 'Structures', 'Commercial Facilities', '".$_POST['tota6_6']."', '".$_POST['par6_6']."', '".$_POST['est6_6']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  '', 'Damaged Properties', 'Structures', 'Others', '".$_POST['tota6_7']."', '".$_POST['par6_7']."', '".$_POST['est6_7']."')");
                $stmt->execute();
                //END STRUCTURES


                //TOURISM
                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Privately owned', 'Damaged Properties', 'Tourism Facilities', 'Resorts', '".$_POST['tota6_8']."', '".$_POST['par6_8']."', '".$_POST['est6_8']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  'Privately owned', 'Damaged Properties', 'Tourism Facilities', 'Hotels', '".$_POST['tota6_9']."', '".$_POST['par6_9']."', '".$_POST['est6_9']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  'Privately owned', 'Damaged Properties', 'Tourism Facilities', 'Restaurants', '".$_POST['tota6_10']."', '".$_POST['par6_10']."', '".$_POST['est6_10']."')");
                $stmt->execute();
                 //SEPARATE
                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  'Government owned', 'Damaged Properties', 'Tourism Facilities', 'Tourism Assisted Center', '".$_POST['tota6_11']."', '".$_POST['par6_11']."', '".$_POST['est6_11']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Government owned',  'Damaged Properties', 'Tourism Facilities', 'Theme Parks', '".$_POST['tota6_12']."', '".$_POST['par6_12']."', '".$_POST['est6_12']."')");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `6dp`(`uid`,`dtype1`,`tname`,`mun`,`own`,`dam`,`dtype`,`dprop`,`tota`,`par`,`est`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."',  'Government owned', 'Damaged Properties', 'Tourism Facilities', 'Visitors Centers', '".$_POST['tota6_13']."', '".$_POST['par6_13']."', '".$_POST['est6_13']."')");
                $stmt->execute();
                //END TOURISM

                //DAMAGED LIFELINES
                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'National', 'Roads', '".$_POST['dopt7_1']."', '".$_POST['num7_1']."', '".$_POST['cost7_1']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'Provincial', 'Roads', '".$_POST['dopt7_2']."', '".$_POST['num7_2']."', '".$_POST['cost7_2']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'Municipality', 'Roads', '".$_POST['dopt7_3']."', '".$_POST['num7_3']."', '".$_POST['cost7_3']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'City', 'Roads', '".$_POST['dopt7_4']."', '".$_POST['num7_4']."', '".$_POST['cost7_4']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'Barangay', 'Roads', '".$_POST['dopt7_5']."', '".$_POST['num7_5']."', '".$_POST['cost7_5']."' )");
                $stmt->execute();
                //SEPARATE
                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'Bailey', 'Bridges', '".$_POST['dopt7_6']."', '".$_POST['num7_6']."', '".$_POST['cost7_6']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'Concrete', 'Bridges', '".$_POST['dopt7_7']."', '".$_POST['num7_7']."', '".$_POST['cost7_7']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'Wooden', 'Bridges', '".$_POST['dopt7_8']."', '".$_POST['num7_8']."', '".$_POST['cost7_8']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'Suspension', 'Bridges', '".$_POST['dopt7_9']."', '".$_POST['num7_9']."', '".$_POST['cost7_9']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Transportation Facilities', 'Railways', 'Bridges', '".$_POST['dopt7_10']."', '".$_POST['num7_10']."', '".$_POST['cost7_10']."' )");
                $stmt->execute();
                //END DAMAGED LIFELINES

                //COMM
                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Communication Facilities', 'PLDT', '".$_POST['dloc7_1']."', '".$_POST['dopt7_11']."', '".$_POST['num7_11']."', '".$_POST['cost7_11']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Communication Facilities', 'Bayan Tel', '".$_POST['dloc7_2']."', '".$_POST['dopt7_12']."', '".$_POST['num7_12']."', '".$_POST['cost7_12']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Communication Facilities', 'Cell Sites', '".$_POST['dloc7_3']."', '".$_POST['dopt7_13']."', '".$_POST['num7_13']."', '".$_POST['cost7_13']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `7dl`(`uid`,`dtype1`,`tname`,`mun`,`dtype`,`dlist`,`dloc`,`dopt`,`num`,`cost`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Communication Facilities', 'Repeaters', '".$_POST['dloc7_4']."', '".$_POST['dopt7_14']."', '".$_POST['num7_14']."', '".$_POST['cost7_14']."' )");
                $stmt->execute();
                //END COMM


                //AGRICULTURE
                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Crops', 'Rice', '".$_POST['hect8_1']."', '".$_POST['metr8_1']."', '".$_POST['loss8_1']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Crops', 'Corn', '".$_POST['hect8_2']."', '".$_POST['metr8_2']."', '".$_POST['loss8_2']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Crops', 'Vegetables', '".$_POST['hect8_3']."', '".$_POST['metr8_3']."', '".$_POST['loss8_3']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Crops', 'Root Crops', '".$_POST['hect8_4']."', '".$_POST['metr8_4']."', '".$_POST['loss8_4']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Crops', 'Fruit Trees', '".$_POST['hect8_5']."', '".$_POST['metr8_5']."', '".$_POST['loss8_5']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Crops', 'Bananas', '".$_POST['hect8_6']."', '".$_POST['metr8_6']."', '".$_POST['loss8_6']."' )");
                $stmt->execute();
                //END AGRICULTURE

                //AGRICULTURE 7.2
                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Fisheries', 'Animals', '".$_POST['hect8_7']."', '', '".$_POST['loss8_7']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Fisheries', 'Farm', '".$_POST['hect8_8']."', '', '".$_POST['loss8_8']."' )");
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `8a`(`uid`,`dtype`,`tname`,`mun`,`ctype`,`alist`,`hect`,`metr`,`loss`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', 'Fisheries', 'Poultry & Fowls', '".$_POST['hect8_9']."', '', '".$_POST['loss8_9']."' )");
                $stmt->execute();
                //END AGRICULTURE

                //LOCAL ACTIONS
                $stmt = $conn->prepare("INSERT INTO `9la`(`uid`,`dtype`,`tname`,`mun`,`resp`,`asse`,`na_f`,`nd_f`,`nd_p`,`nd_i`,`nd_c`,`nd_a`,`ext`)   
                VALUES ('".$uid."','".$dtype."', '".$tname."', '".$u_mun."', '".$_POST['resp9']."', '".$_POST['asse9']."', '".$_POST['na_f9']."', '".$_POST['nd_f9']."', '".$_POST['nd_p9']."', '".$_POST['nd_i9']."', '".$_POST['nd_c9']."', '".$_POST['nd_a9']."', '".$_POST['ext9']."' )");
                $stmt->execute();
                //END LOCAL ACTIONS

              
                echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Added '); window.location='damage_assess.php'</script>";
              }
                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {   echo 'Previous Error: '.$e->getMessage() . "<br/>";  }
                }

        }

        if(isset($_POST['update'])){
          try{
                  $stmt = $conn->prepare("UPDATE 1pod SET t_em ='".$_POST['t_em1']."', d_oc = '".$_POST['d_oc1']."', s_r = '".$_POST['s_r1']."' WHERE iid = '".$_GET['id']."' and tname='".$tname."' and mun='".$u_mun."'   ");
                  $stmt->execute();

                 

                  $brgysval = implode(',', $_POST['brgyaf2']);
                  $stmt = $conn->prepare("UPDATE 2aa SET brgyaf = '".$brgysval."' WHERE dtype='".$dtype."' and tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' ");
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 3pa SET fams = '".$_POST['fams3']."',pers = '".$_POST['pers3']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' ");
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 4pd SET fams = '".$_POST['fams4']."',pers = '".$_POST['pers4']."',infa = '".$_POST['infa4']."',child = '".$_POST['child4']."',adol = '".$_POST['adol4']."',adul = '".$_POST['adul4']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 5c SET dead = '".$_POST['dead5']."',injur = '".$_POST['injur5']."',miss = '".$_POST['miss5']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' ");   
                  $stmt->execute();
                  
                  //STRUCTURES
                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_1']."',par = '".$_POST['par6_1']."',est = '".$_POST['est6_1']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Houses'  ");
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_2']."',par = '".$_POST['par6_2']."',est = '".$_POST['est6_2']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'School Bldgs'  ");  
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_3']."',par = '".$_POST['par6_3']."',est = '".$_POST['est6_3']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Govt Offices'  ");
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_4']."',par = '".$_POST['par6_4']."',est = '".$_POST['est6_4']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Public Markets'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_5']."',par = '".$_POST['par6_5']."',est = '".$_POST['est6_5']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Flood Control'  "); 
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_6']."',par = '".$_POST['par6_6']."',est = '".$_POST['est6_6']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Commercial Facilities'  ");
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_7']."',par = '".$_POST['par6_7']."',est = '".$_POST['est6_7']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Others'  ");   
                  $stmt->execute();
                  //END STRUCTURES


                  //TOURISM
                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_8']."',par = '".$_POST['par6_8']."',est = '".$_POST['est6_8']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Resorts'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_9']."',par = '".$_POST['par6_9']."',est = '".$_POST['est6_9']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Hotels'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_10']."',par = '".$_POST['par6_10']."',est = '".$_POST['est6_10']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Restaurants'  ");   
                  $stmt->execute();
                  //SEPARATE
                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_11']."',par = '".$_POST['par6_11']."',est = '".$_POST['est6_11']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Tourism Assisted Center'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_12']."',par = '".$_POST['par6_12']."',est = '".$_POST['est6_12']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Theme Parks'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 6dp SET tota = '".$_POST['tota6_13']."',par = '".$_POST['par6_13']."',est = '".$_POST['est6_13']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dprop= 'Visitors Centers'  ");   
                  $stmt->execute();
                  //END TOURISM

                  //DAMAGED LIFELINES
                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_1']."',num = '".$_POST['num7_1']."',cost = '".$_POST['cost7_1']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'National'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_2']."',num = '".$_POST['num7_2']."',cost = '".$_POST['cost7_2']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Provincial'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_3']."',num = '".$_POST['num7_3']."',cost = '".$_POST['cost7_3']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Municipality'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_4']."',num = '".$_POST['num7_4']."',cost = '".$_POST['cost7_4']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'City'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_5']."',num = '".$_POST['num7_5']."',cost = '".$_POST['cost7_5']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Barangay'  ");   
                  $stmt->execute();
                  //SEPARATE
                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_6']."',num = '".$_POST['num7_6']."',cost = '".$_POST['cost7_6']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Bailey'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_7']."',num = '".$_POST['num7_7']."',cost = '".$_POST['cost7_7']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Concrete'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_8']."',num = '".$_POST['num7_8']."',cost = '".$_POST['cost7_8']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Wooden'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_9']."',num = '".$_POST['num7_9']."',cost = '".$_POST['cost7_9']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Suspension'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dopt = '".$_POST['dopt7_10']."',num = '".$_POST['num7_10']."',cost = '".$_POST['cost7_10']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Railways'  ");   
                  $stmt->execute();
                  //END DAMAGED LIFELINES

                  //COMM
                  $stmt = $conn->prepare("UPDATE 7dl SET dloc = '".$_POST['dloc7_1']."',dopt = '".$_POST['dopt7_11']."',num = '".$_POST['num7_11']."',cost = '".$_POST['cost7_11']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'PLDT'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dloc = '".$_POST['dloc7_2']."',dopt = '".$_POST['dopt7_12']."',num = '".$_POST['num7_12']."',cost = '".$_POST['cost7_12']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Bayan Tel'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dloc = '".$_POST['dloc7_3']."',dopt = '".$_POST['dopt7_13']."',num = '".$_POST['num7_13']."',cost = '".$_POST['cost7_13']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Cell Sites'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 7dl SET dloc = '".$_POST['dloc7_4']."',dopt = '".$_POST['dopt7_14']."',num = '".$_POST['num7_14']."',cost = '".$_POST['cost7_14']."' WHERE dtype1='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and dlist= 'Repeaters'  ");   
                  $stmt->execute();
                  //END COMM


                  //AGRICULTURE
                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_1']."',metr = '".$_POST['metr8_1']."',loss = '".$_POST['loss8_1']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Rice'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_2']."',metr = '".$_POST['metr8_2']."',loss = '".$_POST['loss8_2']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Corn'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_3']."',metr = '".$_POST['metr8_3']."',loss = '".$_POST['loss8_3']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Vegetables'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_4']."',metr = '".$_POST['metr8_4']."',loss = '".$_POST['loss8_4']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Root Crops'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_5']."',metr = '".$_POST['metr8_5']."',loss = '".$_POST['loss8_5']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Fruit Trees'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_6']."',metr = '".$_POST['metr8_6']."',loss = '".$_POST['loss8_6']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Bananas'  ");   
                  $stmt->execute();
                  //END AGRICULTURE

                  //AGRICULTURE 7.2
                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_7']."',loss = '".$_POST['loss8_7']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Animals'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_8']."',loss = '".$_POST['loss8_8']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Farm'  ");   
                  $stmt->execute();

                  $stmt = $conn->prepare("UPDATE 8a SET hect = '".$_POST['hect8_9']."',loss = '".$_POST['loss8_9']."' WHERE  dtype='".$dtype."' and tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."' and alist= 'Poultry & Fowls'  ");   
                  $stmt->execute();
                  //END AGRICULTURE

                  //LOCAL ACTIONS
                  $stmt = $conn->prepare("UPDATE 9la SET resp = '".$_POST['resp9']."',asse = '".$_POST['asse9']."',na_f = '".$_POST['na_f9']."',nd_f = '".$_POST['nd_f9']."',nd_p = '".$_POST['nd_p9']."',nd_i = '".$_POST['nd_i9']."',nd_c = '".$_POST['nd_c9']."',nd_a = '".$_POST['nd_a9']."',ext = '".$_POST['ext9']."' WHERE dtype='".$dtype."' and  tname='".$tname."' and mun='".$u_mun."' and uid = '".$_GET['id']."'  ");   
                  $stmt->execute();
                  //END LOCAL ACTIONS
                  echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully updated '); window.location='damage_assess.php'</script>";
                 
  
              }
              catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
              }
      }

    }
?>


<!DOCTYPE html>
<html lang="en">
    

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

          
        <!-- Vendor styles -->
        <link rel="stylesheet" href="vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="vendors/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="vendors/bower_components/select2/dist/css/select2.min.css">
         <link rel="stylesheet" href="css/custom.css">

         <!--for date and time-->
        <link rel="stylesheet" href="vendors/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="vendors/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="vendors/bower_components/nouislider/distribute/nouislider.min.css">
        <link rel="stylesheet" href="vendors/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css">
        <link rel="stylesheet" href="vendors/bower_components/trumbowyg/dist/ui/trumbowyg.min.css">
        <link rel="stylesheet" href="vendors/bower_components/rateYo/min/jquery.rateyo.min.css">
        <!--for date and time-->


        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">

        <link rel="stylesheet" href="demo/css/demo.css">

        <?php include('include/head_tab.php')?>
        <?php include('logs.php')?>

        



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
          $("tr").hover(function(){
              $(this).css("background-color", "rgba(0,0,0,0.2)");
              }, function(){
              $(this).css("background-color", "rgba(0,0,0,0)");
          });

        });
        </script>


        
    </head>
      
    <body data-sa-theme="3">
        <main class="main">
            <?php include('include/user_header.php');?>
            <aside class="sidebar">
                <div class="scrollbar-inner">
                   <?php include('include/user_profile.php')?>
                </div>
                    <ul class="navigation">
                       <li style="margin-bottom:1px; border-left:3px solid green;"> <a style="padding:0px; margin:0px;" href="user_home.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-home"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</button> </a> </li>
                       <li style="margin-bottom:1px;"> <a style="padding:0px; margin:0px;" href="changepass_user.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-key"></i> &nbsp;&nbsp;&nbsp;&nbsp;My Account</button> </a> </li>
 
                       <li style="margin-bottom:1px; margin-top:15px;"> <a style="padding:0px; margin:0px;" href="forum.php"> <button style="width:100%; text-align:left;" class="btn btn-light btn--icon-text">&nbsp;&nbsp;&nbsp;&nbsp;<i class="zmdi zmdi-comments"></i> &nbsp;&nbsp;&nbsp;&nbsp;Forum / Suggestions</button> </a> </li>                   
                    </ul>
                </div>
            </aside>


            <section class="content">

                   <div class="row" style="margin-bottom:20px;">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="user_home.php">Reporting  </a>  &nbsp;⟶&nbsp;
                      <a href="user_typhoon.php"><?php echo ucfirst(strtolower($_SESSION['dtype']));?>  </a>  &nbsp;⟶&nbsp;
                      <a href="user_reportfor.php">Report to:  </a>  &nbsp;⟶&nbsp;
                      <label>Manage Damage Assessment Report: <?php echo $_SESSION["typhoonname"]; ?></label>
                   </div>

                <div class="row" style=" padding:15px;  padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:15px;">
                
                


                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"  >
                                <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                    <div class="panel-heading" role="tab" id="headingOne" >
                                        <h4 class="panel-title">
                                           <a role="button" data-toggle="collapse" data-parent="#accordion" id="addcol" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size:12px;"> ADD RECORD </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                          <form action="damage_assess.php" method="post">
                                            <div class="row" style=" padding:0px; padding-bottom:10px; padding-top:10px;">
                                                   
                                                    <script type="text/javascript">
                                                        function success() {
                                                            var tag=document.getElementById("add");
                                                            if(document.getElementById("myText").value==="") {
                                                                document.getElementById('add').disabled = true;
                                                            } else { 
                                                                tag.style.background="green";
                                                                document.getElementById('add').disabled = false;
                                                            }
                                                        }
                                                    </script>
                                            </div>
                                            
                                            
                                            <div class="card">
                                                <div class="card-body" style="padding:0px;">
                                                    <div class="tab-container" style=" margin:0px;">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                              <li class="nav-item"  >
                                                                  <a class="nav-link" data-toggle="tab" href="#summarye" role="tab">DAMAGE ASSESSMENT REPORT</a>
                                                              </li>
                                                        </ul>

                                                        <div class="tab-content">
                                                              <div class="tab-pane active fade show" id="summarye" role="tabpanel" style="padding-left:5px; padding-right:5px; width:1000px; ">




                                                              <label for="" style=" color:#a6bbbb; margin-top:5px; margin-bottom:5px;" > <code style="color:#a6bbbb;"> A. PROFILE OF DISASTER</code></label>

                                                                 


                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);   ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">Profile of Disaster</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">Type of Disaster / Emergency :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control"  name="t_em1"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="" value="<?=$_SESSION['dtype']?>" >
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom:4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Date and Time of Occurrence :</span>
                                                                                      <div class="form-group">
                                                                                          <input style=" padding:6px;  color:white;  border-left:none;" name="d_oc1" id="myText" onchange="success()" type="text" class="form-control datetime-picker" placeholder="Select Date Required">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px;">
                                                                                        <span class="input-group-addon" style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Source of Report :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control"  name="s_r1" style=" border-left:none; padding:6px; color:#939999; font-weight:bold;"  placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>


                                                                        </div>
                                                                    </div>


                                                                <label for="" style=" color:#a6bbbb;  margin-bottom:5px; margin-top:10px;" > <code style="color:#a6bbbb;"> B. SUMMARY OF THE EFFECTS (AS OF REPORTING TIME)</code></label>
                                                             

                                                                <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
                                                                <script>
                                                                        $(document).ready(function(){
                                                                              $("#brgys").select2();
                                                                              $("#s_all").click(function(){
                                                                                  if($("#s_all").is(':checked') ){
                                                                                      $("#brgys > option").prop("selected","selected");
                                                                                      $("#brgys").trigger("change");
                                                                                  }else{
                                                                                      $("#brgys > option").removeAttr("selected");
                                                                                      $("#brgys").trigger("change");
                                                                                  }
                                                                              });

                                                                              $('#brgys').select2({closeOnSelect: false});                                                   
                                                                        });
                                                                </script>

                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);   ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">1. Areas Affected</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;">Barangay : &nbsp; <input type="checkbox" id="s_all" title="Click here to select/deselect all Barangay"></span>
                                                                                    <div class="form-group">
                                                                                        <select class="select2" style="width:100%;" id="brgys" name="brgyaf2[]"  multiple>
                                                                                            <!--<option value="all"  >1. all</option>-->
                                                                                            <?php $i=1;
                                                                                                  foreach($result_brgy as $val){
                                                                                            ?>
                                                                                                     <option value="<?=$val['b_barangay']?>"  ><?=$i.".   ".$val['b_barangay']?></option>
                                                                                            <?php $i++;} ?>
                                                                                           
                                                                                            
                                                                                        </select>
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>


                                                                        </div>
                                                                    </div>

                                                                <div class="card-demo" style="width:323px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px;">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">2. Population Affected ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Families :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="fams3"  style=" border-left:none; padding:6px;   color:#939999; font-weight:bold;" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Persons :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="pers3" style=" border-left:none; padding:6px; color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-demo" style="width:324px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">3. Population Displaced ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Families :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="fams4"  style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Persons :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="pers4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Infants: 0-1 year old :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="infa4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Children: 2-12 years old :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="child4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Adolescents: 13-17 year :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="adol4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Adults 18 years old and above :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="adul4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                 </div>

                                                                 <div class="card-demo" style="width:324px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">4. Casualties ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Dead :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="dead5"  style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Injured :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="injur5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Missing :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="number" class="form-control"  name="miss5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                 </div>

                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">5. Damaged Properties ( Structures )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                <div class="row">
                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;" > &nbsp;&nbsp; A. Breakdown</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Totally</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Patially</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Est. Cost</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Houses</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">School Bldgs.</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Gov't Offices</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Public Markets</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Flood Control (Sea wall, dikes, dams, levees, irrigation system)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Commercial Facilities (Factories/mall, stores, supermarkets)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Other (Specify)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; B. Tourism Facilities</label>

                                                                         <br> <label for="" style=" color:#7e8889; " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. Privately owned:</label>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Resorts</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Hotels</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Restaurants</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <label for="" style=" color:#7e8889; " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Government owned:</label>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Tourism Assisted Center</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Theme Parks</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Visitor Centers</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="tota6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="par6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="est6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                
                                                                               





                                                                        </div>
                                                                   
                                                                 </div></div>












                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">6. Damaged Lifelines</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                              <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 6.1 Transportation Facilities</label>
                                                                                <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >Location <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ▸ Roads:</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Passable/Not Passable</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Number</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Cost</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">National</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_1" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Provincial</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_2" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option  value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Municipal</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_3" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">City</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_4" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Barangay</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_5" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">▸ Bridges:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>  Bailey </label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_6" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Concrete</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_7"  data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Wooden</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_8" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Suspension</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_9" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Railways</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_10" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 6.2 Communication Facilities</label>

                                                                                <div class="row">
                                                                                      <div class="col-sm-2 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >&nbsp;</label>
                                                                                      </div>

                                                                                      <div class="col-sm-4 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >Location</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Optional/Not Not</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Number</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Cost</label>
                                                                                      </div>
                                                                                </div>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">PLDT</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="dloc7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_11" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Bayan Tel</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="dloc7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_12" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Cell Sites</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="dloc7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_13" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Repeaters</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="dloc7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_14" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="">&nbsp;</option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="num7_14" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="cost7_14" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                        </div>
                                                                   
                                                                 </div></div>


















                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">7. Agriculture</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                              <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 7.1 Crops</label>
                                                                                <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" > </label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Areas Damaged (No. of Hectares)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">(Metric Tons)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Lossess (Peso Value)</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Rice</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="hect8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="metr8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Corn</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="hect8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="metr8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Vegetables</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="hect8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="metr8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Root Crops</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="hect8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="metr8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Fruit Trees</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="hect8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="metr8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Bananas</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="hect8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  name="metr8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                               



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 7.2 Fisheries</label>

                                                                                 <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" > </label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Areas Damaged (No. of Heads)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Peso Value</label>
                                                                                      </div>
                                                                                </div>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Animals</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="hect8_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Farm</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="hect8_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                       
                                                                                       
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Poultry & Fowls</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="hect8_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="number" class="form-control"  name="loss8_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                       
                                                                                        </div>
                                                                                </div>









                                                                        </div>
                                                                   
                                                                 </div></div>




                                                           <label for="" style=" color:#a6bbbb;  margin-bottom:5px; margin-top:10px; margin-left:4px;" > <code style="color:#a6bbbb;"> C. LOCAL ACTIONS</code></label>



                                                            
                                                                 <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1); margin-left:3px;   margin-right:3px;  ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">Local Actions</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">1. Emergency Responders Involved :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control"  name="resp9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">2. Assets Deployed :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control"  name="asse9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>
                                                                                 
                                                                                 <label for="" style="color:#7e8889;"> &nbsp;&nbsp;&nbsp; 3. Number of affected population Served</label>
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Families :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control"  name="na_f9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <label for="" style="color:#7e8889;"> &nbsp;&nbsp;&nbsp; 4. Number of Displaced population Served</label>
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Families :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control"  name="nd_f9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Persons :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control"  name="nd_p9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Infants :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control"  name="nd_i9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Children :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control"  name="nd_c9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adults :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control"  name="nd_a9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>


                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">5. Extent of Local Assistance :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control"  name="ext9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>


                                                                                  
                                                                                


                                                                        </div>
                                                                    </div>
                                                                 













                                                                    




                                                                    




                                                              </div>
                                                             
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row" style="margin-bottom:15px;">
                                               <div class="col-sm-3"><button title="Save to Database (Note: Date & Time Required to { enable this Save Button } )" type="submit" name="add" id="add" disabled > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SAVE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Add" href="damage_assess.php"> CANCEL</a>  </div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                            </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>


                               


                                <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse<?php echo $updatestat;?>" role="tabpanel" aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                         <form action="damage_assess.php?id=<?=$_GET['id']?>" method="post">
                                          
                                            <div class="card">
                                                <div class="card-body" style="padding:0px;">
                                                    <div class="tab-container" style=" margin:0px;">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                              <li class="nav-item"  >
                                                                  <a class="nav-link" data-toggle="tab" href="#summarye" role="tab">DAMAGE ASSESSMENT REPORT</a>
                                                              </li>
                                                        </ul>

                                                        <div class="tab-content">
                                                              <div class="tab-pane active fade show" id="summarye" role="tabpanel" style="padding-left:5px; padding-right:5px; width:1000px; ">




                                                              <label for="" style=" color:#a6bbbb; margin-top:5px; margin-bottom:5px;" > <code style="color:#a6bbbb;"> A. PROFILE OF DISASTER</code></label>

                                                                 


                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);   ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">Profile of Disaster</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">Type of Disaster / Emergency :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$profi['t_em']?>"  name="t_em1"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="" value="TYPHOON" >
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom:4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Date and Time of Occurrence :</span>
                                                                                      <div class="form-group">
                                                                                          <input style=" padding:6px;  color:white;  border-left:none;" value="<?=$profi['d_oc']?>" name="d_oc1" id="myText" onchange="success()" type="text" class="form-control datetime-picker" placeholder="Select Date Required">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px;">
                                                                                        <span class="input-group-addon" style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Source of Report :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$profi['s_r']?>"  name="s_r1" style=" border-left:none; padding:6px; color:#939999; font-weight:bold;"  placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>


                                                                        </div>
                                                                    </div>


                                                                <label for="" style=" color:#a6bbbb;  margin-bottom:5px; margin-top:10px;" > <code style="color:#a6bbbb;"> B. SUMMARY OF THE EFFECTS (AS OF REPORTING TIME)</code></label>
                                                             

                                                                <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
                                                                <script>
                                                                        $(document).ready(function(){
                                                                              $("#selecteditem1").select2();
                                                                              $("#s_all1").click(function(){
                                                                                  if($("#s_all1").is(':checked') ){
                                                                                      $("#selecteditem1 > option").prop("selected","selected");
                                                                                      $("#selecteditem1").trigger("change");
                                                                                  }else{
                                                                                      $("#selecteditem1 > option").removeAttr("selected");
                                                                                      $("#selecteditem1").trigger("change");
                                                                                  }
                                                                              });
                                                                              $('#selecteditem1').select2({closeOnSelect: false});                                                   
                                                                        });
                                                                </script>

                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);   ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">1. Areas Affected</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;">Barangay : &nbsp; <input type="checkbox" id="s_all1" title="Click here to select/deselect all Barangay"></span>
                                                                                    <div class="form-group">
                                                                                        <select class="select2" id="selecteditem1" style="width:100%;" id="brgys" name="brgyaf2[]"  multiple>
                                                                                            <!--<option value="all"  >1. all</option>-->
                                                                                            <?php $i=1;
                                                                                                  foreach($result_brgy as $val){
                                                                                            ?>
                                                                                                     <option value="<?=$val['b_barangay']?>"  ><?=$val['b_barangay']?></option>
                                                                                            <?php $i++;} ?>

                                                                                            <?php 
                                                                                              $data =  $areaaf['brgyaf'];
                                                                                              $array =  explode(',', $data);
                                                                                              foreach ($array as $each1) {
                                                                                              ?>
                                                                                                    <script>
                                                                                                      var value = ['<?php print_r($each1);?>'];
                                                                                                      el = document.getElementById("selecteditem1");
                                                                                                        for (var j = 0; j < value.length; j++) {
                                                                                                          for (var i = 0; i < el.length; i++) {
                                                                                                            if (el[i].innerHTML == value[j]) {
                                                                                                              el[i].selected = true;
                                                                                                            }
                                                                                                        }
                                                                                                      }
                                                                                                  </script>

                                                                                            <?php } ?>

                                                                                        </select>

                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>


                                                                        </div>
                                                                    </div>

                                                                <div class="card-demo" style="width:323px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px;">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">2. Population Affected ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Families :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control" value="<?=$popaf['fams']?>"  name="fams3"  style=" border-left:none; padding:6px;   color:#939999; font-weight:bold;" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Persons :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control" value="<?=$popaf['pers']?>"  name="pers3" style=" border-left:none; padding:6px; color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-demo" style="width:324px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">3. Population Displaced ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Families :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control" value="<?=$popdi['fams']?>" name="fams4"  style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Persons :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=$popdi['pers']?>"  name="pers4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Infants: 0-1 year old :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=$popdi['infa']?>"  name="infa4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Children: 2-12 years old :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=$popdi['child']?>"  name="child4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Adolescents: 13-17 year :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=$popdi['adol']?>"  name="adol4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Adults 18 years old and above :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=$popdi['adul']?>"  name="adul4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                 </div>

                                                                 <div class="card-demo" style="width:324px; margin:2px;">
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">4. Casualties ( Cumulative Total )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                <div class="input-group" style="margin-bottom: 4px; ">
                                                                                      <span class="input-group-addon"  style="border:none; color:#7e8889;">Dead :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=$cas['dead']?>"  name="dead5"  style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;" placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Injured :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=$cas['injur']?>"  name="injur5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="input-group" style="margin-bottom: 4px;">
                                                                                      <span class="input-group-addon" style="border:none; color:#7e8889;">Missing :</span>
                                                                                      <div class="form-group">
                                                                                        <input type="text" class="form-control"  value="<?=$cas['miss']?>"  name="miss5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                        <i class="form-group__bar"></i>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                 </div>

                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">5. Damaged Properties ( Structures )</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                <div class="row">
                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;" > &nbsp;&nbsp; A. Breakdown</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Totally</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Patially</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Est. Cost</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Houses</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam['tota']?>" name="tota6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=$dam['par']?>"  name="par6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=$dam['est']?>"  name="est6_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">School Bldgs.</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=$dam1['tota']?>"  name="tota6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=$dam1['par']?>"  name="par6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=$dam1['est']?>"  name="est6_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Gov't Offices</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=$dam2['tota']?>"  name="tota6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=$dam2['par']?>"  name="par6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control"  value="<?=$dam2['est']?>"  name="est6_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Public Markets</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam3['tota']?>" name="tota6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam3['par']?>" name="par6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam3['est']?>" name="est6_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Flood Control (Sea wall, dikes, dams, levees, irrigation system)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam4['tota']?>" name="tota6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam4['par']?>" name="par6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam4['est']?>" name="est6_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Commercial Facilities (Factories/mall, stores, supermarkets)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam5['tota']?>" name="tota6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam5['par']?>" name="par6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam5['est']?>" name="est6_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Other (Specify)</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam6['tota']?>" name="tota6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam6['par']?>" name="par6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam6['est']?>" name="est6_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; B. Tourism Facilities</label>

                                                                         <br> <label for="" style=" color:#7e8889; " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. Privately owned:</label>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Resorts</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam7['tota']?>" name="tota6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam7['par']?>" name="par6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam7['est']?>" name="est6_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Hotels</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam8['tota']?>" name="tota6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam8['par']?>" name="par6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam8['est']?>" name="est6_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Restaurants</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam9['tota']?>" name="tota6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam9['par']?>" name="par6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam9['est']?>" name="est6_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <label for="" style=" color:#7e8889; " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Government owned:</label>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Tourism Assisted Center</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam10['tota']?>" name="tota6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam10['par']?>" name="par6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam10['est']?>" name="est6_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Theme Parks</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam11['tota']?>" name="tota6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam11['par']?>" name="par6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam11['est']?>" name="est6_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Visitor Centers</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam12['tota']?>" name="tota6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam12['par']?>" name="par6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$dam12['est']?>" name="est6_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                
                                                                               





                                                                        </div>
                                                                   
                                                                 </div></div>












                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">6. Damaged Lifelines</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                              <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 6.1 Transportation Facilities</label>
                                                                                <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >Location <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ▸ Roads:</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Passable/Not Passable</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Number</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Cost</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">National</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_1" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml['dopt']?>"><?=$daml['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml['num']?>" name="num7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml['cost']?>" name="cost7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Provincial</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_2" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml1['dopt']?>"><?=$daml1['dopt']?></option> 
                                                                                                        <option  value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml1['num']?>" name="num7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml1['cost']?>" name="cost7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Municipal</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_3" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml2['dopt']?>"><?=$daml2['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml2['num']?>" name="num7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml2['cost']?>" name="cost7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">City</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_4" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml3['dopt']?>"><?=$daml3['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml3['num']?>" name="num7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml3['cost']?>" name="cost7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Barangay</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_5" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml4['dopt']?>"><?=$daml4['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml4['num']?>" name="num7_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml4['cost']?>" name="cost7_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">▸ Bridges:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>  Bailey </label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_6" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml5['dopt']?>"><?=$daml5['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml5['num']?>" name="num7_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml5['cost']?>" name="cost7_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Concrete</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_7"  data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml6['dopt']?>"><?=$daml6['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml6['num']?>" name="num7_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml6['cost']?>" name="cost7_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Wooden</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_8" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml7['dopt']?>"><?=$daml7['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml7['num']?>" name="num7_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml7['cost']?>" name="cost7_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Suspension</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_9" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml8['dopt']?>"><?=$daml8['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml8['num']?>" name="num7_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml8['cost']?>" name="cost7_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Railways</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_10" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml9['dopt']?>"><?=$daml9['dopt']?></option> 
                                                                                                        <option value="Passable">Passable</option> 
                                                                                                        <option value="Not Passable">Not Passable</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml9['num']?>" name="num7_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml9['cost']?>" name="cost7_10" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 6.2 Communication Facilities</label>

                                                                                <div class="row">
                                                                                      <div class="col-sm-2 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >&nbsp;</label>
                                                                                      </div>

                                                                                      <div class="col-sm-4 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" >Location</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Optional/Not Not</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Number</label>
                                                                                      </div>

                                                                                      <div class="col-sm-2">
                                                                                         <label for="" style=" color:#7e8889;">Cost</label>
                                                                                      </div>
                                                                                </div>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">PLDT</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml10['dloc']?>" name="dloc7_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_11" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml10['dopt']?>"><?=$daml10['dopt']?></option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml10['num']?>" name="num7_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml10['cost']?>" name="cost7_11" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Bayan Tel</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml11['dloc']?>" name="dloc7_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_12" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml11['dopt']?>"><?=$daml11['dopt']?></option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml11['num']?>" name="num7_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml11['cost']?>" name="cost7_12" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Cell Sites</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml12['dloc']?>" name="dloc7_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_13" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml12['dopt']?>"><?=$daml12['dopt']?></option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml12['num']?>" name="num7_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml12['cost']?>" name="cost7_13" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-2" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Repeaters</label>
                                                                                        </div>

                                                                                        <div class="col-sm-4">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml13['dloc']?>" name="dloc7_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                    <select class="select2" name="dopt7_14" data-minimum-results-for-search="Infinity">
                                                                                                        <option value="<?=$daml13['dopt']?>"><?=$daml13['dopt']?></option> 
                                                                                                        <option value="Optional">Optional</option> 
                                                                                                        <option value="Not Optional">Not Optional</option>   
                                                                                                    </select>
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml13['num']?>" name="num7_14" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-2">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$daml13['cost']?>" name="cost7_14" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>



                                                                        </div>
                                                                   
                                                                 </div></div>


















                                                                 <div class="row" style="padding-left:18px; padding-right:18px;">
                                                                
                                                                    <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1);  margin-top:10px; width:100%; ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">7. Agriculture</div>    
                                                                        <div class="card-body" style="padding:4px;">
                                                                              <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 7.1 Crops</label>
                                                                                <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" > </label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Areas Damaged (No. of Hectares)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">(Metric Tons)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Lossess (Peso Value)</label>
                                                                                      </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Rice</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri['hect']?>" name="hect8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri['metr']?>" name="metr8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri['loss']?>" name="loss8_1" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Corn</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri1['hect']?>" name="hect8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri1['metr']?>" name="metr8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri1['loss']?>" name="loss8_2" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Vegetables</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri2['hect']?>" name="hect8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri2['metr']?>" name="metr8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri2['loss']?>" name="loss8_3" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Root Crops</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri3['hect']?>" name="hect8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri3['metr']?>" name="metr8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri3['loss']?>" name="loss8_4" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Fruit Trees</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri4['hect']?>" name="hect8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri4['metr']?>" name="metr8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri4['loss']?>" name="loss8_5" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Bananas</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri5['hect']?>"  name="hect8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri5['metr']?>" name="metr8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri5['loss']?>" name="loss8_6" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                               



                                                                         <label for="" style=" color:#7e8889; margin-top:5px; margin-bottom:5px;" > &nbsp;&nbsp; 7.2 Fisheries</label>

                                                                                 <div class="row">
                                                                               
                                                                                      <div class="col-sm-3 " style="text-align:center">
                                                                                         <label for="" style=" color:#7e8889;" > </label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Areas Damaged (No. of Heads)</label>
                                                                                      </div>

                                                                                      <div class="col-sm-3">
                                                                                         <label for="" style=" color:#7e8889;">Peso Value</label>
                                                                                      </div>
                                                                                </div>
                                                                                
                                                                            <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Animals</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri6['hect']?>" name="hect8_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri6['loss']?>" name="loss8_7" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Farm</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri7['hect']?>" name="hect8_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri7['loss']?>" name="loss8_8" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                       
                                                                                       
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="col-sm-3" style="text-align:right;">
                                                                                          <label for=""  style=" color:#7e8889;">Poultry & Fowls</label>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri8['hect']?>" name="hect8_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                        <div class="input-group" style="margin-bottom: 4px;">
                                                                                                <div class="form-group">
                                                                                                <input type="text" class="form-control" value="<?=$agri8['loss']?>" name="loss8_9" style=" border-left:none; padding:6px;  color:#939999; font-weight:bold;"  placeholder="">
                                                                                                  <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-3">
                                                                                       
                                                                                        </div>
                                                                                </div>









                                                                        </div>
                                                                   
                                                                 </div></div>




                                                           <label for="" style=" color:#a6bbbb;  margin-bottom:5px; margin-top:10px; margin-left:4px;" > <code style="color:#a6bbbb;"> C. LOCAL ACTIONS</code></label>



                                                            
                                                                 <div class="card" style=" margin:0px; background-color:rgba(0,0,0,0.1); border: 2px solid rgba(0,0,0,0.1); margin-left:3px;   margin-right:3px;  ">
                                                                      <div class="card-header" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left:5px; font-weight:bold; color:#4d5a5d;">Local Actions</div>
                                                                        <div class="card-body" style="padding:4px;">
                                                                                
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">1. Emergency Responders Involved :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['resp']?>" name="resp9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">2. Assets Deployed :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['asse']?>" name="asse9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>
                                                                                 
                                                                                 <label for="" style="color:#7e8889;"> &nbsp;&nbsp;&nbsp; 3. Number of affected population Served</label>
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Families :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['na_f']?>" name="na_f9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <label for="" style="color:#7e8889;"> &nbsp;&nbsp;&nbsp; 4. Number of Displaced population Served</label>
                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Families :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['nd_f']?>" name="nd_f9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp; Persons :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['nd_p']?>" name="nd_p9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Infants :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control" value="<?=$loca['nd_i']?>" name="nd_i9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Children :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control" value="<?=$loca['nd_c']?>" name="nd_c9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="input-group" style="margin-bottom: 4px; ">
                                                                                                  <span class="input-group-addon"  style="border:none; color:#7e8889;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adults :</span>
                                                                                                  <div class="form-group">
                                                                                                    <input type="text" class="form-control" value="<?=$loca['nd_a']?>" name="nd_a9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                                    <i class="form-group__bar"></i>
                                                                                                </div>
                                                                                            </div>


                                                                                  <div class="input-group" style="margin-bottom: 4px; ">
                                                                                        <span class="input-group-addon"  style="border:none; color:#7e8889;">5. Extent of Local Assistance :</span>
                                                                                        <div class="form-group">
                                                                                          <input type="text" class="form-control" value="<?=$loca['ext']?>" name="ext9"  style=" border-left:none; padding:6px; color:#939999; font-weight:bold;" placeholder="">
                                                                                          <i class="form-group__bar"></i>
                                                                                      </div>
                                                                                  </div>


                                                                                  
                                                                                


                                                                        </div>
                                                                    </div>
                                                                 






                                                              </div>
                                                             
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom:15px;">
                                               <div class="col-sm-3"><button title="Save Changes" type="submit" name="update" > &nbsp;&nbsp;&nbsp; SAVE CHANGES &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Edit" href="damage_assess.php"> CANCEL</a>  </div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                            </div>
                                          
                                          </form>

                                        </div>



                                    </div>
                                </div>




                            </div>




                </div>
                
                <div class="card">
                    <div class="card-body">
                        <!--<h4 class="card-title">Record</h4>-->


                        
                        <div class="table-responsive">
                            <style type="text/css">
                                .tbtn{
                                    background-color: Transparent;
                                    background-repeat:no-repeat;
                                    border: none;
                                    cursor:pointer;
                                    overflow: hidden;
                                    outline:none;
                                    }
                                    .tbtn:hover{
                                    border: 1px solid Transparent;
                                    }
                                    .tbtnicon{
                                    color:#6c757d;
                                    }
                                    .tbtnicon:hover{
                                    color:black;
                                    }
                            </style>
                            <table id="data-table" class="table">
                                <thead>
                                    <tr>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center; width:27px;">&nbsp;</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center; width:27px;">&nbsp;</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center; width:27px;">&nbsp;</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Type of Disaster / Emergency</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center;">Date and Time of Occurence</th>
                                        <th rowspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Source of Report</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Date and Time of Report</th>
                                    </tr>
                                   

                                </thead>
                                <tbody>
                                <?php 
                                
                                 foreach ($result_rec as $value_rec) 
                                 {
                                  
                                ?>
                                    <tr style="border:1px solid #2b4c4a;">
                                        <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                            <a title="Delete" href="damage_assess.php?del=<?=$value_rec['iid']?>" style="padding-left:5px;padding-right:5px; border-radius:50px;" ><i class="zmdi zmdi-delete" ></i></a>
                                        </td>
                                        <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                            <a title="Edit" href="damage_assess.php?id=<?=$value_rec['iid']?>" style="padding-left:5px;padding-right:5px; border-radius:50px;" ><i class="zmdi zmdi-edit" ></i></a>
                                        </td>
                                        <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                            <a title="Print / View" href="damage_assess_view.php?id=<?=$value_rec['iid']?>" style="padding-left:5px;padding-right:5px; border-radius:50px;" ><i class="zmdi zmdi-print" ></i></a>
                                        </td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$value_rec['t_em']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$value_rec['d_oc']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$value_rec['s_r']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$value_rec['d_re']?></td>
                                    </tr>
                                <?php 
                                 }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    






             






                

                <footer class="footer hidden-xs-down">
                    <!--<p>© CopCoder. All rights reserved.</p>-->

                    
                </footer>
            </section>
           
        </main>

        <!-- Vendors -->
        <script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="vendors/bower_components/popper.js/dist/umd/popper.min.js"></script>
        <script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        

        <!-- Vendors: Data tables -->
        <script src="vendors/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="vendors/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>


        <!--for date and time-->
        <script src="vendors/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script src="vendors/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="vendors/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="vendors/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        <script src="vendors/bower_components/nouislider/distribute/nouislider.min.js"></script>
        <script src="vendors/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <script src="vendors/bower_components/trumbowyg/dist/trumbowyg.min.js"></script>
        <script src="vendors/bower_components/rateYo/min/jquery.rateyo.min.js"></script>
        <script src="vendors/bower_components/jquery-text-counter/textcounter.min.js"></script>
        <script src="vendors/bower_components/autosize/dist/autosize.min.js"></script>
        <!--for date and time-->

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
    </body>


</html>