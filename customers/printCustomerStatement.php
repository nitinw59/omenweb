

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>

  </script>
  
  

<?php

	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");

	$company_name=$_GET["company_name"];
	$initialBalance=0;
	$totalCredits=0;
	$totalDebits=0;

	
	$from_date=$_GET["from_date"];
	$to_date=$_GET["to_date"];
	


	$legacyDateObj=new DateTime($legacy_v1_date);
	$fromDateObj=new DateTime($from_date);
	$toDateObj=new DateTime($to_date);
	
	

	$flag=0;	//1.all new 	2.old and new	3.old
	
	if($fromDateObj>$legacyDateObj)
		$flag=1;	
	else {
		if($toDateObj>$legacyDateObj)
		$flag=2;
		else
		$flag=3;
		
	}


	

	$sqlquery="select SUM(B.TOTAL_AMOUNT) AS TOTALAMOUNT FROM bills_tbl B,  customers_tbl C 
				WHERE B.customer_id=C.customer_id AND C.COMPANY_NAME='".$company_name."'  AND b.DATE<'".$from_date."' order by  b.BILL_ID desc";
    $show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	$old_bill_amount=$row['TOTALAMOUNT'];
	
	
	$sqlquery="select SUM(cr.AMOUNT) AS TOTALPAYMENT FROM credits_tbl cr,  customers_tbl C WHERE cr.customer_id=C.customer_id AND C.COMPANY_NAME='".$company_name."'  AND cr.DATE<'".$from_date."' ";
    $show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	if($row['TOTALPAYMENT']==null)
			$row['TOTALPAYMENT']=0;
		
	$old_payment=$row['TOTALPAYMENT'];
	$old_balance=$old_bill_amount-$old_payment;



	
	if($flag==2){  //old and new both
		

	$sqlquerylegacy="select 
		B.BILL_ID as 'BILL ID',
		B.DATE as 'DATE',
		(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_QUANTITY'  ,
		(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_RATE'  ,
		(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
		DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
		tr.LR as LR,
		LR_LOC as LR_LOC,
		tr.transport_name as transport_name,
		tr.transport_parcels as transport_parcels,
		0 as 'PAYMENT AMOUNT',	
		0 as 'PAYMENT DESCRIPTION'

			FROM bills_tbl B,
				transport_tbl tr,
				customers_tbl C 
	
			WHERE 

			B.BILL_ID=tr.BILL_ID AND
			B.customer_id=C.customer_id AND
			C.COMPANY_NAME='$company_name' AND
			b.DATE>='$from_date' AND b.DATE<='$legacy_v1_date' 


		UNION ALL

		SELECT 

		0 as 'BILL ID',
		c.date AS 'DATE',
		0 as 'ITEM_QUANTITY',
		0 as 'ITEM_RATE',
		0 as 'BILL AMOUNT',
		'' as t_date,
		'' as LR,
		'' as LR_LOC,
		'' as transport_name,
		0 as transport_parcels,
		c.amount as 'PAYMENT AMOUNT',
		c.DESCRIPTION as 'PAYMENT DESCRIPTION'


		FROM credits_tbl c,
		customers_tbl Cr 

		WHERE
		cr.customer_id=c.customer_id 
		AND cr.COMPANY_NAME='$company_name' 
		AND c.DATE>='$from_date' 
		AND c.DATE<='$legacy_v1_date'
	
		order by date asc";
		
		
		
	$sqlqueryNew="select 
	B.BILL_ID as 'BILL ID',
	B.DATE as 'DATE',
	(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_QUANTITY'  ,
	(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_RATE'  ,
	(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
	DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
	tr.LR as LR,
	LR_LOC as LR_LOC,
	tr.transport_name as transport_name,
	tr.transport_parcels as transport_parcels,
	0 as 'PAYMENT AMOUNT',	
	0 as 'PAYMENT DESCRIPTION'

	FROM bills_tbl B,
	challan_transport_tbl tr,
	challan_tbl ch,	
	customers_tbl C 
	
	WHERE 

	B.BILL_ID=ch.BILL_ID AND
	ch.challan_no=tr.challan_no AND

	B.customer_id=C.customer_id AND
	C.COMPANY_NAME='$company_name' AND
	b.DATE>='$legacy_v1_date' AND b.DATE<='$to_date' 


	UNION ALL



	SELECT 

	0 as 'BILL ID',
	c.date AS 'DATE',
	0 as 'ITEM_QUANTITY',
	0 as 'ITEM_RATE',
	0 as 'BILL AMOUNT',
	'' as t_date,
	'' as LR,
	'' as LR_LOC,
	'' as transport_name,
	0 as transport_parcels,
	c.amount as 'PAYMENT AMOUNT',
	c.DESCRIPTION as 'PAYMENT DESCRIPTION'


	FROM credits_tbl c,
	customers_tbl Cr 

	WHERE
	cr.customer_id=c.customer_id 
	AND cr.COMPANY_NAME='$company_name' 
	AND c.DATE>'$legacy_v1_date' 
	AND c.DATE<='$to_date'
	
	order by date asc";	
		
	}elseif($flag==1){// all new
		

		
	$sqlqueryNew="select 
	B.BILL_ID as 'BILL ID',
	B.DATE as 'DATE',
	(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_QUANTITY'  ,
	(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_RATE'  ,
	(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
	DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
	tr.LR as LR,
	LR_LOC as LR_LOC,
	tr.transport_name as transport_name,
	tr.transport_parcels as transport_parcels,
	0 as 'PAYMENT AMOUNT',	
	0 as 'PAYMENT DESCRIPTION'

	FROM bills_tbl B,
	challan_transport_tbl tr,
	challan_tbl ch,	
	customers_tbl C 
	
	WHERE 

	B.BILL_ID=ch.BILL_ID AND
	ch.challan_no=tr.challan_no AND

	B.customer_id=C.customer_id AND
	C.COMPANY_NAME='$company_name' AND
	b.DATE>='$from_date' AND b.DATE<='$to_date' 


	UNION ALL



	SELECT 

	0 as 'BILL ID',
	c.date AS 'DATE',
	0 as 'ITEM_QUANTITY',
	0 as 'ITEM_RATE',
	0 as 'BILL AMOUNT',
	'' as t_date,
	'' as LR,
	'' as LR_LOC,
	'' as transport_name,
	0 as transport_parcels,
	c.amount as 'PAYMENT AMOUNT',
	c.DESCRIPTION as 'PAYMENT DESCRIPTION'


	FROM credits_tbl c,
	customers_tbl Cr 

	WHERE
	cr.customer_id=c.customer_id 
	AND cr.COMPANY_NAME='$company_name' 
	AND c.DATE>='$from_date' 
	AND c.DATE<='$to_date'
	
	order by date asc";	
	}elseif($flag==3){// all old legacy
		

		$sqlquerylegacy="select 
		B.BILL_ID as 'BILL ID',
		B.DATE as 'DATE',
		(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_QUANTITY'  ,
		(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_RATE'  ,
		(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
		DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
		tr.LR as LR,
		LR_LOC as LR_LOC,
		tr.transport_name as transport_name,
		tr.transport_parcels as transport_parcels,
		0 as 'PAYMENT AMOUNT',	
		0 as 'PAYMENT DESCRIPTION'

			FROM bills_tbl B,
				transport_tbl tr,
				customers_tbl C 
	
			WHERE 

			B.BILL_ID=tr.BILL_ID AND
			B.customer_id=C.customer_id AND
			C.COMPANY_NAME='$company_name' AND
			b.DATE>='$from_date' AND b.DATE<='$to_date' 


		UNION ALL

		SELECT 

		0 as 'BILL ID',
		c.date AS 'DATE',
		0 as 'ITEM_QUANTITY',
		0 as 'ITEM_RATE',
		0 as 'BILL AMOUNT',
		'' as t_date,
		'' as LR,
		'' as LR_LOC,
		'' as transport_name,
		0 as transport_parcels,
		c.amount as 'PAYMENT AMOUNT',
		c.DESCRIPTION as 'PAYMENT DESCRIPTION'


		FROM credits_tbl c,
		customers_tbl Cr 

		WHERE
		cr.customer_id=c.customer_id 
		AND cr.COMPANY_NAME='$company_name' 
		AND c.DATE>='$from_date' 
		AND c.DATE<='$to_date'
	
		order by date asc";
		
	
	}
		
		//echo $sqlquery;
				
		echo "<title> Print Customer Statement</title>";
		echo "<nitin><left></br></br><h3>For: M/s. $company_name </h3></left>";
		echo "<table style='width:80%'  align=center><center>";
		echo "<caption>Invoice Statement From:     ".date('d/m/Y', strtotime($from_date))."       To: ".date('d/m/Y', strtotime($to_date))." </caption>";
		
		echo "<tr>";
				echo "<th><center>BILL_ID</center></th>";
				echo "<th ><center>*****DATE*****</center></th>";
				echo "<th><center>TRANSPORT</center></th>";
				echo "<th><center>BOOKING DATE</center></th>";
				
				echo "<th><center>LR</center></th>";
				
				echo "<th><center>PARCELS</center></th>";
				echo "<th><center>QUANTITY</center></th>";
				echo "<th><center>RATE</center></th>";
				echo "<th><center>DESCRIPTION</center></th>";
				echo "<th><center>BILL AMOUNT</center></th>";
				echo "<th><center>PAYMENT</center></th>";
				echo "<th><center>BALANCE</center></th>";
				
				
				echo "</tr>";
		
		$bill;
        
		$bill['BILL ID']="-";
		$bill['DATE']='-';
		$bill['ITEM_QUANTITY']="-";
		$bill['ITEM_RATE']="-";
		$bill['BILL AMOUNT']="-";
		$bill['t_date']='-';
		$bill['LR']="-";
		$bill['transport_name']='-';
		$bill['transport_parcels']="-";
		$bill['PAYMENT AMOUNT']="-";
		$bill['PAYMENT DESCRIPTION']='-';
				
				 
				echo "<tr>";
				echo "<td><center>".$bill['BILL ID']."</center></td>";
				echo "<td><center>".$bill['DATE']."</center></td>";
				echo "<td><center>".$bill['transport_name']."</center></td>";
				
				echo "<td><center>".$bill['t_date']."</center></td>";
				
				echo "<td><center>".$bill['LR']."</center></td>";
				echo "<td><center>".$bill['transport_parcels']."</center></td>";
				
				echo "<td><center>".$bill['ITEM_QUANTITY']."</center></td>";
				echo "<td><center>".$bill['ITEM_RATE']."</center></td>";
				echo "<td><center>".$bill['PAYMENT DESCRIPTION']."</center></td>";
				echo "<td><center>".$bill['BILL AMOUNT']."</center></td>";
				echo "<td><center>".$bill['PAYMENT AMOUNT']."</center></td>";
				echo "<td><center>".$old_balance."</center></td>";
				
				echo "</tr>";

				$initialBalance=$old_balance;
				
		$count =0;
		$billList;		
		if($flag==2 or $flag==3){		
		$show=mysqli_query($dbhandle,$sqlquerylegacy);
				
					while($row=mysqli_fetch_array($show)){
					$bill;
					
					$bill['BILL ID']=$row['BILL ID'];
					$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
					$bill['BILL AMOUNT']=$row['BILL AMOUNT'];
					$bill['t_date']=$row['t_date'];
					$bill['LR']=$row['LR'];
					$bill['transport_name']=$row['transport_name'];
					$bill['transport_parcels']=$row['transport_parcels'];
					$bill['PAYMENT AMOUNT']=$row['PAYMENT AMOUNT'];
					$bill['PAYMENT DESCRIPTION']=$row['PAYMENT DESCRIPTION'];
					$bill['ITEM_QUANTITY']=$row['ITEM_QUANTITY'];
					$bill['ITEM_RATE']=$row['ITEM_RATE'];
					$billList[$count]=$bill;
					$count++;
					}
		
	
		}

		if($flag==2 or $flag==1){
			$show=mysqli_query($dbhandle,$sqlqueryNew);
				
					while($row=mysqli_fetch_array($show)){
					$bill;
					
					$bill['BILL ID']=$row['BILL ID'];
					$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
					$bill['BILL AMOUNT']=$row['BILL AMOUNT'];
					$bill['t_date']=$row['t_date'];
					$bill['LR']=$row['LR'];
					$bill['transport_name']=$row['transport_name'];
					$bill['transport_parcels']=$row['transport_parcels'];
					$bill['PAYMENT AMOUNT']=$row['PAYMENT AMOUNT'];
					$bill['PAYMENT DESCRIPTION']=$row['PAYMENT DESCRIPTION'];
					$bill['ITEM_QUANTITY']=$row['ITEM_QUANTITY'];
					$bill['ITEM_RATE']=$row['ITEM_RATE'];
					$billList[$count]=$bill;
					$count++;
					}


		}
		



		foreach($billList as $bill){

		$old_balance=$old_balance+$bill['BILL AMOUNT']-$bill['PAYMENT AMOUNT'];
		
		 if($bill["PAYMENT AMOUNT"]==0){
			$bill["PAYMENT AMOUNT"]="-";
			$bill["PAYMENT DESCRIPTION"]="-";
			$totalDebits+=$bill['BILL AMOUNT'];
			
								
		}else{
			$bill['BILL AMOUNT']="-";
			$bill["BILL ID"]="-";
			$bill["transport_name"]="-";
			$bill["LR"]="-";
			$bill["transport_parcels"]="-";
			$bill["ITEM_QUANTITY"]="-";
			$bill["ITEM_RATE"]="-";
			$totalCredits+=$bill['PAYMENT AMOUNT'];
		}
				echo "<tr>";
				echo "<td><center>".$bill['BILL ID']."</center></td>";
				echo "<td><center>".$bill['DATE']."</center></td>";
				echo "<td><center>".$bill['transport_name']."</center></td>";
				
				echo "<td><center>".$bill['t_date']."</center></td>";
				
				echo "<td><center>".$bill['LR']."</center></td>";
				echo "<td><center>".$bill['transport_parcels']."</center></td>";
				
				echo "<td><center>".$bill['ITEM_QUANTITY']."</center></td>";
				echo "<td><center>".$bill['ITEM_RATE']."</center></td>";
				echo "<td><center>".$bill['PAYMENT DESCRIPTION']."</center></td>";
				echo "<td><center>".$bill['BILL AMOUNT']."</center></td>";
				echo "<td><center>".$bill['PAYMENT AMOUNT']."</center></td>";
				echo "<td><center>".$old_balance."</center></td>";
				
				
				
				echo "</tr>";
			}		
				
		
		
		
		echo "<tr><td colspan='11'><center>Total Balance Till Date: ".date('d/m/Y', strtotime($to_date))."</center></td><td><center>$old_balance</center></td></tr>";
		
		
		echo "</center>	</table>";


		echo "</br></br>INITIAL BALANCE:$initialBalance";
		echo "</br></br>TOTAL CREDITS:$totalCredits";
		echo "</br></br>TOTAL DEBITS:$totalDebits";
		?>
		
	<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
	</style>