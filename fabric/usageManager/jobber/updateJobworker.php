<?php
include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	
	include($_SERVER['DOCUMENT_ROOT']."/$stockManager/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/$stockManager/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$stockManager/index.php");
	$a_old=$_GET['alias'];



    $querry="select * from jobworker where alias='$a_old'";

    if($result=mysqli_query($dbhandle,$querry)){
        while($row=mysqli_fetch_array($result)){
            $a= $row[0];
            $b= $row[1];
            }
    }

?>

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="stylesheet" href="/<?=$stockManager?>/css/normalize.css">
        <link rel="stylesheet" href="/<?=$stockManager?>/css/demo.css">
        <!-- Pushy CSS -->
        <link rel="stylesheet" href="/<?=$stockManager?>/css/pushy.css">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
		
   </head>
   <script type="text/javascript">
	
	
	
  $(document).ready(function() {
    
     
    $("#btnUpdate").click(function(){
     
      
      var oldAlias=$("#a_old").val();
      var jobworker=$("#jobworker").val();
      var alias=$("#alias").val();
      
      
      $.ajax({
                    type:"post",
                    url:"jobworkerAction.php",
                    data:"jobworker="+jobworker+"&oldAlias="+oldAlias+"&alias="+alias+"&action=updateJobworker",
                    success:function(data){
       alert(data);
        var status = JSON.parse(data);
        
        if(status["STATUS"]==1){
          alert("Update Made Successfuly.\n old="+status["oldalias"]+"\nNew="+status["newalias"]+"\nJobworker="+status["jobworker"]);
          window.location.replace("listAlljobworkerTypes.php");
        
        }else{
          alert("Update Failed.");
        }
                
                    }
                });
      
    });
    
    
    
   
   
    
    
    
  });
</script>


	

 

    <body>
	
	
	   
    <div class="buyerdetailst" id="buyerdetailst">
    <center><h3>Update Jobworker </h3></center>
	
	<label for="mail" hidden>Alias:  <input type="text" id="a_old" name="a_old" value="<?=$a_old?>" hidden></label>
	
    
    <label for="mail">jobworker Name:  <input type="text" id="jobworker" name="jobworker" value="<?=$a?>" required></label>
	
	<label for="mail">Alias :  <input type="text" id="alias" name="alias" value="<?=$b?>" ></label>
	
    <button id="btnUpdate">Update</button>
     

</div>

	<!-- Pushy JS -->
        <script src="/<?=$stockManager?>/js/pushy.min.js"></script>


    </body>





    <style>
	*, *:before, *:after {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}

body {
  font-family: 'Nunito', sans-serif;
  color: #384047;
}

form {
  max-width: 300px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #f4f7f8;
  border-radius: 8px;
}

.buyerdetailst {
  max-width: 450px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
h1 {
  margin: 0 0 30px 0;
  text-align: center;
}

input[type="text"],
input[type="password"],
input[type="date"],
input[type="datetime"],
input[type="email"],
input[type="number"],
input[type="search"],
input[type="tel"],
input[type="time"],
input[type="url"],
textarea,
select {
  background: rgba(255,255,255,0.1);
  border: none;
  font-size: 16px;
  height: auto;
  margin: 0;
  outline: 0;
  padding: 15px;
  width: 100%;
  background-color: #e8eeef;
  color: #8a97a0;
  box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
  margin-bottom: 30px;
}

input[type="radio"],
input[type="checkbox"] {
  margin: 0 4px 8px 0;
}

select {
  padding: 6px;
  height: 32px;
  border-radius: 2px;
}

button {
  padding: 19px 39px 18px 39px;
  color: #FFF;
  background-color: #4bc970;
  font-size: 18px;
  text-align: center;
  font-style: normal;
  border-radius: 5px;
  width: 100%;
  border: 1px solid #3ac162;
  border-width: 1px 1px 3px;
  box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
  margin-bottom: 10px;
}

fieldset {
  margin-bottom: 30px;
  border: none;
}

legend {
  font-size: 1.4em;
  margin-bottom: 10px;
}

label {
  display: block;
  margin-bottom: 8px;
}

label.light {
  font-weight: 300;
  display: inline;
}

.number {
  background-color: #5fcf80;
  color: #fff;
  height: 30px;
  width: 30px;
  display: inline-block;
  font-size: 0.8em;
  margin-right: 4px;
  line-height: 30px;
  text-align: center;
  text-shadow: 0 1px 0 rgba(255,255,255,0.2);
  border-radius: 100%;
}

@media screen and (min-width: 480px) {

  form {
    max-width: 480px;
  }

}

	</style>
</html>




