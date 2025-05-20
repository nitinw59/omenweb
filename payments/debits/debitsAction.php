<?php

	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
   
  $action=$_POST["action"];
 
  if($action=="fetchcustomerdetail"){
	 $customercompanyname=$_POST["customercompanyname"];
	 $sqlquery="Select * from FABRIC_MERCHANTS_TBL where COMPANY_NAME='".$customercompanyname."'";
     $show=mysqli_query($dbhandle,$sqlquery);
 
     while($row=mysqli_fetch_array($show)){
        $customerdetail['FABRIC_MERCHANTS_ID']=$row['FABRIC_MERCHANTS_ID'];
		$customerdetail['FNAME']=$row['FNAME'];
		$customerdetail['LNAME']=$row['LNAME'];
		$customerdetail['COMPANY_NAME']=$row['COMPANY_NAME'];
		$customerdetail['EMAIL']=$row['EMAIL'];
		$customerdetail['ADDRESS']=$row['ADDRESS'];
		$customerdetail['CITY']=$row['CITY'];
		$customerdetail['STATE']=$row['STATE'];
		$customerdetail['ZIP']=$row['ZIP'];
		
		
		echo json_encode($customerdetail);
		
		
		
		
     }
  }
  else if($action=="fetchbilldetails"){
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
		
  }else if($action=="addDebits"){
	 $f = new NumberFormatter("en-IN", NumberFormatter::SPELLOUT);

	 $customer_id=$_POST["customer_id"];
	 $date=$_POST["date"];
	 $amount=$_POST["amount"];
	 $remark=$_POST["remark"];
	 
	$sqlquery="INSERT INTO debits_tbl (FABRIC_MERCHANTS_ID,DATE,AMOUNT,DESCRIPTION)VALUES(".$customer_id.",'".$date."',".$amount.",'".$remark."')";
    
    $show['STATUS']=mysqli_query($dbhandle,$sqlquery);
	$show['AmountInwords']=	$f->format($amount);
    echo json_encode($show);
  }else if($action=="listPayment"){
	$company_name=$_POST["company_name"];
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	 $sqlquery="SELECT
	 				lg.MERCHANT_CREDITS_LOGGER_ID, 
	 				lg.BILL_ID,
					lg.DEBIT_ID,
					lg.DATE,
					lg.AMOUNT,
					lg.AVAILABLE_CREDITS	 
	 				FROM MERCHANT_CREDITS_LOGGER_TBL lg, 
						fabric_merchants_tbl fm 
						WHERE 
						fm.FABRIC_MERCHANTS_ID=lg.FABRIC_MERCHANTS_ID AND 
						fm.COMPANY_NAME='".$company_name."' AND 
						lg.DATE>='".$from_date."' AND 
						lg.DATE<='".$to_date."' 
						order by lg.MERCHANT_CREDITS_LOGGER_ID";
    $show=mysqli_query($dbhandle,$sqlquery);
 
	$payments_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$payment;
        $payment['BILL_ID']=$row['BILL_ID'];
		$payment['DEBIT_ID']=$row['DEBIT_ID'];
		$payment['DATE']=$row['DATE'];
		$payment['AMOUNT']=$row['AMOUNT'];
		$payment['AVAILABLE_CREDITS']=$row['AVAILABLE_CREDITS'];
		
		$payments_list[$row_count]=$payment;
		$row_count++;
		}
		
		//echo $sqlquery;
		echo json_encode($payments_list);
		
  } else if($action=="deletePayment"){
	 $debits_id=$_POST["debits_id"];
	 
	 $sqlquery="DELETE FROM debits_tbl WHERE  debit_id=".$debits_id ;
     $show=mysqli_query($dbhandle,$sqlquery);
 
    echo $show."--".$sqlquery;
  }else if($action=="listAllPayment"){
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	 $sqlquery="SELECT c.debit_id,c.date,c.amount,c.DESCRIPTION , (select company_name FROM FABRIC_MERCHANTS_TBL CR WHERE CR.FABRIC_MERCHANTS_ID = C.FABRIC_MERCHANTS_ID) AS COMPANY_NAME FROM debits_tbl c WHERE  c.DATE>='".$from_date."' AND c.DATE<='".$to_date."' order by c.debit_id desc";
     $show=mysqli_query($dbhandle,$sqlquery);
 
	$payments_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$payment;
        $payment['debits_id']=$row['debit_id'];
		$payment['date']=$row['date'];
		$payment['amount']=$row['amount'];
		$payment['DESCRIPTION']=$row['DESCRIPTION'];
		$payment['COMPANY_NAME']=$row['COMPANY_NAME'];
		
		$payments_list[$row_count]=$payment;
		$row_count++;
		}
		
		//echo $sqlquery;
		echo json_encode($payments_list);
		
  }else if($action="spellAmountInWords"){
	$amount=$_POST["amount"];
	$f = new NumberFormatter("en-IN", NumberFormatter::SPELLOUT);
	echo $f->format($amount);
}
  
?>





