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


</style>
</head>


<?php

include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/var.php");

$MY_COMPANY_NAME="NITIN TRADERS";
$MY_COMPANY_ADDR="107,Gangaram Market";
$MY_COMPANY_CITY="ULHASNAGAR";
$MY_COMPANY_ZIP="421005";
$MY_COMPANY_STATE="MAHARASHTRA";
$MY_COMPANY_STATE_CODE="27";
$MY_COMPANY_MOB="9146962469";
$MY_COMPANY_GSTN="27ACMPW5678E1ZV";

$MY_COMPANY_PAN="ACMPW5678E";
$MY_COMPANY_BANK_NAME="AXIS BANK";
$MY_COMPANY_BANK_ACCOUNT_NUMBER="911010054022294";
$MY_COMPANY_BANK_IFSC="UTIB0001053";
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

					}
					
				
				}
  


$BILL_ID=$_GET["bill_id"];




?>


<body>

<table style="width:100%"  align=center>
<caption> <font size="14px" face="selfish">O-men</font></br><font size="2px" >Jeans & Casuals</font> </caption> 
 
  <tr>
    <td rowspan="3" colspan="2" cellpadding="10px">To,</br><font size="5px" ><?php 
	
	echo $COMPANY_NAME."</br>".$COMPANY_CITY."</td>"?></font></td>
    <td>Invoice No.</br><?=$BILL_ID?></td>
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
   $sqlquery="select  i.items_id,bi.quantity,bi.rate,i.description,I.SIZE,I.TAX_RATE  from bill_items_tbl bi,items_tbl i where bi.bill_id=".$BILL_ID." and bi.items_id=i.items_id;";
  
  
  $show=mysqli_query($dbhandle,$sqlquery);
 
 
 
	$row_count=0;
	$total_quantity=0;
     while($row=mysqli_fetch_array($show)){
        $row_count++;
	echo "<tr>";
    echo "<td>$row_count</td>";
    echo "<td>lot no.".$row['items_id']." ".$row['description']." ".$row['SIZE']."</td>";
	
    echo "<td>".$row['quantity']."</td>";
	echo "<td>".($row['rate']+120)."</td>";
	echo "<td>pcs</td>";
	echo "<td>".($row['quantity']*($row['rate']+120))."</td>";
	echo "</tr>";
	$total_quantity += $row['quantity'];
		
     }
	 
	
	$dummy_row=(7-$row_count)*2;
	
	
	
	
	
	 
  while($dummy_row>-1){
	echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
    echo "<td></td>";
	
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	$dummy_row--;
  }
  
	
	$total_amount_plus=$TOTAL_AMOUNT+($total_quantity*120);
	$total_amount_plus_taxes=($TOTAL_AMOUNT*(5/100));
	
	
	
 echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
    echo "<td>GST</td>";
	echo "<td></td>";
	
	echo "<td>".$total_amount_plus_taxes."</td>";
	echo "</tr>";
	$row_count++;
	echo "</table>";
  
  
  
  
  ?>
  
  
</table>

<table style="width:100%"  align=center id="items">

 
  <tr>
    <th width="43%" colspan="3"> Total</th>
    
	<th width="23%" colspan="3" align=left><?=$total_quantity?></th>
	<th width="10%">₹ <?=($total_amount_plus+$total_amount_plus_taxes)?></th>
	
	
  </tr>
  </table>
  
  
	<table  id="amountinwords" style="width:100%" align=center>

 
  <tr>
    <td >Amount In Words<b> Indian Rupee(s) <?php 
	
	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

	echo $f->format(($TOTAL_AMOUNT+($total_quantity*120)));
	
	?> Only. </b></td>
    <td align=right>E&OE</td>
    
	
	
  </tr>
  </table>
  
</br>


<table style="width:100%"  align=center>
<caption> <font size="14px" face="selfish">O-men</font></br><font size="2px" >Jeans & Casuals</font> </caption> 
 
  <tr>
    <td rowspan="3" colspan="2" cellpadding="10px">To,</br><font size="5px" ><?php 
	
	echo $COMPANY_NAME."</br>".$COMPANY_CITY."</td>"?></font></td>
    <td>Invoice No.</br><?=$BILL_ID?></td>
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
   $sqlquery="select  i.items_id,bi.quantity,bi.rate,i.description,I.SIZE,I.TAX_RATE  from bill_items_tbl bi,items_tbl i where bi.bill_id=".$BILL_ID." and bi.items_id=i.items_id;";
  
  
  $show=mysqli_query($dbhandle,$sqlquery);
 
 
 
	$row_count=0;
	$total_quantity=0;
     while($row=mysqli_fetch_array($show)){
        $row_count++;
	echo "<tr>";
    echo "<td>$row_count</td>";
    echo "<td>lot no.".$row['items_id']." ".$row['description']." ".$row['SIZE']."</td>";
	
    echo "<td>".$row['quantity']."</td>";
	echo "<td>".($row['rate']+120)."</td>";
	echo "<td>pcs</td>";
	echo "<td>".($row['quantity']*($row['rate']+120))."</td>";
	echo "</tr>";
	$total_quantity += $row['quantity'];
		
     }
	 
	
	$dummy_row=(7-$row_count)*2;
	
	
	
	
	
	 
  while($dummy_row>-1){
	echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
    echo "<td></td>";
	
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	$dummy_row--;
  }
  
	
	$total_amount_plus=$TOTAL_AMOUNT+($total_quantity*120);
	$total_amount_plus_taxes=($TOTAL_AMOUNT*(5/100));
	
	
	
 echo "<tr height='10px'>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td>";
    echo "<td>GST</td>";
	echo "<td></td>";
	
	echo "<td>".$total_amount_plus_taxes."</td>";
	echo "</tr>";
	$row_count++;
	echo "</table>";
  
  
  
  
  ?>
  
  
</table>

<table style="width:100%"  align=center id="items">

 
  <tr>
    <th width="43%" colspan="3"> Total</th>
    
	<th width="23%" colspan="3" align=left><?=$total_quantity?></th>
	<th width="10%">₹ <?=($total_amount_plus+$total_amount_plus_taxes)?></th>
	
	
  </tr>
  </table>
  
  
	<table  id="amountinwords" style="width:100%" align=center>

 
  <tr>
    <td >Amount In Words<b> Indian Rupee(s) <?php 
	
	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

	echo $f->format(($TOTAL_AMOUNT+($total_quantity*120)));
	
	?> Only. </b></td>
    <td align=right>E&OE</td>
    
	
	
  </tr>
  </table>
  





</body>
</html>
