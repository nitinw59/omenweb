


<script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

  <script>
    function downloadAsPDF() {
			
			const queryString = window.location.search;
			const urlParams = new URLSearchParams(queryString);
			
			
			
			const element = document.querySelector('nitin');
			const opt = {
				filename: urlParams.get('company_name')+'_'+urlParams.get('to_date'),
				margin: 5,
				image: {type: 'jpeg', quality: 0.9},
				jsPDF: {format: 'a3', orientation: 'landscape'}
				};
			// New Promise-based usage:
			html2pdf().set(opt).from(element).save();	
		
    //  var element = document.querySelector('body');
    // html2pdf(element);
    }
  </script>
  
  

<?php

	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
	$company_name=$_GET["company_name"];
	$from_date=$_GET["from_date"];
	$to_date=$_GET["to_date"];
	
	
	//fetch credits
	
	$sqlquery="select ADVANCE_CREDITS FROM FABRIC_MERCHANTS_TBL C WHERE C.COMPANY_NAME='".$company_name."'  ";
    $show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	$ADVANCE_CREDITS=$row['ADVANCE_CREDITS'];
	
	
	//fetch old balance pending
	
	$sqlquery="
	select 
			SUM(B.AMOUNT) as 'total_amount' ,
			SUM(b.payment_amount) as 'total_payment'
			FROM MERCHANT_BILLS_TBL B,
			
			FABRIC_MERCHANTS_TBL C 
			
			WHERE 
			B.DATE<'$from_date' AND
			B.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND
			C.COMPANY_NAME='$company_name'

			ORDER BY DATE ASC	
	 
	 
	"; 
	$show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	$old_amount=$row['total_amount'];
	$old_payment=$row['total_payment'];
	$old_balance=$old_amount-$old_payment;
	
	
	//fetch bills 
	
	
	$sqlquery="select 
			B.BILL_ID as 'BILL ID',
			B.DATE as 'DATE',
			B.meter as 'MTR'  ,
			B.meterRate as 'RATE'  ,
			B.AMOUNT AS 'BILL_AMOUNT',
			B.loc AS 'loc',
			payment_amount as 'PAYMENT AMOUNT',	
			payment_description as 'PAYMENT DESCRIPTION'

			FROM MERCHANT_BILLS_TBL B,
			
			FABRIC_MERCHANTS_TBL C 
			
			WHERE 

			B.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND
			C.COMPANY_NAME='$company_name' AND
			b.DATE>='$from_date' AND b.DATE<='$to_date' 
    
			order by date asc";
		
		
		$show=mysqli_query($dbhandle,$sqlquery);
	
		echo "<nitin><left></br></br><h3>For: M/s. $company_name </h3></left>";
		echo "<table style='width:80%'  align=center><center>";
		echo "<caption>Purchase Statement From:     ".date('d/m/Y', strtotime($from_date))."       To: ".date('d/m/Y', strtotime($to_date))." </caption>";
		
		echo "<tr>";
				echo "<th><center>BILL_ID</center></th>";
				echo "<th ><center>*****DATE*****</center></th>";
				echo "<th><center>MTR</center></th>";
				echo "<th><center>RATE</center></th>";
				
				echo "<th><center>BILL AMOUNT</center></th>";
				
				echo "<th><center>PAYMENT DATE</center></th>";
				echo "<th><center>AMOUNT</center></th>";
				
				
				
				echo "</tr>";
		
		$TOTALAMOUNT=0;
		$TOTALMTR=0;
				
		echo "<tr>";
				
		echo "<td style='height:30px'><center></center></td>";
		echo "<td><center></center></td>";
		echo "<td><center></center></td>";
		echo "<td><center></center></td>";
		echo "<td><center></center></td>";
		echo "<td><center>OLD BALANCE</center></td>";
		echo "<td><center>$old_balance</center></td>";
		
		echo "</tr>";		
				
				
		while($row=mysqli_fetch_array($show)){
	    
		
		$bill;
        $bill['BILL ID']=$row['BILL ID'];
		$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
		$bill['BILL_AMOUNT']=$row['BILL_AMOUNT'];
		$bill['MTR']=(double)$row['MTR'];
		$bill['RATE']=$row['RATE'];
		$bill['PAYMENT AMOUNT']=$row['PAYMENT AMOUNT'];
		if($bill['PAYMENT AMOUNT']==0)
			$bill['PAYMENT AMOUNT']="";
				
				echo "<tr>";
				echo "<td style='height:30px'><center>".$bill['BILL ID']."</center></td>";
				echo "<td><center>".$bill['DATE']."</center></td>";
				echo "<td><center>".$bill['MTR']."</center></td>";
				
				echo "<td><center>".$bill['RATE']."</center></td>";
				
				echo "<td><center>".$bill['BILL_AMOUNT']."</center></td>";
				echo "<td><center></center></td>";
				
				echo "<td><center>".$bill['PAYMENT AMOUNT']."</center></td>";
				
				
				echo "</tr>";
				$TOTALAMOUNT=$TOTALAMOUNT+$bill['BILL_AMOUNT'];
				$TOTALMTR=$TOTALMTR+$bill['MTR'];
		
				
		
		}
		
		
		echo "<tr><td colspan='4'><center>Total AMOUNT From Date:".date('d/m/Y', strtotime($from_date))." TO Date: ".date('d/m/Y', strtotime($to_date))."</center></td><td><center>$TOTALAMOUNT</center></td></tr>";
		echo "<tr><td colspan='6'><center>Total MTR From Date:".date('d/m/Y', strtotime($from_date))." TO Date: ".date('d/m/Y', strtotime($to_date))."</center></td><td><center>$TOTALMTR</center></td></tr>";
		
		
		echo "</center>	</table></nitin>";
		echo " <p><button onclick='downloadAsPDF()'>Download as PDF</button></p>";
		?>
		
	<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
	</style>