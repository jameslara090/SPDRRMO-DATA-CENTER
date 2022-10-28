<?php

session_start();

    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != "" && $_SESSION['utype'] == "Administrator" ) {
        $user_id = $_SESSION['user_id'];
        $email = $_SESSION['email'];
        $utype = $_SESSION['utype'];
        $mun = $_SESSION['mun'];
        
        require_once('config/dbcon.php');
        $conn_3 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn_3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn_3->prepare("SELECT COUNT(iid) AS cnt FROM ftopic  ");
        $stmt->execute(); 
        $res_3 = $stmt->fetch(PDO::FETCH_ASSOC);
        $c=$res_3['cnt'];

        if($c < "10"){
            $c="&nbsp;".$c."&nbsp;";
        }
        
    }else{
        header("location:login.php");
    }

?>
<!--
<script type="text/javascript">
	refreshSn();
	var refreshSn = function ()
	{
	    var time = 960000; //<15 mins>   //600000; // 10 mins
	    setTimeout(
	        function ()
	        {
	        $.ajax({
	           url: 'refresh_session.php',
	           cache: false,
	           complete: function () {refreshSn();}
	        });
	    },
	    time
	);
	};
</script>-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <script type="text/javascript">

            var data1="";
            var f = "";
            function request(){
                var req = $.ajax({
                        type:"post",
                        url:"session/refresh_session.php",
                        data:{data:"Hello World"}
                    });

                    req.done(function(data){
                        console.log("Request successful! ==== "+data);
                        if (data=="good working"){ data1="" ; }else{
                        data1 = "4";  
                        }

                    });

            };
            request();
            var autoloadaa=setInterval(function(num){
                    if(data1=="4"){
                        request();  
                    }else{     
                    }  
            },1000);
                
              
        </script>
        
        
        
        
        <style>
    .marked {font-size:11px; font-weight:bold; color:#E69DA4 !important; padding-left:2px; padding-right:2px;border-radius:50%;}
</style>


<!--<script src="https://code.jquery.com/jquery-3.5.0.js"></script>-->
  <script>
    $(function() {
            var valu = "<?php echo $c;?>";
            var word = 'Forum / Suggestions';
            var re = new RegExp('[^<\\/](' + word + ')', 'g');
            $('.btn').each(function() {
                $(this).html( $(this).html().replace( re, ' $1 <span class="btn-light marked">'+valu+'</span>' ) );
            });
            $('button.undo').on( 'click', function() {
                $('.btn').find( 'span.marked' ).each(function() {
                    $(this).replaceWith( word );
                });
            });
    });
  </script>