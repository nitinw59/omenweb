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
</style>


<?php

include("mysqlconnectdb.php");
include("var.php");




// Attempt select query execution
$sql = "SELECT * FROM items_tbl ORDER BY items_id DESC";

if($result = mysqli_query($dbhandle,$sql)){
    
	if(mysqli_num_rows($result) > 0){
		
        echo "<table>";
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
				
		



    
	echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr><form action='updateItems.php' enctype='multipart/form-data' method='post'>";
                echo "<td><input type='hidden' name='item_id' value=" . $row['items_id'] . ">" . $row['items_id'] . "</td>";
                echo "<td><input type='text' id='description' name='description' required value=" . $row['DESCRIPTION'] . "></td>";
                echo "<td><input type='text' id='maker' name='maker' required value=" . $row['MAKER'] . "></td>";
                echo "<td><input type='text' id='quantity' name='quantity' required value=" . $row['QUANTITY'] . "></td>";
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
                
				echo "<td><input type='text' id='quantity_received' name='quantity_received' required value=" . $row['QUANTITY_RECEIVED'] . "></td>";
				echo "<td><input type='text' id='quantity_alter' name='quantity_alter' required value=" . $row['QUANTITY_ALTER'] . "></td>";
				echo "<td><input type='text' id='rate' name='rate' required value=" . $row['RATE'] . "></td>";
                echo "<td> <input name='uploadedimage' type='file' >  <a href='" . $row['images_path'] . "'> image</a></td>";
                echo "<td><button type='submit'>Order Now</button></td>";
	

            echo "</form></tr>";
        }
        echo "</table>";
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