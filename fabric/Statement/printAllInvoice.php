<?php
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
		$company_name=$_GET["company_name"];
	$from_date=$_GET["from_date"];
	$to_date=$_GET["to_date"];
	
	
	
	$sql = "SELECT B.BILL_id, DATE, COMPANY_NAME, AMOUNT FROM MERCHANT_BILLS_TBL B,FABRIC_MERCHANTS_TBL C WHERE B.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND b.DATE>='$from_date' AND b.DATE<='$to_date' ORDER BY date asc";

	$show=mysqli_query($dbhandle,$sql);
	
		echo "<table style='width:80%'  align=center><center>";
		echo "<caption>Suppliers Invoice Statement Of <b>$MY_COMPANY_GSTN($MY_COMPANY_NAME)</b>  From: $from_date        To: $to_date </caption>";
		
		echo "<tr>";
				echo "<th><center>BILL_NO</center></th>";
				echo "<th ><center>*****DATE*****</center></th>";
				echo "<th><center>SUPPLIER</center></th>";
				
				echo "<th><center>AMOUNT</center></th>";
				
				echo "<th><center>TOTAL_AMOUNT</center></th>";
				
				
				echo "</tr>";
		
				
		while($row=mysqli_fetch_array($show)){
	    	$bill;
        
		$bill['BILL_NO']=$row['BILL_id'];
		$bill['DATE']=$row['DATE'];
		$bill['COMPANY_NAME']=$row['COMPANY_NAME'];
		$bill['AMOUNT']=$row['AMOUNT'];
		
		
		 
				echo "<tr>";
				echo "<td><center>".$bill['BILL_NO']."</center></td>";
				echo "<td><center>".$bill['DATE']."</center></td>";
				echo "<td><center>".$bill['COMPANY_NAME']."</center></td>";
				
				
				echo "<td><center>".$bill['AMOUNT']."</center></td>";
					
				
				echo "</tr>";
				
				
		
		}
		
		
		echo "<tr>";
				echo "<td colspan='5'><center>TOTAL</center></td>";
				
				echo "<td><center></center></td>";
				
				
				
				echo "</tr>";
		
		echo "</center>	</table>";
		?>
		
	<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
	</style>