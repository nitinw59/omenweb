<?php
 
$server_root="/omenwebNX";

include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");
 
  $action=$_POST["action"];
 
   if($action=="listCustomerInvoice"){
	$company_name=$_POST["company_name"];
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	$sqlquery="select B.BILL_ID,B.DATE,(SELECT SUM(quantity*RATE) FROM bill_items_tbl WHERE BILL_ID=B.BILL_ID) AS TOTAL_AMOUNT, LR_LOC AS LR_LOC FROM bills_tbl B,transport_tbl T, customers_tbl C WHERE B.customer_id=C.customer_id AND B.BILL_ID=T.BILL_ID AND C.COMPANY_NAME='".$company_name."' AND b.DATE>='".$from_date."' AND b.DATE<='".$to_date."'  ORDER BY B.BILL_ID DESC";
     $show=mysqli_query($dbhandle,$sqlquery);
	
	$bills_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$bill;
        $bill['BILL_ID']=$row['BILL_ID'];
		$bill['DATE']=$row['DATE'];
		$bill['AMOUNT']=$row['TOTAL_AMOUNT'];
		
		$bill['LR_LOC']=$row['LR_LOC'];
		
		$bills_list[$row_count]=$bill;
		$row_count++;
		}
		
		//echo $sqlquery;
		echo json_encode($bills_list);
		
  }
  
?>
