<?php
 

 include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
 include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
 include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
   
  $action=$_POST["action"];
 
  if($action=="updateCustomer"){
    $customer_id=$_POST["customer_id"];
	$bill_id=$_POST["bill_id"];
	
	$sqlquery="update bills_tbl set customer_id = ".$customer_id." where BILL_ID=".$bill_id;                                                           
	
	$show=mysqli_query($dbhandle,$sqlquery);
	
	echo $show ; 
	
	
	
	
  }else if($action=="removeBillItem"){
  	$item_id=$_POST["item_id"];
	$bill_id=$_POST["bill_id"];
	
	$sqlquery="DELETE FROM BILL_ITEMS_TBL WHERE BILL_ID=".$bill_id." AND ITEMS_ID = ".$item_id;
                                                           
	
	$show=mysqli_query($dbhandle,$sqlquery);
	
	echo $show ; 
	
	
  }else if($action=="addBillItem"){
	$item_id=$_POST["item_id"];
	$bill_id=$_POST["bill_id"];
	$description=$_POST["description"];
	$QUANTITY=$_POST["quantity"];
	$RATE=$_POST["rate"];
	
	
	
	
	$sqlquery="INSERT INTO bill_items_tbl (BILL_ID,ITEMS_ID,QUANTITY,RATE,description) VALUE ($bill_id,$item_id,$QUANTITY,$RATE,'$description')";
    $show=mysqli_query($dbhandle,$sqlquery);
	
	
	echo $show ; 
	
	
  }else if($action=="updateTransport"){
    $transportname=$_POST["transportname"];
	$transportparcels=$_POST["transportparcels"];
	$bill_id=$_POST["bill_id"];
	
	$sqlquery="update bills_tbl set transport_name ='".$transportname."',transport_parcels ='".$transportparcels."' where BILL_ID=".$bill_id;                                                           
	
	$show=mysqli_query($dbhandle,$sqlquery);
	
	echo $show ; 
	
	
	
	
  }else if($action=="searchBillId"){
	$bill_id=$_POST["bill_id"];
	
	$sqlquery = "SELECT b.date,b.due_date,b.total_amount,c.fname,c.lname,c.company_name,c.mobile,c.gsttreatment,c.gstn,c.address,c.city,c.state,c.zip,c.mobile FROM bills_tbl b,customers_tbl c where b.customer_id=c.customer_id and bill_id='".$bill_id."' ORDER BY  bill_id DESC";
	
				
	$show=mysqli_query($dbhandle,$sqlquery);
	
	while($row=mysqli_fetch_array($show)){
        
	$COMPANY['NAME']=$row['company_name'];
	$COMPANY['ADDR']=$row['address'];
	$COMPANY['CITY']=$row['city'];
	$COMPANY['ZIP']=$row['zip'];
	$COMPANY['STATE']=$row['state'];
	$COMPANY['GSTTREATMENT']=$row['gsttreatment'];
	$COMPANY['GSTN']=$row['gstn'];
	$COMPANY['MOB']=$row['mobile'];
	
	$bill['COMPANY']=$COMPANY;
	$bill['date']=$row['date'];
	
	//$BILL_DATE['=$row['date'];
	//$BILL_DUE_DATE['=$row['due_date'];

	//	$TOTAL_AMOUNT=$row['total_amount'];
	
	$bill_items_list;
	$sqlquery="select  i.items_id,bi.quantity,bi.rate,i.description,I.SIZE,I.TAX_RATE  from bill_items_tbl bi,items_tbl i where bi.bill_id=".$BILL_ID." and bi.items_id=i.items_id;";
 
				
	$show=mysqli_query($dbhandle,$sqlquery);
	$rowcount=0;
	 while($row=mysqli_fetch_array($show)){
		$bill_item;
        
		$bill_item['items_id']=$row['items_id'];
		$bill_item['quantity']=$row['quantity'];
		$bill_item['rate']=$row['rate'];
		$bill_item['description']=$row['description'];
		$bill_item['SIZE']=$row['SIZE'];
		$bill_item['TAX_RATE']=$row['TAX_RATE'];
		
		$bill_items_list[$rowcount]=$bill_item;
     }
	
	$bill['bill_items_list']=$bill_items_list;
		
		echo json_encode($bill);
     }
	
	
	
  }
  
?>
