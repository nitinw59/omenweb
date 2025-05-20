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




	$sql = "SELECT DISTINCT BRAND from GENERALIZED_ITEMS WHERE AVAIALABLE_QTY > 0 ";
	$generalizedItems = array();
	if($result = mysqli_query($dbhandle_stockmanager,$sql) ){
		$count=0;
		$generalizedItems[$count]="Select Item";
		$count++;
		while($row = mysqli_fetch_array($result)) {
		$generalizedItems[$count] = $row['BRAND'];
		$count++;
		}
		
		
	}



	
	
	
	?>
	


  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>New Invoice</title>
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
	
	var companydetails = {company_name:"Mukesh Garments", address:"gangaram", state:"Maharashtra"}; 
	
	var current_item_rate=0;
	var customerdetails;
	var items_list;
	var total_amount=0;
	var total_bamount=0;
	
	var totalquntity=0;
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
				var generalizedItems = <?php echo json_encode($generalizedItems); ?>;
				
				$("#buyername").select2({
					
					data: buyernameArray
				});
				$("#brand").select2({
				  data: generalizedItems
				});
				
				var url = new URL(window.location.href);
				var challanNo=url.searchParams.get("challanNo");
				$("#challanNo").html(challanNo);
				var items_array={};
				var items_count=0;
				
				$("#buyername").select2("open");
				
				$('#rate').on("keypress", function(e) {
					
					if (e.keyCode == 13) {
						var item_id=$("#item_id").val();
						var description=$("#brand").val()+"_"+$("#item_style").val()+"_"+$("#size").val();
						var rate=$("#rate").val();
						var quantity=$("#quantity").val();
						
						var markup = "<tr><td><center><input type='checkbox' name='record'></center></td>    <td><center>" 
									+ item_id + 
									"</center></td><td><center>" 
									+ description + 
									"</center></td><td><center>" 
									+ quantity + 
									"</center></td><td><center>" 
									+ rate + 
									"</center></td><td><center>" 
									+ ((rate*quantity)) 
									+ "</center></td></tr>";

						
						var quantity_available=parseInt($("#item_availability").html());
						if(quantity_available>=quantity){
						$("#items_table_body").append(markup);
						total_amount += (rate*quantity);
						totalquntity+= parseInt(quantity);
						
						$("#totalamountlabel").html("₹"+total_amount);
						$("#totalquantitylabel").html(totalquntity);
						
						
						//alert(JSON.stringify(items_array));	
						
						$("#item_id").val('');
						//$("#brand").val("Select Item").trigger("change");
						$("#item_style").empty();
						$("#size").empty();
						$("#rate").val('');
						$("#quantity").val('');
						$("#brand").val('Select Item').trigger('change');
						$("#brand").select2("open");
						$("#brand").focus();
						
						
					return false; // prevent the button click from happening
					
					}else{
					alert ("Total Available Quantity : "+quantity_available);
					}
					
					
					
					}
				});
				
				
				
		
		
		
		
		
		
		
		$(".generateBill").click(function(){
			var challanNo=$("#challanNo").html();
			
			$("#items_table_body").find('input[name="record"]').each(function(){	
				
				
				var item_id;
				var description;
				var quantity;
				var rate;
				

				$(this).parents("tr").find("td").each(function (colIndex, c) {
					if(colIndex==1)	
						item_id = c.textContent;
					if(colIndex==2)
						description= c.textContent;
					if(colIndex==3)
						quantity= c.textContent;
					if(colIndex==4)
						rate= c.textContent;	
					});

		
			items_array[items_count] = [item_id,description,quantity,rate];	
			items_count++;
			
			

			});	


			
			
			$.ajax({
                        type:"post",
                        url:"newInvoiceAction.php",
                        data:"customer_id="+customerdetails['customer_id']+"&challanNo="+challanNo+"&billdate="+billdate+"&duedate="+duedate+"&total_amount="+total_amount+"&action=insertBill",
                        success:function(data){
							$("#totalquantitylabel").html(data);
							
							try{
							if(data>-1){
											var bill_id=data;
											

												$.ajax({
													type:"post",
													url:"newInvoiceAction.php",
													data:"itemsrow="+JSON.stringify(items_array)+"&bill_id="+bill_id+"&action=insertBillItems",
													success:function(data){
														
														//$("#totalamountlabel").html("₹"+data);

													window.open("showInvoicesecond.php?bill_id="+bill_id,"_blank");
													window.location.replace("../challan/listChallan.php");					

													}
												});
							
							
										}
							}catch(e){
								
							
							}
							
                        }
                     });
				

        return false; 
			
		});


				
				
				
				
				
				
				
				
				
				
				
				
				
				$("#billdate").change(function(){
                    
					if((new Date())<(new Date($("#billdate").val()))) 
					alert("Mehrbani karke month change kar ");
					else	
					billdate=$("#billdate").val();
					
										
               });

				
				
			   $("#size").change(function(){
				var brand=$("#brand").val();
				var itemStyle=$("#item_style").val();
				var size=$("#size").val();
				
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
								
										$("#brand").val('');
										$("#item_style").val('');
										$("#size").val('');
										$("#rate").val('');
										$("#quantity").val('');
										$("#item_id").val('');
										$("#item_availability").html("Item not available");
							
							}
							
							
							
							
                        }
                     });






			   });
				
				
			   $("#brand").change(function(){
				$("#size").html('');
				$("#item_style").html('');
				var brand= $("#brand").val();
				if(brand!="Select Item")
				{
				$.ajax({
					type:"post",
					url:"newInvoiceAction.php",
					data:"brand="+brand+"&action=getStyleList",
					success:function(data){
						
						
						$("#item_style").select2({
				  			data: JSON.parse(data)
						});
						$("#item_style").select2("open");


					}
				});

			}

			   });




			   $("#item_style").change(function(){

					$("#size").html('');
					var brand= $("#brand").val();
					var style= $("#item_style").val();

					$.ajax({
						type:"post",
						url:"newInvoiceAction.php",
						data:"brand="+brand+"&style="+style+"&action=getSizeList",
						success:function(data){
							$("#size").select2({
			  					data: JSON.parse(data)
							});
							$("#size").select2("open");

						}
					});
				});




			   $(".syncGeneralizedItemsList").click(function(){
				$("#item_style").empty().trigger("change");
				
				$("#size").empty().trigger("change");
				$("#brand").html('');
				$.ajax({
					type:"post",
					url:"newInvoiceAction.php",
					data:"action=getBrandList",
					success:function(data){
						$("#brand").select2({
				  			data: JSON.parse(data)
						});
						$("#brand").select2("open");

					}
				});

				
			   });


				
				$("#buyername").change(function(){
					
                     var customercompanyname=$("#buyername").val();
					
					 
					 
                     $.ajax({
                        type:"post",
                        url:"newInvoiceAction.php",
                        data:"customercompanyname="+customercompanyname+"&action=fetchcustomerdetail",
                        success:function(data){
							

							customerdetails = JSON.parse(data);
							$("#company_name").html(""+customerdetails["COMPANY_NAME"]);
							$("#customername").html(""+customerdetails["FNAME"] +" "+ customerdetails["LNAME"]);
							$("#address").html(""+customerdetails["ADDRESS"]);
							$("#city").html(""+customerdetails["CITY"]);
							$("#state").html(""+customerdetails["STATE"]);
							
							$("#brand").select2("open");
							
							
							
							
							
                        }
                     });
               
				
				
				
				});

				
				
			});
	</script>
	
	
	</head>



    <body>
	
	<?php
	$current_date=date('Y-m-d', time());
	$current_due_date=date('Y-m-d', strtotime("+60 days"));
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	
	$sql = "SELECT COMPANY_NAME FROM customers_tbl";
	$customercompanynames = array();
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
		
		while($row = mysqli_fetch_array($result)) {
		$customercompanynames[$count] = $row['COMPANY_NAME'];
		}
		
		
	}
	
	
	
	?>

	
    
	<div class="buyerdetailst" id="buyerdetailst">
		<legend class="challanNo" id="challanNo"></legend>
	<table>
	<tr>
	<td width="50%">
	<center><select id="buyername" style="width:300px;">
			<!-- Dropdown List Option -->
	</select>
	</center>
	</td>
	<td width="25%">
	Bill Date: <input type="date" id="billdate" value="<?=$current_date?>"  >
	</td>
	<td width="25%">
	Due Date: <input type="date" id="duedate" value="<?=$current_due_date?>" >
	</td>
	</tr>
	
	</table>
	<div class="buyerdetails" id="buyerdetails">
	<table>
	
	<tr> 
	<td><label id="company_name"> COMPANY NAME:  </label> </td>
	<td><label id="customername"> BUYER NAME:  </label> </td>
	</tr>
	
	<tr>
	<td rowspan=2><label id="address">  ADDRESS:  </label></td> 
	<td><label id="city">  CITY:  </label></td>
	</tr> 
	
	<tr>
	<td><label id="state">  STATE:  </label></td>
	</tr>
	
	<tr>
	<td><label id="gsttreatment">  GST TREATMENT:  </label></td> 
	<td><label id="gstn">  GSTN:  </label></td>
	</tr>	
	</table>
	
	</div></div>
    

	
      
		<div class="ItemsDetails" id="ItemsDetails">
		<table >
           <tr>
                <th>Sr.</th>
                <th>Item Code</th>
                <th>Description</th>
				
				<th>QUANTITY</th>
				<th>RATE</th>
				<th>AMOUNT</th>
				
				
			</tr>
			
			<tbody id="items_table_body">
			</tbody>
		</table>
		</br>
			<table><tr>
			<td width="50%"><button type="button" class="delete-row">Delete Row</button></td>
			<td  width="20%"><label id="totalquantitylabel">0</label></td>
			<td width="20%"></td>
			<td  width="20%"><label id="totalamountlabel"> ₹0</label></td>
			</tr>
			</table>
		
		<div class="ItemsDetails2" id="ItemsDetailsInput">
		<table>
		<tr><th>BRAND</th><th>STYLE</th><th>SIZE</th><th>QUANTITY</th><th>Rate</th></tr>
		<tr>
		
		
		<td><center>
			<select id="brand" style="width:120px;">
			<!-- Dropdown List Option -->
			</select></center></td>

		<td><center>
			<select id="item_style" style="width:150px;">
			<!-- Dropdown List Option -->
			</select></center></td>

		<td><center>
			<select id="size" style="width:100px;">
			<!-- Dropdown List Option -->
			</select></center></td>


    	<td><center><input type="number" id="quantity" name="quantity" ></center></td>
		<td><center><input type="number" id="rate" name="rate" ></center></td>
		<td ><center><input type="number" id="item_id" name="item_id" class="itemdetailbox" readonly hidden></center></td>

		</table>
		</br>
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
				
		</div>
		
		
		
		
		
		<div class="TaxDetail" id="TaxDetail">
		</br>
		
			<center><button type="button" class="generateBill">Generate</button></center>
	
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
legend.challanNo{
	text-align: center;
	color:red;
}

.companydetails {
  max-width: 90%;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
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

.buyerdetails {
  max-width: 90%;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.buyerdetailst {
  max-width: 90%;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.ItemsDetails {
  max-width: 90%;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.ItemsDetails2 {
  
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}

.TaxDetail{
	 max-width:  90%;
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




