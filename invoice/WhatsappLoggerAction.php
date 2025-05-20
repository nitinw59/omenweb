<?php
  
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
  
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
   
  $action=$_POST["action"];
 
   if($action=="getMessageLog"){
	
	
	
	$sqlquery="select 
					w.BILL_ID,
					c.COMPANY_NAME,
					w.receiverno,
					w.AMOUNT,
					w.LR,
					w.message_date,
					w.wamid 
					from whatsappmessage_logger w, customers_tbl c 
					where w.receiverno=CONCAT('91',c.MOBILE) AND 
					c.archive_state = FALSE
					ORDER BY w.BILL_ID DESC;";
		
		
			
		
	$show=mysqli_query($dbhandle,$sqlquery);
	
	$message_list;
	$row_count=0;
    while($row=mysqli_fetch_array($show)){
		$message;
        
		$message['BILL_ID']=$row['BILL_ID'];
		$message['message_date']=date('d/m/Y', strtotime($row['message_date']));
		$message['receiverno']=$row['receiverno'];
		$message['AMOUNT']=$row['AMOUNT'];
		$message['LR']=$row['LR'];
		$message['COMPANY_NAME']=$row['COMPANY_NAME'];
		$message['wamid']=$row['wamid'];
		
		
		$message_list[$row_count]=$message;
		$row_count++;
		}
		
		//echo $old_bill_amount;
		//echo $sqlquery;
		
		echo json_encode($message_list);
		
  }
  
?>
