<?php

$server_root="/omenwebNX";

include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");

	$company_name=$_GET["company_name"];
	$from_date=$_GET["from_date"];
	$to_date=$_GET["to_date"];
	  
	$sqlquery="select B.BILL_ID,B.DATE,(SELECT SUM(quantity*RATE) FROM bill_items_tbl WHERE BILL_ID=B.BILL_ID) AS TOTAL_AMOUNT, (SELECT SUM(AMOUNT) FROM payments_tbl WHERE BILL_ID=B.BILL_ID) AS TOTAL_PAYMENT FROM bills_tbl B, customers_tbl C WHERE B.customer_id=C.customer_id AND C.COMPANY_NAME='".$company_name."' AND b.DATE>='".$from_date."' AND b.DATE<='".$to_date."'  ORDER BY B.BILL_ID DESC";
    $show=mysqli_query($dbhandle,$sqlquery);
	
		echo "<left></br></br><h3>For: M/s. $company_name </h3></left>";
		echo "<table style='width:80%'  align=center><center>";
		echo "<caption>Invoice Statement From: $from_date        To: $to_date </caption>";
		
		echo "<tr>";
				echo "<th><center>BILL_ID</center></th>";
				echo "<th width='20%'><center>DATE</center></th>";
				echo "<th><center>AMOUNT</center></th>";
				
				echo "<th><center>TOTALAMOUNT</center></th>";
				
				echo "<th><center>TOTAL_PAYMENT</center></th>";
				echo "<th><center>PENDING</center></th>";
				
				
				echo "</tr>";
		$TOTAL_AMOUNT=0;
		$TOTAL_PAYMENT=0;
		$TOTAL_PENDING=0;
		
	 while($row=mysqli_fetch_array($show)){
	    
		
		
		 
				echo "<tr>";
				echo "<td><center>".$row['BILL_ID']."</center></td>";
				echo "<td><center>".$row['DATE']."</center></td>";
				echo "<td><center>".$row['TOTAL_AMOUNT']."</center></td>";
				echo "<td><center>0</center></td>";
				
				if($row['TOTAL_PAYMENT']==null)
					$row['TOTAL_PAYMENT']=0;
				echo "<td><center>".$row['TOTAL_PAYMENT']."</center></td>";
				$pending=($row['TOTAL_AMOUNT']-$row['TOTAL_PAYMENT']);
				echo "<td><center>".$pending."</center></td>";
				
				
				echo "</tr>";
				
				$TOTAL_AMOUNT+=$row['TOTAL_AMOUNT'];
				$TOTAL_PAYMENT+=$row['TOTAL_PAYMENT'];
				$TOTAL_PENDING+=$pending;
				
		
		}
		
		
		echo "<tr><td colspan='4'><center>Total</center></td><td><center>$TOTAL_AMOUNT</center></td><td><center>$TOTAL_PAYMENT</center></td><td><center>$TOTAL_PENDING</center></td></tr>";
		
		
		echo "</center>	</table>";
		?>
		
	<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
	</style>