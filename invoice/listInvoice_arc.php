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
			$(".updateInvoice").click(function(){
			window.location.replace("updateInvoice.php?bill_id="+$(this).val());


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




.updateInvoice {
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

.updateInvoice:hover {
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


include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	



// Attempt select query execution
$sql = "SELECT B.BILL_ID, B.DATE, DUE_DATE,COMPANY_NAME,LR_LOC,LR, TOTAL_AMOUNT FROM bills_tbl B,customers_tbl C,transport_tbl t WHERE  B.CUSTOMER_ID=C.CUSTOMER_ID and b.bill_id=t.bill_id ORDER BY BILL_ID DESC";

if($result = mysqli_query($dbhandle,$sql)){
    
	if(mysqli_num_rows($result) > 0){
		
        echo "<body><div id='invoicelist'><table>";
            echo "<tr>";
                echo "<th>BILL ID</th>";
                echo "<th>LR</th>";
                echo "<th>DATE</th>";
                echo "<th>COMPANY NAME</th>";
				echo "<th>TOTAL AMOUNT</th>";
				echo "<th>VIEW/UPDATE </th>";
				
		



    
	echo "</tr>";
        while($row = mysqli_fetch_array($result)){
          
                echo "<td><center><a href='showInvoicesecond.php?bill_id=".$row['BILL_ID']."'>".$row['BILL_ID']." </a><center></td>";
                echo "<td><center><a href='/$omenNX/invoice/".$row["LR_LOC"]."'>".$row['LR']."</a> <center></td>";
                echo "<td><center>" . $row['DATE'] . "<center></td>";
                echo "<td><center>" . $row['COMPANY_NAME'] . "<center></td>";
                
                
				echo "<td><center>" . $row['TOTAL_AMOUNT'] . "<center></td>";
				echo "<td><center><button type='submit' class='updateInvoice' value=".$row['BILL_ID'].">UPDATE/VIEW</button><center></td>";
				
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