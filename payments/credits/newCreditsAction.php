<?php


	$server_root="/omenwebNX";
  include($_SERVER['DOCUMENT_ROOT']."/$server_root/mysqlconnectdb.php");
 include($_SERVER['DOCUMENT_ROOT']."/$server_root/var.php");
 
   
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
		
		
		echo json_encode($customerdetail);
		
		
		
		
     }
  }
  else if($action=="fetchcustomerbills"){
	  
	$company_name=$_POST["customercompanyname"];
	  
    $sql = "SELECT b.bill_id  FROM bills_tbl b, customers_tbl c where b.customer_id=c.customer_id and c.company_name='".$company_name."'";
	$customerbills = array();
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
		$customerbills[$count]="select";
		$count++;
		while($row = mysqli_fetch_array($result)) {
		$customerbills[$count] = $row['bill_id'];
		$count++;
		}
		
		echo json_encode($customerbills);
	
	}
	
  }else if($action=="fetchbilldetails"){
	 $bill_id=$_POST["bill_id"];
	 $sqlquery="Select b.bill_id,b.TOTAL_AMOUNT,B.DATE,T.CGST,T.SGST,T.IGST from bills_tbl B, TAX_DETAILS_TBL T where B.BILL_ID=T.BILL_ID AND b.bill_id=".$bill_id;
     $show=mysqli_query($dbhandle,$sqlquery);
 
     while($row=mysqli_fetch_array($show)){
        $billdetail['bill_id']=$row['bill_id'];
		$billdetail['AMOUNT']=$row['TOTAL_AMOUNT'];
		$billdetail['DATE']=$row['DATE'];
		
		$billdetail['DATE']=$row['DATE'];
		$billdetail['CGST']=$row['CGST'];
		$billdetail['SGST']=$row['SGST'];
		$billdetail['IGST']=$row['IGST'];
		
		
		}
		
		$sqlquery="SELECT sum(amount) as amount FROM `payments_tbl` WHERE bill_id=".$bill_id;
		$show=mysqli_query($dbhandle,$sqlquery);
		if($row=mysqli_fetch_array($show))
		$billdetail['payment_amount']=$row['amount'];
		echo json_encode($billdetail);
		
  }else if($action=="listPayment"){
	$company_name=$_POST["company_name"];
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	 $sqlquery="SELECT p.date,p.amount,p.DESCRIPTION,p.BILL_ID FROM payments_tbl P, bills_tbl B, customers_tbl C WHERE P.BILL_ID=B.BILL_ID AND B.customer_id=C.customer_id AND C.COMPANY_NAME='".$company_name."' AND P.DATE>='".$from_date."' AND P.DATE<='".$to_date."'";
     $show=mysqli_query($dbhandle,$sqlquery);
 
	$payments_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$payment;
        $payment['date']=$row['date'];
		$payment['amount']=$row['amount'];
		$payment['DESCRIPTION']=$row['DESCRIPTION'];
		$payment['BILL_ID']=$row['BILL_ID'];
		
		$payments_list[$row_count]=$payment;
		$row_count++;
		}
		
		
		echo json_encode($payments_list);
		
  }
  
?>
