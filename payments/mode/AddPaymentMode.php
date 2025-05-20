<?php
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	

?>

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Add Mode</title>
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="stylesheet" href="/<?=$omenNX?>/css/normalize.css">
        <link rel="stylesheet" href="/<?=$omenNX?>/css/demo.css">
        <!-- Pushy CSS -->
        <link rel="stylesheet" href="/<?=$omenNX?>/css/pushy.css">
        <link rel="stylesheet" href="/<?=$omenNX?>/css/global.css">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    
		</head>


	
    <body>
	
	<?php
		include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	?>
      <form action="AddPaymentModeSubmit.php" enctype="multipart/form-data" method="post">
      



        <h1>Add Payment Mode</h1>
        
        <fieldset>
          <legend><span class="number">1</span>Basic info</legend>
          
          <label for="name">ALIAS:</label>
          <input type="text" id="alias" name="alias" required>
          
	        <label for="mail">Type</label>
		
		
          <?php
            echo "<td><select name='mode_type' required>" ;
    
            
               echo "<option value='BANK'  selected >BANK</option>";
            
           
            echo "</select></td>";
  
        ?>



			<label for="name">A/C Name:</label>
			<input type="text" id="ac_name" name="ac_name" required>
			
			<label for="name">A/C No:</label>
			<input type="text" id="ac_no" name="ac_no" required>

		  <label for="name">BANK:</label>
			<input type="text" id="ac_bank" name="ac_bank" required>

		  
		  
        </fieldset>
        
        <fieldset>
          <legend><span class="number">2</span>Contact Details</legend>

			<label for="mail">Address </label>
			<input type="text" id="address" name="address" required>	          
			
			<label for="mail">City </label>
			<input type="text" id="city" name="city" required>	          
          
	
	
    

	
			<label for="mail">Zip </label>
			<input type="text" id="zip" name="zip" required>	          
          
			<label for="mail">Mobile No: </label>
			<input type="text" id="mobile" name="mobile" required>
			<label for="mail">Email </label>
			<input type="text" id="email" name="email" >
	
	
	
	</fieldset>
        <button type="submit" id="addcustomer">ADD Customer</button>
      </form>
      



	<!-- Pushy JS -->
        <script src="/<?=$omenNX?>/js/pushy.min.js"></script>


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




