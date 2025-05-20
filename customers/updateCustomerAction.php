<?php
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");


   
  $action=$_POST["action"];
 
  if($action=="archiveCustomer"){
    $COMPANY_NAME=$_POST["COMPANY_NAME"];
    $ARC_COMPANY_NAME=$_POST["ARC_COMPANY_NAME"];
	  $sqlquery="update customers_tbl set COMPANY_NAME ='".$ARC_COMPANY_NAME."', archive_state =1 where COMPANY_NAME='".$COMPANY_NAME."'";                                                           
	  $show=mysqli_query($dbhandle,$sqlquery);
    echo $show ; 
	
  }elseif($action=="getCustomerArchiveState"){
  
    $COMPANY_NAME=$_POST["COMPANY_NAME"];
    $sqlquery="select archive_state from customers_tbl where COMPANY_NAME='".$COMPANY_NAME."'";                                                           
		$result=mysqli_query($dbhandle,$sqlquery);
    $row=mysqli_fetch_array($result);
	  $companyState["archive_state"]= $row['archive_state'] ;

    $sqlquery="select SUM(B.TOTAL_AMOUNT) AS TOTALAMOUNT FROM bills_tbl B,  customers_tbl C WHERE B.customer_id=C.customer_id AND C.COMPANY_NAME='".$COMPANY_NAME."'  order by  b.BILL_ID desc";
    $show=mysqli_query($dbhandle,$sqlquery);
	  $row=mysqli_fetch_array($show);
	  $old_bill_amount=$row['TOTALAMOUNT'];
	
	
	  $sqlquery="select SUM(cr.AMOUNT) AS TOTALPAYMENT FROM credits_tbl cr,  customers_tbl C WHERE cr.customer_id=C.customer_id AND C.COMPANY_NAME='".$COMPANY_NAME."' ";
    $show=mysqli_query($dbhandle,$sqlquery);
  	$row=mysqli_fetch_array($show);
	  if($row['TOTALPAYMENT']==null)
		    	$row['TOTALPAYMENT']=0;
		
		$old_payment=$row['TOTALPAYMENT'];
	  $old_balance=$old_bill_amount-$old_payment;
	
    $companyState["archive_balance"]=$old_balance;

    echo json_encode($companyState);



}
  
?>
