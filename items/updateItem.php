  <?php
include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");

?>

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Pushy - Off-Canvas Navigation Menu</title>
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="stylesheet" href="/<?=$omenNX?>/css/normalize.css">
        <link rel="stylesheet" href="/<?=$omenNX?>/css/demo.css">
        <!-- Pushy CSS -->
        <link rel="stylesheet" href="/<?=$omenNX?>/css/pushy.css">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
   </head>

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

	
	
	
	<?php

$discount=100;


$selected = mysqli_select_db($dbhandle,$dbname) 
  or die(mysql_error());

// Attempt select query execution
$sql = "SELECT  *  FROM items_tbl where items_id='".$_GET["item_id"]."' ORDER BY  items_id";

if($result = mysqli_query($dbhandle,$sql) ){
	if(mysqli_num_rows($result) > 0){
		
		$row = mysqli_fetch_array($result);
		
		$ITEMS_ID = $row['items_id'] ;
        $DESCRIPTION =   $row['DESCRIPTION'] ;
        $MAKER =  $row['MAKER'];
        $QUANTITY =    $row['QUANTITY'];
        $SIZE  = $row['SIZE'] ;
        $QUANTITY_RECEIVED = $row['QUANTITY_RECEIVED'] ;
        $QUANTITY_ALTER  = $row['QUANTITY_ALTER'] ;
        $RATE  = $row['RATE'] ;	
		$images_path=$row['images_path'];	
			
		
	}
}  

?>
	
	
	
    <body>
	
<?php
		

		include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
		?>

      <form action="updateItemSubmit.php" enctype="multipart/form-data" method="post">
      



        <h1>Add New Item</h1>
        
        <fieldset>
          <legend><span class="number">1</span>Item basic info</legend>
          
		  
		  <label for="name">Items Id:</label>
          <input type="text" id="items_id" name="items_id" value="<?=$ITEMS_ID?>" required>
          
          <label for="name">Description:</label>
          <input type="text" id="description" name="description"  value="<?=$DESCRIPTION?>" required>

		  <label for="mail">Maker :</label>
          <input type="text" id="maker" name="maker" value="<?=$MAKER?>" required>	 
		  
		  
		  
		  
		  <label for="mail">Size</label>
		
		
	<?php
	
	echo "<td><select name='Size'>" ;
				
				for($i=0;$i<count($sizes);$i++){ 
				echo "<option value='".$sizes[$i]."'";
					if($sizes[$i]==$row['SIZE']){
					echo " selected >".$sizes[$i]."</option>";
					}else{
						echo ">".$sizes[$i]."</option>";
					}
				}
				echo "</select></td>";

	?>
		  
        </fieldset>
        
        <fieldset>
          <legend><span class="number">2</span>Item Quantity </legend>


	         
          
	
	<label for="mail">Quantity</label>
          <input type="number" id="quantity" name="quantity"  value="<?=$QUANTITY?>" required>
    

	
	<label for="mail">Quantity Received</label>
    
	<input type="number" id="quantityrec" name="quantityrec" value="<?=$QUANTITY_RECEIVED?>" required>
    
	<label for="mail">Quantity Alt</label>
          <input type="number" id="quantityalt" name="quantityalt" value="<?=$QUANTITY_ALTER?>" required>
    
	<label for="mail">Rate</label>
          <input type="number" id="rate" name="rate" value="<?=$RATE?>" required>
    <label for="mail">Tax Rate</label>
          <input type="number" id="taxrate" name="taxrate" value="<?=$ITEMS_ID?>" required>
    	
	<!--<label for="bio">Caption:</label>
          <textarea id="Caption" name="user_caption" required></textarea>
        -->
	</fieldset>
        <fieldset>
        <label for="job">File :</label>
        <input name="uploadedimage" type="file" value="null" >
	</fieldset>


	<!-- <input type="radio" name="productid" value="6"> Permanent (1 Posts)- <s>$49.99</s>  $0.00<font color="red">(100% Off)</font><br> -->
	
	
        <button type="submit">Update</button>
      </form>
      

 <script src="/<?=$omenNX?>/js/pushy.min.js"></script>

    </body>
</html>




