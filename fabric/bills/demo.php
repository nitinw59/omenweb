<?php
$COMPANY['NAME']="1111";
$COMPANY['ADDR']="2222";
	
	
	$bill['COMPANY']=$COMPANY;
	$bill['date']="333";
	
	//$BILL_DATE['=$row['date'];
	//$BILL_DUE_DATE['=$row['due_date'];

	//	$TOTAL_AMOUNT=$row['total_amount'];
	
		
		echo json_encode($bill);

?>
