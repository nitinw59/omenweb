
<?php
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
  $action=$_POST["action"];
 
   if($action=="listStatement"){
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	$sql = "SELECT B.BILL_ID, DATE, COMPANY_NAME,AMOUNT, LOC   FROM merchant_bills_tbl B,fabric_merchants_tbl C WHERE B.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND B.DATE>='$from_date' AND B.DATE<='$to_date' ORDER BY B.DATE ASC,B.BILL_ID ASC ";
	$show=mysqli_query($dbhandle,$sql);
	
	$bills_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		$bill;
        $bill['BILL_NO']=$row['BILL_ID'];
		$bill['DATE']=$row['DATE'];
		$bill['COMPANY_NAME']=$row['COMPANY_NAME'];
		$bill['LOC']=$row['LOC'];
		
		$bill['AMOUNT']=$row['AMOUNT'];
		
		$bills_list[$row_count]=$bill;
		$row_count++;
		}
		
		//echo $sqlquery;
		echo json_encode($bills_list);
		
  }else if($action=="deleteBill"){
	 $bill_no=$_POST["bill_no"];
	 
	 $sqlquery="DELETE FROM merchant_bills_tbl WHERE  BILL_ID=".$bill_no ;
     $show=mysqli_query($dbhandle,$sqlquery);
 
    echo $show;
  }
  
?>
