<?php

	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
   
  $action=$_POST["action"];
 
   if($action=="listChallan"){
	
	
	
	$sqlquery="select 
			ch.challan_no as 'challanNo',
			ch.DATE as 'DATE',
			ch.BILL_ID as 'BILL_ID',
			C.COMPANY_NAME,
			(select GROUP_CONCAT(chi.quantity) from challan_items_tbl chi where chi.challan_no=ch.challan_no) as 'itemQuantity'  ,
			DATE_FORMAT(tr.DATE, '%d/%m/%Y') as 't_DATE',		
			tr.LR as LR,
			tr.LR_LOC as LR_LOC,
			tr.transport_name as transportName,
			tr.transport_parcels as transportParcels
			
			FROM challan_tbl ch 
            LEFT JOIN
			challan_transport_tbl tr 
			ON
			ch.challan_no=tr.challan_no
            LEFT JOIN 
            customers_tbl C 
            ON 
            ch.customer_id= C.customer_id
			
			order by ch.challan_no desc";
		
		
			
		
	$show=mysqli_query($dbhandle,$sqlquery);
	
	$challan_list;
	$row_count=0;
    while($row=mysqli_fetch_array($show)){
       

		$challan;
		$challan['challanNo']=$row['challanNo'];
		$challan['BILL_ID']=$row['BILL_ID'];
		$challan['DATE']=date('d/m/Y', strtotime($row['DATE']));
		$challan['COMPANY_NAME']=$row['COMPANY_NAME'];
		$challan['itemQuantity']=substr($row['itemQuantity'],0,30);
		$challan['transportName']=$row['transportName'];
		$challan['bookingDate']=$row['t_DATE'];
		$challan['LR']=$row['LR'];
		$challan['LR_LOC']=$row['LR_LOC'];
		$challan['transportParcels']=$row['transportParcels'];
		
		
		$challan_list[$row_count]=$challan;
		$row_count++;
		}
		
		
		echo json_encode($challan_list);
		
  }
  
?>
