
<?php
include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");

?>

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>List Customer</title>
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="stylesheet" href="/<?=$omenNX?>/css/normalize.css">
        <link rel="stylesheet" href="/<?=$omenNX?>/css/demo.css">
        <!-- Pushy CSS -->
        <link rel="stylesheet" href="/<?=$omenNX?>/css/pushy.css">
         <link rel="stylesheet" href="/<?=$omenNX?>/css/global.css">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script>
		$(document).ready(function(){
			$(".archiveCustomer").click(function(){
			window.location.replace("archiveCustomer.php?bill_id="+$(this).val());


		});



        $(".updateCustomer").click(function(){
			window.location.replace("updateCustomer.php?COMPANY_NAME="+$(this).val());


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




.archiveCustomer {
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

.archiveCustomer:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}


.updateCustomer {
  background: #FFFF82;
  background-image: -webkit-linear-gradient(top, #FFFF82, #FFFF82);
  background-image: -moz-linear-gradient(top, #FFFF82, #FFFF82);
  background-image: -ms-linear-gradient(top, #FFFF82, #FFFF82);
  background-image: -o-linear-gradient(top, #FFFF82, #FFFF82);
  background-image: linear-gradient(to bottom, #FFFF82, #FFFF82);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #000000;
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.updateCustomer:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}
</style>



<?php
$sql = "SELECT * FROM customers_tbl ORDER BY customer_id DESC";

if($result = mysqli_query($dbhandle,$sql)){
    
	if(mysqli_num_rows($result) > 0){
		
        echo "<body>";
		include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	
		echo"<div id='customerlist'><table>";
            echo "<tr>";
                echo "<th>CUSTOMERS ID</th>";
                echo "<th>First Name</th>";
                
				echo "<th>Last Name</th>";
                echo "<th>COMPANY NAME</th>";
               echo "<th>ADDRESS</th>";
				echo "<th>CITY</th>";
				echo "<th>STATE </th>";
				echo "<th>ZIP </th>";
				echo "<th>MOBILE </th>";
				
				echo "<th>EMAIL </th>";
				echo "<th>Archive </th>";
				echo "<th>Update </th>";
				
				
		



    
	echo "</tr>";
        while($row = mysqli_fetch_array($result)){
                echo "<td><center><label>" . $row['customer_id'] . "</label></center></td>";
                echo "<td><center><label>" . $row['FNAME'] . "</label></center></td>";
                echo "<td><center><label>" . $row['LNAME'] . "</label></center></td>";
                echo "<td><center><label>" . $row['COMPANY_NAME'] . "</label></center></td>";
                
				
				
				
				echo "<td><center><label>" . $row['ADDRESS'] . "</label></center></td>";
                echo "<td><center><label>" . $row['CITY'] . "</label></center></td>";
                
				echo "<td><center><label>".$row['STATE']."</label></center></td>";
				
				
				echo "<td><center><label>" . $row['ZIP'] . "</label></center></td>";
				echo "<td><center><label>" . $row['MOBILE'] . "</label></center></td>";
                echo "<td><center><label>" . $row['EMAIL'] . "</label></center></td>";
              
				
                echo "<td><center><label><button type='submit' class='archiveCustomer' value='".$row['COMPANY_NAME']."'>Archive</button></label></center></td>";
                echo "<td><center><label><button type='submit' class='updateCustomer' value='".$row['COMPANY_NAME']."'>Update</button></label></center></td>";
				

            echo "</tr>";
        }
        echo "</table>";
		
		

			echo "</br><script src='/$omenNX/js/pushy.min.js'></script>";
			echo "</div></body>";
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