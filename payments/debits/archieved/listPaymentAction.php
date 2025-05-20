<?php
$server_root="/omenwebNX";
  include($_SERVER['DOCUMENT_ROOT']."/$server_root/mysqlconnectdb.php");
 
  $action=$_POST["action"];
 
  if($action=="fetchcustomerdetail"){
	 $customercompanyname=$_POST["customercompanyname"];
	 $sqlquery="Select * from customers_tbl where COMPANY_NAME='".$customercompanyname."'";
     $show=mysqli_query($dbhandle,$sqlquery);
 
     while($row=mysqli_fetch_array($show)){
        $customerdetail['customer_id']=$row['customer_id'];
		$customerdetail['FNAME']=$row['FNAME'];
		$customerdetail['LNAME']=$row['LNAME'];
		$customerdetail['COMPANY_NAME']=$row['COMPANY_NAME'];
		$customerdetail['EMAIL']=$row['EMAIL'];
		$customerdetail['GSTTREATMENT']=$row['GSTTREATMENT'];
		$customerdetail['GSTN']=$row['GSTN'];
		$customerdetail['ADDRESS']=$row['ADDRESS'];
		$customerdetail['CITY']=$row['CITY'];
		$customerdetail['STATE']=$row['STATE'];
		$customerdetail['ZIP']=$row['ZIP'];
		
		
		
		
		
		
     }
	 
		 
	 echo json_encode($customerdetail);
		
	 
  }else if($action=="listPayment"){
	$company_name=$_POST["company_name"];
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	 $sqlquery="SELECT d.debit_id,d.date,d.amount,d.DESCRIPTION FROM debits_tbl d, fabric_merchants_tbl fm WHERE fm.FABRIC_MERCHANTS_ID=d.FABRIC_MERCHANTS_ID AND fm.COMPANY_NAME='".$company_name."' AND d.DATE>='".$from_date."' AND d.DATE<='".$to_date."' order by d.date";
    $show=mysqli_query($dbhandle,$sqlquery);
 
	$payments_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$payment;
        $payment['date']=$row['date'];
		$payment['amount']=$row['amount'];
		$payment['DESCRIPTION']=$row['DESCRIPTION'];
		$payment['debit_id']=$row['debit_id'];
		
		$payments_list[$row_count]=$payment;
		$row_count++;
		}
		
		//echo $sqlquery;
		echo json_encode($payments_list);
		
  } else if($action=="deletePayment"){
	 $debit_id=$_POST["debit_id"];
	 
	 $sqlquery="DELETE FROM debits_tbl WHERE  debit_id=".$debit_id ;
     $show=mysqli_query($dbhandle,$sqlquery);
 
    echo $show;
  }
  
?>
