<?php
  include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/mysqlconnectdb.php");
 
   
  //$action="listInvoice";
  $action=$_POST["action"];
 
   if($action=="listInvoice"){
	
	$sqlqueryV1="select 
	B.BILL_ID as 'BILL ID',
	B.DATE as 'DATE',
	  C.COMPANY_NAME,
	(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_QUANTITY'  ,
	(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_RATE'  ,
	(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
	DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
	tr.LR as LR,
	LR_LOC as LR_LOC,
	tr.transport_name as transport_name,
	tr.transport_parcels as transport_parcels
	
	FROM bills_tbl B,
	transport_tbl tr,
	
	customers_tbl C 
	
	WHERE 

	

	B.customer_id=C.customer_id 
	AND
	b.BILL_ID=tr.BILL_ID
	order by b.BILL_ID DESC;";
	
	$sqlqueryV2="select 
	B.BILL_ID as 'BILL ID',
	B.DATE as 'DATE',
	  C.COMPANY_NAME,
	(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_QUANTITY'  ,
	(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=b.BILL_ID) as 'ITEM_RATE'  ,
	(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
	DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
	tr.LR as LR,
	LR_LOC as LR_LOC,
	tr.transport_name as transport_name,
	tr.transport_parcels as transport_parcels
	
	FROM bills_tbl B,
	challan_transport_tbl tr,
	
	customers_tbl C 
	
	WHERE 

	B.challanNo=tr.challan_no AND

	B.customer_id=C.customer_id 
	order by b.BILL_ID DESC";
		
		
			
		
	$show=mysqli_query($dbhandle,$sqlqueryV2);
	
	$bills_list;
	$row_count=0;
    while($row=mysqli_fetch_array($show)){
		$bill;
        
		$bill['BILL ID']=$row['BILL ID'];
		$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
		$bill['BILL AMOUNT']=$row['BILL AMOUNT'];
		$bill['ITEM_QUANTITY']=substr($row['ITEM_QUANTITY'],0,15);
		$bill['ITEM_RATE']=substr($row['ITEM_RATE'],0,20);
		$bill['COMPANY_NAME']=$row['COMPANY_NAME'];
		$bill['t_date']=$row['t_date'];
		$bill['LR']=$row['LR'];
		$bill['LR_LOC']=$row['LR_LOC'];
		$bill['transport_name']=$row['transport_name'];
		$bill['transport_parcels']=$row['transport_parcels'];
		
		$bills_list[$row_count]=$bill;
		$row_count++;
		
		}
		
		//echo $old_bill_amount;
		//echo $sqlquery;
		
		$show=mysqli_query($dbhandle,$sqlqueryV1);
		
		while($row=mysqli_fetch_array($show)){
		
		$bill;
        $bill['BILL ID']=$row['BILL ID'];
		$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
		$bill['BILL AMOUNT']=$row['BILL AMOUNT'];
		$bill['ITEM_QUANTITY']=substr($row['ITEM_QUANTITY'],0,15);
		$bill['ITEM_RATE']=substr($row['ITEM_RATE'],0,20);
		$bill['COMPANY_NAME']=$row['COMPANY_NAME'];
		$bill['t_date']=$row['t_date'];
		$bill['LR']=$row['LR'];
		$bill['LR_LOC']="ARCHIEVED/".$row['LR_LOC'];
		$bill['transport_name']=$row['transport_name'];
		$bill['transport_parcels']=$row['transport_parcels'];
		$bills_list[$row_count]=$bill;
		$row_count++;
		}
		
		//echo $old_bill_amount;
		//echo $sqlquery;

		
		
		echo json_encode($bills_list);
		
  }
  
?>
