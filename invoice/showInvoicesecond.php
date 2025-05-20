<!DOCTYPE html>
<html>
<head>
<title>invoice</title>
<style>

table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}





table#items { 
	border-collapse: collapse; 
	
	
	}

table#items td {
  border-top: solid 1px #ffffff; 
  border-bottom: solid 1px #ffffff;
  height: 20px;
}

table#branding td {
	border:none;
	
}

td#total {
  border-top: solid 1px #00; 
  border-bottom: solid 1px #00;
}

table#amountinwords td {
  border-right: solid 1px #ffffff;
  border-left: solid 1px #ffffff;
  
  border-bottom: solid 1px #ffffff;
}


</style>
</head>


<?php
include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 


 // Attempt select query execution

	$sqlquery = "SELECT b.date,
						b.due_date,
						b.total_amount,
						c.fname,
						c.lname,
						c.company_name,
						c.mobile,
						c.address,
						c.city,
						c.state,
						c.zip,
						c.mobile
						 
							FROM bills_tbl b,
								customers_tbl c
								
									where 
									b.customer_id=c.customer_id and 
									
									b.bill_id='".$_GET["bill_id"]."' ORDER BY  b.bill_id DESC";






	
	$show=mysqli_query($dbhandle,$sqlquery);
 
     while($row=mysqli_fetch_array($show)){
        
	$COMPANY_NAME=$row['company_name'];
	$COMPANY_ADDR=$row['address'];
	$COMPANY_CITY=$row['city'];
	$COMPANY_ZIP=$row['zip'];
	$COMPANY_STATE=$row['state'];
	$COMPANY_MOB=$row['mobile'];
		
	$BILL_DATE=date('d/m/Y', strtotime($row['date']));
	$BILL_DUE_DATE=$row['due_date'];
	$TOTAL_AMOUNT=$row['total_amount'];
	

	$legacyDateObj=new DateTime($legacy_v1_date);
	$billDateObj=new DateTime($row['date']);
	if($billDateObj>$legacyDateObj){//new transport
			$sqlquery = "SELECT t.DATE as 't_date',
								t.LR,
								t.transport_name,
								t.transport_parcels	
								FROM bills_tbl b,
									challan_transport_tbl t
									where 
										b.challanNo=t.challan_no AND
										b.BILL_ID='".$_GET["bill_id"]."' ORDER BY  b.bill_id DESC";




	}else{//old transport
		$sqlquery = "SELECT t.date as 't_date',
								t.LR,
								t.transport_name,
								t.transport_parcels	
								FROM bills_tbl b,
									 transport_tbl  t
									where 
										t.bill_id=b.bill_id  AND
										b.BILL_ID='".$_GET["bill_id"]."' ORDER BY  b.bill_id DESC";





	}


	$show=mysqli_query($dbhandle,$sqlquery);
 
     while($row=mysqli_fetch_array($show)){

	$BILL_TRANSPORT_NAME=$row['transport_name'];
	$BILL_TRANSPORT_PARCELS=$row['transport_parcels'];
	
	if(date('Y', strtotime($row['t_date']))!=1970)
		$t_date=date('d/m/Y', strtotime($row['t_date']));
	else
		$t_date='';
		$LR=$row['LR'];
	 }
	

	
		
     }
	 
	 
	 
  


$BILL_ID=$_GET["bill_id"];




?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script>
    function downloadAsPDF() {

		const queryString = window.location.search;
		const urlParams = new URLSearchParams(queryString);
			

		$.ajax({
    		type:"post",
       		url:"sendInvoice.php",
    		data:"bill_id="+urlParams.get('bill_id'),
    		success:function(data){
            			   alert(data);
                			
							window.location.replace("addTransportDetails.php");					



       			}
 			});

			
    	}


		$(document).ajaxStart(function(){
				$("#wait").css("display", "block");
			});
			$(document).ajaxComplete(function(){
				$("#wait").css("display", "none");
			});
		
		
  </script>
  
  
<body>
<nitin>



<div id="wait" style="display:none;width:90px;height:890px;position:absolute;top:30%;left:30%;padding:2px;">
<img src='reload.gif' width='20px' height='220px'>
</div>

<table style="width:100%;border=none;" id="branding">

<td><center><img src="/<?=$omenNX?>/lh.png" alt="Girl in a jacket" style="width:30px;height:20px;"> </center></td>
<td><center><font size="14px" face="selfish">O-men </font></br><font size="2px" >Jeans & Casuals</font> </center></td>
<td><center><img src="/<?=$omenNX?>/dc.png" alt="Girl in a jacket" style="width:30px;height:20px;"> </center></td>

</table>



<table style="width:100%" >

<td width="70%" height="100px"> To,</br><b>M/s <?=$COMPANY_NAME?> </b></td>
<td><b>Invoice No. B-<?=$BILL_ID?></br> </br> </br> Date: <?=$BILL_DATE?></b></td>
   
</table>












</br>




<table style="width:100%"  align=center id="items">

 
  <tr>
    <th width="2%">Sr.</th>
    <th width="55%">Description Of Goods </th>
	
    <th width="15%">Quantity</th>
	<th  width="15%">Rate</th>
	<th width="3%">per</th>
	<th width="10%">Amount</th>
	
	
  </tr>
  
  
  
  
  
  <?php
   $sqlquery="select  
   				bi.items_id,
				bi.quantity,
				bi.rate,
				bi.description as 'bi_desc'
				from bill_items_tbl bi
				where bi.bill_id=".$BILL_ID;
  
  
  	$show=mysqli_query($dbhandle,$sqlquery);
 
			
	
	$row_count=0;
	$total_quantity=0;
	$total_amount=0;
     while($row=mysqli_fetch_array($show)){
        $row_count++;
		
	$ratebrate=	($row['rate']);
	$amount=0;
	
	
	echo "<tr>";
    echo "<td>$row_count</td>";
    
	if($row['bi_desc']=="")
	echo "<td>IC.".$row['items_id']." ".$row['i_desc']." ".$row['SIZE']."</td>";
	else
	echo "<td>IC.".$row['items_id']." ".$row['bi_desc']."</td>";
	
    echo "<td>".$row['quantity']."</td>";
	
	echo "<td>".$ratebrate."</td>";
	echo "<td>pcs</td>";
	$amount=$row['quantity']*$ratebrate;
	echo "<td>".$amount."</td>";
	echo "</tr>";
	
	
	
	
	$total_quantity += $row['quantity'];
	$total_amount+=$amount;	
     }
	 
	
	$dummy_row=(4-$row_count)*2;
	
	
	
	
	
	 
  while($dummy_row>-1){
	echo "<tr >";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
    echo "<td></td>";
	
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	$dummy_row--;
  }
  
  
  echo "<tr >";
    echo "<td></td>";
    echo "<td>
			<table>
			
				<tr><td>Transport : $BILL_TRANSPORT_NAME</td></tr>
				
				<tr><td>Parcels : $BILL_TRANSPORT_PARCELS</td></tr>
				<tr><td>LR : $LR</td></tr>
				<tr><td>Booking Date : $t_date</td></tr >
				
			
				
				</table>";
	echo "<td></td>";
    echo "<td></td>";
	
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	
	$total_amount_plus=$TOTAL_AMOUNT+($total_quantity*120);
	$total_amount_plus_taxes=($TOTAL_AMOUNT*(1));
	
	
	
	
	 
    
 
	
 echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
	
	echo "<td></td>";
	echo "</tr>";
	$row_count++;
	echo "</table>";
  
  
  
  
  ?>
  
  
</table>

<table style="width:100%"  align=center id="items">

 
  <tr>
    <th width="43%" colspan="3"> Total</th>
    
	<th width="23%" colspan="3" align=left><?=$total_quantity?></th>
	<th width="10%">₹ <?=($total_amount)?></th>
	
	
  </tr>
  </table>
  
  
	<table  id="amountinwords" style="width:100%" align=center>

 
  <tr>
    <td >Amount In Words<b> Indian Rupee(s) <?php 
	
	$f = new NumberFormatter("en-IN", NumberFormatter::SPELLOUT);

	echo $f->format(($total_amount));
	
	?> Only. </b></td>
    <td align=right>E&OE</td>
    
	
	
  </tr>
  </table>
  
 </nitin>
 
 

</body>

</html>
