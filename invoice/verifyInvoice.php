
<?php
    include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 


 // Attempt select query execution
  $sqlquery="SELECT 
  t.date as t_date,
  t.LR, 
  b.date,
  b.BILL_ID,
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
  c.mobile,
  t.transport_name,
  t.transport_parcels ,
  ch.challan_no
    FROM 
    bills_tbl b,
    customers_tbl c,
    challan_transport_tbl t ,
    challan_tbl ch
      where 
      ch.challan_no=b.challanNo
         and 
        b.customer_id=c.customer_id 
        and 
        t.challan_no=ch.challan_no 
        and 
        ch.challan_no=".$_GET['bill_id']." ORDER BY  
        b.bill_id DESC;";

                     
        
	$show=mysqli_query($dbhandle,$sqlquery);
 
     while($row=mysqli_fetch_array($show)){
        
	$COMPANY_NAME=$row['company_name'];
	$COMPANY_ADDR=$row['address'];
	$COMPANY_CITY=$row['city'];
	$COMPANY_ZIP=$row['zip'];
	$COMPANY_STATE=$row['state'];
	$COMPANY_MOB=$row['mobile'];
	$BILL_ID=$row['BILL_ID'];
	$challan_no=$row['challan_no'];
	$COMPANY_MOB=$row['mobile'];
		
	$BILL_DATE=date('d/m/Y', strtotime($row['date']));
	$BILL_DUE_DATE=$row['due_date'];
	
	$BILL_TRANSPORT_NAME=$row['transport_name'];
	$BILL_TRANSPORT_PARCELS=$row['transport_parcels'];
	
	if(date('Y', strtotime($row['t_date']))!=1970)
		$t_date=date('d/m/Y', strtotime($row['t_date']));
	else
		$t_date='';
	$LR=$row['LR'];
	
	

	$TOTAL_AMOUNT=$row['total_amount'];
	
		
     }
	 
	 
	 
  



?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

.button2 {background-color: #008CBA;} /* Blue */
.button3 {background-color: #f44336;} /* Red */ 
.send {background-color: #008CBA;align:center;} /* Red */ 



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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script>
    
    
    lrCheckedFlag=false;
    itemsCheckedFlag=false;
    
    function lrChecked() {
       
        $("#lrButton").css("background-color","green");
        $("#lrButton").html("Checked");
        lrCheckedFlag=true;

    }
    function itemsChecked() {
        
        $("#itemsButton").css("background-color","green");
        $("#itemsButton").html("Checked");
        itemsCheckedFlag=true;

    }


    function downloadAsPDF() {
       
     

        if(lrCheckedFlag && itemsCheckedFlag){
		      
			

		$.ajax({
    		type:"post",
       		url:"sendInvoice.php",
    		data:"bill_id="+$("#BILL_ID").html(),
    		success:function(data){
            			   alert(data);
                			
							window.location.replace("addTransportDetails.php");					



       			}
 			});

        }else{
            alert("plese Verify LR And Items Both");
        }	
    }


		$(document).ajaxStart(function(){
				$("#wait").css("display", "block");
			});
			$(document).ajaxComplete(function(){
				$("#wait").css("display", "none");
			});
		
		
  </script>
  
  
<body>

<div id="wait" style="display:none;width:690px;height:890px;position:absolute;top:30%;left:30%;padding:2px;">
<img src='reload.gif' width='20px' height='220px'>
</div>

<table style="width:100%;border=none;" id="branding">

<td><center><img src="/<?=$omenNX?>/lh.png" alt="Girl in a jacket" style="width:30px;height:20px;"> </center></td>
<td><center><font size="14px" face="selfish">O-men </font></br><font size="2px" >Jeans & Casuals</font> </center></td>
<td><center><img src="/<?=$omenNX?>/dc.png" alt="Girl in a jacket" style="width:30px;height:20px;"> </center></td>

</table>

<table style="width:100%" >

<tr>
    <td align="center"> </br><b> LR DETAILS </b></td>
</tr>

<tr>
    <td align="center">
        </br>
        <b align="center"><?=$challan_no?>--<legend id="BILL_ID"><?=$BILL_ID?></legend></br> </br>  
        <b>M/s <?=$COMPANY_NAME?> </b></br> </br> 
        <b><?=$BILL_TRANSPORT_NAME?> </b></br></br>  
        <b><?=$LR?> </b></br> </br> 
        <b> <?=$BILL_TRANSPORT_PARCELS?> </b></br> </br> 
    </td>
</tr>

<tr>
    <td align="center"> 


    <p><button onclick="lrChecked()" class="button button3" id="lrButton">Un checked</button></p>

    </td>
</tr>
   
</table>



</br>




<table style="width:100%"  align=center >

<caption>ITEMS</caption>
<th>QTY</th>
<th>RATE</th>  
  
  
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
   
    echo "<td>".$row['quantity']."</td>";
	
	echo "<td>".$ratebrate."</td>";
	
	$amount=$row['quantity']*$ratebrate;
	
	echo "</tr>";
	
	
	
	
	$total_quantity += $row['quantity'];
	$total_amount+=$amount;	
     }
	 
	
	 
    
  
  ?>


    <tr>
     <td colspan="2" align="center">
    <p><button onclick="itemsChecked()" class="button button3" id="itemsButton">Un checked</button></p>
    </td>
    </tr>
  
</table>

  
  
  
 <p><button onclick="downloadAsPDF()" class="button send">Verify & Send</button></p>
 

</body>
</html>
