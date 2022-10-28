<?php

 if(isset($_POST['submit'])){
    $upass  = trim($_POST['U_PASS']);
    $h_upass = sha1($upass);    
 }
?>

<form method="post" action="passwordverify.php">  
<input type="text" name="U_PASS">
<button type="submit" name="submit">Submit</button>
</form>