
<?php
    error_reporting(0);

    require_once('session/check_user.php');

    $tname=$_SESSION['typhoonname'];
    $dtype=$_SESSION['dtype'];
    if (!isset($_SESSION['dtype']) || ($_SESSION['dtype'] == '')) {
        header("location:user_home.php");
        exit();
    }

    if ($tname != "" && $u_mun=="sorsogon"){
    }else{
        header("location:user_typhoon.php");
    }

    $mun=$u_mun;
    $mun_b=$u_mun;
    


    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    //filter search check
    $filter=$_GET['trig'];  $filters="";  if($filter =="fil"){  $filters="1";  }else{  $filters="";  }
    //end
    if ($filters=="1"){
        if ($_POST['tdatetime']=="All" || $_POST['tdatetime']==""){
            $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and mun='".$mun."' order by tdatetime DESC, tid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();
            echo "<script>window.location='user_entry_sorsogoncity.php'</script>";
        }else{
            $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and SUBSTRING_INDEX(tdatetime,' ',1)='".$_POST['tdatetime']."'   and mun='".$mun."'  order by tdatetime DESC, tid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();
        }
    }else{
        $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."'  and mun='".$mun."'  order by tdatetime DESC, tid ASC");
        $stmt->execute(); 
        $resulttf = $stmt->fetchAll();

    }
    

    $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and tid='".$_GET['id']."' ");
    $stmt->execute(); 
    $resulttfselect = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //populate combobox decamped all
    $stmt = $conn->prepare("SELECT DISTINCT tdatetime,stat FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and stat='Camped' and mun='".$u_mun."' order by tdatetime DESC ");
    $stmt->execute(); 
    $resulttfdate = $stmt->fetchAll();
    //end

    //populate combobox delete filter
    $stmt = $conn->prepare("SELECT DISTINCT tdatetime FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and mun='".$u_mun."' order by tdatetime DESC ");
    $stmt->execute(); 
    $resulttfdatedeletefilter = $stmt->fetchAll();
    //end

    //populate combobox filter by
    //$stmt = $conn->prepare("SELECT DISTINCT tdatetime FROM typhoon_form  order by tdatetime DESC ");
    $stmt=$conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."'    and mun='".$mun."' GROUP BY SUBSTRING_INDEX(tdatetime,' ',1) order by SUBSTRING_INDEX(tdatetime,' ',1) DESC ");
    $stmt->execute(); 
    $resulttfdateall = $stmt->fetchAll();
    //end


    //open close colapse
    $update=$_GET['id'];  $updatestat="";  if($update>0){  $updatestat="-in";  }else{  $updatestat="";  }
    //end



    //$stmt = $conn->prepare("SELECT COUNT(tid) AS id,mun,brgy FROM typhoon_form where mun='sorsogon city' order by tid DESC");
    //$stmt->execute(); 
    //$ct = $stmt->fetch(PDO::FETCH_ASSOC);
    //echo " || ".$ct['id']." || ".$ct['mun']." || ".$ct['brgy'];
    



    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['update'])){
            $num="0";  if ($_POST['infamilies'] < 1 && $_POST['inpersons'] < 1 && $_POST['oufamilies'] < 1 && $_POST['oupersons'] < 1  && $_POST['missing'] < 1 && $_POST['injured'] < 1 && $_POST['dead'] < 1 && $_POST['repartial'] < 1 && $_POST['retotal'] < 1 )    { $num="0"; }else{ $num="1"; }
            try{
                    $stmt = $conn->prepare("UPDATE typhoon_form SET num=:num, infamilies=:infamilies, inpersons=:inpersons, oufamilies=:oufamilies, oupersons=:oupersons, evacu=:evacu, missing=:missing, injured=:injured, dead=:dead, repartial=:repartial, retotal=:retotal, stat=:stat, remarks=:remarks, tdatetime=:tdatetime WHERE dtype='".$dtype."' and  tid=:tid");
                    $stmt->bindParam(':num', $num);
                    $stmt->bindParam(':infamilies', $_POST['infamilies']);
                    $stmt->bindParam(':inpersons', $_POST['inpersons']);
                    $stmt->bindParam(':oufamilies', $_POST['oufamilies']);
                    $stmt->bindParam(':oupersons', $_POST['oupersons']);
                    $stmt->bindParam(':evacu', $_POST['evacu']);
                    $stmt->bindParam(':missing', $_POST['missing']);
                    $stmt->bindParam(':injured', $_POST['injured']);
                    $stmt->bindParam(':dead', $_POST['dead']);
                    $stmt->bindParam(':repartial', $_POST['repartial']);
                    $stmt->bindParam(':retotal', $_POST['retotal']);
                    $stmt->bindParam(':stat', $_POST['stat']);
                    $stmt->bindParam(':remarks', $_POST['remarks']);
                    $stmt->bindParam(':tdatetime', $_POST['tdatetime']);
                    $stmt->bindParam(':tid', $_POST['tid']);
                    $stmt->execute();
                    if ($num < 1){  echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully updated : ( ".$_POST['brgy']." ) Not Affected '); window.location='user_entry_sorsogoncity.php'</script>";
                    }else{  echo "<script>alert('✔ Successfully updated : ( ".$_POST['brgy']." ) Affected '); window.location='user_entry_sorsogoncity.php'</script>";  }

                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
                }
        }
        if(isset($_POST['decamped'])){
            try{
                    $let="Decamped";
                    $stmt = $conn->prepare("UPDATE typhoon_form SET stat=:stat WHERE tdatetime=:tdatetime and mun='".$u_mun."' and stat='Camped' and dtype='".$dtype."' and  typhoon_name='".$tname."' ");
                    $stmt->bindParam(':tdatetime', $_POST['tdatetime']);
                    $stmt->bindParam(':stat', $let);
                    $stmt->execute();
                    echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Decamped in : ( ".$_POST['tdatetime']." ) '); window.location='user_entry_sorsogoncity.php'</script>";
                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
                }
        }
        if(isset($_POST['delete'])){
            try{
                    $stmt = $conn->prepare("DELETE FROM typhoon_form WHERE tdatetime=:tdatetime and mun='".$u_mun."' and dtype='".$dtype."' and  typhoon_name='".$tname."' ");
                    $stmt->bindParam(':tdatetime', $_POST['tdatetime']);
                    $stmt->execute();

                    $stmt = $conn->prepare("DELETE FROM actionun WHERE tdatetime='".$_POST['tdatetime']."' and mun='".$u_mun."' and dtype='".$dtype."' and  typhoon_name='".$tname."' ");
                    $stmt->execute();


                    echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Deleted : ( ".$_POST['tdatetime']." ) Record '); window.location='user_entry_sorsogoncity.php'</script>";
                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
                }
        }
        if(isset($_POST['add'])){
            $mun=$u_mun;
            $mun_b=$u_mun;
            $num="0";  if ($_POST['infamilies'] < 1 && $_POST['inpersons'] < 1 && $_POST['oufamilies'] < 1 && $_POST['oupersons'] < 1  && $_POST['missing'] < 1 && $_POST['injured'] < 1 && $_POST['dead'] < 1 && $_POST['repartial'] < 1 && $_POST['retotal'] < 1 )    { $num="0"; }else{ $num="1"; }
            $num1="0";  if ($_POST['infamilies1'] < 1 && $_POST['inpersons1'] < 1 && $_POST['oufamilies1'] < 1 && $_POST['oupersons1'] < 1  && $_POST['missing1'] < 1 && $_POST['injured1'] < 1 && $_POST['dead1'] < 1 && $_POST['repartial1'] < 1 && $_POST['retotal1'] < 1 )    { $num1="0"; }else{ $num1="1"; }
            $num2="0";  if ($_POST['infamilies2'] < 1 && $_POST['inpersons2'] < 1 && $_POST['oufamilies2'] < 1 && $_POST['oupersons2'] < 1  && $_POST['missing2'] < 1 && $_POST['injured2'] < 1 && $_POST['dead2'] < 1 && $_POST['repartial2'] < 1 && $_POST['retotal2'] < 1 )    { $num2="0"; }else{ $num2="1"; }
            $num3="0";  if ($_POST['infamilies3'] < 1 && $_POST['inpersons3'] < 1 && $_POST['oufamilies3'] < 1 && $_POST['oupersons3'] < 1  && $_POST['missing3'] < 1 && $_POST['injured3'] < 1 && $_POST['dead3'] < 1 && $_POST['repartial3'] < 1 && $_POST['retotal3'] < 1 )    { $num3="0"; }else{ $num3="1"; }
            $num4="0";  if ($_POST['infamilies4'] < 1 && $_POST['inpersons4'] < 1 && $_POST['oufamilies4'] < 1 && $_POST['oupersons4'] < 1  && $_POST['missing4'] < 1 && $_POST['injured4'] < 1 && $_POST['dead4'] < 1 && $_POST['repartial4'] < 1 && $_POST['retotal4'] < 1 )    { $num4="0"; }else{ $num4="1"; }
            $num5="0";  if ($_POST['infamilies5'] < 1 && $_POST['inpersons5'] < 1 && $_POST['oufamilies5'] < 1 && $_POST['oupersons5'] < 1  && $_POST['missing5'] < 1 && $_POST['injured5'] < 1 && $_POST['dead5'] < 1 && $_POST['repartial5'] < 1 && $_POST['retotal5'] < 1 )    { $num5="0"; }else{ $num5="1"; }
            $num6="0";  if ($_POST['infamilies6'] < 1 && $_POST['inpersons6'] < 1 && $_POST['oufamilies6'] < 1 && $_POST['oupersons6'] < 1  && $_POST['missing6'] < 1 && $_POST['injured6'] < 1 && $_POST['dead6'] < 1 && $_POST['repartial6'] < 1 && $_POST['retotal6'] < 1 )    { $num6="0"; }else{ $num6="1"; }
            $num7="0";  if ($_POST['infamilies7'] < 1 && $_POST['inpersons7'] < 1 && $_POST['oufamilies7'] < 1 && $_POST['oupersons7'] < 1  && $_POST['missing7'] < 1 && $_POST['injured7'] < 1 && $_POST['dead7'] < 1 && $_POST['repartial7'] < 1 && $_POST['retotal7'] < 1 )    { $num7="0"; }else{ $num7="1"; }
            $num8="0";  if ($_POST['infamilies8'] < 1 && $_POST['inpersons8'] < 1 && $_POST['oufamilies8'] < 1 && $_POST['oupersons8'] < 1  && $_POST['missing8'] < 1 && $_POST['injured8'] < 1 && $_POST['dead8'] < 1 && $_POST['repartial8'] < 1 && $_POST['retotal8'] < 1 )    { $num8="0"; }else{ $num8="1"; }
            $num9="0";  if ($_POST['infamilies9'] < 1 && $_POST['inpersons9'] < 1 && $_POST['oufamilies9'] < 1 && $_POST['oupersons9'] < 1  && $_POST['missing9'] < 1 && $_POST['injured9'] < 1 && $_POST['dead9'] < 1 && $_POST['repartial9'] < 1 && $_POST['retotal9'] < 1 )    { $num9="0"; }else{ $num9="1"; }
            $num10="0";  if ($_POST['infamilies10'] < 1 && $_POST['inpersons10'] < 1 && $_POST['oufamilies10'] < 1 && $_POST['oupersons10'] < 1  && $_POST['missing10'] < 1 && $_POST['injured10'] < 1 && $_POST['dead10'] < 1 && $_POST['repartial10'] < 1 && $_POST['retotal10'] < 1 )    { $num10="0"; }else{ $num10="1"; }
            $num11="0";  if ($_POST['infamilies11'] < 1 && $_POST['inpersons11'] < 1 && $_POST['oufamilies11'] < 1 && $_POST['oupersons11'] < 1  && $_POST['missing11'] < 1 && $_POST['injured11'] < 1 && $_POST['dead11'] < 1 && $_POST['repartial11'] < 1 && $_POST['retotal11'] < 1 )    { $num11="0"; }else{ $num11="1"; }
            $num12="0";  if ($_POST['infamilies12'] < 1 && $_POST['inpersons12'] < 1 && $_POST['oufamilies12'] < 1 && $_POST['oupersons12'] < 1  && $_POST['missing12'] < 1 && $_POST['injured12'] < 1 && $_POST['dead12'] < 1 && $_POST['repartial12'] < 1 && $_POST['retotal12'] < 1 )    { $num12="0"; }else{ $num12="1"; }
            $num13="0";  if ($_POST['infamilies13'] < 1 && $_POST['inpersons13'] < 1 && $_POST['oufamilies13'] < 1 && $_POST['oupersons13'] < 1  && $_POST['missing13'] < 1 && $_POST['injured13'] < 1 && $_POST['dead13'] < 1 && $_POST['repartial13'] < 1 && $_POST['retotal13'] < 1 )    { $num13="0"; }else{ $num13="1"; }
            $num14="0";  if ($_POST['infamilies14'] < 1 && $_POST['inpersons14'] < 1 && $_POST['oufamilies14'] < 1 && $_POST['oupersons14'] < 1  && $_POST['missing14'] < 1 && $_POST['injured14'] < 1 && $_POST['dead14'] < 1 && $_POST['repartial14'] < 1 && $_POST['retotal14'] < 1 )    { $num14="0"; }else{ $num14="1"; }
            $num15="0";  if ($_POST['infamilies15'] < 1 && $_POST['inpersons15'] < 1 && $_POST['oufamilies15'] < 1 && $_POST['oupersons15'] < 1  && $_POST['missing15'] < 1 && $_POST['injured15'] < 1 && $_POST['dead15'] < 1 && $_POST['repartial15'] < 1 && $_POST['retotal15'] < 1 )    { $num15="0"; }else{ $num15="1"; }
            $num16="0";  if ($_POST['infamilies16'] < 1 && $_POST['inpersons16'] < 1 && $_POST['oufamilies16'] < 1 && $_POST['oupersons16'] < 1  && $_POST['missing16'] < 1 && $_POST['injured16'] < 1 && $_POST['dead16'] < 1 && $_POST['repartial16'] < 1 && $_POST['retotal16'] < 1 )    { $num16="0"; }else{ $num16="1"; }
            $num17="0";  if ($_POST['infamilies17'] < 1 && $_POST['inpersons17'] < 1 && $_POST['oufamilies17'] < 1 && $_POST['oupersons17'] < 1  && $_POST['missing17'] < 1 && $_POST['injured17'] < 1 && $_POST['dead17'] < 1 && $_POST['repartial17'] < 1 && $_POST['retotal17'] < 1 )    { $num17="0"; }else{ $num17="1"; }
            $num18="0";  if ($_POST['infamilies18'] < 1 && $_POST['inpersons18'] < 1 && $_POST['oufamilies18'] < 1 && $_POST['oupersons18'] < 1  && $_POST['missing18'] < 1 && $_POST['injured18'] < 1 && $_POST['dead18'] < 1 && $_POST['repartial18'] < 1 && $_POST['retotal18'] < 1 )    { $num18="0"; }else{ $num18="1"; }
            $num19="0";  if ($_POST['infamilies19'] < 1 && $_POST['inpersons19'] < 1 && $_POST['oufamilies19'] < 1 && $_POST['oupersons19'] < 1  && $_POST['missing19'] < 1 && $_POST['injured19'] < 1 && $_POST['dead19'] < 1 && $_POST['repartial19'] < 1 && $_POST['retotal19'] < 1 )    { $num19="0"; }else{ $num19="1"; }
            $num20="0";  if ($_POST['infamilies20'] < 1 && $_POST['inpersons20'] < 1 && $_POST['oufamilies20'] < 1 && $_POST['oupersons20'] < 1  && $_POST['missing20'] < 1 && $_POST['injured20'] < 1 && $_POST['dead20'] < 1 && $_POST['repartial20'] < 1 && $_POST['retotal20'] < 1 )    { $num20="0"; }else{ $num20="1"; }
            $num21="0";  if ($_POST['infamilies21'] < 1 && $_POST['inpersons21'] < 1 && $_POST['oufamilies21'] < 1 && $_POST['oupersons21'] < 1  && $_POST['missing21'] < 1 && $_POST['injured21'] < 1 && $_POST['dead21'] < 1 && $_POST['repartial21'] < 1 && $_POST['retotal21'] < 1 )    { $num21="0"; }else{ $num21="1"; }
            $num22="0";  if ($_POST['infamilies22'] < 1 && $_POST['inpersons22'] < 1 && $_POST['oufamilies22'] < 1 && $_POST['oupersons22'] < 1  && $_POST['missing22'] < 1 && $_POST['injured22'] < 1 && $_POST['dead22'] < 1 && $_POST['repartial22'] < 1 && $_POST['retotal22'] < 1 )    { $num22="0"; }else{ $num22="1"; }
            $num23="0";  if ($_POST['infamilies23'] < 1 && $_POST['inpersons23'] < 1 && $_POST['oufamilies23'] < 1 && $_POST['oupersons23'] < 1  && $_POST['missing23'] < 1 && $_POST['injured23'] < 1 && $_POST['dead23'] < 1 && $_POST['repartial23'] < 1 && $_POST['retotal23'] < 1 )    { $num23="0"; }else{ $num23="1"; }
            $num24="0";  if ($_POST['infamilies24'] < 1 && $_POST['inpersons24'] < 1 && $_POST['oufamilies24'] < 1 && $_POST['oupersons24'] < 1  && $_POST['missing24'] < 1 && $_POST['injured24'] < 1 && $_POST['dead24'] < 1 && $_POST['repartial24'] < 1 && $_POST['retotal24'] < 1 )    { $num24="0"; }else{ $num24="1"; }
            //end old
            $num25="0";  if ($_POST['infamilies25'] < 1 && $_POST['inpersons25'] < 1 && $_POST['oufamilies25'] < 1 && $_POST['oupersons25'] < 1  && $_POST['missing25'] < 1 && $_POST['injured25'] < 1 && $_POST['dead25'] < 1 && $_POST['repartial25'] < 1 && $_POST['retotal25'] < 1 )    { $num25="0"; }else{ $num25="1"; }
            $num26="0";  if ($_POST['infamilies26'] < 1 && $_POST['inpersons26'] < 1 && $_POST['oufamilies26'] < 1 && $_POST['oupersons26'] < 1  && $_POST['missing26'] < 1 && $_POST['injured26'] < 1 && $_POST['dead26'] < 1 && $_POST['repartial26'] < 1 && $_POST['retotal26'] < 1 )    { $num26="0"; }else{ $num26="1"; }
            $num27="0";  if ($_POST['infamilies27'] < 1 && $_POST['inpersons27'] < 1 && $_POST['oufamilies27'] < 1 && $_POST['oupersons27'] < 1  && $_POST['missing27'] < 1 && $_POST['injured27'] < 1 && $_POST['dead27'] < 1 && $_POST['repartial27'] < 1 && $_POST['retotal27'] < 1 )    { $num27="0"; }else{ $num27="1"; }
            $num28="0";  if ($_POST['infamilies28'] < 1 && $_POST['inpersons28'] < 1 && $_POST['oufamilies28'] < 1 && $_POST['oupersons28'] < 1  && $_POST['missing28'] < 1 && $_POST['injured28'] < 1 && $_POST['dead28'] < 1 && $_POST['repartial28'] < 1 && $_POST['retotal28'] < 1 )    { $num28="0"; }else{ $num28="1"; }
            $num29="0";  if ($_POST['infamilies29'] < 1 && $_POST['inpersons29'] < 1 && $_POST['oufamilies29'] < 1 && $_POST['oupersons29'] < 1  && $_POST['missing29'] < 1 && $_POST['injured29'] < 1 && $_POST['dead29'] < 1 && $_POST['repartial29'] < 1 && $_POST['retotal29'] < 1 )    { $num29="0"; }else{ $num29="1"; }
            $num30="0";  if ($_POST['infamilies30'] < 1 && $_POST['inpersons30'] < 1 && $_POST['oufamilies30'] < 1 && $_POST['oupersons30'] < 1  && $_POST['missing30'] < 1 && $_POST['injured30'] < 1 && $_POST['dead30'] < 1 && $_POST['repartial30'] < 1 && $_POST['retotal30'] < 1 )    { $num30="0"; }else{ $num30="1"; }
            $num31="0";  if ($_POST['infamilies31'] < 1 && $_POST['inpersons31'] < 1 && $_POST['oufamilies31'] < 1 && $_POST['oupersons31'] < 1  && $_POST['missing31'] < 1 && $_POST['injured31'] < 1 && $_POST['dead31'] < 1 && $_POST['repartial31'] < 1 && $_POST['retotal31'] < 1 )    { $num31="0"; }else{ $num31="1"; }
            $num32="0";  if ($_POST['infamilies32'] < 1 && $_POST['inpersons32'] < 1 && $_POST['oufamilies32'] < 1 && $_POST['oupersons32'] < 1  && $_POST['missing32'] < 1 && $_POST['injured32'] < 1 && $_POST['dead32'] < 1 && $_POST['repartial32'] < 1 && $_POST['retotal32'] < 1 )    { $num32="0"; }else{ $num32="1"; }
            $num33="0";  if ($_POST['infamilies33'] < 1 && $_POST['inpersons33'] < 1 && $_POST['oufamilies33'] < 1 && $_POST['oupersons33'] < 1  && $_POST['missing33'] < 1 && $_POST['injured33'] < 1 && $_POST['dead33'] < 1 && $_POST['repartial33'] < 1 && $_POST['retotal33'] < 1 )    { $num33="0"; }else{ $num33="1"; }
            $num34="0";  if ($_POST['infamilies34'] < 1 && $_POST['inpersons34'] < 1 && $_POST['oufamilies34'] < 1 && $_POST['oupersons34'] < 1  && $_POST['missing34'] < 1 && $_POST['injured34'] < 1 && $_POST['dead34'] < 1 && $_POST['repartial34'] < 1 && $_POST['retotal34'] < 1 )    { $num34="0"; }else{ $num34="1"; }
            $num35="0";  if ($_POST['infamilies35'] < 1 && $_POST['inpersons35'] < 1 && $_POST['oufamilies35'] < 1 && $_POST['oupersons35'] < 1  && $_POST['missing35'] < 1 && $_POST['injured35'] < 1 && $_POST['dead35'] < 1 && $_POST['repartial35'] < 1 && $_POST['retotal35'] < 1 )    { $num35="0"; }else{ $num35="1"; }
            $num36="0";  if ($_POST['infamilies36'] < 1 && $_POST['inpersons36'] < 1 && $_POST['oufamilies36'] < 1 && $_POST['oupersons36'] < 1  && $_POST['missing36'] < 1 && $_POST['injured36'] < 1 && $_POST['dead36'] < 1 && $_POST['repartial36'] < 1 && $_POST['retotal36'] < 1 )    { $num36="0"; }else{ $num36="1"; }
            $num37="0";  if ($_POST['infamilies37'] < 1 && $_POST['inpersons37'] < 1 && $_POST['oufamilies37'] < 1 && $_POST['oupersons37'] < 1  && $_POST['missing37'] < 1 && $_POST['injured37'] < 1 && $_POST['dead37'] < 1 && $_POST['repartial37'] < 1 && $_POST['retotal37'] < 1 )    { $num37="0"; }else{ $num37="1"; }
            $num38="0";  if ($_POST['infamilies38'] < 1 && $_POST['inpersons38'] < 1 && $_POST['oufamilies38'] < 1 && $_POST['oupersons38'] < 1  && $_POST['missing38'] < 1 && $_POST['injured38'] < 1 && $_POST['dead38'] < 1 && $_POST['repartial38'] < 1 && $_POST['retotal38'] < 1 )    { $num38="0"; }else{ $num38="1"; }
            $num39="0";  if ($_POST['infamilies39'] < 1 && $_POST['inpersons39'] < 1 && $_POST['oufamilies39'] < 1 && $_POST['oupersons39'] < 1  && $_POST['missing39'] < 1 && $_POST['injured39'] < 1 && $_POST['dead39'] < 1 && $_POST['repartial39'] < 1 && $_POST['retotal39'] < 1 )    { $num39="0"; }else{ $num39="1"; }
            $num40="0";  if ($_POST['infamilies40'] < 1 && $_POST['inpersons40'] < 1 && $_POST['oufamilies40'] < 1 && $_POST['oupersons40'] < 1  && $_POST['missing40'] < 1 && $_POST['injured40'] < 1 && $_POST['dead40'] < 1 && $_POST['repartial40'] < 1 && $_POST['retotal40'] < 1 )    { $num40="0"; }else{ $num40="1"; }
            $num41="0";  if ($_POST['infamilies41'] < 1 && $_POST['inpersons41'] < 1 && $_POST['oufamilies41'] < 1 && $_POST['oupersons41'] < 1  && $_POST['missing41'] < 1 && $_POST['injured41'] < 1 && $_POST['dead41'] < 1 && $_POST['repartial41'] < 1 && $_POST['retotal41'] < 1 )    { $num41="0"; }else{ $num41="1"; }
            $num42="0";  if ($_POST['infamilies42'] < 1 && $_POST['inpersons42'] < 1 && $_POST['oufamilies42'] < 1 && $_POST['oupersons42'] < 1  && $_POST['missing42'] < 1 && $_POST['injured42'] < 1 && $_POST['dead42'] < 1 && $_POST['repartial42'] < 1 && $_POST['retotal42'] < 1 )    { $num42="0"; }else{ $num42="1"; }
            $num43="0";  if ($_POST['infamilies43'] < 1 && $_POST['inpersons43'] < 1 && $_POST['oufamilies43'] < 1 && $_POST['oupersons43'] < 1  && $_POST['missing43'] < 1 && $_POST['injured43'] < 1 && $_POST['dead43'] < 1 && $_POST['repartial43'] < 1 && $_POST['retotal43'] < 1 )    { $num43="0"; }else{ $num43="1"; }
            $num44="0";  if ($_POST['infamilies44'] < 1 && $_POST['inpersons44'] < 1 && $_POST['oufamilies44'] < 1 && $_POST['oupersons44'] < 1  && $_POST['missing44'] < 1 && $_POST['injured44'] < 1 && $_POST['dead44'] < 1 && $_POST['repartial44'] < 1 && $_POST['retotal44'] < 1 )    { $num44="0"; }else{ $num44="1"; }
            $num45="0";  if ($_POST['infamilies45'] < 1 && $_POST['inpersons45'] < 1 && $_POST['oufamilies45'] < 1 && $_POST['oupersons45'] < 1  && $_POST['missing45'] < 1 && $_POST['injured45'] < 1 && $_POST['dead45'] < 1 && $_POST['repartial45'] < 1 && $_POST['retotal45'] < 1 )    { $num45="0"; }else{ $num45="1"; } 
            $num46="0";  if ($_POST['infamilies46'] < 1 && $_POST['inpersons46'] < 1 && $_POST['oufamilies46'] < 1 && $_POST['oupersons46'] < 1  && $_POST['missing46'] < 1 && $_POST['injured46'] < 1 && $_POST['dead46'] < 1 && $_POST['repartial46'] < 1 && $_POST['retotal46'] < 1 )    { $num46="0"; }else{ $num46="1"; }
            $num47="0";  if ($_POST['infamilies47'] < 1 && $_POST['inpersons47'] < 1 && $_POST['oufamilies47'] < 1 && $_POST['oupersons47'] < 1  && $_POST['missing47'] < 1 && $_POST['injured47'] < 1 && $_POST['dead47'] < 1 && $_POST['repartial47'] < 1 && $_POST['retotal47'] < 1 )    { $num47="0"; }else{ $num47="1"; }
            $num48="0";  if ($_POST['infamilies48'] < 1 && $_POST['inpersons48'] < 1 && $_POST['oufamilies48'] < 1 && $_POST['oupersons48'] < 1  && $_POST['missing48'] < 1 && $_POST['injured48'] < 1 && $_POST['dead48'] < 1 && $_POST['repartial48'] < 1 && $_POST['retotal48'] < 1 )    { $num48="0"; }else{ $num48="1"; }
            $num49="0";  if ($_POST['infamilies49'] < 1 && $_POST['inpersons49'] < 1 && $_POST['oufamilies49'] < 1 && $_POST['oupersons49'] < 1  && $_POST['missing49'] < 1 && $_POST['injured49'] < 1 && $_POST['dead49'] < 1 && $_POST['repartial49'] < 1 && $_POST['retotal49'] < 1 )    { $num49="0"; }else{ $num49="1"; }
            $num50="0";  if ($_POST['infamilies50'] < 1 && $_POST['inpersons50'] < 1 && $_POST['oufamilies50'] < 1 && $_POST['oupersons50'] < 1  && $_POST['missing50'] < 1 && $_POST['injured50'] < 1 && $_POST['dead50'] < 1 && $_POST['repartial50'] < 1 && $_POST['retotal50'] < 1 )    { $num50="0"; }else{ $num50="1"; }
            $num51="0";  if ($_POST['infamilies51'] < 1 && $_POST['inpersons51'] < 1 && $_POST['oufamilies51'] < 1 && $_POST['oupersons51'] < 1  && $_POST['missing51'] < 1 && $_POST['injured51'] < 1 && $_POST['dead51'] < 1 && $_POST['repartial51'] < 1 && $_POST['retotal51'] < 1 )    { $num51="0"; }else{ $num51="1"; }
            $num52="0";  if ($_POST['infamilies52'] < 1 && $_POST['inpersons52'] < 1 && $_POST['oufamilies52'] < 1 && $_POST['oupersons52'] < 1  && $_POST['missing52'] < 1 && $_POST['injured52'] < 1 && $_POST['dead52'] < 1 && $_POST['repartial52'] < 1 && $_POST['retotal52'] < 1 )    { $num52="0"; }else{ $num52="1"; }
            $num53="0";  if ($_POST['infamilies53'] < 1 && $_POST['inpersons53'] < 1 && $_POST['oufamilies53'] < 1 && $_POST['oupersons53'] < 1  && $_POST['missing53'] < 1 && $_POST['injured53'] < 1 && $_POST['dead53'] < 1 && $_POST['repartial53'] < 1 && $_POST['retotal53'] < 1 )    { $num53="0"; }else{ $num53="1"; }
            $num54="0";  if ($_POST['infamilies54'] < 1 && $_POST['inpersons54'] < 1 && $_POST['oufamilies54'] < 1 && $_POST['oupersons54'] < 1  && $_POST['missing54'] < 1 && $_POST['injured54'] < 1 && $_POST['dead54'] < 1 && $_POST['repartial54'] < 1 && $_POST['retotal54'] < 1 )    { $num54="0"; }else{ $num54="1"; }
            $num55="0";  if ($_POST['infamilies55'] < 1 && $_POST['inpersons55'] < 1 && $_POST['oufamilies55'] < 1 && $_POST['oupersons55'] < 1  && $_POST['missing55'] < 1 && $_POST['injured55'] < 1 && $_POST['dead55'] < 1 && $_POST['repartial55'] < 1 && $_POST['retotal55'] < 1 )    { $num55="0"; }else{ $num55="1"; }
            $num56="0";  if ($_POST['infamilies56'] < 1 && $_POST['inpersons56'] < 1 && $_POST['oufamilies56'] < 1 && $_POST['oupersons56'] < 1  && $_POST['missing56'] < 1 && $_POST['injured56'] < 1 && $_POST['dead56'] < 1 && $_POST['repartial56'] < 1 && $_POST['retotal56'] < 1 )    { $num56="0"; }else{ $num56="1"; }
            $num57="0";  if ($_POST['infamilies57'] < 1 && $_POST['inpersons57'] < 1 && $_POST['oufamilies57'] < 1 && $_POST['oupersons57'] < 1  && $_POST['missing57'] < 1 && $_POST['injured57'] < 1 && $_POST['dead57'] < 1 && $_POST['repartial57'] < 1 && $_POST['retotal57'] < 1 )    { $num57="0"; }else{ $num57="1"; }
            $num58="0";  if ($_POST['infamilies58'] < 1 && $_POST['inpersons58'] < 1 && $_POST['oufamilies58'] < 1 && $_POST['oupersons58'] < 1  && $_POST['missing58'] < 1 && $_POST['injured58'] < 1 && $_POST['dead58'] < 1 && $_POST['repartial58'] < 1 && $_POST['retotal58'] < 1 )    { $num58="0"; }else{ $num58="1"; }
            $num59="0";  if ($_POST['infamilies59'] < 1 && $_POST['inpersons59'] < 1 && $_POST['oufamilies59'] < 1 && $_POST['oupersons59'] < 1  && $_POST['missing59'] < 1 && $_POST['injured59'] < 1 && $_POST['dead59'] < 1 && $_POST['repartial59'] < 1 && $_POST['retotal59'] < 1 )    { $num59="0"; }else{ $num59="1"; }
            $num60="0";  if ($_POST['infamilies60'] < 1 && $_POST['inpersons60'] < 1 && $_POST['oufamilies60'] < 1 && $_POST['oupersons60'] < 1  && $_POST['missing60'] < 1 && $_POST['injured60'] < 1 && $_POST['dead60'] < 1 && $_POST['repartial60'] < 1 && $_POST['retotal60'] < 1 )    { $num60="0"; }else{ $num60="1"; }
            $num61="0";  if ($_POST['infamilies61'] < 1 && $_POST['inpersons61'] < 1 && $_POST['oufamilies61'] < 1 && $_POST['oupersons61'] < 1  && $_POST['missing61'] < 1 && $_POST['injured61'] < 1 && $_POST['dead61'] < 1 && $_POST['repartial61'] < 1 && $_POST['retotal61'] < 1 )    { $num61="0"; }else{ $num61="1"; }
            $num62="0";  if ($_POST['infamilies62'] < 1 && $_POST['inpersons62'] < 1 && $_POST['oufamilies62'] < 1 && $_POST['oupersons62'] < 1  && $_POST['missing62'] < 1 && $_POST['injured62'] < 1 && $_POST['dead62'] < 1 && $_POST['repartial62'] < 1 && $_POST['retotal62'] < 1 )    { $num62="0"; }else{ $num62="1"; }
            $num63="0";  if ($_POST['infamilies63'] < 1 && $_POST['inpersons63'] < 1 && $_POST['oufamilies63'] < 1 && $_POST['oupersons63'] < 1  && $_POST['missing63'] < 1 && $_POST['injured63'] < 1 && $_POST['dead63'] < 1 && $_POST['repartial63'] < 1 && $_POST['retotal63'] < 1 )    { $num63="0"; }else{ $num63="1"; }
            try{
              if($_POST['tdatetime']==""){ } else{
                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name, :mun, :brgy, :num, :infamilies, :inpersons, :oufamilies, :oupersons, :evacu, :missing, :injured, :dead, :repartial, :retotal, :stat, :remarks, :tdatetime)");
                $stmt->bindParam(':typhoon_name', $tname);
                $stmt->bindParam(':mun', $mun);
                $stmt->bindParam(':brgy', $_POST['brgy']);
                $stmt->bindParam(':num', $num);
                $stmt->bindParam(':infamilies', $_POST['infamilies']);
                $stmt->bindParam(':inpersons', $_POST['inpersons']);
                $stmt->bindParam(':oufamilies', $_POST['oufamilies']);
                $stmt->bindParam(':oupersons', $_POST['oupersons']);
                $stmt->bindParam(':evacu', $_POST['evacu']);
                $stmt->bindParam(':missing', $_POST['missing']);
                $stmt->bindParam(':injured', $_POST['injured']);
                $stmt->bindParam(':dead', $_POST['dead']);
                $stmt->bindParam(':repartial', $_POST['repartial']);
                $stmt->bindParam(':retotal', $_POST['retotal']);
                $stmt->bindParam(':stat', $_POST['stat']);
                $stmt->bindParam(':remarks', $_POST['remarks']);
                $stmt->bindParam(':tdatetime', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name1, :mun1, :brgy1, :num1, :infamilies1, :inpersons1, :oufamilies1, :oupersons1, :evacu1, :missing1, :injured1, :dead1, :repartial1, :retotal1, :stat1, :remarks1, :tdatetime1)");
                $stmt->bindParam(':typhoon_name1', $tname);
                $stmt->bindParam(':mun1', $mun);
                $stmt->bindParam(':brgy1', $_POST['brgy1']);
                $stmt->bindParam(':num1', $num1);
                $stmt->bindParam(':infamilies1', $_POST['infamilies1']);
                $stmt->bindParam(':inpersons1', $_POST['inpersons1']);
                $stmt->bindParam(':oufamilies1', $_POST['oufamilies1']);
                $stmt->bindParam(':oupersons1', $_POST['oupersons1']);
                $stmt->bindParam(':evacu1', $_POST['evacu1']);
                $stmt->bindParam(':missing1', $_POST['missing1']);
                $stmt->bindParam(':injured1', $_POST['injured1']);
                $stmt->bindParam(':dead1', $_POST['dead1']);
                $stmt->bindParam(':repartial1', $_POST['repartial1']);
                $stmt->bindParam(':retotal1', $_POST['retotal1']);
                $stmt->bindParam(':stat1', $_POST['stat1']);
                $stmt->bindParam(':remarks1', $_POST['remarks1']);
                $stmt->bindParam(':tdatetime1', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name2, :mun2, :brgy2, :num2, :infamilies2, :inpersons2, :oufamilies2, :oupersons2, :evacu2, :missing2, :injured2, :dead2, :repartial2, :retotal2, :stat2, :remarks2, :tdatetime2)");
                $stmt->bindParam(':typhoon_name2', $tname);
                $stmt->bindParam(':mun2', $mun);
                $stmt->bindParam(':brgy2', $_POST['brgy2']);
                $stmt->bindParam(':num2', $num2);
                $stmt->bindParam(':infamilies2', $_POST['infamilies2']);
                $stmt->bindParam(':inpersons2', $_POST['inpersons2']);
                $stmt->bindParam(':oufamilies2', $_POST['oufamilies2']);
                $stmt->bindParam(':oupersons2', $_POST['oupersons2']);
                $stmt->bindParam(':evacu2', $_POST['evacu2']);
                $stmt->bindParam(':missing2', $_POST['missing2']);
                $stmt->bindParam(':injured2', $_POST['injured2']);
                $stmt->bindParam(':dead2', $_POST['dead2']);
                $stmt->bindParam(':repartial2', $_POST['repartial2']);
                $stmt->bindParam(':retotal2', $_POST['retotal2']);
                $stmt->bindParam(':stat2', $_POST['stat2']);
                $stmt->bindParam(':remarks2', $_POST['remarks2']);
                $stmt->bindParam(':tdatetime2', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name3, :mun3, :brgy3, :num3, :infamilies3, :inpersons3, :oufamilies3, :oupersons3, :evacu3, :missing3, :injured3, :dead3, :repartial3, :retotal3, :stat3, :remarks3, :tdatetime3)");
                $stmt->bindParam(':typhoon_name3', $tname);
                $stmt->bindParam(':mun3', $mun);
                $stmt->bindParam(':brgy3', $_POST['brgy3']);
                $stmt->bindParam(':num3', $num3);
                $stmt->bindParam(':infamilies3', $_POST['infamilies3']);
                $stmt->bindParam(':inpersons3', $_POST['inpersons3']);
                $stmt->bindParam(':oufamilies3', $_POST['oufamilies3']);
                $stmt->bindParam(':oupersons3', $_POST['oupersons3']);
                $stmt->bindParam(':evacu3', $_POST['evacu3']);
                $stmt->bindParam(':missing3', $_POST['missing3']);
                $stmt->bindParam(':injured3', $_POST['injured3']);
                $stmt->bindParam(':dead3', $_POST['dead3']);
                $stmt->bindParam(':repartial3', $_POST['repartial3']);
                $stmt->bindParam(':retotal3', $_POST['retotal3']);
                $stmt->bindParam(':stat3', $_POST['stat3']);
                $stmt->bindParam(':remarks3', $_POST['remarks3']);
                $stmt->bindParam(':tdatetime3', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name4, :mun4, :brgy4, :num4, :infamilies4, :inpersons4, :oufamilies4, :oupersons4, :evacu4, :missing4, :injured4, :dead4, :repartial4, :retotal4, :stat4, :remarks4, :tdatetime4)");
                $stmt->bindParam(':typhoon_name4', $tname);
                $stmt->bindParam(':mun4', $mun);
                $stmt->bindParam(':brgy4', $_POST['brgy4']);
                $stmt->bindParam(':num4', $num4);
                $stmt->bindParam(':infamilies4', $_POST['infamilies4']);
                $stmt->bindParam(':inpersons4', $_POST['inpersons4']);
                $stmt->bindParam(':oufamilies4', $_POST['oufamilies4']);
                $stmt->bindParam(':oupersons4', $_POST['oupersons4']);
                $stmt->bindParam(':evacu4', $_POST['evacu4']);
                $stmt->bindParam(':missing4', $_POST['missing4']);
                $stmt->bindParam(':injured4', $_POST['injured4']);
                $stmt->bindParam(':dead4', $_POST['dead4']);
                $stmt->bindParam(':repartial4', $_POST['repartial4']);
                $stmt->bindParam(':retotal4', $_POST['retotal4']);
                $stmt->bindParam(':stat4', $_POST['stat4']);
                $stmt->bindParam(':remarks4', $_POST['remarks4']);
                $stmt->bindParam(':tdatetime4', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name5, :mun5, :brgy5, :num5, :infamilies5, :inpersons5, :oufamilies5, :oupersons5, :evacu5, :missing5, :injured5, :dead5, :repartial5, :retotal5, :stat5, :remarks5, :tdatetime5)");
                $stmt->bindParam(':typhoon_name5', $tname);
                $stmt->bindParam(':mun5', $mun);
                $stmt->bindParam(':brgy5', $_POST['brgy5']);
                $stmt->bindParam(':num5', $num5);
                $stmt->bindParam(':infamilies5', $_POST['infamilies5']);
                $stmt->bindParam(':inpersons5', $_POST['inpersons5']);
                $stmt->bindParam(':oufamilies5', $_POST['oufamilies5']);
                $stmt->bindParam(':oupersons5', $_POST['oupersons5']);
                $stmt->bindParam(':evacu5', $_POST['evacu5']);
                $stmt->bindParam(':missing5', $_POST['missing5']);
                $stmt->bindParam(':injured5', $_POST['injured5']);
                $stmt->bindParam(':dead5', $_POST['dead5']);
                $stmt->bindParam(':repartial5', $_POST['repartial5']);
                $stmt->bindParam(':retotal5', $_POST['retotal5']);
                $stmt->bindParam(':stat5', $_POST['stat5']);
                $stmt->bindParam(':remarks5', $_POST['remarks5']);
                $stmt->bindParam(':tdatetime5', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name6, :mun6, :brgy6, :num6, :infamilies6, :inpersons6, :oufamilies6, :oupersons6, :evacu6, :missing6, :injured6, :dead6, :repartial6, :retotal6, :stat6, :remarks6, :tdatetime6)");
                $stmt->bindParam(':typhoon_name6', $tname);
                $stmt->bindParam(':mun6', $mun);
                $stmt->bindParam(':brgy6', $_POST['brgy6']);
                $stmt->bindParam(':num6', $num6);
                $stmt->bindParam(':infamilies6', $_POST['infamilies6']);
                $stmt->bindParam(':inpersons6', $_POST['inpersons6']);
                $stmt->bindParam(':oufamilies6', $_POST['oufamilies6']);
                $stmt->bindParam(':oupersons6', $_POST['oupersons6']);
                $stmt->bindParam(':evacu6', $_POST['evacu6']);
                $stmt->bindParam(':missing6', $_POST['missing6']);
                $stmt->bindParam(':injured6', $_POST['injured6']);
                $stmt->bindParam(':dead6', $_POST['dead6']);
                $stmt->bindParam(':repartial6', $_POST['repartial6']);
                $stmt->bindParam(':retotal6', $_POST['retotal6']);
                $stmt->bindParam(':stat6', $_POST['stat6']);
                $stmt->bindParam(':remarks6', $_POST['remarks6']);
                $stmt->bindParam(':tdatetime6', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name7, :mun7, :brgy7, :num7, :infamilies7, :inpersons7, :oufamilies7, :oupersons7, :evacu7, :missing7, :injured7, :dead7, :repartial7, :retotal7, :stat7, :remarks7, :tdatetime7)");
                $stmt->bindParam(':typhoon_name7', $tname);
                $stmt->bindParam(':mun7', $mun);
                $stmt->bindParam(':brgy7', $_POST['brgy7']);
                $stmt->bindParam(':num7', $num7);
                $stmt->bindParam(':infamilies7', $_POST['infamilies7']);
                $stmt->bindParam(':inpersons7', $_POST['inpersons7']);
                $stmt->bindParam(':oufamilies7', $_POST['oufamilies7']);
                $stmt->bindParam(':oupersons7', $_POST['oupersons7']);
                $stmt->bindParam(':evacu7', $_POST['evacu7']);
                $stmt->bindParam(':missing7', $_POST['missing7']);
                $stmt->bindParam(':injured7', $_POST['injured7']);
                $stmt->bindParam(':dead7', $_POST['dead7']);
                $stmt->bindParam(':repartial7', $_POST['repartial7']);
                $stmt->bindParam(':retotal7', $_POST['retotal7']);
                $stmt->bindParam(':stat7', $_POST['stat7']);
                $stmt->bindParam(':remarks7', $_POST['remarks7']);
                $stmt->bindParam(':tdatetime7', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name8, :mun8, :brgy8, :num8, :infamilies8, :inpersons8, :oufamilies8, :oupersons8, :evacu8, :missing8, :injured8, :dead8, :repartial8, :retotal8, :stat8, :remarks8, :tdatetime8)");
                $stmt->bindParam(':typhoon_name8', $tname);
                $stmt->bindParam(':mun8', $mun);
                $stmt->bindParam(':brgy8', $_POST['brgy8']);
                $stmt->bindParam(':num8', $num8);
                $stmt->bindParam(':infamilies8', $_POST['infamilies8']);
                $stmt->bindParam(':inpersons8', $_POST['inpersons8']);
                $stmt->bindParam(':oufamilies8', $_POST['oufamilies8']);
                $stmt->bindParam(':oupersons8', $_POST['oupersons8']);
                $stmt->bindParam(':evacu8', $_POST['evacu8']);
                $stmt->bindParam(':missing8', $_POST['missing8']);
                $stmt->bindParam(':injured8', $_POST['injured8']);
                $stmt->bindParam(':dead8', $_POST['dead8']);
                $stmt->bindParam(':repartial8', $_POST['repartial8']);
                $stmt->bindParam(':retotal8', $_POST['retotal8']);
                $stmt->bindParam(':stat8', $_POST['stat8']);
                $stmt->bindParam(':remarks8', $_POST['remarks8']);
                $stmt->bindParam(':tdatetime8', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name9, :mun9, :brgy9, :num9, :infamilies9, :inpersons9, :oufamilies9, :oupersons9, :evacu9, :missing9, :injured9, :dead9, :repartial9, :retotal9, :stat9, :remarks9, :tdatetime9)");
                $stmt->bindParam(':typhoon_name9', $tname);
                $stmt->bindParam(':mun9', $mun);
                $stmt->bindParam(':brgy9', $_POST['brgy9']);
                $stmt->bindParam(':num9', $num9);
                $stmt->bindParam(':infamilies9', $_POST['infamilies9']);
                $stmt->bindParam(':inpersons9', $_POST['inpersons9']);
                $stmt->bindParam(':oufamilies9', $_POST['oufamilies9']);
                $stmt->bindParam(':oupersons9', $_POST['oupersons9']);
                $stmt->bindParam(':evacu9', $_POST['evacu9']);
                $stmt->bindParam(':missing9', $_POST['missing9']);
                $stmt->bindParam(':injured9', $_POST['injured9']);
                $stmt->bindParam(':dead9', $_POST['dead9']);
                $stmt->bindParam(':repartial9', $_POST['repartial9']);
                $stmt->bindParam(':retotal9', $_POST['retotal9']);
                $stmt->bindParam(':stat9', $_POST['stat9']);
                $stmt->bindParam(':remarks9', $_POST['remarks9']);
                $stmt->bindParam(':tdatetime9', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name10, :mun10, :brgy10, :num10, :infamilies10, :inpersons10, :oufamilies10, :oupersons10, :evacu10, :missing10, :injured10, :dead10, :repartial10, :retotal10, :stat10, :remarks10, :tdatetime10)");
                $stmt->bindParam(':typhoon_name10', $tname);
                $stmt->bindParam(':mun10', $mun);
                $stmt->bindParam(':brgy10', $_POST['brgy10']);
                $stmt->bindParam(':num10', $num10);
                $stmt->bindParam(':infamilies10', $_POST['infamilies10']);
                $stmt->bindParam(':inpersons10', $_POST['inpersons10']);
                $stmt->bindParam(':oufamilies10', $_POST['oufamilies10']);
                $stmt->bindParam(':oupersons10', $_POST['oupersons10']);
                $stmt->bindParam(':evacu10', $_POST['evacu10']);
                $stmt->bindParam(':missing10', $_POST['missing10']);
                $stmt->bindParam(':injured10', $_POST['injured10']);
                $stmt->bindParam(':dead10', $_POST['dead10']);
                $stmt->bindParam(':repartial10', $_POST['repartial10']);
                $stmt->bindParam(':retotal10', $_POST['retotal10']);
                $stmt->bindParam(':stat10', $_POST['stat10']);
                $stmt->bindParam(':remarks10', $_POST['remarks10']);
                $stmt->bindParam(':tdatetime10', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name11, :mun11, :brgy11, :num11, :infamilies11, :inpersons11, :oufamilies11, :oupersons11, :evacu11, :missing11, :injured11, :dead11, :repartial11, :retotal11, :stat11, :remarks11, :tdatetime11)");
                $stmt->bindParam(':typhoon_name11', $tname);
                $stmt->bindParam(':mun11', $mun);
                $stmt->bindParam(':brgy11', $_POST['brgy11']);
                $stmt->bindParam(':num11', $num11);
                $stmt->bindParam(':infamilies11', $_POST['infamilies11']);
                $stmt->bindParam(':inpersons11', $_POST['inpersons11']);
                $stmt->bindParam(':oufamilies11', $_POST['oufamilies11']);
                $stmt->bindParam(':oupersons11', $_POST['oupersons11']);
                $stmt->bindParam(':evacu11', $_POST['evacu11']);
                $stmt->bindParam(':missing11', $_POST['missing11']);
                $stmt->bindParam(':injured11', $_POST['injured11']);
                $stmt->bindParam(':dead11', $_POST['dead11']);
                $stmt->bindParam(':repartial11', $_POST['repartial11']);
                $stmt->bindParam(':retotal11', $_POST['retotal11']);
                $stmt->bindParam(':stat11', $_POST['stat11']);
                $stmt->bindParam(':remarks11', $_POST['remarks11']);
                $stmt->bindParam(':tdatetime11', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name12, :mun12, :brgy12, :num12, :infamilies12, :inpersons12, :oufamilies12, :oupersons12, :evacu12, :missing12, :injured12, :dead12, :repartial12, :retotal12, :stat12, :remarks12, :tdatetime12)");
                $stmt->bindParam(':typhoon_name12', $tname);
                $stmt->bindParam(':mun12', $mun);
                $stmt->bindParam(':brgy12', $_POST['brgy12']);
                $stmt->bindParam(':num12', $num12);
                $stmt->bindParam(':infamilies12', $_POST['infamilies12']);
                $stmt->bindParam(':inpersons12', $_POST['inpersons12']);
                $stmt->bindParam(':oufamilies12', $_POST['oufamilies12']);
                $stmt->bindParam(':oupersons12', $_POST['oupersons12']);
                $stmt->bindParam(':evacu12', $_POST['evacu12']);
                $stmt->bindParam(':missing12', $_POST['missing12']);
                $stmt->bindParam(':injured12', $_POST['injured12']);
                $stmt->bindParam(':dead12', $_POST['dead12']);
                $stmt->bindParam(':repartial12', $_POST['repartial12']);
                $stmt->bindParam(':retotal12', $_POST['retotal12']);
                $stmt->bindParam(':stat12', $_POST['stat12']);
                $stmt->bindParam(':remarks12', $_POST['remarks12']);
                $stmt->bindParam(':tdatetime12', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name13, :mun13, :brgy13, :num13, :infamilies13, :inpersons13, :oufamilies13, :oupersons13, :evacu13, :missing13, :injured13, :dead13, :repartial13, :retotal13, :stat13, :remarks13, :tdatetime13)");
                $stmt->bindParam(':typhoon_name13', $tname);
                $stmt->bindParam(':mun13', $mun);
                $stmt->bindParam(':brgy13', $_POST['brgy13']);
                $stmt->bindParam(':num13', $num13);
                $stmt->bindParam(':infamilies13', $_POST['infamilies13']);
                $stmt->bindParam(':inpersons13', $_POST['inpersons13']);
                $stmt->bindParam(':oufamilies13', $_POST['oufamilies13']);
                $stmt->bindParam(':oupersons13', $_POST['oupersons13']);
                $stmt->bindParam(':evacu13', $_POST['evacu13']);
                $stmt->bindParam(':missing13', $_POST['missing13']);
                $stmt->bindParam(':injured13', $_POST['injured13']);
                $stmt->bindParam(':dead13', $_POST['dead13']);
                $stmt->bindParam(':repartial13', $_POST['repartial13']);
                $stmt->bindParam(':retotal13', $_POST['retotal13']);
                $stmt->bindParam(':stat13', $_POST['stat13']);
                $stmt->bindParam(':remarks13', $_POST['remarks13']);
                $stmt->bindParam(':tdatetime13', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name14, :mun14, :brgy14, :num14, :infamilies14, :inpersons14, :oufamilies14, :oupersons14, :evacu14, :missing14, :injured14, :dead14, :repartial14, :retotal14, :stat14, :remarks14, :tdatetime14)");
                $stmt->bindParam(':typhoon_name14', $tname);
                $stmt->bindParam(':mun14', $mun);
                $stmt->bindParam(':brgy14', $_POST['brgy14']);
                $stmt->bindParam(':num14', $num14);
                $stmt->bindParam(':infamilies14', $_POST['infamilies14']);
                $stmt->bindParam(':inpersons14', $_POST['inpersons14']);
                $stmt->bindParam(':oufamilies14', $_POST['oufamilies14']);
                $stmt->bindParam(':oupersons14', $_POST['oupersons14']);
                $stmt->bindParam(':evacu14', $_POST['evacu14']);
                $stmt->bindParam(':missing14', $_POST['missing14']);
                $stmt->bindParam(':injured14', $_POST['injured14']);
                $stmt->bindParam(':dead14', $_POST['dead14']);
                $stmt->bindParam(':repartial14', $_POST['repartial14']);
                $stmt->bindParam(':retotal14', $_POST['retotal14']);
                $stmt->bindParam(':stat14', $_POST['stat14']);
                $stmt->bindParam(':remarks14', $_POST['remarks14']);
                $stmt->bindParam(':tdatetime14', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name15, :mun15, :brgy15, :num15, :infamilies15, :inpersons15, :oufamilies15, :oupersons15, :evacu15, :missing15, :injured15, :dead15, :repartial15, :retotal15, :stat15, :remarks15, :tdatetime15)");
                $stmt->bindParam(':typhoon_name15', $tname);
                $stmt->bindParam(':mun15', $mun);
                $stmt->bindParam(':brgy15', $_POST['brgy15']);
                $stmt->bindParam(':num15', $num15);
                $stmt->bindParam(':infamilies15', $_POST['infamilies15']);
                $stmt->bindParam(':inpersons15', $_POST['inpersons15']);
                $stmt->bindParam(':oufamilies15', $_POST['oufamilies15']);
                $stmt->bindParam(':oupersons15', $_POST['oupersons15']);
                $stmt->bindParam(':evacu15', $_POST['evacu15']);
                $stmt->bindParam(':missing15', $_POST['missing15']);
                $stmt->bindParam(':injured15', $_POST['injured15']);
                $stmt->bindParam(':dead15', $_POST['dead15']);
                $stmt->bindParam(':repartial15', $_POST['repartial15']);
                $stmt->bindParam(':retotal15', $_POST['retotal15']);
                $stmt->bindParam(':stat15', $_POST['stat15']);
                $stmt->bindParam(':remarks15', $_POST['remarks15']);
                $stmt->bindParam(':tdatetime15', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name16, :mun16, :brgy16, :num16, :infamilies16, :inpersons16, :oufamilies16, :oupersons16, :evacu16, :missing16, :injured16, :dead16, :repartial16, :retotal16, :stat16, :remarks16, :tdatetime16)");
                $stmt->bindParam(':typhoon_name16', $tname);
                $stmt->bindParam(':mun16', $mun);
                $stmt->bindParam(':brgy16', $_POST['brgy16']);
                $stmt->bindParam(':num16', $num16);
                $stmt->bindParam(':infamilies16', $_POST['infamilies16']);
                $stmt->bindParam(':inpersons16', $_POST['inpersons16']);
                $stmt->bindParam(':oufamilies16', $_POST['oufamilies16']);
                $stmt->bindParam(':oupersons16', $_POST['oupersons16']);
                $stmt->bindParam(':evacu16', $_POST['evacu16']);
                $stmt->bindParam(':missing16', $_POST['missing16']);
                $stmt->bindParam(':injured16', $_POST['injured16']);
                $stmt->bindParam(':dead16', $_POST['dead16']);
                $stmt->bindParam(':repartial16', $_POST['repartial16']);
                $stmt->bindParam(':retotal16', $_POST['retotal16']);
                $stmt->bindParam(':stat16', $_POST['stat16']);
                $stmt->bindParam(':remarks16', $_POST['remarks16']);
                $stmt->bindParam(':tdatetime16', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name17, :mun17, :brgy17, :num17, :infamilies17, :inpersons17, :oufamilies17, :oupersons17, :evacu17, :missing17, :injured17, :dead17, :repartial17, :retotal17, :stat17, :remarks17, :tdatetime17)");
                $stmt->bindParam(':typhoon_name17', $tname);
                $stmt->bindParam(':mun17', $mun);
                $stmt->bindParam(':brgy17', $_POST['brgy17']);
                $stmt->bindParam(':num17', $num17);
                $stmt->bindParam(':infamilies17', $_POST['infamilies17']);
                $stmt->bindParam(':inpersons17', $_POST['inpersons17']);
                $stmt->bindParam(':oufamilies17', $_POST['oufamilies17']);
                $stmt->bindParam(':oupersons17', $_POST['oupersons17']);
                $stmt->bindParam(':evacu17', $_POST['evacu17']);
                $stmt->bindParam(':missing17', $_POST['missing17']);
                $stmt->bindParam(':injured17', $_POST['injured17']);
                $stmt->bindParam(':dead17', $_POST['dead17']);
                $stmt->bindParam(':repartial17', $_POST['repartial17']);
                $stmt->bindParam(':retotal17', $_POST['retotal17']);
                $stmt->bindParam(':stat17', $_POST['stat17']);
                $stmt->bindParam(':remarks17', $_POST['remarks17']);
                $stmt->bindParam(':tdatetime17', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name18, :mun18, :brgy18, :num18, :infamilies18, :inpersons18, :oufamilies18, :oupersons18, :evacu18, :missing18, :injured18, :dead18, :repartial18, :retotal18, :stat18, :remarks18, :tdatetime18)");
                $stmt->bindParam(':typhoon_name18', $tname);
                $stmt->bindParam(':mun18', $mun);
                $stmt->bindParam(':brgy18', $_POST['brgy18']);
                $stmt->bindParam(':num18', $num18);
                $stmt->bindParam(':infamilies18', $_POST['infamilies18']);
                $stmt->bindParam(':inpersons18', $_POST['inpersons18']);
                $stmt->bindParam(':oufamilies18', $_POST['oufamilies18']);
                $stmt->bindParam(':oupersons18', $_POST['oupersons18']);
                $stmt->bindParam(':evacu18', $_POST['evacu18']);
                $stmt->bindParam(':missing18', $_POST['missing18']);
                $stmt->bindParam(':injured18', $_POST['injured18']);
                $stmt->bindParam(':dead18', $_POST['dead18']);
                $stmt->bindParam(':repartial18', $_POST['repartial18']);
                $stmt->bindParam(':retotal18', $_POST['retotal18']);
                $stmt->bindParam(':stat18', $_POST['stat18']);
                $stmt->bindParam(':remarks18', $_POST['remarks18']);
                $stmt->bindParam(':tdatetime18', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name19, :mun19, :brgy19, :num19, :infamilies19, :inpersons19, :oufamilies19, :oupersons19, :evacu19, :missing19, :injured19, :dead19, :repartial19, :retotal19, :stat19, :remarks19, :tdatetime19)");
                $stmt->bindParam(':typhoon_name19', $tname);
                $stmt->bindParam(':mun19', $mun);
                $stmt->bindParam(':brgy19', $_POST['brgy19']);
                $stmt->bindParam(':num19', $num19);
                $stmt->bindParam(':infamilies19', $_POST['infamilies19']);
                $stmt->bindParam(':inpersons19', $_POST['inpersons19']);
                $stmt->bindParam(':oufamilies19', $_POST['oufamilies19']);
                $stmt->bindParam(':oupersons19', $_POST['oupersons19']);
                $stmt->bindParam(':evacu19', $_POST['evacu19']);
                $stmt->bindParam(':missing19', $_POST['missing19']);
                $stmt->bindParam(':injured19', $_POST['injured19']);
                $stmt->bindParam(':dead19', $_POST['dead19']);
                $stmt->bindParam(':repartial19', $_POST['repartial19']);
                $stmt->bindParam(':retotal19', $_POST['retotal19']);
                $stmt->bindParam(':stat19', $_POST['stat19']);
                $stmt->bindParam(':remarks19', $_POST['remarks19']);
                $stmt->bindParam(':tdatetime19', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name20, :mun20, :brgy20, :num20, :infamilies20, :inpersons20, :oufamilies20, :oupersons20, :evacu20, :missing20, :injured20, :dead20, :repartial20, :retotal20, :stat20, :remarks20, :tdatetime20)");
                $stmt->bindParam(':typhoon_name20', $tname);
                $stmt->bindParam(':mun20', $mun);
                $stmt->bindParam(':brgy20', $_POST['brgy20']);
                $stmt->bindParam(':num20', $num20);
                $stmt->bindParam(':infamilies20', $_POST['infamilies20']);
                $stmt->bindParam(':inpersons20', $_POST['inpersons20']);
                $stmt->bindParam(':oufamilies20', $_POST['oufamilies20']);
                $stmt->bindParam(':oupersons20', $_POST['oupersons20']);
                $stmt->bindParam(':evacu20', $_POST['evacu20']);
                $stmt->bindParam(':missing20', $_POST['missing20']);
                $stmt->bindParam(':injured20', $_POST['injured20']);
                $stmt->bindParam(':dead20', $_POST['dead20']);
                $stmt->bindParam(':repartial20', $_POST['repartial20']);
                $stmt->bindParam(':retotal20', $_POST['retotal20']);
                $stmt->bindParam(':stat20', $_POST['stat20']);
                $stmt->bindParam(':remarks20', $_POST['remarks20']);
                $stmt->bindParam(':tdatetime20', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name21, :mun21, :brgy21, :num21, :infamilies21, :inpersons21, :oufamilies21, :oupersons21, :evacu21, :missing21, :injured21, :dead21, :repartial21, :retotal21, :stat21, :remarks21, :tdatetime21)");
                $stmt->bindParam(':typhoon_name21', $tname);
                $stmt->bindParam(':mun21', $mun);
                $stmt->bindParam(':brgy21', $_POST['brgy21']);
                $stmt->bindParam(':num21', $num21);
                $stmt->bindParam(':infamilies21', $_POST['infamilies21']);
                $stmt->bindParam(':inpersons21', $_POST['inpersons21']);
                $stmt->bindParam(':oufamilies21', $_POST['oufamilies21']);
                $stmt->bindParam(':oupersons21', $_POST['oupersons21']);
                $stmt->bindParam(':evacu21', $_POST['evacu21']);
                $stmt->bindParam(':missing21', $_POST['missing21']);
                $stmt->bindParam(':injured21', $_POST['injured21']);
                $stmt->bindParam(':dead21', $_POST['dead21']);
                $stmt->bindParam(':repartial21', $_POST['repartial21']);
                $stmt->bindParam(':retotal21', $_POST['retotal21']);
                $stmt->bindParam(':stat21', $_POST['stat21']);
                $stmt->bindParam(':remarks21', $_POST['remarks21']);
                $stmt->bindParam(':tdatetime21', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name22, :mun22, :brgy22, :num22, :infamilies22, :inpersons22, :oufamilies22, :oupersons22, :evacu22, :missing22, :injured22, :dead22, :repartial22, :retotal22, :stat22, :remarks22, :tdatetime22)");
                $stmt->bindParam(':typhoon_name22', $tname);
                $stmt->bindParam(':mun22', $mun);
                $stmt->bindParam(':brgy22', $_POST['brgy22']);
                $stmt->bindParam(':num22', $num22);
                $stmt->bindParam(':infamilies22', $_POST['infamilies22']);
                $stmt->bindParam(':inpersons22', $_POST['inpersons22']);
                $stmt->bindParam(':oufamilies22', $_POST['oufamilies22']);
                $stmt->bindParam(':oupersons22', $_POST['oupersons22']);
                $stmt->bindParam(':evacu22', $_POST['evacu22']);
                $stmt->bindParam(':missing22', $_POST['missing22']);
                $stmt->bindParam(':injured22', $_POST['injured22']);
                $stmt->bindParam(':dead22', $_POST['dead22']);
                $stmt->bindParam(':repartial22', $_POST['repartial22']);
                $stmt->bindParam(':retotal22', $_POST['retotal22']);
                $stmt->bindParam(':stat22', $_POST['stat22']);
                $stmt->bindParam(':remarks22', $_POST['remarks22']);
                $stmt->bindParam(':tdatetime22', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name23, :mun23, :brgy23, :num23, :infamilies23, :inpersons23, :oufamilies23, :oupersons23, :evacu23, :missing23, :injured23, :dead23, :repartial23, :retotal23, :stat23, :remarks23, :tdatetime23)");
                $stmt->bindParam(':typhoon_name23', $tname);
                $stmt->bindParam(':mun23', $mun);
                $stmt->bindParam(':brgy23', $_POST['brgy23']);
                $stmt->bindParam(':num23', $num23);
                $stmt->bindParam(':infamilies23', $_POST['infamilies23']);
                $stmt->bindParam(':inpersons23', $_POST['inpersons23']);
                $stmt->bindParam(':oufamilies23', $_POST['oufamilies23']);
                $stmt->bindParam(':oupersons23', $_POST['oupersons23']);
                $stmt->bindParam(':evacu23', $_POST['evacu23']);
                $stmt->bindParam(':missing23', $_POST['missing23']);
                $stmt->bindParam(':injured23', $_POST['injured23']);
                $stmt->bindParam(':dead23', $_POST['dead23']);
                $stmt->bindParam(':repartial23', $_POST['repartial23']);
                $stmt->bindParam(':retotal23', $_POST['retotal23']);
                $stmt->bindParam(':stat23', $_POST['stat23']);
                $stmt->bindParam(':remarks23', $_POST['remarks23']);
                $stmt->bindParam(':tdatetime23', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name24, :mun24, :brgy24, :num24, :infamilies24, :inpersons24, :oufamilies24, :oupersons24, :evacu24, :missing24, :injured24, :dead24, :repartial24, :retotal24, :stat24, :remarks24, :tdatetime24)");
                $stmt->bindParam(':typhoon_name24', $tname);
                $stmt->bindParam(':mun24', $mun);
                $stmt->bindParam(':brgy24', $_POST['brgy24']);
                $stmt->bindParam(':num24', $num24);
                $stmt->bindParam(':infamilies24', $_POST['infamilies24']);
                $stmt->bindParam(':inpersons24', $_POST['inpersons24']);
                $stmt->bindParam(':oufamilies24', $_POST['oufamilies24']);
                $stmt->bindParam(':oupersons24', $_POST['oupersons24']);
                $stmt->bindParam(':evacu24', $_POST['evacu24']);
                $stmt->bindParam(':missing24', $_POST['missing24']);
                $stmt->bindParam(':injured24', $_POST['injured24']);
                $stmt->bindParam(':dead24', $_POST['dead24']);
                $stmt->bindParam(':repartial24', $_POST['repartial24']);
                $stmt->bindParam(':retotal24', $_POST['retotal24']);
                $stmt->bindParam(':stat24', $_POST['stat24']);
                $stmt->bindParam(':remarks24', $_POST['remarks24']);
                $stmt->bindParam(':tdatetime24', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name25, :mun25, :brgy25, :num25, :infamilies25, :inpersons25, :oufamilies25, :oupersons25, :evacu25, :missing25, :injured25, :dead25, :repartial25, :retotal25, :stat25, :remarks25, :tdatetime25)");
                $stmt->bindParam(':typhoon_name25', $tname);
                $stmt->bindParam(':mun25', $mun);
                $stmt->bindParam(':brgy25', $_POST['brgy25']);
                $stmt->bindParam(':num25', $num25);
                $stmt->bindParam(':infamilies25', $_POST['infamilies25']);
                $stmt->bindParam(':inpersons25', $_POST['inpersons25']);
                $stmt->bindParam(':oufamilies25', $_POST['oufamilies25']);
                $stmt->bindParam(':oupersons25', $_POST['oupersons25']);
                $stmt->bindParam(':evacu25', $_POST['evacu25']);
                $stmt->bindParam(':missing25', $_POST['missing25']);
                $stmt->bindParam(':injured25', $_POST['injured25']);
                $stmt->bindParam(':dead25', $_POST['dead25']);
                $stmt->bindParam(':repartial25', $_POST['repartial25']);
                $stmt->bindParam(':retotal25', $_POST['retotal25']);
                $stmt->bindParam(':stat25', $_POST['stat25']);
                $stmt->bindParam(':remarks25', $_POST['remarks25']);
                $stmt->bindParam(':tdatetime25', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name26, :mun26, :brgy26, :num26, :infamilies26, :inpersons26, :oufamilies26, :oupersons26, :evacu26, :missing26, :injured26, :dead26, :repartial26, :retotal26, :stat26, :remarks26, :tdatetime26)");
                $stmt->bindParam(':typhoon_name26', $tname);
                $stmt->bindParam(':mun26', $mun);
                $stmt->bindParam(':brgy26', $_POST['brgy26']);
                $stmt->bindParam(':num26', $num26);
                $stmt->bindParam(':infamilies26', $_POST['infamilies26']);
                $stmt->bindParam(':inpersons26', $_POST['inpersons26']);
                $stmt->bindParam(':oufamilies26', $_POST['oufamilies26']);
                $stmt->bindParam(':oupersons26', $_POST['oupersons26']);
                $stmt->bindParam(':evacu26', $_POST['evacu26']);
                $stmt->bindParam(':missing26', $_POST['missing26']);
                $stmt->bindParam(':injured26', $_POST['injured26']);
                $stmt->bindParam(':dead26', $_POST['dead26']);
                $stmt->bindParam(':repartial26', $_POST['repartial26']);
                $stmt->bindParam(':retotal26', $_POST['retotal26']);
                $stmt->bindParam(':stat26', $_POST['stat26']);
                $stmt->bindParam(':remarks26', $_POST['remarks26']);
                $stmt->bindParam(':tdatetime26', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name27, :mun27, :brgy27, :num27, :infamilies27, :inpersons27, :oufamilies27, :oupersons27, :evacu27, :missing27, :injured27, :dead27, :repartial27, :retotal27, :stat27, :remarks27, :tdatetime27)");
                $stmt->bindParam(':typhoon_name27', $tname);
                $stmt->bindParam(':mun27', $mun);
                $stmt->bindParam(':brgy27', $_POST['brgy27']);
                $stmt->bindParam(':num27', $num27);
                $stmt->bindParam(':infamilies27', $_POST['infamilies27']);
                $stmt->bindParam(':inpersons27', $_POST['inpersons27']);
                $stmt->bindParam(':oufamilies27', $_POST['oufamilies27']);
                $stmt->bindParam(':oupersons27', $_POST['oupersons27']);
                $stmt->bindParam(':evacu27', $_POST['evacu27']);
                $stmt->bindParam(':missing27', $_POST['missing27']);
                $stmt->bindParam(':injured27', $_POST['injured27']);
                $stmt->bindParam(':dead27', $_POST['dead27']);
                $stmt->bindParam(':repartial27', $_POST['repartial27']);
                $stmt->bindParam(':retotal27', $_POST['retotal27']);
                $stmt->bindParam(':stat27', $_POST['stat27']);
                $stmt->bindParam(':remarks27', $_POST['remarks27']);
                $stmt->bindParam(':tdatetime27', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name28, :mun28, :brgy28, :num28, :infamilies28, :inpersons28, :oufamilies28, :oupersons28, :evacu28, :missing28, :injured28, :dead28, :repartial28, :retotal28, :stat28, :remarks28, :tdatetime28)");
                $stmt->bindParam(':typhoon_name28', $tname);
                $stmt->bindParam(':mun28', $mun);
                $stmt->bindParam(':brgy28', $_POST['brgy28']);
                $stmt->bindParam(':num28', $num28);
                $stmt->bindParam(':infamilies28', $_POST['infamilies28']);
                $stmt->bindParam(':inpersons28', $_POST['inpersons28']);
                $stmt->bindParam(':oufamilies28', $_POST['oufamilies28']);
                $stmt->bindParam(':oupersons28', $_POST['oupersons28']);
                $stmt->bindParam(':evacu28', $_POST['evacu28']);
                $stmt->bindParam(':missing28', $_POST['missing28']);
                $stmt->bindParam(':injured28', $_POST['injured28']);
                $stmt->bindParam(':dead28', $_POST['dead28']);
                $stmt->bindParam(':repartial28', $_POST['repartial28']);
                $stmt->bindParam(':retotal28', $_POST['retotal28']);
                $stmt->bindParam(':stat28', $_POST['stat28']);
                $stmt->bindParam(':remarks28', $_POST['remarks28']);
                $stmt->bindParam(':tdatetime28', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name29, :mun29, :brgy29, :num29, :infamilies29, :inpersons29, :oufamilies29, :oupersons29, :evacu29, :missing29, :injured29, :dead29, :repartial29, :retotal29, :stat29, :remarks29, :tdatetime29)");
                $stmt->bindParam(':typhoon_name29', $tname);
                $stmt->bindParam(':mun29', $mun);
                $stmt->bindParam(':brgy29', $_POST['brgy29']);
                $stmt->bindParam(':num29', $num29);
                $stmt->bindParam(':infamilies29', $_POST['infamilies29']);
                $stmt->bindParam(':inpersons29', $_POST['inpersons29']);
                $stmt->bindParam(':oufamilies29', $_POST['oufamilies29']);
                $stmt->bindParam(':oupersons29', $_POST['oupersons29']);
                $stmt->bindParam(':evacu29', $_POST['evacu29']);
                $stmt->bindParam(':missing29', $_POST['missing29']);
                $stmt->bindParam(':injured29', $_POST['injured29']);
                $stmt->bindParam(':dead29', $_POST['dead29']);
                $stmt->bindParam(':repartial29', $_POST['repartial29']);
                $stmt->bindParam(':retotal29', $_POST['retotal29']);
                $stmt->bindParam(':stat29', $_POST['stat29']);
                $stmt->bindParam(':remarks29', $_POST['remarks29']);
                $stmt->bindParam(':tdatetime29', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name30, :mun30, :brgy30, :num30, :infamilies30, :inpersons30, :oufamilies30, :oupersons30, :evacu30, :missing30, :injured30, :dead30, :repartial30, :retotal30, :stat30, :remarks30, :tdatetime30)");
                $stmt->bindParam(':typhoon_name30', $tname);
                $stmt->bindParam(':mun30', $mun);
                $stmt->bindParam(':brgy30', $_POST['brgy30']);
                $stmt->bindParam(':num30', $num30);
                $stmt->bindParam(':infamilies30', $_POST['infamilies30']);
                $stmt->bindParam(':inpersons30', $_POST['inpersons30']);
                $stmt->bindParam(':oufamilies30', $_POST['oufamilies30']);
                $stmt->bindParam(':oupersons30', $_POST['oupersons30']);
                $stmt->bindParam(':evacu30', $_POST['evacu30']);
                $stmt->bindParam(':missing30', $_POST['missing30']);
                $stmt->bindParam(':injured30', $_POST['injured30']);
                $stmt->bindParam(':dead30', $_POST['dead30']);
                $stmt->bindParam(':repartial30', $_POST['repartial30']);
                $stmt->bindParam(':retotal30', $_POST['retotal30']);
                $stmt->bindParam(':stat30', $_POST['stat30']);
                $stmt->bindParam(':remarks30', $_POST['remarks30']);
                $stmt->bindParam(':tdatetime30', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name31, :mun31, :brgy31, :num31, :infamilies31, :inpersons31, :oufamilies31, :oupersons31, :evacu31, :missing31, :injured31, :dead31, :repartial31, :retotal31, :stat31, :remarks31, :tdatetime31)");
                $stmt->bindParam(':typhoon_name31', $tname);
                $stmt->bindParam(':mun31', $mun);
                $stmt->bindParam(':brgy31', $_POST['brgy31']);
                $stmt->bindParam(':num31', $num31);
                $stmt->bindParam(':infamilies31', $_POST['infamilies31']);
                $stmt->bindParam(':inpersons31', $_POST['inpersons31']);
                $stmt->bindParam(':oufamilies31', $_POST['oufamilies31']);
                $stmt->bindParam(':oupersons31', $_POST['oupersons31']);
                $stmt->bindParam(':evacu31', $_POST['evacu31']);
                $stmt->bindParam(':missing31', $_POST['missing31']);
                $stmt->bindParam(':injured31', $_POST['injured31']);
                $stmt->bindParam(':dead31', $_POST['dead31']);
                $stmt->bindParam(':repartial31', $_POST['repartial31']);
                $stmt->bindParam(':retotal31', $_POST['retotal31']);
                $stmt->bindParam(':stat31', $_POST['stat31']);
                $stmt->bindParam(':remarks31', $_POST['remarks31']);
                $stmt->bindParam(':tdatetime31', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name32, :mun32, :brgy32, :num32, :infamilies32, :inpersons32, :oufamilies32, :oupersons32, :evacu32, :missing32, :injured32, :dead32, :repartial32, :retotal32, :stat32, :remarks32, :tdatetime32)");
                $stmt->bindParam(':typhoon_name32', $tname);
                $stmt->bindParam(':mun32', $mun);
                $stmt->bindParam(':brgy32', $_POST['brgy32']);
                $stmt->bindParam(':num32', $num32);
                $stmt->bindParam(':infamilies32', $_POST['infamilies32']);
                $stmt->bindParam(':inpersons32', $_POST['inpersons32']);
                $stmt->bindParam(':oufamilies32', $_POST['oufamilies32']);
                $stmt->bindParam(':oupersons32', $_POST['oupersons32']);
                $stmt->bindParam(':evacu32', $_POST['evacu32']);
                $stmt->bindParam(':missing32', $_POST['missing32']);
                $stmt->bindParam(':injured32', $_POST['injured32']);
                $stmt->bindParam(':dead32', $_POST['dead32']);
                $stmt->bindParam(':repartial32', $_POST['repartial32']);
                $stmt->bindParam(':retotal32', $_POST['retotal32']);
                $stmt->bindParam(':stat32', $_POST['stat32']);
                $stmt->bindParam(':remarks32', $_POST['remarks32']);
                $stmt->bindParam(':tdatetime32', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name33, :mun33, :brgy33, :num33, :infamilies33, :inpersons33, :oufamilies33, :oupersons33, :evacu33, :missing33, :injured33, :dead33, :repartial33, :retotal33, :stat33, :remarks33, :tdatetime33)");
                $stmt->bindParam(':typhoon_name33', $tname);
                $stmt->bindParam(':mun33', $mun);
                $stmt->bindParam(':brgy33', $_POST['brgy33']);
                $stmt->bindParam(':num33', $num33);
                $stmt->bindParam(':infamilies33', $_POST['infamilies33']);
                $stmt->bindParam(':inpersons33', $_POST['inpersons33']);
                $stmt->bindParam(':oufamilies33', $_POST['oufamilies33']);
                $stmt->bindParam(':oupersons33', $_POST['oupersons33']);
                $stmt->bindParam(':evacu33', $_POST['evacu33']);
                $stmt->bindParam(':missing33', $_POST['missing33']);
                $stmt->bindParam(':injured33', $_POST['injured33']);
                $stmt->bindParam(':dead33', $_POST['dead33']);
                $stmt->bindParam(':repartial33', $_POST['repartial33']);
                $stmt->bindParam(':retotal33', $_POST['retotal33']);
                $stmt->bindParam(':stat33', $_POST['stat33']);
                $stmt->bindParam(':remarks33', $_POST['remarks33']);
                $stmt->bindParam(':tdatetime33', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name34, :mun34, :brgy34, :num34, :infamilies34, :inpersons34, :oufamilies34, :oupersons34, :evacu34, :missing34, :injured34, :dead34, :repartial34, :retotal34, :stat34, :remarks34, :tdatetime34)");
                $stmt->bindParam(':typhoon_name34', $tname);
                $stmt->bindParam(':mun34', $mun);
                $stmt->bindParam(':brgy34', $_POST['brgy34']);
                $stmt->bindParam(':num34', $num34);
                $stmt->bindParam(':infamilies34', $_POST['infamilies34']);
                $stmt->bindParam(':inpersons34', $_POST['inpersons34']);
                $stmt->bindParam(':oufamilies34', $_POST['oufamilies34']);
                $stmt->bindParam(':oupersons34', $_POST['oupersons34']);
                $stmt->bindParam(':evacu34', $_POST['evacu34']);
                $stmt->bindParam(':missing34', $_POST['missing34']);
                $stmt->bindParam(':injured34', $_POST['injured34']);
                $stmt->bindParam(':dead34', $_POST['dead34']);
                $stmt->bindParam(':repartial34', $_POST['repartial34']);
                $stmt->bindParam(':retotal34', $_POST['retotal34']);
                $stmt->bindParam(':stat34', $_POST['stat34']);
                $stmt->bindParam(':remarks34', $_POST['remarks34']);
                $stmt->bindParam(':tdatetime34', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name35, :mun35, :brgy35, :num35, :infamilies35, :inpersons35, :oufamilies35, :oupersons35, :evacu35, :missing35, :injured35, :dead35, :repartial35, :retotal35, :stat35, :remarks35, :tdatetime35)");
                $stmt->bindParam(':typhoon_name35', $tname);
                $stmt->bindParam(':mun35', $mun);
                $stmt->bindParam(':brgy35', $_POST['brgy35']);
                $stmt->bindParam(':num35', $num35);
                $stmt->bindParam(':infamilies35', $_POST['infamilies35']);
                $stmt->bindParam(':inpersons35', $_POST['inpersons35']);
                $stmt->bindParam(':oufamilies35', $_POST['oufamilies35']);
                $stmt->bindParam(':oupersons35', $_POST['oupersons35']);
                $stmt->bindParam(':evacu35', $_POST['evacu35']);
                $stmt->bindParam(':missing35', $_POST['missing35']);
                $stmt->bindParam(':injured35', $_POST['injured35']);
                $stmt->bindParam(':dead35', $_POST['dead35']);
                $stmt->bindParam(':repartial35', $_POST['repartial35']);
                $stmt->bindParam(':retotal35', $_POST['retotal35']);
                $stmt->bindParam(':stat35', $_POST['stat35']);
                $stmt->bindParam(':remarks35', $_POST['remarks35']);
                $stmt->bindParam(':tdatetime35', $_POST['tdatetime']);
                $stmt->execute();

                // ==============================================================================
                // ==============================================================================
                // ==============================================================================
                // ==============================================================================
                // ==============================================================================
                // ==============================================================================


                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name36, :mun36, :brgy36, :num36, :infamilies36, :inpersons36, :oufamilies36, :oupersons36, :evacu36, :missing36, :injured36, :dead36, :repartial36, :retotal36, :stat36, :remarks36, :tdatetime36)");
                $stmt->bindParam(':typhoon_name36', $tname);
                $stmt->bindParam(':mun36', $mun_b);
                $stmt->bindParam(':brgy36', $_POST['brgy36']);
                $stmt->bindParam(':num36', $num36);
                $stmt->bindParam(':infamilies36', $_POST['infamilies36']);
                $stmt->bindParam(':inpersons36', $_POST['inpersons36']);
                $stmt->bindParam(':oufamilies36', $_POST['oufamilies36']);
                $stmt->bindParam(':oupersons36', $_POST['oupersons36']);
                $stmt->bindParam(':evacu36', $_POST['evacu36']);
                $stmt->bindParam(':missing36', $_POST['missing36']);
                $stmt->bindParam(':injured36', $_POST['injured36']);
                $stmt->bindParam(':dead36', $_POST['dead36']);
                $stmt->bindParam(':repartial36', $_POST['repartial36']);
                $stmt->bindParam(':retotal36', $_POST['retotal36']);
                $stmt->bindParam(':stat36', $_POST['stat36']);
                $stmt->bindParam(':remarks36', $_POST['remarks36']);
                $stmt->bindParam(':tdatetime36', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name37, :mun37, :brgy37, :num37, :infamilies37, :inpersons37, :oufamilies37, :oupersons37, :evacu37, :missing37, :injured37, :dead37, :repartial37, :retotal37, :stat37, :remarks37, :tdatetime37)");
                $stmt->bindParam(':typhoon_name37', $tname);
                $stmt->bindParam(':mun37', $mun_b);
                $stmt->bindParam(':brgy37', $_POST['brgy37']);
                $stmt->bindParam(':num37', $num37);
                $stmt->bindParam(':infamilies37', $_POST['infamilies37']);
                $stmt->bindParam(':inpersons37', $_POST['inpersons37']);
                $stmt->bindParam(':oufamilies37', $_POST['oufamilies37']);
                $stmt->bindParam(':oupersons37', $_POST['oupersons37']);
                $stmt->bindParam(':evacu37', $_POST['evacu37']);
                $stmt->bindParam(':missing37', $_POST['missing37']);
                $stmt->bindParam(':injured37', $_POST['injured37']);
                $stmt->bindParam(':dead37', $_POST['dead37']);
                $stmt->bindParam(':repartial37', $_POST['repartial37']);
                $stmt->bindParam(':retotal37', $_POST['retotal37']);
                $stmt->bindParam(':stat37', $_POST['stat37']);
                $stmt->bindParam(':remarks37', $_POST['remarks37']);
                $stmt->bindParam(':tdatetime37', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name38, :mun38, :brgy38, :num38, :infamilies38, :inpersons38, :oufamilies38, :oupersons38, :evacu38, :missing38, :injured38, :dead38, :repartial38, :retotal38, :stat38, :remarks38, :tdatetime38)");
                $stmt->bindParam(':typhoon_name38', $tname);
                $stmt->bindParam(':mun38', $mun_b);
                $stmt->bindParam(':brgy38', $_POST['brgy38']);
                $stmt->bindParam(':num38', $num38);
                $stmt->bindParam(':infamilies38', $_POST['infamilies38']);
                $stmt->bindParam(':inpersons38', $_POST['inpersons38']);
                $stmt->bindParam(':oufamilies38', $_POST['oufamilies38']);
                $stmt->bindParam(':oupersons38', $_POST['oupersons38']);
                $stmt->bindParam(':evacu38', $_POST['evacu38']);
                $stmt->bindParam(':missing38', $_POST['missing38']);
                $stmt->bindParam(':injured38', $_POST['injured38']);
                $stmt->bindParam(':dead38', $_POST['dead38']);
                $stmt->bindParam(':repartial38', $_POST['repartial38']);
                $stmt->bindParam(':retotal38', $_POST['retotal38']);
                $stmt->bindParam(':stat38', $_POST['stat38']);
                $stmt->bindParam(':remarks38', $_POST['remarks38']);
                $stmt->bindParam(':tdatetime38', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name39, :mun39, :brgy39, :num39, :infamilies39, :inpersons39, :oufamilies39, :oupersons39, :evacu39, :missing39, :injured39, :dead39, :repartial39, :retotal39, :stat39, :remarks39, :tdatetime39)");
                $stmt->bindParam(':typhoon_name39', $tname);
                $stmt->bindParam(':mun39', $mun_b);
                $stmt->bindParam(':brgy39', $_POST['brgy39']);
                $stmt->bindParam(':num39', $num39);
                $stmt->bindParam(':infamilies39', $_POST['infamilies39']);
                $stmt->bindParam(':inpersons39', $_POST['inpersons39']);
                $stmt->bindParam(':oufamilies39', $_POST['oufamilies39']);
                $stmt->bindParam(':oupersons39', $_POST['oupersons39']);
                $stmt->bindParam(':evacu39', $_POST['evacu39']);
                $stmt->bindParam(':missing39', $_POST['missing39']);
                $stmt->bindParam(':injured39', $_POST['injured39']);
                $stmt->bindParam(':dead39', $_POST['dead39']);
                $stmt->bindParam(':repartial39', $_POST['repartial39']);
                $stmt->bindParam(':retotal39', $_POST['retotal39']);
                $stmt->bindParam(':stat39', $_POST['stat39']);
                $stmt->bindParam(':remarks39', $_POST['remarks39']);
                $stmt->bindParam(':tdatetime39', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name40, :mun40, :brgy40, :num40, :infamilies40, :inpersons40, :oufamilies40, :oupersons40, :evacu40, :missing40, :injured40, :dead40, :repartial40, :retotal40, :stat40, :remarks40, :tdatetime40)");
                $stmt->bindParam(':typhoon_name40', $tname);
                $stmt->bindParam(':mun40', $mun_b);
                $stmt->bindParam(':brgy40', $_POST['brgy40']);
                $stmt->bindParam(':num40', $num40);
                $stmt->bindParam(':infamilies40', $_POST['infamilies40']);
                $stmt->bindParam(':inpersons40', $_POST['inpersons40']);
                $stmt->bindParam(':oufamilies40', $_POST['oufamilies40']);
                $stmt->bindParam(':oupersons40', $_POST['oupersons40']);
                $stmt->bindParam(':evacu40', $_POST['evacu40']);
                $stmt->bindParam(':missing40', $_POST['missing40']);
                $stmt->bindParam(':injured40', $_POST['injured40']);
                $stmt->bindParam(':dead40', $_POST['dead40']);
                $stmt->bindParam(':repartial40', $_POST['repartial40']);
                $stmt->bindParam(':retotal40', $_POST['retotal40']);
                $stmt->bindParam(':stat40', $_POST['stat40']);
                $stmt->bindParam(':remarks40', $_POST['remarks40']);
                $stmt->bindParam(':tdatetime40', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name41, :mun41, :brgy41, :num41, :infamilies41, :inpersons41, :oufamilies41, :oupersons41, :evacu41, :missing41, :injured41, :dead41, :repartial41, :retotal41, :stat41, :remarks41, :tdatetime41)");
                $stmt->bindParam(':typhoon_name41', $tname);
                $stmt->bindParam(':mun41', $mun_b);
                $stmt->bindParam(':brgy41', $_POST['brgy41']);
                $stmt->bindParam(':num41', $num41);
                $stmt->bindParam(':infamilies41', $_POST['infamilies41']);
                $stmt->bindParam(':inpersons41', $_POST['inpersons41']);
                $stmt->bindParam(':oufamilies41', $_POST['oufamilies41']);
                $stmt->bindParam(':oupersons41', $_POST['oupersons41']);
                $stmt->bindParam(':evacu41', $_POST['evacu41']);
                $stmt->bindParam(':missing41', $_POST['missing41']);
                $stmt->bindParam(':injured41', $_POST['injured41']);
                $stmt->bindParam(':dead41', $_POST['dead41']);
                $stmt->bindParam(':repartial41', $_POST['repartial41']);
                $stmt->bindParam(':retotal41', $_POST['retotal41']);
                $stmt->bindParam(':stat41', $_POST['stat41']);
                $stmt->bindParam(':remarks41', $_POST['remarks41']);
                $stmt->bindParam(':tdatetime41', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name42, :mun42, :brgy42, :num42, :infamilies42, :inpersons42, :oufamilies42, :oupersons42, :evacu42, :missing42, :injured42, :dead42, :repartial42, :retotal42, :stat42, :remarks42, :tdatetime42)");
                $stmt->bindParam(':typhoon_name42', $tname);
                $stmt->bindParam(':mun42', $mun_b);
                $stmt->bindParam(':brgy42', $_POST['brgy42']);
                $stmt->bindParam(':num42', $num42);
                $stmt->bindParam(':infamilies42', $_POST['infamilies42']);
                $stmt->bindParam(':inpersons42', $_POST['inpersons42']);
                $stmt->bindParam(':oufamilies42', $_POST['oufamilies42']);
                $stmt->bindParam(':oupersons42', $_POST['oupersons42']);
                $stmt->bindParam(':evacu42', $_POST['evacu42']);
                $stmt->bindParam(':missing42', $_POST['missing42']);
                $stmt->bindParam(':injured42', $_POST['injured42']);
                $stmt->bindParam(':dead42', $_POST['dead42']);
                $stmt->bindParam(':repartial42', $_POST['repartial42']);
                $stmt->bindParam(':retotal42', $_POST['retotal42']);
                $stmt->bindParam(':stat42', $_POST['stat42']);
                $stmt->bindParam(':remarks42', $_POST['remarks42']);
                $stmt->bindParam(':tdatetime42', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name43, :mun43, :brgy43, :num43, :infamilies43, :inpersons43, :oufamilies43, :oupersons43, :evacu43, :missing43, :injured43, :dead43, :repartial43, :retotal43, :stat43, :remarks43, :tdatetime43)");
                $stmt->bindParam(':typhoon_name43', $tname);
                $stmt->bindParam(':mun43', $mun_b);
                $stmt->bindParam(':brgy43', $_POST['brgy43']);
                $stmt->bindParam(':num43', $num43);
                $stmt->bindParam(':infamilies43', $_POST['infamilies43']);
                $stmt->bindParam(':inpersons43', $_POST['inpersons43']);
                $stmt->bindParam(':oufamilies43', $_POST['oufamilies43']);
                $stmt->bindParam(':oupersons43', $_POST['oupersons43']);
                $stmt->bindParam(':evacu43', $_POST['evacu43']);
                $stmt->bindParam(':missing43', $_POST['missing43']);
                $stmt->bindParam(':injured43', $_POST['injured43']);
                $stmt->bindParam(':dead43', $_POST['dead43']);
                $stmt->bindParam(':repartial43', $_POST['repartial43']);
                $stmt->bindParam(':retotal43', $_POST['retotal43']);
                $stmt->bindParam(':stat43', $_POST['stat43']);
                $stmt->bindParam(':remarks43', $_POST['remarks43']);
                $stmt->bindParam(':tdatetime43', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name44, :mun44, :brgy44, :num44, :infamilies44, :inpersons44, :oufamilies44, :oupersons44, :evacu44, :missing44, :injured44, :dead44, :repartial44, :retotal44, :stat44, :remarks44, :tdatetime44)");
                $stmt->bindParam(':typhoon_name44', $tname);
                $stmt->bindParam(':mun44', $mun_b);
                $stmt->bindParam(':brgy44', $_POST['brgy44']);
                $stmt->bindParam(':num44', $num44);
                $stmt->bindParam(':infamilies44', $_POST['infamilies44']);
                $stmt->bindParam(':inpersons44', $_POST['inpersons44']);
                $stmt->bindParam(':oufamilies44', $_POST['oufamilies44']);
                $stmt->bindParam(':oupersons44', $_POST['oupersons44']);
                $stmt->bindParam(':evacu44', $_POST['evacu44']);
                $stmt->bindParam(':missing44', $_POST['missing44']);
                $stmt->bindParam(':injured44', $_POST['injured44']);
                $stmt->bindParam(':dead44', $_POST['dead44']);
                $stmt->bindParam(':repartial44', $_POST['repartial44']);
                $stmt->bindParam(':retotal44', $_POST['retotal44']);
                $stmt->bindParam(':stat44', $_POST['stat44']);
                $stmt->bindParam(':remarks44', $_POST['remarks44']);
                $stmt->bindParam(':tdatetime44', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name45, :mun45, :brgy45, :num45, :infamilies45, :inpersons45, :oufamilies45, :oupersons45, :evacu45, :missing45, :injured45, :dead45, :repartial45, :retotal45, :stat45, :remarks45, :tdatetime45)");
                $stmt->bindParam(':typhoon_name45', $tname);
                $stmt->bindParam(':mun45', $mun_b);
                $stmt->bindParam(':brgy45', $_POST['brgy45']);
                $stmt->bindParam(':num45', $num45);
                $stmt->bindParam(':infamilies45', $_POST['infamilies45']);
                $stmt->bindParam(':inpersons45', $_POST['inpersons45']);
                $stmt->bindParam(':oufamilies45', $_POST['oufamilies45']);
                $stmt->bindParam(':oupersons45', $_POST['oupersons45']);
                $stmt->bindParam(':evacu45', $_POST['evacu45']);
                $stmt->bindParam(':missing45', $_POST['missing45']);
                $stmt->bindParam(':injured45', $_POST['injured45']);
                $stmt->bindParam(':dead45', $_POST['dead45']);
                $stmt->bindParam(':repartial45', $_POST['repartial45']);
                $stmt->bindParam(':retotal45', $_POST['retotal45']);
                $stmt->bindParam(':stat45', $_POST['stat45']);
                $stmt->bindParam(':remarks45', $_POST['remarks45']);
                $stmt->bindParam(':tdatetime45', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name46, :mun46, :brgy46, :num46, :infamilies46, :inpersons46, :oufamilies46, :oupersons46, :evacu46, :missing46, :injured46, :dead46, :repartial46, :retotal46, :stat46, :remarks46, :tdatetime46)");
                $stmt->bindParam(':typhoon_name46', $tname);
                $stmt->bindParam(':mun46', $mun_b);
                $stmt->bindParam(':brgy46', $_POST['brgy46']);
                $stmt->bindParam(':num46', $num46);
                $stmt->bindParam(':infamilies46', $_POST['infamilies46']);
                $stmt->bindParam(':inpersons46', $_POST['inpersons46']);
                $stmt->bindParam(':oufamilies46', $_POST['oufamilies46']);
                $stmt->bindParam(':oupersons46', $_POST['oupersons46']);
                $stmt->bindParam(':evacu46', $_POST['evacu46']);
                $stmt->bindParam(':missing46', $_POST['missing46']);
                $stmt->bindParam(':injured46', $_POST['injured46']);
                $stmt->bindParam(':dead46', $_POST['dead46']);
                $stmt->bindParam(':repartial46', $_POST['repartial46']);
                $stmt->bindParam(':retotal46', $_POST['retotal46']);
                $stmt->bindParam(':stat46', $_POST['stat46']);
                $stmt->bindParam(':remarks46', $_POST['remarks46']);
                $stmt->bindParam(':tdatetime46', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name47, :mun47, :brgy47, :num47, :infamilies47, :inpersons47, :oufamilies47, :oupersons47, :evacu47, :missing47, :injured47, :dead47, :repartial47, :retotal47, :stat47, :remarks47, :tdatetime47)");
                $stmt->bindParam(':typhoon_name47', $tname);
                $stmt->bindParam(':mun47', $mun_b);
                $stmt->bindParam(':brgy47', $_POST['brgy47']);
                $stmt->bindParam(':num47', $num47);
                $stmt->bindParam(':infamilies47', $_POST['infamilies47']);
                $stmt->bindParam(':inpersons47', $_POST['inpersons47']);
                $stmt->bindParam(':oufamilies47', $_POST['oufamilies47']);
                $stmt->bindParam(':oupersons47', $_POST['oupersons47']);
                $stmt->bindParam(':evacu47', $_POST['evacu47']);
                $stmt->bindParam(':missing47', $_POST['missing47']);
                $stmt->bindParam(':injured47', $_POST['injured47']);
                $stmt->bindParam(':dead47', $_POST['dead47']);
                $stmt->bindParam(':repartial47', $_POST['repartial47']);
                $stmt->bindParam(':retotal47', $_POST['retotal47']);
                $stmt->bindParam(':stat47', $_POST['stat47']);
                $stmt->bindParam(':remarks47', $_POST['remarks47']);
                $stmt->bindParam(':tdatetime47', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name48, :mun48, :brgy48, :num48, :infamilies48, :inpersons48, :oufamilies48, :oupersons48, :evacu48, :missing48, :injured48, :dead48, :repartial48, :retotal48, :stat48, :remarks48, :tdatetime48)");
                $stmt->bindParam(':typhoon_name48', $tname);
                $stmt->bindParam(':mun48', $mun_b);
                $stmt->bindParam(':brgy48', $_POST['brgy48']);
                $stmt->bindParam(':num48', $num48);
                $stmt->bindParam(':infamilies48', $_POST['infamilies48']);
                $stmt->bindParam(':inpersons48', $_POST['inpersons48']);
                $stmt->bindParam(':oufamilies48', $_POST['oufamilies48']);
                $stmt->bindParam(':oupersons48', $_POST['oupersons48']);
                $stmt->bindParam(':evacu48', $_POST['evacu48']);
                $stmt->bindParam(':missing48', $_POST['missing48']);
                $stmt->bindParam(':injured48', $_POST['injured48']);
                $stmt->bindParam(':dead48', $_POST['dead48']);
                $stmt->bindParam(':repartial48', $_POST['repartial48']);
                $stmt->bindParam(':retotal48', $_POST['retotal48']);
                $stmt->bindParam(':stat48', $_POST['stat48']);
                $stmt->bindParam(':remarks48', $_POST['remarks48']);
                $stmt->bindParam(':tdatetime48', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name49, :mun49, :brgy49, :num49, :infamilies49, :inpersons49, :oufamilies49, :oupersons49, :evacu49, :missing49, :injured49, :dead49, :repartial49, :retotal49, :stat49, :remarks49, :tdatetime49)");
                $stmt->bindParam(':typhoon_name49', $tname);
                $stmt->bindParam(':mun49', $mun_b);
                $stmt->bindParam(':brgy49', $_POST['brgy49']);
                $stmt->bindParam(':num49', $num49);
                $stmt->bindParam(':infamilies49', $_POST['infamilies49']);
                $stmt->bindParam(':inpersons49', $_POST['inpersons49']);
                $stmt->bindParam(':oufamilies49', $_POST['oufamilies49']);
                $stmt->bindParam(':oupersons49', $_POST['oupersons49']);
                $stmt->bindParam(':evacu49', $_POST['evacu49']);
                $stmt->bindParam(':missing49', $_POST['missing49']);
                $stmt->bindParam(':injured49', $_POST['injured49']);
                $stmt->bindParam(':dead49', $_POST['dead49']);
                $stmt->bindParam(':repartial49', $_POST['repartial49']);
                $stmt->bindParam(':retotal49', $_POST['retotal49']);
                $stmt->bindParam(':stat49', $_POST['stat49']);
                $stmt->bindParam(':remarks49', $_POST['remarks49']);
                $stmt->bindParam(':tdatetime49', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name50, :mun50, :brgy50, :num50, :infamilies50, :inpersons50, :oufamilies50, :oupersons50, :evacu50, :missing50, :injured50, :dead50, :repartial50, :retotal50, :stat50, :remarks50, :tdatetime50)");
                $stmt->bindParam(':typhoon_name50', $tname);
                $stmt->bindParam(':mun50', $mun_b);
                $stmt->bindParam(':brgy50', $_POST['brgy50']);
                $stmt->bindParam(':num50', $num50);
                $stmt->bindParam(':infamilies50', $_POST['infamilies50']);
                $stmt->bindParam(':inpersons50', $_POST['inpersons50']);
                $stmt->bindParam(':oufamilies50', $_POST['oufamilies50']);
                $stmt->bindParam(':oupersons50', $_POST['oupersons50']);
                $stmt->bindParam(':evacu50', $_POST['evacu50']);
                $stmt->bindParam(':missing50', $_POST['missing50']);
                $stmt->bindParam(':injured50', $_POST['injured50']);
                $stmt->bindParam(':dead50', $_POST['dead50']);
                $stmt->bindParam(':repartial50', $_POST['repartial50']);
                $stmt->bindParam(':retotal50', $_POST['retotal50']);
                $stmt->bindParam(':stat50', $_POST['stat50']);
                $stmt->bindParam(':remarks50', $_POST['remarks50']);
                $stmt->bindParam(':tdatetime50', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name51, :mun51, :brgy51, :num51, :infamilies51, :inpersons51, :oufamilies51, :oupersons51, :evacu51, :missing51, :injured51, :dead51, :repartial51, :retotal51, :stat51, :remarks51, :tdatetime51)");
                $stmt->bindParam(':typhoon_name51', $tname);
                $stmt->bindParam(':mun51', $mun_b);
                $stmt->bindParam(':brgy51', $_POST['brgy51']);
                $stmt->bindParam(':num51', $num51);
                $stmt->bindParam(':infamilies51', $_POST['infamilies51']);
                $stmt->bindParam(':inpersons51', $_POST['inpersons51']);
                $stmt->bindParam(':oufamilies51', $_POST['oufamilies51']);
                $stmt->bindParam(':oupersons51', $_POST['oupersons51']);
                $stmt->bindParam(':evacu51', $_POST['evacu51']);
                $stmt->bindParam(':missing51', $_POST['missing51']);
                $stmt->bindParam(':injured51', $_POST['injured51']);
                $stmt->bindParam(':dead51', $_POST['dead51']);
                $stmt->bindParam(':repartial51', $_POST['repartial51']);
                $stmt->bindParam(':retotal51', $_POST['retotal51']);
                $stmt->bindParam(':stat51', $_POST['stat51']);
                $stmt->bindParam(':remarks51', $_POST['remarks51']);
                $stmt->bindParam(':tdatetime51', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name52, :mun52, :brgy52, :num52, :infamilies52, :inpersons52, :oufamilies52, :oupersons52, :evacu52, :missing52, :injured52, :dead52, :repartial52, :retotal52, :stat52, :remarks52, :tdatetime52)");
                $stmt->bindParam(':typhoon_name52', $tname);
                $stmt->bindParam(':mun52', $mun_b);
                $stmt->bindParam(':brgy52', $_POST['brgy52']);
                $stmt->bindParam(':num52', $num52);
                $stmt->bindParam(':infamilies52', $_POST['infamilies52']);
                $stmt->bindParam(':inpersons52', $_POST['inpersons52']);
                $stmt->bindParam(':oufamilies52', $_POST['oufamilies52']);
                $stmt->bindParam(':oupersons52', $_POST['oupersons52']);
                $stmt->bindParam(':evacu52', $_POST['evacu52']);
                $stmt->bindParam(':missing52', $_POST['missing52']);
                $stmt->bindParam(':injured52', $_POST['injured52']);
                $stmt->bindParam(':dead52', $_POST['dead52']);
                $stmt->bindParam(':repartial52', $_POST['repartial52']);
                $stmt->bindParam(':retotal52', $_POST['retotal52']);
                $stmt->bindParam(':stat52', $_POST['stat52']);
                $stmt->bindParam(':remarks52', $_POST['remarks52']);
                $stmt->bindParam(':tdatetime52', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name53, :mun53, :brgy53, :num53, :infamilies53, :inpersons53, :oufamilies53, :oupersons53, :evacu53, :missing53, :injured53, :dead53, :repartial53, :retotal53, :stat53, :remarks53, :tdatetime53)");
                $stmt->bindParam(':typhoon_name53', $tname);
                $stmt->bindParam(':mun53', $mun_b);
                $stmt->bindParam(':brgy53', $_POST['brgy53']);
                $stmt->bindParam(':num53', $num53);
                $stmt->bindParam(':infamilies53', $_POST['infamilies53']);
                $stmt->bindParam(':inpersons53', $_POST['inpersons53']);
                $stmt->bindParam(':oufamilies53', $_POST['oufamilies53']);
                $stmt->bindParam(':oupersons53', $_POST['oupersons53']);
                $stmt->bindParam(':evacu53', $_POST['evacu53']);
                $stmt->bindParam(':missing53', $_POST['missing53']);
                $stmt->bindParam(':injured53', $_POST['injured53']);
                $stmt->bindParam(':dead53', $_POST['dead53']);
                $stmt->bindParam(':repartial53', $_POST['repartial53']);
                $stmt->bindParam(':retotal53', $_POST['retotal53']);
                $stmt->bindParam(':stat53', $_POST['stat53']);
                $stmt->bindParam(':remarks53', $_POST['remarks53']);
                $stmt->bindParam(':tdatetime53', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name54, :mun54, :brgy54, :num54, :infamilies54, :inpersons54, :oufamilies54, :oupersons54, :evacu54, :missing54, :injured54, :dead54, :repartial54, :retotal54, :stat54, :remarks54, :tdatetime54)");
                $stmt->bindParam(':typhoon_name54', $tname);
                $stmt->bindParam(':mun54', $mun_b);
                $stmt->bindParam(':brgy54', $_POST['brgy54']);
                $stmt->bindParam(':num54', $num54);
                $stmt->bindParam(':infamilies54', $_POST['infamilies54']);
                $stmt->bindParam(':inpersons54', $_POST['inpersons54']);
                $stmt->bindParam(':oufamilies54', $_POST['oufamilies54']);
                $stmt->bindParam(':oupersons54', $_POST['oupersons54']);
                $stmt->bindParam(':evacu54', $_POST['evacu54']);
                $stmt->bindParam(':missing54', $_POST['missing54']);
                $stmt->bindParam(':injured54', $_POST['injured54']);
                $stmt->bindParam(':dead54', $_POST['dead54']);
                $stmt->bindParam(':repartial54', $_POST['repartial54']);
                $stmt->bindParam(':retotal54', $_POST['retotal54']);
                $stmt->bindParam(':stat54', $_POST['stat54']);
                $stmt->bindParam(':remarks54', $_POST['remarks54']);
                $stmt->bindParam(':tdatetime54', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name55, :mun55, :brgy55, :num55, :infamilies55, :inpersons55, :oufamilies55, :oupersons55, :evacu55, :missing55, :injured55, :dead55, :repartial55, :retotal55, :stat55, :remarks55, :tdatetime55)");
                $stmt->bindParam(':typhoon_name55', $tname);
                $stmt->bindParam(':mun55', $mun_b);
                $stmt->bindParam(':brgy55', $_POST['brgy55']);
                $stmt->bindParam(':num55', $num55);
                $stmt->bindParam(':infamilies55', $_POST['infamilies55']);
                $stmt->bindParam(':inpersons55', $_POST['inpersons55']);
                $stmt->bindParam(':oufamilies55', $_POST['oufamilies55']);
                $stmt->bindParam(':oupersons55', $_POST['oupersons55']);
                $stmt->bindParam(':evacu55', $_POST['evacu55']);
                $stmt->bindParam(':missing55', $_POST['missing55']);
                $stmt->bindParam(':injured55', $_POST['injured55']);
                $stmt->bindParam(':dead55', $_POST['dead55']);
                $stmt->bindParam(':repartial55', $_POST['repartial55']);
                $stmt->bindParam(':retotal55', $_POST['retotal55']);
                $stmt->bindParam(':stat55', $_POST['stat55']);
                $stmt->bindParam(':remarks55', $_POST['remarks55']);
                $stmt->bindParam(':tdatetime55', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name56, :mun56, :brgy56, :num56, :infamilies56, :inpersons56, :oufamilies56, :oupersons56, :evacu56, :missing56, :injured56, :dead56, :repartial56, :retotal56, :stat56, :remarks56, :tdatetime56)");
                $stmt->bindParam(':typhoon_name56', $tname);
                $stmt->bindParam(':mun56', $mun_b);
                $stmt->bindParam(':brgy56', $_POST['brgy56']);
                $stmt->bindParam(':num56', $num56);
                $stmt->bindParam(':infamilies56', $_POST['infamilies56']);
                $stmt->bindParam(':inpersons56', $_POST['inpersons56']);
                $stmt->bindParam(':oufamilies56', $_POST['oufamilies56']);
                $stmt->bindParam(':oupersons56', $_POST['oupersons56']);
                $stmt->bindParam(':evacu56', $_POST['evacu56']);
                $stmt->bindParam(':missing56', $_POST['missing56']);
                $stmt->bindParam(':injured56', $_POST['injured56']);
                $stmt->bindParam(':dead56', $_POST['dead56']);
                $stmt->bindParam(':repartial56', $_POST['repartial56']);
                $stmt->bindParam(':retotal56', $_POST['retotal56']);
                $stmt->bindParam(':stat56', $_POST['stat56']);
                $stmt->bindParam(':remarks56', $_POST['remarks56']);
                $stmt->bindParam(':tdatetime56', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name57, :mun57, :brgy57, :num57, :infamilies57, :inpersons57, :oufamilies57, :oupersons57, :evacu57, :missing57, :injured57, :dead57, :repartial57, :retotal57, :stat57, :remarks57, :tdatetime57)");
                $stmt->bindParam(':typhoon_name57', $tname);
                $stmt->bindParam(':mun57', $mun_b);
                $stmt->bindParam(':brgy57', $_POST['brgy57']);
                $stmt->bindParam(':num57', $num57);
                $stmt->bindParam(':infamilies57', $_POST['infamilies57']);
                $stmt->bindParam(':inpersons57', $_POST['inpersons57']);
                $stmt->bindParam(':oufamilies57', $_POST['oufamilies57']);
                $stmt->bindParam(':oupersons57', $_POST['oupersons57']);
                $stmt->bindParam(':evacu57', $_POST['evacu57']);
                $stmt->bindParam(':missing57', $_POST['missing57']);
                $stmt->bindParam(':injured57', $_POST['injured57']);
                $stmt->bindParam(':dead57', $_POST['dead57']);
                $stmt->bindParam(':repartial57', $_POST['repartial57']);
                $stmt->bindParam(':retotal57', $_POST['retotal57']);
                $stmt->bindParam(':stat57', $_POST['stat57']);
                $stmt->bindParam(':remarks57', $_POST['remarks57']);
                $stmt->bindParam(':tdatetime57', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name58, :mun58, :brgy58, :num58, :infamilies58, :inpersons58, :oufamilies58, :oupersons58, :evacu58, :missing58, :injured58, :dead58, :repartial58, :retotal58, :stat58, :remarks58, :tdatetime58)");
                $stmt->bindParam(':typhoon_name58', $tname);
                $stmt->bindParam(':mun58', $mun_b);
                $stmt->bindParam(':brgy58', $_POST['brgy58']);
                $stmt->bindParam(':num58', $num58);
                $stmt->bindParam(':infamilies58', $_POST['infamilies58']);
                $stmt->bindParam(':inpersons58', $_POST['inpersons58']);
                $stmt->bindParam(':oufamilies58', $_POST['oufamilies58']);
                $stmt->bindParam(':oupersons58', $_POST['oupersons58']);
                $stmt->bindParam(':evacu58', $_POST['evacu58']);
                $stmt->bindParam(':missing58', $_POST['missing58']);
                $stmt->bindParam(':injured58', $_POST['injured58']);
                $stmt->bindParam(':dead58', $_POST['dead58']);
                $stmt->bindParam(':repartial58', $_POST['repartial58']);
                $stmt->bindParam(':retotal58', $_POST['retotal58']);
                $stmt->bindParam(':stat58', $_POST['stat58']);
                $stmt->bindParam(':remarks58', $_POST['remarks58']);
                $stmt->bindParam(':tdatetime58', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name59, :mun59, :brgy59, :num59, :infamilies59, :inpersons59, :oufamilies59, :oupersons59, :evacu59, :missing59, :injured59, :dead59, :repartial59, :retotal59, :stat59, :remarks59, :tdatetime59)");
                $stmt->bindParam(':typhoon_name59', $tname);
                $stmt->bindParam(':mun59', $mun_b);
                $stmt->bindParam(':brgy59', $_POST['brgy59']);
                $stmt->bindParam(':num59', $num59);
                $stmt->bindParam(':infamilies59', $_POST['infamilies59']);
                $stmt->bindParam(':inpersons59', $_POST['inpersons59']);
                $stmt->bindParam(':oufamilies59', $_POST['oufamilies59']);
                $stmt->bindParam(':oupersons59', $_POST['oupersons59']);
                $stmt->bindParam(':evacu59', $_POST['evacu59']);
                $stmt->bindParam(':missing59', $_POST['missing59']);
                $stmt->bindParam(':injured59', $_POST['injured59']);
                $stmt->bindParam(':dead59', $_POST['dead59']);
                $stmt->bindParam(':repartial59', $_POST['repartial59']);
                $stmt->bindParam(':retotal59', $_POST['retotal59']);
                $stmt->bindParam(':stat59', $_POST['stat59']);
                $stmt->bindParam(':remarks59', $_POST['remarks59']);
                $stmt->bindParam(':tdatetime59', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name60, :mun60, :brgy60, :num60, :infamilies60, :inpersons60, :oufamilies60, :oupersons60, :evacu60, :missing60, :injured60, :dead60, :repartial60, :retotal60, :stat60, :remarks60, :tdatetime60)");
                $stmt->bindParam(':typhoon_name60', $tname);
                $stmt->bindParam(':mun60', $mun_b);
                $stmt->bindParam(':brgy60', $_POST['brgy60']);
                $stmt->bindParam(':num60', $num60);
                $stmt->bindParam(':infamilies60', $_POST['infamilies60']);
                $stmt->bindParam(':inpersons60', $_POST['inpersons60']);
                $stmt->bindParam(':oufamilies60', $_POST['oufamilies60']);
                $stmt->bindParam(':oupersons60', $_POST['oupersons60']);
                $stmt->bindParam(':evacu60', $_POST['evacu60']);
                $stmt->bindParam(':missing60', $_POST['missing60']);
                $stmt->bindParam(':injured60', $_POST['injured60']);
                $stmt->bindParam(':dead60', $_POST['dead60']);
                $stmt->bindParam(':repartial60', $_POST['repartial60']);
                $stmt->bindParam(':retotal60', $_POST['retotal60']);
                $stmt->bindParam(':stat60', $_POST['stat60']);
                $stmt->bindParam(':remarks60', $_POST['remarks60']);
                $stmt->bindParam(':tdatetime60', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name61, :mun61, :brgy61, :num61, :infamilies61, :inpersons61, :oufamilies61, :oupersons61, :evacu61, :missing61, :injured61, :dead61, :repartial61, :retotal61, :stat61, :remarks61, :tdatetime61)");
                $stmt->bindParam(':typhoon_name61', $tname);
                $stmt->bindParam(':mun61', $mun_b);
                $stmt->bindParam(':brgy61', $_POST['brgy61']);
                $stmt->bindParam(':num61', $num61);
                $stmt->bindParam(':infamilies61', $_POST['infamilies61']);
                $stmt->bindParam(':inpersons61', $_POST['inpersons61']);
                $stmt->bindParam(':oufamilies61', $_POST['oufamilies61']);
                $stmt->bindParam(':oupersons61', $_POST['oupersons61']);
                $stmt->bindParam(':evacu61', $_POST['evacu61']);
                $stmt->bindParam(':missing61', $_POST['missing61']);
                $stmt->bindParam(':injured61', $_POST['injured61']);
                $stmt->bindParam(':dead61', $_POST['dead61']);
                $stmt->bindParam(':repartial61', $_POST['repartial61']);
                $stmt->bindParam(':retotal61', $_POST['retotal61']);
                $stmt->bindParam(':stat61', $_POST['stat61']);
                $stmt->bindParam(':remarks61', $_POST['remarks61']);
                $stmt->bindParam(':tdatetime61', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name62, :mun62, :brgy62, :num62, :infamilies62, :inpersons62, :oufamilies62, :oupersons62, :evacu62, :missing62, :injured62, :dead62, :repartial62, :retotal62, :stat62, :remarks62, :tdatetime62)");
                $stmt->bindParam(':typhoon_name62', $tname);
                $stmt->bindParam(':mun62', $mun_b);
                $stmt->bindParam(':brgy62', $_POST['brgy62']);
                $stmt->bindParam(':num62', $num62);
                $stmt->bindParam(':infamilies62', $_POST['infamilies62']);
                $stmt->bindParam(':inpersons62', $_POST['inpersons62']);
                $stmt->bindParam(':oufamilies62', $_POST['oufamilies62']);
                $stmt->bindParam(':oupersons62', $_POST['oupersons62']);
                $stmt->bindParam(':evacu62', $_POST['evacu62']);
                $stmt->bindParam(':missing62', $_POST['missing62']);
                $stmt->bindParam(':injured62', $_POST['injured62']);
                $stmt->bindParam(':dead62', $_POST['dead62']);
                $stmt->bindParam(':repartial62', $_POST['repartial62']);
                $stmt->bindParam(':retotal62', $_POST['retotal62']);
                $stmt->bindParam(':stat62', $_POST['stat62']);
                $stmt->bindParam(':remarks62', $_POST['remarks62']);
                $stmt->bindParam(':tdatetime62', $_POST['tdatetime']);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`, `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name63, :mun63, :brgy63, :num63, :infamilies63, :inpersons63, :oufamilies63, :oupersons63, :evacu63, :missing63, :injured63, :dead63, :repartial63, :retotal63, :stat63, :remarks63, :tdatetime63)");
                $stmt->bindParam(':typhoon_name63', $tname);
                $stmt->bindParam(':mun63', $mun_b);
                $stmt->bindParam(':brgy63', $_POST['brgy63']);
                $stmt->bindParam(':num63', $num63);
                $stmt->bindParam(':infamilies63', $_POST['infamilies63']);
                $stmt->bindParam(':inpersons63', $_POST['inpersons63']);
                $stmt->bindParam(':oufamilies63', $_POST['oufamilies63']);
                $stmt->bindParam(':oupersons63', $_POST['oupersons63']);
                $stmt->bindParam(':evacu63', $_POST['evacu63']);
                $stmt->bindParam(':missing63', $_POST['missing63']);
                $stmt->bindParam(':injured63', $_POST['injured63']);
                $stmt->bindParam(':dead63', $_POST['dead63']);
                $stmt->bindParam(':repartial63', $_POST['repartial63']);
                $stmt->bindParam(':retotal63', $_POST['retotal63']);
                $stmt->bindParam(':stat63', $_POST['stat63']);
                $stmt->bindParam(':remarks63', $_POST['remarks63']);
                $stmt->bindParam(':tdatetime63', $_POST['tdatetime']);
                $stmt->execute();


                $datesel=$_POST['tdatetime'];
                $stmt = $conn->prepare("SELECT COUNT(tid) AS maxid FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."'   and mun='".$u_mun."' and tdatetime='".$datesel."' ");
                $stmt->execute(); 
                $resmax = $stmt->fetch(PDO::FETCH_ASSOC);
                $m=$resmax['maxid'];
                if($m > "0"){
                    $stmt = $conn->prepare("INSERT INTO `actionun`(`dtype`, `typhoon_name`, `mun`, `tdatetime`, `act`) VALUES ('".$dtype."', '".$tname."', '".$u_mun."', '".$datesel."', '".$_POST['actionun']."')");
                    $stmt->execute();
                }

        
                echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Added '); window.location='user_entry_sorsogoncity.php'</script>";
              }
                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {   echo 'Previous Error: '.$e->getMessage() . "<br/>";  }
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
                      <a href="user_typhoon.php"> <?php echo ucfirst(strtolower($_SESSION['dtype']));?>  </a>  &nbsp;⟶&nbsp;
                      <a href="user_reportfor.php">Report to:  </a>  &nbsp;⟶&nbsp;
                      <label>Manage  <?php echo ucfirst(strtolower($_SESSION['dtype']));?> Form For: <?php echo $_SESSION["typhoonname"]; ?></label>
                   </div>

                <div class="row" style=" padding:15px;  padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:15px;">
                
                


                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"  >
                                <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                    <div class="panel-heading" role="tab" id="headingOne" >
                                        <h4 class="panel-title">
                                           <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size:12px;"> ADD RECORD </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                          <form action="user_entry_sorsogoncity.php" method="post">
                                            <div class="row" style=" padding:0px; padding-bottom:10px; padding-top:10px;">
                                                    <div class="col-sm-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp;&nbsp;As of:&nbsp;</span>
                                                            <div class="form-group">
                                                                <input style="border:1px solid #406362;" name="tdatetime" id="myText" onchange="success()" type="text" class="form-control datetime-picker" placeholder="Click here to select Date and Time">
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                        </div>
                                                    </div>

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

                                                            <div class="input-group" style="margin-top: 9px; margin-bottom: 15px;">
                                                                    <div class="form-group" style=" padding:0px;margin:0px;">
                                                                    <textarea class="form-control textarea-autosize"  name="actionun"  placeholder="Action Under Taken" style=" padding:8px; border:2px solid #1E5253;" rows="5"></textarea>
                                                                    <i class="form-group__bar" style="padding:0px; margin:0px;"></i>
                                                                    </div>
                                                            </div> 

                                            <div class="table-responsive">
                                                <table id="table" class="table" >
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center;">Barangay</th>
                                                            <th colspan="4" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Evacuees</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Evacuation Center</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Missing</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Injured</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Dead</th>
                                                            <th colspan="2" rowspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Residential Houses</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Status</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Remarks</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Inside</th>
                                                            <th colspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Outside</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Families</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Persons</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Families</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Persons</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Partially</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Totally</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>


                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->

                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;"><input type="text"    value="SORSOGON CITY" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                        </tr>


                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->




                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy" value="Abuyog" style="width:200px; height:100%; margin:0px; text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy1" value="Almendras-Cogon (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies1"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons1"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies1"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons1"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu1"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing1"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured1"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead1"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial1"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal1"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat1" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks1"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy2" value="Balogo" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies2"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons2"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies2"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons2"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu2"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing2"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured2"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead2"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial2"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal2"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat2" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks2"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy3" value="Barayong" style="width:200px; height:100%; margin:0px; text-align:left; padding-left:8px; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies3"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons3"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies3"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons3"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu3"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing3"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured3"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead3"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial3"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal3"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat3" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks3"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy4" value="Basud" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies4"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons4"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies4"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons4"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu4"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing4"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured4"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead4"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial4"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal4"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat4" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks4"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy5" value="Bibincahan" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies5"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons5"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies5"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons5"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu5"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing5"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured5"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead5"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial5"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal5"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat5" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks5"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy6" value="Bitan-o/Dalipay (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies6"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons6"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies6"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons6"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu6"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing6"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured6"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead6"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial6"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal6"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat6" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks6"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy7" value="Bucalbucalan" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies7"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons7"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies7"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons7"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu7"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing7"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured7"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead7"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial7"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal7"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat7" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks7"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy8" value="Buenavista" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies8"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons8"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies8"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons8"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu8"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing8"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured8"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead8"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial8"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal8"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat8" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks8"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy9" value="Buhatan" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies9"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons9"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies9"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons9"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu9"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing9"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured9"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead9"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial9"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal9"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat9" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks9"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy10" value="Bulabog" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies10"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons10"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies10"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons10"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu10"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing10"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured10"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead10"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial10"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal10"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat10" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks10"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy11" value="Burabod (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies11"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons11"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies11"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons11"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu11"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing11"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured11"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead11"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial11"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal11"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat11" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks11"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy12" value="Cabid-An" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies12"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons12"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies12"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons12"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu12"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing12"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured12"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead12"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial12"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal12"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat12" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks12"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy13" value="Cambulaga" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies13"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons13"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies13"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons13"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu13"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing13"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured13"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead13"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial13"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal13"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat13" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks13"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy14" value="Capuy" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies14"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons14"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies14"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons14"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu14"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing14"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured14"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead14"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial14"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal14"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat14" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks14"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy15" value="Gimaloto" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies15"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons15"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies15"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons15"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu15"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing15"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured15"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead15"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial15"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal15"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat15" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks15"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy16" value="Guinlajon" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies16"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons16"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies16"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons16"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu16"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing16"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured16"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead16"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial16"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal16"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat16" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks16"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy17" value="Macabog" style="width:200px; height:100%; margin:0px; text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies17"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons17"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies17"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons17"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu17"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing17"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured17"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead17"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial17"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal17"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat17" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks17"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy18" value="Marinas" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies18"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons18"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies18"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons18"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu18"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing18"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured18"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead18"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial18"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal18"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat18" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks18"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy19" value="Pamurayan" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies19"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons19"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies19"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons19"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu19"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing19"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured19"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead19"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial19"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal19"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat19" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks19"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy20" value="Pangpang" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies20"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons20"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies20"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons20"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu20"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing20"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured20"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead20"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial20"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal20"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat20" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks20"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy21" value="Panlayaan" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies21"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons21"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies21"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons21"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu21"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing21"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured21"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead21"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial21"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal21"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat21" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks21"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy22" value="Peñafrancia" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies22"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons22"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies22"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons22"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu22"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing22"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured22"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead22"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial22"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal22"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat22" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks22"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy23" value="Piot (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies23"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons23"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies23"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons23"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu23"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing23"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured23"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead23"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial23"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal23"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat23" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks23"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy24" value="Polvorista (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies24"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons24"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies24"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons24"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu24"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing24"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured24"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead24"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial24"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal24"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat24" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks24"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <!-- end old=============================================================================================================================== -->
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy25" value="Rizal" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies25"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons25"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies25"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons25"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu25"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing25"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured25"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead25"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial25"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal25"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat25" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks25"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy26" value="Salog (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies26"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons26"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies26"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons26"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu26"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing26"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured26"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead26"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial26"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal26"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat26" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks26"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy27" value="Salvacion" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies27"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons27"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies27"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons27"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu27"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing27"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured27"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead27"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial27"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal27"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat27" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks27"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy28" value="Sampaloc (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies28"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons28"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies28"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons28"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu28"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing28"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured28"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead28"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial28"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal28"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat28" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks28"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy29" value="San Isidro" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies29"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons29"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies29"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons29"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu29"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing29"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured29"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead29"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial29"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal29"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat29" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks29"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy30" value="San Juan (Roro)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies30"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons30"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies30"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons30"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu30"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing30"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured30"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead30"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial30"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal30"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat30" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks30"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy31" value="Sirangan (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies31"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons31"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies31"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons31"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu31"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing31"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured31"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead31"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial31"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal31"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat31" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks31"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy32" value="Sulucan (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies32"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons32"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies32"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons32"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu32"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing32"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured32"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead32"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial32"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal32"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat32" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks32"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy33" value="Talisay (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies33"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons33"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies33"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons33"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu33"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing33"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured33"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead33"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial33"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal33"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat33" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks33"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy34" value="Ticol" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies34"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons34"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies34"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons34"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu34"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing34"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured34"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead34"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial34"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal34"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat34" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks34"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy35" value="Tugos" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies35"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons35"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies35"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons35"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu35"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing35"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured35"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead35"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial35"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal35"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat35" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks35"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>

                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->

                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;"><input type="text"    value="BACON DISTRICT" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                            <td style=" padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:#227447;">&nbsp;</td>
                                                        </tr>


                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->
                                                        <!--  =============================================================================================================================== -->


                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy36" value="Balete [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies36"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons36"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies36"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons36"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu36"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing36"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured36"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead36"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial36"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal36"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat36" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks36"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy37" value="Balogo [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies37"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons37"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies37"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons37"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu37"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing37"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured37"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead37"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial37"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal37"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat37" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks37"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy38" value="Bato [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies38"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons38"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies38"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons38"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu38"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing38"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured38"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead38"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial38"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal38"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat38" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks38"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy39" value="Bon-Ot [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies39"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons39"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies39"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons39"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu39"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing39"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured39"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead39"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial39"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal39"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat39" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks39"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy40" value="Bogña [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies40"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons40"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies40"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons40"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu40"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing40"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured40"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead40"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial40"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal40"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat40" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks40"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy41" value="Buenavista [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies41"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons41"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies41"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons41"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu41"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing41"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured41"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead41"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial41"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal41"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat41" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks41"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy42" value="Cabarbuhan [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies42"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons42"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies42"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons42"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu42"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing42"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured42"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead42"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial42"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal42"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat42" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks42"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy43" value="Caricaran [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies43"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons43"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies43"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons43"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu43"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing43"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured43"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead43"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial43"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal43"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat43" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks43"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy44" value="Del Rosario [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies44"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons44"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies44"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons44"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu44"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing44"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured44"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead44"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial44"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal44"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat44" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks44"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy45" value="Gatbo [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies45"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons45"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies45"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons45"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu45"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing45"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured45"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead45"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial45"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal45"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat45" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks45"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy46" value="Jamislangan [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies46"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons46"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies46"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons46"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu46"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing46"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured46"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead46"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial46"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal46"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat46" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks46"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy47" value="Maricrum (San Jose) [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies47"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons47"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies47"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons47"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu47"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing47"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured47"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead47"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial47"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal47"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat47" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks47"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy48" value="Osiao [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies48"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons48"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies48"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons48"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu48"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing48"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured48"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead48"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial48"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal48"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat48" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks48"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy49" value="Poblacion [Bacon]" style="width:200px; height:100%; margin:0px; text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies49"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons49"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies49"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons49"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu49"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing49"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured49"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead49"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial49"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal49"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat49" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks49"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy50" value="Rawis [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies50"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons50"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies50"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons50"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu50"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing50"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured50"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead50"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial50"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal50"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat50" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks50"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy51" value="Salvacion [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies51"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons51"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies51"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons51"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu51"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing51"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured51"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead51"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial51"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal51"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat51" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks51"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy52" value="San Isidro [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies52"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons52"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies52"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons52"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu52"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing52"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured52"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead52"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial52"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal52"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat52" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks52"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy53" value="San Juan [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies53"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons53"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies53"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons53"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu53"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing53"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured53"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead53"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial53"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal53"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat53" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks53"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy54" value="San Pascual [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies54"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons54"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies54"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons54"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu54"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing54"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured54"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead54"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial54"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal54"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat54" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks54"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy55" value="San Ramon [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies55"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons55"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies55"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons55"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu55"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing55"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured55"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead55"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial55"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal55"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat55" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks55"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy56" value="San Roque [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies56"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons56"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies56"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons56"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu56"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing56"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured56"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead56"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial56"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal56"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat56" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks56"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy57" value="San Vicente [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies57"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons57"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies57"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons57"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu57"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing57"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured57"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead57"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial57"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal57"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat57" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks57"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy58" value="Santa Cruz [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies58"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons58"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies58"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons58"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu58"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing58"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured58"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead58"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial58"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal58"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat58" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks58"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy59" value="Santa Lucia [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies59"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons59"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies59"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons59"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu59"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing59"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured59"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead59"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial59"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal59"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat59" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks59"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy60" value="Santo Domingo [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies60"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons60"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies60"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons60"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu60"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing60"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured60"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead60"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial60"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal60"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat60" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks60"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy61" value="Santo Niño [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies61"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons61"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies61"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons61"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu61"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing61"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured61"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead61"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial61"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal61"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat61" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks61"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy62" value="Sawanga [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies62"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons62"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies62"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons62"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu62"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing62"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured62"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead62"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial62"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal62"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat62" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks62"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy63" value="Sugod [Bacon]" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies63"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons63"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies63"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons63"  style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="evacu63"  style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing63"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured63"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead63"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial63"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal63"  style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><select              name="stat63" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">   <option value="">&nbsp;</option> <option value="Camped">Camped</option> <option value="Decamped">Decamped</option>         </select>  </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="remarks63"  style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row" style="margin-bottom:15px;">
                                               <div class="col-sm-3"><button title="Save to Database (Note: Date & Time Required to { enable this Save Button } )" type="submit" name="add" id="add" disabled > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SAVE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Add" href="user_entry_sorsogoncity.php"> CANCEL</a>  </div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                               <div class="col-sm-3"></div>
                                            </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>


                                <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                    <div class="panel-heading" role="tab" id="headingTree">
                                        <h4 class="panel-title">
                                           <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTree" aria-expanded="false" aria-controls="collapseTree" style="font-size:12px;"> DECAMPMENT </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTree">
                                        <div class="panel-body">
                                         <form action="user_entry_sorsogoncity.php" method="post" style=" width:500px;">
                                            <div class="row" style=" padding:0px; padding-bottom:10px; padding-top:10px; padding-left:15px;">
                                                <div class="input-group">
                                                    <div class="form-group">
                                                    <select class="select2"  name="tdatetime" style="width:300px;" >
                                                        <option value="norecord" disabled selected >Select Date & Time</option>
                                                        <?php foreach($resulttfdate as $popdate){?>
                                                           <option value="<?=$popdate['tdatetime']?>" ><?=$popdate['tdatetime']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    </div>
                                                    <span style=" vertical-align : middle;text-align:center; padding:5px;  margin-right:15px;">  <button title="Confirm Decampment" type="submit" name="decamped" style="heigth:100%;" > &nbsp;&nbsp;&nbsp; DECAMPED &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Decampment" href="user_entry_sorsogoncity.php"> CANCEL</a>  </span>
                                                </div>
                                            </div>
                                          </form>
                                        </div>
                                    </div>
                                </div>


                                <div class="panel panel-default" style="background-color:rgba(0,0,0,0.1); padding:0px; padding-left: 5px; padding-right:5px; border:1px solid rgba(0,0,0,0.2);">
                                    <div class="panel-heading" role="tab" id="headingFour">
                                        <h4 class="panel-title">
                                           <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="font-size:12px;"> DELETE FILTER </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                        <div class="panel-body">
                                         <form action="user_entry_sorsogoncity.php" method="post" style=" width:500px;">
                                            <div class="row" style=" padding:0px; padding-bottom:10px; padding-top:10px; padding-left:15px;">
                                                <div class="input-group">
                                                    <div class="form-group">
                                                    <select class="select2"  name="tdatetime" style="width:300px;" >
                                                        <option value="norecord" disabled selected >Select Date & Time to Delete</option>
                                                        <?php foreach($resulttfdatedeletefilter as $popdate1){?>
                                                           <option value="<?=$popdate1['tdatetime']?>" ><?=$popdate1['tdatetime']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    </div>
                                                    <span style=" vertical-align : middle;text-align:center; padding:5px;  margin-right:15px;">  <button title="Confirm Delete" type="submit" name="delete" style="heigth:100%;" > &nbsp;&nbsp;&nbsp; DELETE &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Decampment" href="user_entry_sorsogon.php"> CANCEL</a>  </span>
                                                </div>
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
                                         <form action="user_entry_sorsogoncity.php" method="post">
                                            <div class="row" style=" padding:0px; padding-bottom:10px; padding-top:10px;">
                                                    <div class="col-sm-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> &nbsp;&nbsp;&nbsp;As of:&nbsp;</span>
                                                            <div class="form-group">
                                                                <input type="text" name="tdatetime" value="<?=$resulttfselect['tdatetime']?>" style="width:120px; height:100%; margin:0px; background-color:rgba(0,0,0,0); border:1px solid #3d5859; border-left:none; color:#a8b6b5;" readonly> <input type="hidden" name="tid" value="<?=$resulttfselect['tid']?>" style="width:50px; height:100%; margin:0px; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="table" class="table" >
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:center;">Barangay</th>
                                                            <th colspan="4" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Evacuees</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Evacuation Center</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Missing</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Injured</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Dead</th>
                                                            <th colspan="2" rowspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Residential Houses</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Status</th>
                                                            <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Remarks</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Inside</th>
                                                            <th colspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Outside</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Families</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Persons</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Families</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Persons</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Partially</th>
                                                            <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Totally</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text" name="brgy" value="<?=$resulttfselect['brgy']?>" style="width:200px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="infamilies" value="<?=$resulttfselect['infamilies']?>" style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="inpersons" value="<?=$resulttfselect['inpersons']?>" style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oufamilies" value="<?=$resulttfselect['oufamilies']?>" style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="oupersons" value="<?=$resulttfselect['oupersons']?>" style="width:85px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text" name="evacu" value="<?=$resulttfselect['evacu']?>" style="width:230px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="missing" value="<?=$resulttfselect['missing']?>" style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="injured" value="<?=$resulttfselect['injured']?>" style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="dead" value="<?=$resulttfselect['dead']?>" style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="repartial" value="<?=$resulttfselect['repartial']?>" style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="number" name="retotal" value="<?=$resulttfselect['retotal']?>" style="width:75px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                                               <select name="stat" id="" style="width:80px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#fff;">
                                                                   <option value="<?=$resulttfselect['stat']?>"><?=$resulttfselect['stat']?></option>
                                                                   <option value="Decamped">Decamped</option>
                                                                   <option value="Camped">Camped</option>
                                                                   <option value="">&nbsp;</option>
                                                               </select>
                                                            </td>
                                                            <td style="border:2px solid #1e5253; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"  name="remarks" value="<?=$resulttfselect['remarks']?>" style="width:330px; height:100%; margin:0px; text-align:center; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" > </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row" style="margin-bottom:15px;">
                                               <div class="col-sm-3"><button title="Save Changes" type="submit" name="update" > &nbsp;&nbsp;&nbsp; SAVE CHANGES &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Edit" href="user_entry_sorsogoncity.php"> CANCEL</a>  </div>
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


                        <form action="user_entry_sorsogoncity.php?trig=fil" method="post" style=" width:250px; height:33px; padding:0px; margin:0px;">
                            <div class="row" style=" padding:0px; margin:0px;">
                               <div class="col-sm-9" style="padding:0px; margin:0px;" >
                                    <div class="form-group" style="margin:0px; height:25px; padding:0px;">
                                       <select class="select2"  name="tdatetime" style="background-color:rgba(0,0,0,0); color:rgba(0,0,0,0); border:none; height:25px;">
                                          <?php if($_POST['tdatetime'] != ""){ ?>
                                          <option  value="<?=$_POST['tdatetime']?>" disabled selected ><?=$_POST['tdatetime']?></option>
                                          <?php }else{ ?>
                                            <option value="" disabled selected >Select Filter</option>
                                          <?php } ?>
                                          <?php foreach($resulttfdateall as $popdateall){
                                              $exp = explode(" ", $popdateall['tdatetime']);
                                          ?>
                                          <option value="<?=$exp[0]?>" ><?=$exp[0]?></option>
                                          <?php } ?>
                                         <option value="All" >View All Record</option>
                                       </select>
                                    </div>
                                </div>
                                <div class="col-sm-3" style=" padding:0px; margin:0px;">
                                    <div class="form-group" style=" margin-top:7px;">
                                        <button style="border-radius:7px; background-color:rgba(0,0,0,0.1);"  title="Show Record" type="submit" name="filtersearch" >🔍</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        
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
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">&nbsp;</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Status</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:left; padding-left:8px;">(64) Barangay</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">#</th>
                                        <th colspan="4" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Evacuees</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Evacuation Center</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Missing</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Injured</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Dead</th>
                                        <th colspan="2" rowspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Residential Houses</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Status</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Remarks</th>
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Datetime</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Inside</th>
                                        <th colspan="2" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Outside</th>
                                    </tr>
                                    <tr>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Families</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Persons</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Families</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Persons</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Partially</th>
                                        <th colspan="1" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px; vertical-align : middle;text-align:center;">Totally</th>
                                    </tr>

                                </thead>
                                <tbody>
                                <?php 
                                 $num="1";
                                 foreach ($resulttf as $valuetf) 
                                 {
                                    if ($num=="65"){
                                        $num="1";
                                    }else{}  
                                    
                                    require_once('config/dbcon.php');
                                    $connu = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                    $connu->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $stmt = $connu->prepare("SELECT * FROM actionun WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and mun='".$u_mun."' and tdatetime='".$valuetf['tdatetime']."' ");
                                    $stmt->execute(); 
                                    $res_act = $stmt->fetch(PDO::FETCH_ASSOC);

                                ?>
                                    <tr style="border:1px solid #2b4c4a;" data-toggle="popover" data-placement="left" data-html="true" data-content=" <span style=font-size:11px;> <?='<span style=color:green;>Added Date: </span> '.$res_act['tdatetime'].'<br><span style=color:green;>Action Under Taken: </span> <textarea id=d wrap=off cols=37 rows=8 style=background-color:rgba(0,0,0,0);color:white; readonly >'.$res_act['act'].'</textarea>' ?></span>  ">
                                        <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                            <a title="Edit ( <?=$valuetf['brgy']?> )" href="user_entry_sorsogoncity.php?id=<?=$valuetf['tid']?>" style="padding-left:5px;padding-right:5px; border-radius:50px;" ><i class="zmdi zmdi-edit" ></i></a>
                                        </td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?php if ($valuetf['notif'] > "0"){ echo "Recorded"; }else{ echo "Not Seen"; } ?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:left; padding-left:8px; color:#a8b6b5;"><?=$num.". &nbsp;".$valuetf['brgy']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['num'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['infamilies'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['inpersons'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['oufamilies'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['oupersons'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['evacu']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['missing'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['injured'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['dead'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['repartial'])?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=intval($valuetf['retotal'])?></td>
                                          <?php if($valuetf['stat']=="Camped"){?>
                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5; background-color:rgba(0,0,0,0.2);"><?=$valuetf['stat']?></td>
                                          <?php }else{ ?>
                                                <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['stat']?></td>
                                          <?php }?>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['remarks']?></td>
                                        <td style="border:1px solid #2b4c4a; padding:2px; vertical-align : middle;text-align:center; color:#a8b6b5;"><?=$valuetf['tdatetime']?></td>
                                    </tr>
                                <?php 
                                $num++; } ?>
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