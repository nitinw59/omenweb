<!DOCTYPE html>
<html>
<head>
<title>Nitin Traders</title>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}





table#items { border-collapse: collapse; }

table#items td {
  border-top: solid 1px #ffffff; 
  border-bottom: solid 1px #ffffff;
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



p.heading {
    text-align: center;
	
}


</style>
</head>


<?php

$server_root="/omenwebNX";

include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");

$MY_COMPANY_NAME="NITIN TRADERS";
$MY_COMPANY_ADDR="107,Gangaram Market";
$MY_COMPANY_CITY="ULHASNAGAR";
$MY_COMPANY_ZIP="421005";
$MY_COMPANY_STATE="MAHARASHTRA";
$MY_COMPANY_STATE_CODE="27";
$MY_COMPANY_MOB="9146962469";
$MY_COMPANY_GSTN="27ACMPW5678E1ZV";

$MY_COMPANY_PAN="ACMPW5678E";
$MY_COMPANY_BANK_NAME="ICICI BANK";
$MY_COMPANY_BANK_ACCOUNT_NUMBER="218205000800";
$MY_COMPANY_BANK_IFSC="ICIC0002182";
$MY_COMPANY_BANK_BRANCH="ULHASNAGAR-5";


 // Attempt select query execution

	$sqlquery = "SELECT b.date,b.due_date,b.total_amount,c.fname,c.lname,c.company_name,c.mobile,c.gsttreatment,c.gstn,c.address,c.city,c.state,c.zip,c.mobile,b.transport_name,b.transport_parcels FROM bills_tbl b,customers_tbl c where b.customer_id=c.customer_id and bill_id='".$_GET["bill_id"]."' ORDER BY  bill_id DESC";
	$show=mysqli_query($dbhandle,$sqlquery);
 
     while($row=mysqli_fetch_array($show)){
        
	$COMPANY_NAME=$row['company_name'];
	$COMPANY_ADDR=$row['address'];
	$COMPANY_CITY=$row['city'];
	$COMPANY_ZIP=$row['zip'];
	$COMPANY_STATE=$row['state'];
	$COMPANY_GSTTREATMENT=$row['gsttreatment'];
	$COMPANY_GSTN=$row['gstn'];
	$COMPANY_MOB=$row['mobile'];
		
	$BILL_DATE=$row['date'];
	$BILL_DUE_DATE=$row['due_date'];
	
	$BILL_TRANSPORT_NAME=$row['transport_name'];
	$BILL_TRANSPORT_PARCELS=$row['transport_parcels'];

	$TOTAL_AMOUNT=$row['total_amount'];
	
		
     }
	 
	 
	 
			for($i=0;$i<count($states);$i++){ 
					if($states[$i][0]==$COMPANY_STATE){
					
					$COMPANY_STATE_CODE=$states[$i][1];
				break;
					}else{
					
					$COMPANY_STATE_CODE=27;

					}
					
				
				}
  


$BILL_ID=$_GET["bill_id"];




?>


<body>
 <p class="heading"><b>TAX INVOICE</b></p>
 
<table style="width:100%"  align=center>

  <tr>
    <td rowspan="3" colspan="2" cellpadding="10px"><b><?=$MY_COMPANY_NAME?></b></br><?=$MY_COMPANY_ADDR?></br><?php echo $MY_COMPANY_CITY."-".$MY_COMPANY_ZIP."</br>".$MY_COMPANY_STATE."(".$MY_COMPANY_STATE_CODE.")</br> MOB. ".$MY_COMPANY_MOB."</br>GSTN- ".$MY_COMPANY_GSTN;?></td>
    <td>Invoice No.</br><b><?=$BILL_ID?></b></td>
    <td>Dated </br> <?=$BILL_DATE?></td>
  </tr>
  <tr>
    
    <td>Delivery Note</td>
    <td>mode/terms of payments</td>
  </tr>
  <tr>
	<td>Suppliers Ref</br><?=$BILL_ID?></td>
    <td>Other Reference(s)</td>
  </tr>
  <tr>
	<td colspan="4" align=left>Buyers & shipped to.</td>
    
  </tr>
  
  <tr>
    <td rowspan="4" colspan="2"><b><?php 
	if($COMPANY_GSTTREATMENT=='UNREGISTERED')
		$COMPANY_GSTN='N/A';
	
	
	echo $COMPANY_NAME."</b>,</br>".chunk_split($COMPANY_ADDR,25,"</br>")."".$COMPANY_CITY."-".$COMPANY_ZIP."</br>".$COMPANY_STATE." (".$COMPANY_STATE_CODE.")</br> MOB. ".$COMPANY_MOB."</br>GSTN- ".$COMPANY_GSTN;?></td>
    <td>Buyer Order No.</br>1</td>
    <td>Dated </br> <?=$BILL_DATE?></td>
  </tr>
  <tr>
    
    <td>Dispatch No.</td>
    <td>Dispatched Note Date</td>
  </tr>
  <tr>
	<td>Dispatched through</br>: <b><?=$BILL_TRANSPORT_NAME?> Transport</b></td>
    <td>Destination:<b> <?=$COMPANY_CITY?></b></td>
  </tr>
  <tr>
	<td colspan="2">Total Parcels</br>: <b><?=$BILL_TRANSPORT_PARCELS?> parcel(s)</b></td>
    
  </tr>
</table>



</br>
<table style="width:100%"  align=center id="items">

 
  <tr>
    <th width="2%">Sr.</th>
    <th width="55%">Description Of Goods </th>
	<th width="10%">HSN</th>
    <th width="10%">Quantity</th>
	<th  width="10%">Rate</th>
	<th width="3%">per</th>
	<th width="10%">Amount</th>
	
	
  </tr>
  
  
  
  
  
  <?php
 
  $sqlquery="select  i.items_id,bi.quantity,bi.rate,i.description,I.SIZE,I.TAX_RATE  from bill_items_tbl bi,items_tbl i where bi.bill_id=".$BILL_ID." and bi.items_id=i.items_id;";
  
  
  $show=mysqli_query($dbhandle,$sqlquery);
 
 
 
	$row_count=0;
	$total_quantity=0;
     while($row=mysqli_fetch_array($show)){
        $row_count++;
	echo "<tr>";
    echo "<td>$row_count</td>";
    echo "<td>lot no.".$row['items_id']." ".$row['description']." ".$row['SIZE']."</td>";
	echo "<td>62</td>";
    echo "<td>".$row['quantity']."</td>";
	echo "<td>".$row['rate']."</td>";
	echo "<td>pcs</td>";
	echo "<td>".($row['quantity']*$row['rate'])."</td>";
	echo "</tr>";
	$total_quantity += $row['quantity'];
		
     }
	 
	
	 echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	$row_count++;
	echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td align=right>TOTAL</td>";
	echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>".$TOTAL_AMOUNT."</td>";
	echo "</tr>";
	
	
	
	 
  while($row_count<7){
	echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	$row_count++;
  }
  
	
 
  if($COMPANY_STATE_CODE==$MY_COMPANY_STATE_CODE){
  $same_state=true;
  echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td align=right>SGST</td>";
	echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>".((2.5/100)*$TOTAL_AMOUNT)."</td>";
	echo "</tr>";
	$row_count++;
	
	
	echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td align=right>CGST</td>";
	echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>".((2.5/100)*$TOTAL_AMOUNT)."</td>";
	echo "</tr>";
	$row_count++;
	
  }else{
   $same_state=false;
	echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td align=right><b>IGST</b></td>";
	echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>".((5/100)*$TOTAL_AMOUNT)."</td>";
	echo "</tr>";
	$row_count++;
  }
  
  
  
  ?>
  
  
</table>

<table style="width:100%"  align=center id="items">

 
  <tr>
    <th width="67%" colspan="3"> Total</th>
    
	<th width="23%" colspan="3" align=left><?=$total_quantity?></th>
	<th width="10%">₹ <?=($TOTAL_AMOUNT+(((5/100)*$TOTAL_AMOUNT)))?></th>
	
	
  </tr>
  </table>
  
  
	<table  id="amountinwords" style="width:100%" align=center>

 
  <tr>
    <td >Amount In Words<b> Indian Rupee(s) <?php 
	
	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

	echo $f->format(($TOTAL_AMOUNT+(((5/100)*$TOTAL_AMOUNT))));
	
	?> Only. </b></td>
    <td align=right>E&OE</td>
    
	
	
  </tr>
  </table>
  
</br>

<table style="width:100%"  align=center >

  <tr>
    <th rowspan="2" width="60%">HSN</th>
    <th rowspan="2" width="10%">Taxable value</th>
	
	
	
	<?php
	
	if($same_state){
		
			
			echo "<th colspan='2' width='10%'>CGST</th>";
			echo "<th colspan='2' width='10%'>SGST</th>";
	
		}else{
			
			echo "<th colspan='2' width='10%'>IGST</th>";
		}
		
	
	
	
   
			echo "</tr>";
  
			echo "<tr>";
    
	
	if($same_state){
	
			echo "<td>Rate </td>";
			echo "<td>Amount </td>";
			echo "<td>Rate </td>";
			echo "<td>Amount </td>";
	}else{
			echo "<td>Rate </td>";
			echo "<td>Amount </td>";
	}
	
			echo "</tr>";
  
			echo "<tr>";
    
			echo "<td>62 </td>";
			echo "<td>".$TOTAL_AMOUNT." </td>";
			
			
			if($same_state){
			echo "<td>2.5% </td>";
			echo "<td>".((2.5/100)*$TOTAL_AMOUNT)." </td>";
			echo "<td>2.5% </td>";
			echo "<td>".((2.5/100)*$TOTAL_AMOUNT)." </td>";
			}else{
			
			echo "<td>5% </td>";
			echo "<td>".((5/100)*$TOTAL_AMOUNT)." </td>";
			}
			echo "</tr>";
  
			
  
			echo "<tr>";
    
			echo "<td colspan='8'>Tax Amount (in words)<b> Indian Rupee(s)";
			
			
		
	
			$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

			echo $f->format((5/100)*$TOTAL_AMOUNT);
	
			echo " Only. </b>";
			
			
			echo "</td>";
	
	
			echo "</tr>";
  
  ?>
  
</table>


</br>

<table style="width:100%"  align=center >

  <tr>
    
	<th >Company PAN</th>
	<th  align=left> <?=$MY_COMPANY_PAN?></th>
	
	
  </tr>
  
  <tr>
    
    <td width="50%">Declaration</br> We declare that this invoice shows the actual price </br>of the goods described and that all particulars are true and correct</td>
	<td >Bank Details  </br>Bank Name: <?=$MY_COMPANY_BANK_NAME?></br>A/C No. <?=$MY_COMPANY_BANK_ACCOUNT_NUMBER?></br> Branch & IFSC : <?=$MY_COMPANY_BANK_BRANCH?> <?=$MY_COMPANY_BANK_IFSC?></td>
	
	
	
  </tr>
  
  
  <tr>
    
    <td width="50%">Customers seal & Signature</td>
	<td align=right >For <?=$MY_COMPANY_NAME?></br></br>Authorised signature</td>
	
	
	
  </tr>
  
  
  
</table> <p align=center>SUBJECT TO ULHASNAGAR JURISDICTION</br >  This is computer generated invoice.</p>







</body>
</html>
