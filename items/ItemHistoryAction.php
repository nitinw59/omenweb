<?php
  
$server_root="/omenwebNX";

include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");

   
  $action=$_POST["action"];
 
   if($action=="listItemHistory"){
	$item_no=$_POST["item_no"];
	
	  
	 $sqlquery="SELECT BILL_ID FROM `bill_items_tbl` where items_id=".$item_no;
     $show=mysqli_query($dbhandle,$sqlquery);
	
	$bills_list;
	$row_count=0;
     while($row=mysqli_fetch_array($show)){
		
        $bills_list[$row_count]=$row['BILL_ID'];
		
		$row_count++;
		}
		
		
		echo json_encode($bills_list);
		
  }
  
?>
