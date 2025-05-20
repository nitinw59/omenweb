<?php

	include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/mysqlconnectdb.php");
 
	$company_name=$_GET["company_name"];
	$from_date=$_GET["from_date"];
	$to_date=$_GET["to_date"];
	  
		$sqlquery="SELECT * FROM `fabric_merchants_tbl` where COMPANY_NAME='$company_name'";
		$show=mysqli_query($dbhandle,$sqlquery);
		$row=mysqli_fetch_array($show);
		echo "<left>For: M/s. $company_name </left>";
		echo "<left></br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp ".$row['ADDRESS'] ."</left>";
		echo "<left></br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp ".$row['CITY'] ."</left>";
		echo "<left></br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp GSTN-".$row['GSTN'] ."</left>";
		
		echo "</br><table style='width:80%'  align=center><center>";
		echo "<caption>Purchase Statement From: $from_date        To: $to_date </caption>";
		
		echo "<tr>";
				echo "<th><center>BILL_ID</center></th>";
				echo "<th ><center>***DATE***</center></th>";
				echo "<th ><center>DESCRIPTION</center></th>";
				echo "<th ><center>MAKER</center></th>";
				echo "<th ><center>METER</center></th>";
				echo "<th ><center>RATE</center></th>";
				echo "<th><center>AMOUNT</center></th>";
				echo "<th><center>TAX</center></th>";
				
				echo "<th><center>TOTALAMOUNT</center></th>";
				
				echo "<th><center>TOTAL_PAYMENT</center></th>";
				echo "<th><center>PENDING</center></th>";
				
				
				echo "</tr>";
		$TOTAL_AMOUNT=0;
		$TOTAL_PAYMENT=0;
		$TOTAL_PENDING=0;
		$sqlquery="select B.BILL_ID, B.MERCHANT_BILL_NUMBER,B.DATE,B.AMOUNT AS AMOUNT,B.TAX_AMOUNT AS TAX,(B.AMOUNT+B.TAX_AMOUNT) AS TOTAL_AMOUNT , (SELECT SUM(AMOUNT) FROM merchant_payments_tbl WHERE BILL_ID=B.BILL_ID) AS TOTAL_PAYMENT,to_maker,meters,rate,description FROM MERCHANT_BILLS_TBL B,  FABRIC_MERCHANTS_TBL FM WHERE B.FABRIC_MERCHANTS_ID=FM.FABRIC_MERCHANTS_ID AND FM.COMPANY_NAME='".$company_name."' AND B.DATE>='".$from_date."' AND B.DATE<='".$to_date."' ORDER BY B.DATE";
		$show=mysqli_query($dbhandle,$sqlquery);
	
	 while($row=mysqli_fetch_array($show)){
	    
		
		
		 
				echo "<tr>";
				echo "<td><center>".$row['MERCHANT_BILL_NUMBER']."</center></td>";
				echo "<td><center>".$row['DATE']."</center></td>";
				echo "<td><center>".$row['description']."</center></td>";
				echo "<td><center>".$row['to_maker']."</center></td>";
				echo "<td><center>".$row['meters']."</center></td>";
				echo "<td><center>".$row['rate']."</center></td>";
				echo "<td><center>".$row['AMOUNT']."</center></td>";
				echo "<td><center>".$row['TAX']."</center></td>";
				
				echo "<td><center>".$row['TOTAL_AMOUNT']."</center></td>";
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
		
		
		echo "<tr><td colspan='8'><center>Total</center></td><td><center>$TOTAL_AMOUNT</center></td><td><center>$TOTAL_PAYMENT</center></td><td><center>$TOTAL_PENDING</center></td></tr>";
		
		
		echo "</center>	</table>";
		?>
		
	<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
	</style>