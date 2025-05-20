
<?php
	
	include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/var.php");
	$sql = "SELECT COMPANY_NAME FROM customers_tbl";
	$customercompanynames = array();
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
		$customercompanynames[$count]="Select Customer";
		$count++;
		while($row = mysqli_fetch_array($result)) {
		$customercompanynames[$count] = $row['COMPANY_NAME'];
		$count++;
		}
		
		
	}
	
	
	
	?>
	

 <?php
$server_root="/omenwebNX";

?>

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Pushy - Off-Canvas Navigation Menu</title>
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="stylesheet" href="<?=$server_root?>/css/normalize.css">
        <link rel="stylesheet" href="<?=$server_root?>/css/demo.css">
        <!-- Pushy CSS -->
        <link rel="stylesheet" href="<?=$server_root?>/css/pushy.css">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        
	<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
		
		
		
	<script type="text/javascript">
	
		var companydetails = {company_name:"Mukesh Garments", address:"gangaram", state:"Maharashtra"}; 
	
	
	var customerdetails;
	var items_list;
	var total_amount=0;
	
	var today = new Date();
	
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	
	var billdate=yyyy+"-"+mm+"-"+dd+"";
	today.setDate(today.getDate() + 60);
	
	dd = today.getDate();
	mm = today.getMonth()+1; //January is 0!
	yyyy = today.getFullYear();
	var duedate=yyyy+"-"+mm+"-"+dd+"";
	
			$(document).ready(function() {
				
				var buyernameArray = <?php echo json_encode($customercompanynames); ?>;
				$("#buyername").select2({
				  data: buyernameArray
				});
				
				
				
				
				$('#item_id').on("keypress", function(e) {
					if (e.keyCode == 13) {
						
						var item_id=$("#item_id").val();
						$.ajax({
                        type:"post",
                        url:"newInvoiceAction.php",
                        data:"item_id="+item_id+"&action=getitemdetail",
                        success:function(data){
							try{
							
							var itemdetail = JSON.parse(data);
							$("#description").val((itemdetail["DESCRIPTION"]+" "+itemdetail["SIZE"]));
							$("#rate").val(itemdetail["RATE"]);
							
							$("#quantity").val('');
							
							$("#item_availability").html(itemdetail["QUANTITY_RECEIVED"]);
							}catch(e){
								
							$("#description").val('');
							$("#rate").val('');
							
							$("#quantity").val('');
							$("#item_id").val('');
							
								
							$("#item_availability").html("Item not available");
							
							}
							
                        }
                     });											
					return false; // prevent the button click from happening
					}
				});
				
				$('#rate').on("keypress", function(e) {
					if (e.keyCode == 13) {
						var item_id=$("#item_id").val();
						var description=$("#description").val();
						var rate=$("#rate").val();
						var taxrate=5;
						var quantity=$("#quantity").val();
						var hsn="HSN";
						var markup = "<tr><td colspan='2'><input type='checkbox' name='record'></td><td><center>" + item_id + "</center></td><td><center>" + description + "</center></td><td><center>" + quantity + "</center></td><td><center>" + rate + "</center></td><td><center>" + ((rate*quantity)) + "</center></td></tr>";
						var samestate;
						
						if($("#customerstate").html()==$("#companystate").html()){
						samestate=1;
						}else{
						samestate=-1;
						}
						
						
						
						
						var quantity_available=parseInt($("#item_availability").html());
						if(quantity_available>=quantity){
						$("#items_table_body").append(markup);
						total_amount += (rate*quantity);
						
						
						
						try{
						$.ajax({
                        type:"post",
                        url:"updateInvoiceAction.php",
                        data:"item_id="+item_id+"&bill_id="+$.getUrlVar('bill_id')+"&quantity="+quantity+"&rate="+rate+"&action=addBillItem",
                        success:function(data){
							
							try{
								
							if(data>-1){
							alert ("updated Successfuly.");
							
							}else{
								
							alert("Failed To Update");
							}
							}catch(e){
							$("#item_availability").html("Item not available");
							
							}
							
                        }
                     });
						
						}catch(e){
							alert(e);
							
							}
						
						
						
						
						
						
						try{
						
						var taxdetailshtml="<tr><th>sr</th><th>HSN</th><th>Taxable Amount</th>";
						if(samestate>-1){
						taxdetailshtml += "<th>CGST</th><th>SGST</th></tr>";
						taxdetailshtml +="<tr><td>1.</td><td>HSN</td><td>"+total_amount+"</td><td>"+(total_amount*(2.5/100))+"\n@2.5%</td><td>"+(total_amount*(2.5/100))+"\n@2.5%</td></tr>";
						
						}else{
						taxdetailshtml += "<th>IGST</th></tr>";
						taxdetailshtml +="<tr><td>1.</td><td>HSN</td><td>"+total_amount+"</td><td>"+(total_amount*(5/100))+"\n@5%</td></tr>";
						
						}
						$("#taxdetailtable").html(taxdetailshtml);
						
						$("#item_id").val('');
							
						$("#description").attr("value","");
						$("#rate").val('');
							
						$("#quantity").val('');
							
						$("#item_id").focus();
						
						
						
						}catch(e){
						alert(e);
						}
						
						
						
						
						
					return false; // prevent the button click from happening
					
					}else{
					alert ("Total Available Quantity : "+quantity_available);
					}
					
					
					
					}
				});
				
				
				
				$(".delete-row").click(function(){
				
				$("#items_table_body").find('input[name="record"]').each(function(){

                if($(this).is(":checked")){

                    $(this).parents("tr").remove();
					
					$(this).parents("tr").find("td").each(function (colIndex, c) {
					if(colIndex==1)	{
					$.ajax({
                        type:"post",
                        url:"updateInvoiceAction.php",
                        data:"item_id="+c.textContent+"&bill_id="+$.getUrlVar('bill_id')+"&action=removeBillItem",
                        success:function(data){
							try{
								
							if(data>-1){
							alert ("updated Successfuly.");
							
							}else{
								
							alert("Failed To Update");
							}
							}catch(e){
							$("#item_availability").html("Item not available");
							
							}
							
                        }
                     });
					
					
					}
					});

						
               }

            });
				
				
				
				
					

        return false; 
		});
		
		
		
		
		
		
		
		
		
		
		
		
		$(".generateBill").click(function(){

		
			
							
							window.open("showInvoice.php?bill_id="+$.getUrlVar('bill_id'),"_blank");
													location.reload();

							

        return false; 
		});


				
				$("#transportcheck").click(function(){
					
					
					if($(this).is(":checked")){
					$("#TransportDetail").show();
					}
					else{
						$("#TransportDetail").hide();
					}
					
					
				});
				
				
				
				$("#billdate").change(function(){
                    				
					 billdate=$("#billdate").val();
					
										
               });

				
				
				$("#buyername").change(function(){
					
                     var customercompanyname=$("#buyername").val();
					
					 
					 
                     $.ajax({
                        type:"post",
                        url:"newInvoiceAction.php",
                        data:"customercompanyname="+customercompanyname+"&action=fetchcustomerdetail",
                        success:function(data){
							customerdetails = JSON.parse(data);
							
							$("#company_name").html("COMPANY NAME: "+customerdetails["COMPANY_NAME"]);
							$("#customername").html(" BUYER NAME: "+customerdetails["FNAME"] +" "+ customerdetails["LNAME"]);
							$("#address").html("ADDRESS : "+customerdetails["ADDRESS"]);
							$("#city").html("CITY : "+customerdetails["CITY"]);
							$("#customerstate").html("STATE : "+customerdetails["STATE"]);
							$("#gsttreatment").html("GST TREATMENT: "+customerdetails["GSTTREATMENT"]);
							$("#gstn").html("GSTN: "+customerdetails["GSTN"]);
							
							
							
							
                        }
                     });
               });

			


			
			$("#updatetransportbutton").click(function(){
				
				
				alert($.getUrlVar('bill_id'));
				
				var transportname=$("#transportname").val();
				var transportparcels=$("#transportparcels").val();
				
				
				$.ajax({
                        type:"post",
                        url:"updateInvoiceAction.php",
                        data:"transportname="+transportname+"&transportparcels="+transportparcels+"&bill_id="+$.getUrlVar('bill_id')+"&action=updateTransport",
                        success:function(data){
							try{
							alert (data);
							if(data>-1){
							alert ("updated Successfuly.123");
							
							}else{
								
							alert("Failed To Update");
							}
							}catch(e){
							$("#item_availability").html("Item not available");
							
							}
							
                        }
                     });	

        return false; 
		});
			
			
			
			
			

		
		
		
		
		
		$("#updatecustomerbutton").click(function(){
				
				
				alert($.getUrlVar('bill_id'));
				$.ajax({
                        type:"post",
                        url:"updateInvoiceAction.php",
                        data:"customer_id="+customerdetails['customer_id']+"&bill_id="+$.getUrlVar('bill_id')+"&action=updateCustomer",
                        success:function(data){
							try{
								
							if(data>-1){
							alert ("updated Successfuly.");
							
							}else{
								
							alert("Failed To Update");
							}
							}catch(e){
							$("#item_availability").html("Item not available");
							
							}
							
                        }
                     });	

        return false; 
		});
		
		

			
				
			});
			
			
			
			
			$.extend({
			getUrlVars: function(){
			var vars = [], hash;
			var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for(var i = 0; i < hashes.length; i++)
				{
				hash = hashes[i].split('=');
				vars.push(hash[0]);
				vars[hash[0]] = hash[1];
				}
			return vars;
			},
			getUrlVar: function(name){
			return $.getUrlVars()[name];
			}
			});
				
	</script>
	
	
	</head>



    <body>
	<?php
	
	include($_SERVER['DOCUMENT_ROOT']."$server_root/index.php");
	
	$sql = "SELECT COMPANY_NAME FROM customers_tbl";
	$customercompanynames = array();
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
		
		while($row = mysqli_fetch_array($result)) {
		$customercompanynames[$count] = $row['COMPANY_NAME'];
		}
		
		
	}
	
	
	
	?>

	<div class="companydetails" id="companydetail" style="display:none;">
	
	<center>NITIN TRADERS</center>
	<center>107, Gangaram Market</center>
	<center>Opp. Bhagwandar Hospital</center>
	<label id="companystate">Maharashtra</label>
	<center>Ulhasnagar- 421005</center>
	<center>Mob: 9146962469</center>
	<center>GSTN: 33ACMPW56789E1ZV</center>
	
	
	
	
	</div>
    
	<div class="buyerdetailst" id="buyerdetailst">
	
	
	<select id="buyername" style="width:300px;">
			<!-- Dropdown List Option -->
	</select>
	
	
	
	
	<?php
		
	$sql = "SELECT * FROM customers_tbl c, bills_tbl b where c.customer_id=b.customer_id AND b.bill_id=".$_GET["bill_id"];
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
	
	while($row = mysqli_fetch_array($result)) {
		$transportname=$row['transport_name'];
		$parcels=$row['transport_parcels'];
		$total_amount=$row['TOTAL_AMOUNT'];
		$date=$row['DATE'];
		$due_date=$row['DUE_DATE'];
		
	echo "Bill Date: <input type='date' id='billdate' value=".$date." >";
	echo "Due Date: <input type='date' id='duedate' value=".$due_date.">";
	
	echo "<div class='buyerdetails' id='buyerdetails'>";
	
	
	
	
	
		
		echo "<table>";
	
	echo "<tr> ";
	echo "<td><label id='company_name'> ".$row['COMPANY_NAME']." </label> </td>";
	echo "<td><label id='customername'> ".$row['FNAME']." ".$row['LNAME']." </label> </td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td rowspan=2><label id='address'>  ".$row['ADDRESS']."  </label></td> ";
	echo "<td><label id='city'>  ".$row['CITY']."  </label></td>";
	echo "</tr> ";
	
	echo "<tr>";
	echo "<td><label id='customerstate'>".$row['STATE']."</label></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><label id='gsttreatment'>  ".$row['GSTTREATMENT']."  </label></td> ";
	echo "<td><label id='gstn'>  ".$row['GSTN']."  </label></td>";
	echo "</tr>	";
	echo "</table>";
	if(strtoupper($row['STATE'])==strtoupper($MY_COMPANY_STATE)){
	$samestate=true;
	}else{
	$samestate=false;
	}
	
		
		}
		
		
	}
	
	
	
	?>
	
	
	
	
	
	<left><button type="button" class="updatecustomerbutton" id="updatecustomerbutton">update Customer</button></left>
	
	
	
	</div></div>
    

	
      
		<div class="ItemsDetails" id="ItemsDetails">
		<table >
           <tr>
                <th colspan="2">Sr.</th>
                <th>Item Code</th>
                <th>Description</th>
				<th>QUANTITY</th>
				<th>RATE</th>
				<th>AMOUNT</th>
				
			</tr>
			
			<tbody id="items_table_body">
				
				<?php
				$count=1;
				$sql = "SELECT I.DESCRIPTION, I.SIZE, I.ITEMS_ID,BI.QUANTITY, BI.RATE FROM Items_TBL I, bill_ITEMS_tbl bI where BI.ITEMS_ID=I.ITEMS_ID AND BI.bill_id=".$_GET["bill_id"];
				if($result = mysqli_query($dbhandle,$sql) ){
				
					while($row = mysqli_fetch_array($result)) {
		
				echo "<tr>";
				echo "<td colspan='2'><input type='checkbox' name='record'></td>";
				
				echo "<td><center>".$row['ITEMS_ID']."</center></td>";
				echo "<td><center>".$row['DESCRIPTION']." ".$row['SIZE']."</center></td>";
				echo "<td><center>".$row['QUANTITY']."</center></td>";
				echo "<td><center>".$row['RATE']."</center></td>";
				echo "<td><center>".($row['QUANTITY']*$row['RATE'])."</center></td>";
				
				echo "</tr>";
					}
				}
				?>
			
			</tbody>
		</table>
		</br>
			<table><tr>
			<td><button type="button" class="delete-row">Delete Row</button></td>
			<td><center><label id="totalquantitylabel">0</label></center></td>
			<td><center><label id="totalamountlabel"> ₹0</center></td>
			</tr>
			</table>
		<table>
		<tr><th>ITEM ID</th><th>Description</th><th>Quantity</th><th>Rate</th></tr>
		<tr>
		<td ><input type="number" id="item_id" name="item_id" ></td>
		<td><input type="text" id="description" name="description" ></td>
    	<td><input type="number" id="quantity" name="quantity" ></td>
		<td><input type="number" id="rate" name="rate" ></td>
		</tr>
		</table>
		</br>
		<p id="item_availability">Available Quantity.</p>
		
		
		<left><button type="button" class="generateBill" id="updateitemsbutton">update Items</button></left>
	
		
		
				
		</div>
		
		
		
		
		
		<div class="TaxDetail" id="TaxDetail">
		<table id="taxdetailtable">
           
			<?php
			
			
			echo "<tr><th>sr</th><th>HSN</th><th>Taxable Amount</th>";
					
			if($samestate){
			echo "<th>CGST</th><th>SGST</th></tr>";
			
			}else{
				echo "<th>IGST</th></tr>";
			}
			
			$count=1;
				$sql = "SELECT * FROM tax_details_tbl t where t.bill_id=".$_GET["bill_id"];
				if($result = mysqli_query($dbhandle,$sql) ){
				
					while($row = mysqli_fetch_array($result)) {
		
				echo "<tr>";
				echo "<td>".$count."</td>";
				echo "<td>62</td>";
				echo "<td>".$total_amount."</td>";
				if($samestate){
				echo "<td>".$row['CGST']."</td>";
				echo "<td>".$row['SGST']."</td>";
				}else{
				echo "<td>".$row['IGST']."</td>";
				}
				echo "</tr>";
					}
				}
			
				
				?>
			
		</table>
			
		
		<input type='checkbox' name='transport' id='transportcheck'> Enter Transport Details
		
		
		<div class="TransportDetail" id="TransportDetail" style="display:none;">
		Transport Name : <input type="text" id="transportname" name="transportname" value="<?=$transportname?>">
    	Transport Parcels :<input type="number" id="transportparcels" name="transportparcels" value="<?=$parcels?>">
		<left><button type="button" class="updatetransportbutton" id="updatetransportbutton">update Transport</button></left>
	
		</div>
		
		
		
		
		
		</br>
		
			<center><button type="button" class="generateBill">Display Bill</button></center>
	
			</div>

		<script src="<?=$server_root?>/js/pushy.min.js"></script>
				
    </body>
	
	
	
	
	<style>
	*, *:before, *:after {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}

body {
  font-family: 'Nunito', sans-serif;
  color: #384047;
}



.companydetails {
  max-width: 850px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}


.buyerdetails {
  max-width: 850px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.buyerdetailst {
  max-width: 850px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.ItemsDetails {
  max-width: 850px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}

.TaxDetail{
	 max-width: 850px;
	margin: 10px auto;
	padding: 10px 20px;
	background: #e8e8df;
	border-radius: 8px;
}



h1 {
  margin: 0 0 30px 0;
  text-align: center;
}


input[type="radio"],
input[type="checkbox"] {
  margin: 0 4px 8px 0;
}

select {
  padding: 6px;
  height: 32px;
  border-radius: 2px;
}

button {
  
  color: #FFF;
  background-color: #4bc970;
  font-size: 18px;
  text-align: center;
  font-style: normal;
  border-radius: 5px;
  width: 30%;
  border: 1px solid #3ac162;
  border-width: 1px 1px 3px;
  box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
  margin-bottom: 10px;
}

fieldset {
  margin-bottom: 30px;
  border: none;
}

legend {
  font-size: 1.4em;
  margin-bottom: 10px;
}

label {
  display: block;
  margin-bottom: 8px;
}

label.light {
  font-weight: 300;
  display: inline;
}

.number {
  background-color: #5fcf80;
  color: #fff;
  height: 30px;
  width: 20px;
  display: inline-block;
  font-size: 0.8em;
  margin-right: 4px;
  line-height: 30px;
  text-align: center;
  text-shadow: 0 1px 0 rgba(255,255,255,0.2);
  border-radius: 100%;
}

table {
    border-collapse: collapse;
    width: 100%;
}

td {
    text-align: left;
    padding: 8px;
}

th {
    background-color: #4CAF50;
    color: white;
}



tr:nth-child(even){background-color: #f2f2f2}


@media screen and (min-width: 480px) {

  form {
    max-width: 480px;
  }

}

	</style>
</html>




