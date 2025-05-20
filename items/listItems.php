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
   
		<script>
		$(document).ready(function(){
			$(".updateItem").click(function(){
			window.location.replace("updateItem.php?item_id="+$(this).val());


		});
		});
</script>
   </head>


<style>
table {
    border-collapse: collapse;
    width: 100%;
}

td {
    text-align: left;
    padding: 8px;
}

th {
    background-color: #4CAF50;
    color: white;
}



tr:nth-child(even){background-color: #f2f2f2}



.updateItem {
  background: #3498db;
  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
  background-image: -o-linear-gradient(top, #3498db, #2980b9);
  background-image: linear-gradient(to bottom, #3498db, #2980b9);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #ffffff;
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.updateItem:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}


#itemslist {
    width: 100%;
	height: 100%;
    overflow: scroll;
}	

</style>


<?php
include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
		




// Attempt select query execution
$sql = "SELECT * FROM items_tbl ORDER BY items_id DESC";

if($result = mysqli_query($dbhandle,$sql)){
    
	if(mysqli_num_rows($result) > 0){
		
        echo "<div id='itemslist'><table>";
            echo "<tr>";
                echo "<th>ITEMS ID</th>";
                echo "<th>DESCRIPTION</th>";
                echo "<th>MAKER</th>";
                echo "<th>QUANTITY</th>";
				echo "<th>SIZE</th>";
				echo "<th>QUANTITY RECEIVED</th>";
				echo "<th>QUANTITY ALTER</th>";
				echo "<th>RATE </th>";
				echo "<th>IMAGE </th>";
				echo "<th>Update </th>";
				
		



    
	echo "</tr>";
        while($row = mysqli_fetch_array($result)){
          
                echo "<td><input type='hidden' name='item_id' value=" . $row['items_id'] . ">" . $row['items_id'] . "</td>";
                echo "<td>" . $row['DESCRIPTION'] . "</td>";
                echo "<td>" . $row['MAKER'] . "</td>";
                echo "<td>" . $row['QUANTITY'] . "</td>";
                echo "<td>".$row['SIZE']."</td>";
                
				echo "<td> <center>" . $row['QUANTITY_RECEIVED'] . "</center></td>";
				echo "<td><center>" . $row['QUANTITY_ALTER'] . "</center></td>";
				echo "<td>" . $row['RATE'] . "</td>";
                echo "<td><a href='" . $row['images_path'] . "'> image</a></td>";
                echo "<td><button type='submit' class='updateItem' value='".$row['items_id']."'>Update</button></td>";
	

            echo "</tr>";
        }
        echo "</table></div>";
		
		
			echo  "<script src='/$omenNX/js/pushy.min.js'></script>";
			echo "</body>";

        // Close result set
        mysqli_free_result($result);
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($dbhandle);
?>