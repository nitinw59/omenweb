<?php
include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
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
	  
	 $sqlquery="SELECT c.credits_id,c.date,c.amount,c.DESCRIPTION FROM credits_tbl c, customers_tbl Cr WHERE cr.customer_id=c.customer_id AND cr.COMPANY_NAME='".$company_name."' AND c.DATE>='".$from_date."' AND c.DATE<='".$to_date."' order by c.date";
    $show=mysqli_query($dbhandle,$sqlquery);
 
	$payments_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$payment;
        $payment['date']=$row['date'];
		$payment['amount']=$row['amount'];
		$payment['DESCRIPTION']=$row['DESCRIPTION'];
		$payment['credits_id']=$row['credits_id'];
		
		$payments_list[$row_count]=$payment;
		$row_count++;
		}
		
		//echo $sqlquery;
		echo json_encode($payments_list);
		
  } else if($action=="deletejobworker"){
	$alias=$_POST["alias"];
	 
	$sqlquery="DELETE FROM jobworker WHERE  alias='$alias'" ;
    $show['STATUS']=mysqli_query($dbhandle,$sqlquery);
    
	echo json_encode($show);
  }
  else if($action=="listAllPayment"){
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	 $sqlquery="SELECT c.credits_id,c.date,c.amount,c.DESCRIPTION , (select company_name FROM CUSTOMERS_TBL CR WHERE CR.CUSTOMER_ID = C.CUSTOMER_ID) AS COMPANY_NAME FROM credits_tbl c WHERE  c.DATE>='".$from_date."' AND c.DATE<='".$to_date."' order by c.credits_id desc";
     $show=mysqli_query($dbhandle,$sqlquery);
 
	$payments_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$payment;
        $payment['credits_id']=$row['credits_id'];
		$payment['date']=$row['date'];
		$payment['amount']=$row['amount'];
		$payment['DESCRIPTION']=$row['DESCRIPTION'];
		$payment['COMPANY_NAME']=$row['COMPANY_NAME'];
		
		$payments_list[$row_count]=$payment;
		$row_count++;
		}
		
		//echo $sqlquery;
		echo json_encode($payments_list);
		
  }else if($action=="updateJobworker"){
	
	$oldalias=$_POST["oldAlias"];
	$newalias=$_POST["alias"];
	$jobworker=$_POST["jobworker"];
	
	$query_upload=" update jobworker set alias='$newalias',jobworkerName='$jobworker' where alias='$oldalias';";
	

	$show['STATUS']=mysqli_query($dbhandle,$query_upload);
	$show['oldalias']=	$oldalias;
	$show['newalias']=	$newalias;
	$show['jobworker']=	$jobworker;
	echo json_encode($show);
  
  }
?>
