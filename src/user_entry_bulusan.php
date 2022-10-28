
<?php
    error_reporting(0);

    require_once('session/check_user.php');

    $tname=$_SESSION['typhoonname'];

    $dtype=$_SESSION['dtype'];
    if (!isset($_SESSION['dtype']) || ($_SESSION['dtype'] == '')) {
        header("location:user_home.php");
        exit();
    }

    if ($tname != "" && $u_mun=="bulusan"){
    }else{
        header("location:user_typhoon.php");
    }
    


    require_once('config/dbcon.php');
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //mysql> SELECT ip, SUBSTRING_INDEX(ip,'.',1) AS part1, SUBSTRING_INDEX(SUBSTRING_INDEX(ip,'.',2),'.',-1) AS part2
    //$stmt = $conn->prepare("SELECT DISTINCT tdatetime, SUBSTRING_INDEX(tdatetime,' ',1) AS part1, SUBSTRING_INDEX(SUBSTRING_INDEX(tdatetime,' ',2),' ',-1) AS part2 FROM typhoon_form order by tdatetime DESC ");
    //$stmt->execute(); 
    //$resulttfselect2 = $stmt->fetchAll();
    //foreach($resulttfselect2 as $val){
    //  echo $val['part1']." | ".$val['part2']." -|- ";
    //}

    //filter search check
    $filter=$_GET['trig'];  $filters="";  if($filter =="fil"){  $filters="1";  }else{  $filters="";  }
    //end
    if ($filters=="1"){
        if ($_POST['tdatetime']=="All" || $_POST['tdatetime']==""){
            $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and mun='".$u_mun."' order by tdatetime DESC, tid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();
            echo "<script>window.location='user_entry_bulusan.php'</script>";
        }else{
            $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and SUBSTRING_INDEX(tdatetime,' ',1)='".$_POST['tdatetime']."'  and mun='".$u_mun."' order by tdatetime DESC, tid ASC");
            $stmt->execute(); 
            $resulttf = $stmt->fetchAll();
        }
    }else{
        $stmt = $conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and mun='".$u_mun."' order by tdatetime DESC, tid ASC");
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
    $stmt=$conn->prepare("SELECT * FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."'   and mun='".$u_mun."' GROUP BY SUBSTRING_INDEX(tdatetime,' ',1) order by SUBSTRING_INDEX(tdatetime,' ',1) DESC ");
    $stmt->execute(); 
    $resulttfdateall = $stmt->fetchAll();
    //end

    //open close colapse
    $update=$_GET['id'];  $updatestat="";  if($update>0){  $updatestat="-in";  }else{  $updatestat="";  }
    //end

    
    



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
                    if ($num < 1){  echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully updated : ( ".$_POST['brgy']." ) Not Affected '); window.location='user_entry_bulusan.php'</script>";
                    }else{  echo "<script>alert('✔ Successfully updated : ( ".$_POST['brgy']." ) Affected '); window.location='user_entry_bulusan.php'</script>";  }

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
                    echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Decamped in : ( ".$_POST['tdatetime']." ) '); window.location='user_entry_bulusan.php'</script>";
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


                    echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Deleted : ( ".$_POST['tdatetime']." ) Record '); window.location='user_entry_bulusan.php'</script>";
                }
                catch (Exception $e){  echo $e->getMessage() . "<br/>";  while($e = $e->getPrevious()) {  echo 'Previous Error: '.$e->getMessage() . "<br/>"; }
                }
        }
        if(isset($_POST['add'])){
            $mun=$u_mun;
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
           
            try{
              if($_POST['tdatetime']==""){ } else{
                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name, :mun, :brgy, :num, :infamilies, :inpersons, :oufamilies, :oupersons, :evacu, :missing, :injured, :dead, :repartial, :retotal, :stat, :remarks, :tdatetime)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name1, :mun1, :brgy1, :num1, :infamilies1, :inpersons1, :oufamilies1, :oupersons1, :evacu1, :missing1, :injured1, :dead1, :repartial1, :retotal1, :stat1, :remarks1, :tdatetime1)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name3, :mun3, :brgy3, :num3, :infamilies3, :inpersons3, :oufamilies3, :oupersons3, :evacu3, :missing3, :injured3, :dead3, :repartial3, :retotal3, :stat3, :remarks3, :tdatetime3)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name4, :mun4, :brgy4, :num4, :infamilies4, :inpersons4, :oufamilies4, :oupersons4, :evacu4, :missing4, :injured4, :dead4, :repartial4, :retotal4, :stat4, :remarks4, :tdatetime4)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name5, :mun5, :brgy5, :num5, :infamilies5, :inpersons5, :oufamilies5, :oupersons5, :evacu5, :missing5, :injured5, :dead5, :repartial5, :retotal5, :stat5, :remarks5, :tdatetime5)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name6, :mun6, :brgy6, :num6, :infamilies6, :inpersons6, :oufamilies6, :oupersons6, :evacu6, :missing6, :injured6, :dead6, :repartial6, :retotal6, :stat6, :remarks6, :tdatetime6)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name7, :mun7, :brgy7, :num7, :infamilies7, :inpersons7, :oufamilies7, :oupersons7, :evacu7, :missing7, :injured7, :dead7, :repartial7, :retotal7, :stat7, :remarks7, :tdatetime7)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name8, :mun8, :brgy8, :num8, :infamilies8, :inpersons8, :oufamilies8, :oupersons8, :evacu8, :missing8, :injured8, :dead8, :repartial8, :retotal8, :stat8, :remarks8, :tdatetime8)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name9, :mun9, :brgy9, :num9, :infamilies9, :inpersons9, :oufamilies9, :oupersons9, :evacu9, :missing9, :injured9, :dead9, :repartial9, :retotal9, :stat9, :remarks9, :tdatetime9)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name10, :mun10, :brgy10, :num10, :infamilies10, :inpersons10, :oufamilies10, :oupersons10, :evacu10, :missing10, :injured10, :dead10, :repartial10, :retotal10, :stat10, :remarks10, :tdatetime10)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name11, :mun11, :brgy11, :num11, :infamilies11, :inpersons11, :oufamilies11, :oupersons11, :evacu11, :missing11, :injured11, :dead11, :repartial11, :retotal11, :stat11, :remarks11, :tdatetime11)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name12, :mun12, :brgy12, :num12, :infamilies12, :inpersons12, :oufamilies12, :oupersons12, :evacu12, :missing12, :injured12, :dead12, :repartial12, :retotal12, :stat12, :remarks12, :tdatetime12)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name13, :mun13, :brgy13, :num13, :infamilies13, :inpersons13, :oufamilies13, :oupersons13, :evacu13, :missing13, :injured13, :dead13, :repartial13, :retotal13, :stat13, :remarks13, :tdatetime13)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name14, :mun14, :brgy14, :num14, :infamilies14, :inpersons14, :oufamilies14, :oupersons14, :evacu14, :missing14, :injured14, :dead14, :repartial14, :retotal14, :stat14, :remarks14, :tdatetime14)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name15, :mun15, :brgy15, :num15, :infamilies15, :inpersons15, :oufamilies15, :oupersons15, :evacu15, :missing15, :injured15, :dead15, :repartial15, :retotal15, :stat15, :remarks15, :tdatetime15)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name16, :mun16, :brgy16, :num16, :infamilies16, :inpersons16, :oufamilies16, :oupersons16, :evacu16, :missing16, :injured16, :dead16, :repartial16, :retotal16, :stat16, :remarks16, :tdatetime16)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name17, :mun17, :brgy17, :num17, :infamilies17, :inpersons17, :oufamilies17, :oupersons17, :evacu17, :missing17, :injured17, :dead17, :repartial17, :retotal17, :stat17, :remarks17, :tdatetime17)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name18, :mun18, :brgy18, :num18, :infamilies18, :inpersons18, :oufamilies18, :oupersons18, :evacu18, :missing18, :injured18, :dead18, :repartial18, :retotal18, :stat18, :remarks18, :tdatetime18)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name19, :mun19, :brgy19, :num19, :infamilies19, :inpersons19, :oufamilies19, :oupersons19, :evacu19, :missing19, :injured19, :dead19, :repartial19, :retotal19, :stat19, :remarks19, :tdatetime19)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name20, :mun20, :brgy20, :num20, :infamilies20, :inpersons20, :oufamilies20, :oupersons20, :evacu20, :missing20, :injured20, :dead20, :repartial20, :retotal20, :stat20, :remarks20, :tdatetime20)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name21, :mun21, :brgy21, :num21, :infamilies21, :inpersons21, :oufamilies21, :oupersons21, :evacu21, :missing21, :injured21, :dead21, :repartial21, :retotal21, :stat21, :remarks21, :tdatetime21)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name22, :mun22, :brgy22, :num22, :infamilies22, :inpersons22, :oufamilies22, :oupersons22, :evacu22, :missing22, :injured22, :dead22, :repartial22, :retotal22, :stat22, :remarks22, :tdatetime22)");
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

                $stmt = $conn->prepare("INSERT INTO `typhoon_form`(`dtype`,  `typhoon_name`, `mun`, `brgy`, `num`, `infamilies`, `inpersons`, `oufamilies`, `oupersons`, `evacu`, `missing`, `injured`, `dead`, `repartial`, `retotal`, `stat`, `remarks`, `tdatetime`) VALUES ('".$dtype."', :typhoon_name23, :mun23, :brgy23, :num23, :infamilies23, :inpersons23, :oufamilies23, :oupersons23, :evacu23, :missing23, :injured23, :dead23, :repartial23, :retotal23, :stat23, :remarks23, :tdatetime23)");
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


                $datesel=$_POST['tdatetime'];
                $stmt = $conn->prepare("SELECT COUNT(tid) AS maxid FROM typhoon_form WHERE dtype='".$dtype."' and  typhoon_name='".$tname."'   and mun='".$u_mun."' and tdatetime='".$datesel."' ");
                $stmt->execute(); 
                $resmax = $stmt->fetch(PDO::FETCH_ASSOC);
                $m=$resmax['maxid'];
                if($m > "0"){
                    $stmt = $conn->prepare("INSERT INTO `actionun`(`dtype`, `typhoon_name`, `mun`, `tdatetime`, `act`) VALUES ('".$dtype."', '".$tname."', '".$u_mun."', '".$datesel."', '".$_POST['actionun']."')");
                    $stmt->execute();
                }

                
                echo "<script>alert('  ✉ MESSAGE \\n  ✔ Successfully Added '); window.location='user_entry_bulusan.php'</script>";
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
                                          <form action="user_entry_bulusan.php" method="post">
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
                                                        <tr style="border:1px solid #2b4c4a;">
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy" value="Bagacay" style="width:200px; height:100%; margin:0px; text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy1" value="Central (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy2" value="Cogon" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy3" value="Dancalan" style="width:200px; height:100%; margin:0px; text-align:left; padding-left:8px; background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy4" value="Dapdap (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy5" value="Lalud" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy6" value="Looban (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy7" value="Mabuhay (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy8" value="Madlawon (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy9" value="Poctol (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy10" value="Porog" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy11" value="Sabang (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy12" value="Salvacion" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy13" value="San Antonio" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy14" value="San Bernardo" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy15" value="San Francisco (Kapangihan)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy16" value="San Isidro" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy17" value="San Jose" style="width:200px; height:100%; margin:0px; text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy18" value="San Rafael" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy19" value="San Roque" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy20" value="San Vicente (Buhang)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy21" value="Santa Barbara" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy22" value="Sapngan (Pob.)" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                            <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;"><input type="text"   name="brgy23" value="Tinampo" style="width:200px; height:100%; margin:0px;  text-align:left; padding-left:8px;  background-color:rgba(0,0,0,0.2); border:none; color:#a8b6b5;" readonly> </td>
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
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row" style="margin-bottom:15px;">
                                               <div class="col-sm-3"><button title="Save to Database (Note: Date & Time Required to { enable this Save Button } )" type="submit" name="add" id="add" disabled > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SAVE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Add" href="user_entry_bulusan.php"> CANCEL</a>  </div>
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
                                         <form action="user_entry_bulusan.php" method="post" style=" width:500px;">
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
                                                    <span style=" vertical-align : middle;text-align:center; padding:5px;  margin-right:15px;">  <button title="Confirm Decampment" type="submit" name="decamped" style="heigth:100%;" > &nbsp;&nbsp;&nbsp; DECAMPED &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Decampment" href="user_entry_bulusan.php"> CANCEL</a>  </span>
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
                                         <form action="user_entry_castilla.php" method="post" style=" width:500px;">
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
                                                    <span style=" vertical-align : middle;text-align:center; padding:5px;  margin-right:15px;">  <button title="Confirm Delete" type="submit" name="delete" style="heigth:100%;" > &nbsp;&nbsp;&nbsp; DELETE &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Decampment" href="user_entry_castilla.php"> CANCEL</a>  </span>
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
                                         <form action="user_entry_bulusan.php" method="post">
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
                                               <div class="col-sm-3"><button title="Save Changes" type="submit" name="update" > &nbsp;&nbsp;&nbsp; SAVE CHANGES &nbsp;&nbsp;&nbsp; </button>   &nbsp;&nbsp;<a style="background-color:orange; padding:4px; padding-left:20px; padding-right:20px; color:white;" title="Cancel Edit" href="user_entry_bulusan.php"> CANCEL</a>  </div>
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


                        <form action="user_entry_bulusan.php?trig=fil" method="post" style=" width:250px; height:33px; padding:0px; margin:0px;">
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
                                        <th rowspan="3" style="border:1px solid rgba(0,0,0,0.4); background-color:rgba(0,0,0,0.2); padding:0px;  vertical-align : middle;text-align:left; padding-left:8px;">(24) Barangay</th>
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
                                    if ($num=="25"){
                                        $num="1";
                                    }else{}    

                                    require_once('config/dbcon.php');
                                    $connu = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                    $connu->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $stmt = $connu->prepare("SELECT * FROM actionun WHERE dtype='".$dtype."' and  typhoon_name='".$tname."' and mun='".$u_mun."' and tdatetime='".$valuetf['tdatetime']."' ");
                                    $stmt->execute(); 
                                    $res_act = $stmt->fetch(PDO::FETCH_ASSOC);

                                ?>
                                    <tr style="border:1px solid #2b4c4a;" data-toggle="popover"  data-placement="left" data-html="true" data-content=" <span style=font-size:11px;> <?='<span style=color:green;>Added Date: </span> '.$res_act['tdatetime'].'<br><span style=color:green;>Action Under Taken: </span> <textarea id=d wrap=off cols=37 rows=8 style=background-color:rgba(0,0,0,0);color:white; readonly >'.$res_act['act'].'</textarea>' ?></span>  ">
                                        <td style="border:1px solid #2b4c4a; padding:0px; vertical-align : middle;text-align:center; color:#a8b6b5;">
                                            <a title="Edit ( <?=$valuetf['brgy']?> )" href="user_entry_bulusan.php?id=<?=$valuetf['tid']?>" style="padding-left:5px;padding-right:5px; border-radius:50px;" ><i class="zmdi zmdi-edit" ></i></a>
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