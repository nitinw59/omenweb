<?php
  include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
  $action=$_POST["action"];
 
   if($action=="listSupplierStatement"){
	
	
	
	//TO REFRESH PAYMENTS FROM CREDITS
	
	$company_name=$_POST["company_name"];
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	  
	$sqlquery="select ADVANCE_CREDITS FROM fabric_merchants_tbl C WHERE C.COMPANY_NAME='".$company_name."'  ";
    $show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	$ADVANCE_CREDITS=0;
	$ADVANCE_CREDITS=$row['ADVANCE_CREDITS'];
	
	//echo $ADVANCE_CREDITS;
	
	
	$sqlquery="select B.BILL_ID , B.AMOUNT as 'BILL AMOUNT' , b.payment_amount FROM MERCHANT_BILLS_TBL B,fabric_merchants_tbl C WHERE B.AMOUNT>B.payment_amount AND	B.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND	C.COMPANY_NAME='$company_name' ORDER BY DATE ASC";	
	
	
	$show=mysqli_query($dbhandle,$sqlquery);
	
	$CREDITS_AVAILABLE= true;
	$ACCUMULATED_PAID_CREDITS=0;
	$payment_querry="";
	$newCredits=$ADVANCE_CREDITS;
	
	//while($row=mysqli_fetch_array($show) AND $CREDITS_AVAILABLE){
			//echo "c";
			//if($row['BILL AMOUNT']<=($ADVANCE_CREDITS-$ACCUMULATED_PAID_CREDITS)){
			//echo "a";
			//$payment_querry.="update merchant_bills_tbl set payment_amount=".$row['BILL AMOUNT']." where bill_id=".$row['BILL_ID'].";";
			//$ACCUMULATED_PAID_CREDITS+=($row['BILL AMOUNT']);
			
			//}else{
			//echo "b";
			
			//$CREDITS_AVAILABLE= false;
			//$newCredits=$ADVANCE_CREDITS-$ACCUMULATED_PAID_CREDITS;
			//$payment_querry.="update fabric_merchants_tbl set advance_credits=$newCredits where COMPANY_NAME='$company_name';";
			
	
			//}
			
		
	//}
	//echo "new credits: ".$ADVANCE_CREDITS;
	//echo "accumulated: ".$ACCUMULATED_PAID_CREDITS;
	
	//echo $ACCUMULATED_PAID_CREDITS;
	
		
    //echo $payment_querry;
	//echo "SHOW: ".$show;
	
	
	//$show=mysqli_multi_query($dbhandle,$payment_querry);
	//while (mysqli_next_result($dbhandle)) {;}
	
	
	//{
		
	
	$sqlquery="
	select 
			SUM(B.AMOUNT) as 'total_amount' ,
			SUM(b.payment_amount) as 'total_payment'
			FROM MERCHANT_BILLS_TBL B,
			
			fabric_merchants_tbl C 
			
			WHERE 
			B.DATE<'$from_date' AND
			B.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND
			C.COMPANY_NAME='$company_name'

			ORDER BY DATE ASC	
	 
	 
	"; 
	
	
	
	$show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	$old_amount=$row['total_amount'];
	$old_payment=$row['total_payment'];
	$old_balance=$old_amount-$old_payment;
	
		
		
	$sqlquery="select 
			B.BILL_ID as 'BILL ID',
			B.DATE as 'DATE',
			B.meter as 'ITEM_QUANTITY'  ,
			B.meterRate as 'ITEM_RATE'  ,
			B.AMOUNT AS 'BILL AMOUNT',
			B.loc AS 'loc',
			payment_amount as 'PAYMENT AMOUNT',	
			payment_description as 'PAYMENT DESCRIPTION'

			FROM MERCHANT_BILLS_TBL B,
			
			fabric_merchants_tbl C 
			
			WHERE 

			B.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND
			C.COMPANY_NAME='$company_name' AND
			b.DATE>='$from_date' AND b.DATE<='$to_date' 
    
			order by date asc";
		
		
		
		
	
	$bills_list;
	$row_count=0;
	
	
	$bill;
        
		$bill['BILL ID']=0;
		$bill['DATE']='';
		$bill['BILL AMOUNT']=$old_balance;
		$bill['loc']='';
		$bill['PAYMENT AMOUNT']=$newCredits;
		$bill['PAYMENT DESCRIPTION']='';
		$bill['ITEM_QUANTITY']='';
		$bill['ITEM_RATE']='';
		
		
		$bills_list[$row_count]=$bill;
	
	$row_count++;
	
	
	
	$show_bill_list=mysqli_query($dbhandle,$sqlquery);
	
    while($row=mysqli_fetch_array($show_bill_list)){
		$bill;
        
		$bill['BILL ID']=$row['BILL ID'];
		$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
		$bill['BILL AMOUNT']=$row['BILL AMOUNT'];
		$bill['loc']=$row['loc'];
		$bill['PAYMENT AMOUNT']=$row['PAYMENT AMOUNT'];
		$bill['PAYMENT DESCRIPTION']=$row['PAYMENT DESCRIPTION'];
		$bill['ITEM_QUANTITY']=$row['ITEM_QUANTITY'];
		$bill['ITEM_RATE']=$row['ITEM_RATE'];
		
		
		$bills_list[$row_count]=$bill;
		$row_count++;
		}
		
		//echo $old_bill_amount;
		//echo $sqlquery;
		
		echo json_encode($bills_list);
	
	//}
		
  }elseif($action=="listSupplierBillStatement"){
	
	
	
	//TO REFRESH PAYMENTS FROM CREDITS
	
	$company_name=$_POST["company_name"];
	
		
	$sqlquery="select 
			B.BILL_ID as 'BILL ID',
			B.DATE as 'DATE',
			B.meter as 'meter'  ,
			B.meterRate as 'rate'  ,
			B.AMOUNT AS 'AMOUNT',
			B.loc AS 'loc'

			FROM MERCHANT_BILLS_TBL B,
			
			fabric_merchants_tbl C 
			
			WHERE 

			B.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND
			C.COMPANY_NAME='$company_name' 
			order by date asc";
		
	
	$show_bill_list=mysqli_query($dbhandle,$sqlquery);
	$bills_list;
	$row_count=0;
    while($row=mysqli_fetch_array($show_bill_list)){
		$bill;
        
		$bill['BILL ID']=$row['BILL ID'];
		$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
		$bill['AMOUNT']=$row['AMOUNT'];
		$bill['loc']=$row['loc'];
		$bill['METER']=$row['meter'];
		$bill['RATE']=$row['rate'];
		
		
		$bills_list[$row_count]=$bill;
		$row_count++;
		}
		
		//echo $old_bill_amount;
		//echo $sqlquery;
		
		echo json_encode($bills_list);
	
	//}
		
  }elseif($action=="listSupplierPaymentStatement"){
	
	
	
	//TO REFRESH PAYMENTS FROM CREDITS
	
	$company_name=$_POST["company_name"];
	
		
	$sqlquery="select 
			D.DATE as 'DATE',
			d.AMOUNT AS 'AMOUNT'
			
			FROM DEBITS_TBL D,
			
			fabric_merchants_tbl C 
			
			WHERE 

			D.FABRIC_MERCHANTS_ID=C.FABRIC_MERCHANTS_ID AND
			C.COMPANY_NAME='$company_name' 
			order by date asc";
		
	
	$show_payment_list=mysqli_query($dbhandle,$sqlquery);
	$payment_list;
	$row_count=0;
    while($row=mysqli_fetch_array($show_payment_list)){
		$payment;
        
		$payment['DATE']=date('d/m/Y', strtotime($row['DATE']));
		$payment['AMOUNT']=$row['AMOUNT'];
		
		
		$payment_list[$row_count]=$payment;
		$row_count++;
		}
		
		//echo $old_bill_amount;
		//echo $sqlquery;
		
		echo json_encode($payment_list);
	
	//}
		
  }else if($action=="getSupplierCredits"){
	$company_name=$_POST["company_name"];
	$sqlquery="select ADVANCE_CREDITS FROM fabric_merchants_tbl C WHERE C.COMPANY_NAME='".$company_name."'  ";
    $show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	$ADVANCE_CREDITS=0;
	$ADVANCE_CREDITS=$row['ADVANCE_CREDITS'];
	echo $ADVANCE_CREDITS;
	
   }else if($action=="makePayment"){
	$billId=$_POST["billId"];
	$creditsAvailable=$_POST["creditsAvailable"];
	$paymentamount=$_POST["paymentamount"];
	
	$sqlquery="select payment_amount,FABRIC_MERCHANTS_ID FROM merchant_bills_tbl  WHERE bill_id=$billId;";
    $show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	$old_payment=0;
	$old_payment=$row['payment_amount'];
	$FABRIC_MERCHANTS_ID=$row['FABRIC_MERCHANTS_ID'];
	$changeAmount=$paymentamount-$old_payment;
	$creditsAvailable=$creditsAvailable-$changeAmount;
	
	$payment_querry="update merchant_bills_tbl set payment_amount=$paymentamount where bill_id=$billId;";
	$payment_querry.="update fabric_merchants_tbl set  ADVANCE_CREDITS= $creditsAvailable where FABRIC_MERCHANTS_ID=$FABRIC_MERCHANTS_ID;";
	$payment_querry.="insert into MERCHANT_CREDITS_LOGGER_TBL(
					BILL_ID,
					DEBIT_ID,
					DATE,
					AMOUNT,
					AVAILABLE_CREDITS,
					FABRIC_MERCHANTS_ID
					)values(
						$billId,
						0,'"
						.date('Y-m-d')."',
						$changeAmount,
						$creditsAvailable,
						$FABRIC_MERCHANTS_ID
					);";
	

	$show=mysqli_multi_query($dbhandle,$payment_querry);
	echo $changeAmount;
	
   }
  
  
?>
