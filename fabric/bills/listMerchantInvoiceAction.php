<?php
  include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/mysqlconnectdb.php");
 
   
  $action=$_POST["action"];
 
   if($action=="listMerchantInvoice"){
	$company_name=$_POST["company_name"];
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	 $sqlquery="select * FROM MERCHANT_BILLS_TBL B,  FABRIC_MERCHANTS_TBL FM WHERE B.FABRIC_MERCHANTS_ID=FM.FABRIC_MERCHANTS_ID AND FM.COMPANY_NAME='".$company_name."' AND B.DATE>='".$from_date."' AND B.DATE<='".$to_date."' order by B.date ASC";
     $show=mysqli_query($dbhandle,$sqlquery);
	
	$bills_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$bill;
        $bill['BILL_ID']=$row['BILL_ID'];
		$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
		$bill['loc']=$row['loc'];
		$bill['meter']=$row['meter'];
		$bill['meterRate']=$row['meterRate'];
		$bill['AMOUNT']=$row['AMOUNT'];
		$bill['payment_description']=$row['payment_description'];
		$bill['payment_amount']=$row['payment_amount'];
		$bill['payment_date']=$row['payment_date'];
		
		$bills_list[$row_count]=$bill;
		$row_count++;
		}
		
		
		echo json_encode($bills_list);
		
  }else if($action=="updateSupplierBill"){
    $bill_id=$_POST["bill_id"];
	$payment_date=$_POST["payment_date"];
	$payment_amount=$_POST["payment_amount"];
	$payment_description=$_POST["payment_description"];
	
	$sqlquery="update MERCHANT_BILLS_TBL set payment_date ='".$payment_date."',payment_amount ='".$payment_amount."', payment_description='$payment_description' where BILL_ID=".$bill_id;                                                           
	
	$show=mysqli_query($dbhandle,$sqlquery);
	
	echo $show ; 
	
	
	
	
  }
  
?>
