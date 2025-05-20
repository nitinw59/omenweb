
<?php
	

	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");

	
	
	$sql = "SELECT COMPANY_NAME FROM customers_tbl where archive_state=0 order by COMPANY_NAME ASC";
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

	$sql = "SELECT BRAND,ITEM_STYLE,SIZE FROM GENERALIZED_ITEMS WHERE AVAIALABLE_QTY > 0 ";
	$generalizedItems = array();
	if($result = mysqli_query($dbhandle_stockmanager,$sql) ){
		$count=0;
		$generalizedItems[$count]="Select Item";
		$count++;
		while($row = mysqli_fetch_array($result)) {
		$generalizedItems[$count] = $row['BRAND']."_".$row['ITEM_STYLE']."_".$row['SIZE'];
		$count++;
		}
		
		
	}
	
		
	$sql = "SELECT * FROM customers_tbl c, bills_tbl b where c.customer_id=b.customer_id AND b.bill_id=".$_GET["bill_id"];
	
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
	
		while($row = mysqli_fetch_array($result)) {
			$company_name=$row['COMPANY_NAME'];
			$FNAME=$row['FNAME'];
			$LNAME=$row['LNAME'];
			$company_name=$row['COMPANY_NAME'];
			$transportname=$row['transport_name'];
			$parcels=$row['transport_parcels'];
			$total_amount=$row['TOTAL_AMOUNT'];
			$date=$row['DATE'];
			$due_date=$row['DUE_DATE'];
		}
	}
	
	?>
	

 

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Update Invoice</title>
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="stylesheet" href="/<?=$omenNX?>/css/normalize.css">
        <link rel="stylesheet" href="/<?=$omenNX?>/css/demo.css">
        <!-- Pushy CSS -->
        <link rel="stylesheet" href="/<?=$omenNX?>/css/pushy.css">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        
	<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
		
		
		
	<script type="text/javascript">
	
	
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
				var text = prompt("Enter Password", "nitsy1015");
				if(text!="corsair1015"){
					alert("eneter Correct Password!");
					location.reload();
				}else{
					$("#ItemsDetails").show();
					$("#buyerdetailst").show();

				}
				
				var buyernameArray = <?php echo json_encode($customercompanynames); ?>;
				var generalizedItems = <?php echo json_encode($generalizedItems); ?>;
				
				$("#buyername").select2({
					
					data: buyernameArray
				});
				$("#description").select2({
				  data: generalizedItems
				});
				
				
				
				$("#description").change(function(){
					var item=$("#description").val();
					const item_array=item.split("_");
					var brand=item_array[0];
					var itemStyle=item_array[1];
					var size=item_array[2];

					if(item=="SelectItem")
						alert("please select Item.");
					else{
						$.ajax({
                        type:"post",
                        url:"newInvoiceAction.php",
                        data:"brand="+brand+"&itemStyle="+itemStyle+"&size="+size+"&action=fetchItemDetails",
                        success:function(data){
							try{
							
										var itemdetail = JSON.parse(data);
										$("#rate").val(itemdetail["SELLING_PRICE"]);
										current_item_rate=parseInt(itemdetail["SELLING_PRICE"]);
										$("#quantity").val('');
										$("#quantity").focus();
										$("#item_id").val(itemdetail["items_id"])
										$("#item_availability").html(itemdetail["AVAIALABLE_QTY"]);
							}catch(e){
								
										$("#description").val('');
										$("#rate").val('');
										$("#quantity").val('');
										$("#item_id").val('');
										$("#item_availability").html("Item not available");
							
							}
						}
                    	});
					}
				});
				
			
				
				$('#rate').on("keypress", function(e) {
					if (e.keyCode == 13) {
						var item_id=$("#item_id").val();
						var description=$("#description").val();
						var rate=$("#rate").val();
						var quantity=$("#quantity").val();
						//var total_quantity=$("#totalquantitylabel").val();
						var total_quantity=Number($("#totalquantitylabel").attr("value"));
						var total_bill_amount=Number($("#totalamountlabel").attr("value"));
						var markup = "<tr><td colspan='2'><input type='checkbox' name='record'></td><td><center>" + item_id + "</center></td><td><center>" + description + "</center></td><td><center>" + quantity + "</center></td><td><center>" + rate + "</center></td><td><center>" + ((rate*quantity)) + "</center></td></tr>";
						var quantity_available=parseInt($("#item_availability").html());
							if(quantity_available>=quantity){
								
									try{
										$.ajax({
                        					type:"post",
                       						url:"updateInvoiceAction.php",
                        					data:"item_id="+item_id+"&bill_id="+$.getUrlVar('bill_id')+"&description="+description+"&quantity="+quantity+"&rate="+rate+"&action=addBillItem",
                        					success:function(data){
												try{
													if(data>-1){
														$("#totalquantitylabel").attr("value",(total_quantity+Number(quantity)));
														$("#totalquantitylabel").html(total_quantity+Number(quantity));
														$("#totalamountlabel").attr("value",(total_bill_amount+Number(rate*quantity)));
														$("#totalamountlabel").html(total_bill_amount+Number(rate*quantity));
														$("#items_table_body").append(markup);	
														alert ("updated Successfuly.");
														$("#item_id").val('');

														$("#description").val("Select Item").trigger("change");
														$("#rate").val('');
														$("#quantity").val('');
														$("#description").select2("open");
							
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
						
						
						
					return false; // prevent the button click from happening
					
					}else{
					alert ("Total Available Quantity : "+quantity_available);
					}
					
					
					
					}
				});
				
				
				
				$(".delete-row").click(function(){
					if (confirm("You really want to delete? ")){
				
					
						var rate=0;
						var quantity=0;
						//var total_quantity=$("#totalquantitylabel").val();
						var total_quantity=Number($("#totalquantitylabel").attr("value"));
						var total_bill_amount=Number($("#totalamountlabel").attr("value"));
						var amount=0;
				
						$("#items_table_body").find('input[name="record"]').each(function(){

                			if($(this).is(":checked")){
					
								$(this).parents("tr").remove();
								
								$(this).parents("tr").find("td").each(function (colIndex, c) {
						
								if(colIndex==3)
									quantity=Number(c.textContent);
								if(colIndex==4)
									rate=Number(c.textContent);
								if(colIndex==5)
									amount=Number(c.textContent);
					
								if(colIndex==1)	{
									$.ajax({
                     	   				type:"post",
                     	   				url:"updateInvoiceAction.php",
                    	   				data:"item_id="+c.textContent+"&bill_id="+$.getUrlVar('bill_id')+"&action=removeBillItem",
                    	    			success:function(data){
												if(data>-1)
													alert ("updated Successfuly.");
													
												else
													alert("Failed To Update");
										}
                     				});
					
								}
								});
							
							$("#totalquantitylabel").attr("value",(total_quantity-(quantity)));
							$("#totalquantitylabel").html(total_quantity-(quantity));
							
							$("#totalamountlabel").attr("value",(total_bill_amount-(amount)));
							$("#totalamountlabel").html(total_bill_amount-(amount));
						
               				}
						});
					}
        		return false; 
					});
		
		
		
		
		$(".generateBill").click(function(){
			window.open("showInvoicesecond.php?bill_id="+$.getUrlVar('bill_id'),"_blank");
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
							
							
							
							
                        }
                     });
               });

			


						$(".syncGeneralizedItemsList").click(function(){


						$("#description").html('');
						$.ajax({
						type:"post",
						url:"newInvoiceAction.php",
						data:"action=getGeneralizedItemsList",
						success:function(data){
						

						$("#description").select2({
						data: JSON.parse(data)
						});

						}
						});


						});



			
			$("#updatetransportbutton").click(function(){
				
				
				var transportname=$("#transportname").val();
				var transportparcels=$("#transportparcels").val();
				
				
				$.ajax({
                        type:"post",
                        url:"updateInvoiceAction.php",
                        data:"transportname="+transportname+"&transportparcels="+transportparcels+"&bill_id="+$.getUrlVar('bill_id')+"&action=updateTransport",
                        success:function(data){
							try{
							
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
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	
	
	
	
	?>

	<div class="companydetails" id="companydetail" style="display:none;">
	
	
	
	
	</div>
    
	<div class="buyerdetailst" id="buyerdetailst" style="display:none;">
	
	
	<select id="buyername" style="width:300px;">
			<!-- Dropdown List Option -->
	</select>
	
	
	
	
	<?php
	
		
		echo "Bill Date: <input type='date' id='billdate' value=".$date." >";
		echo "Due Date: <input type='date' id='duedate' value=".$due_date.">";
	
		echo "<div class='buyerdetails' id='buyerdetails' >";
	
	
	
	
	
		
		echo "<table>";
	
		echo "<tr> ";
		echo "<td><label id='company_name'> ".$company_name." </label> </td>";
		echo "<td><label id='customername'> ".$FNAME." ".$LNAME." </label> </td>";
		echo "</tr>";
	
		
	
		echo "<tr>";
		echo "</tr>	";
		echo "</table>";
	
	
		
	
	
	
	?>
	
	
	
	
	
	<left><button type="button" class="updatecustomerbutton" id="updatecustomerbutton">update Customer</button></left>
	
	
	
	</div></div>
    

	
      
		<div class="ItemsDetails" id="ItemsDetails" style="display:none;">
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
				$quantity=0;
				$sql = "SELECT 
						DESCRIPTION,
						bill_ITEMS_ID as ITEMS_ID,
						QUANTITY,
						RATE
							FROM  bill_ITEMS_tbl 
							where bill_id=".$_GET["bill_id"];
				if($result = mysqli_query($dbhandle,$sql) ){
				
					while($row = mysqli_fetch_array($result)) {
		
				echo "<tr>";
				echo "<td colspan='2'><input type='checkbox' name='record'></td>";
				
				echo "<td><center>".$row['ITEMS_ID']."</center></td>";
				echo "<td><center>".$row['DESCRIPTION']."</center></td>";
				echo "<td><center>".$row['QUANTITY']."</center></td>";
				echo "<td><center>".$row['RATE']."</center></td>";
				echo "<td><center>".($row['QUANTITY']*$row['RATE'])."</center></td>";
				
				echo "</tr>";
				$quantity=$quantity+$row['QUANTITY'];
					}
				}
				?>
			
			</tbody>
		</table>
		</br>
			<table><tr>
			<td><button type="button" class="delete-row">Delete Row</button></td>
			<td><center><label id="totalquantitylabel" value="<?=$quantity?>"><?=$quantity?></label></center></td>
			<td><center><label id="totalamountlabel" value="<?=$total_amount?>"> ₹<?=$total_amount?></center></td>
			</tr>
			</table>
		
		
		<div class="ItemsDetails2" id="ItemsDetailsInput">
		<table>
		<tr><th width="20%">ITEM ID</th><th width="20%">Description</th><th width="20%">Quantity</th><th width="20%">Rate</th></tr>
		<tr>
		<td ><center><input type="number" id="item_id" name="item_id" class="itemdetailbox" readonly></center></td>
		<td><center>
			<select id="description" style="width:300px;">
			<!-- Dropdown List Option -->
			</select></center></td>
    	<td><center><input type="number" id="quantity" name="quantity" ></center></td>
		<td><center><input type="number" id="rate" name="rate" ></center></td>
		</table>
		<table>
			<tr>
				<td>
					<center><button type="button" class="syncGeneralizedItemsList">Sync List</button></center>
				</td>
				<td>
					<p id="item_availability">0</p>
				</td>
			</tr>
		</table>

		
		
				
		</div>
		
		
		
		
		
		<div class="TaxDetail" id="TaxDetail">
		
			
		
		
		
		
		
		
		
		</br>
		
			<center><button type="button" class="generateBill">Display Bill</button></center>
	
			</div>

			
	
			<script src="/<?=$omenNX?>/js/pushy.min.js"></script>
				
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



.syncGeneralizedItemsList {
  background: #3498db;
  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
  background-image: -o-linear-gradient(top, #3498db, #2980b9);
  background-image: linear-gradient(to bottom, #3498db, #2980b9);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #ffffff;
  font-size: 15px;
  padding: 10px 10px 10px 10px;
  text-decoration: none;
  width: 100px
}

.syncGeneralizedItemsList:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}
.companydetails {
  max-width:  90%;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}


.buyerdetails {
  max-width: 90%;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.buyerdetailst {
  max-width:  90%;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.ItemsDetails {
  max-width:  90%;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}

.TaxDetail{
	 max-width: 90%;
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




