
<?php
	
	
$server_root="/omenwebNX";

include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");
$sql = "SELECT COMPANY_NAME FROM customers_tbl";
	$customercompanynames = array();
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
		
		while($row = mysqli_fetch_array($result)) {
		$customercompanynames[$count] = $row['COMPANY_NAME'];
		$count++;
		}
		
		
	}
	
	
	
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
   	
	<script type="text/javascript">
	
	var companydetails = {company_name:"Mukesh Garments", address:"gangaram", state:"Maharashtra"}; 
	
	
	
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
							$("#description").attr("value",itemdetail["DESCRIPTION"]+" "+itemdetail["SIZE"]);
							$("#rate").attr("value",itemdetail["RATE"]);
							$("#taxrate").attr("value",itemdetail["TAX_RATE"]);
							
							
							$("#item_availability").html(itemdetail["QUANTITY_RECEIVED"]);
							}catch(e){
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
						var taxrate=$("#taxrate").val();
						var quantity=$("#quantity").val();
						var hsn="HSN";
						var markup = "<tr><td><input type='checkbox' name='record'></td><td>" + item_id + "</td><td>" + description + "</td><td>" + hsn + "</td><td>" + quantity + "</td><td>" + rate + "</td><td>" + taxrate + "</td><td>" + ((rate*quantity)) + "</td></tr>";
						
						$("#items_table_body").append(markup);
						total_amount += (rate*quantity);
						
						
						
						
						
						
						
						
						
						var taxdetailshtml="<tr><th>sr</th><th>HSN</th><th>Taxable Amount</th>";
						if(customerdetails["STATE"]==companydetails["state"]){
						taxdetailshtml += "<th>CGST</th><th>SGST</th></tr>";
						taxdetailshtml +="<tr><td>1.</td><td>HSN</td><td>"+total_amount+"</td><td>"+(total_amount*(2.5/100))+"\n@2.5%</td><td>"+(total_amount*(2.5/100))+"\n@2.5%</td></tr>";
						
						}else{
						taxdetailshtml += "<th>IGST</th></tr>";
						taxdetailshtml +="<tr><td>1.</td><td>HSN</td><td>"+total_amount+"</td><td>"+(total_amount*(5/100))+"\n@5%</td></tr>";
						
						}
						$("#taxdetailtable").html(taxdetailshtml);
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
					return false; // prevent the button click from happening
					}
				});
				
				
				
				$(".delete-row").click(function(){

				$("#items_table_body").find('input[name="record"]').each(function(){

                if($(this).is(":checked")){

                    $(this).parents("tr").remove();
					
					$(this).parents("tr").find("td").each(function (colIndex, c) {
					if(colIndex==7)	
					total_amount -= c.textContent;
					});

						
               }

            });

        });
		
		
		
		
		
		
		
		
		$(".generateBill").click(function(){

				$.ajax({
                        type:"post",
                        url:"newInvoiceAction.php",
                        data:"customer_id="+customerdetails['customer_id']+"&billdate="+billdate+"&duedate="+duedate+"&total_amount="+total_amount+"&action=insertBill",
                        success:function(data){
							try{
								
							if(data>-1){
								var bill_id=data;
							
								var items_row_string="";
								
							$("#items_table_body").find('tr').each(function (rowIndex, r) {
								
								$(this).find('th,td').each(function (colIndex, c) {
									if(colIndex==1 || colIndex==4||colIndex==5)
										items_row_string += c.textContent+"||";
								});
							items_row_string += "||";
							});

							
							
							$.ajax({
							type:"post",
							url:"newInvoiceAction.php",
							data:"itemsrow="+items_row_string+"&bill_id="+bill_id+"&action=insertBillItems",
							success:function(data){
							try{
							
							
							
							
							$("#item_availability").html("Item not available"+data);
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							var CGST;
							var SGST;
							var IGST;
							
							if(customerdetails["STATE"]==companydetails["state"]){
								CGST=(total_amount*(2.5/100));
								SGST=(total_amount*(2.5/100));
								IGST=0;
							}else{
								IGST=(total_amount*(5/100));
							 	CGST=0;
								SGST=0;
							}
							
							
							
							$.ajax({
							type:"post",
							url:"newInvoiceAction.php",
							data:"CGST="+CGST+"&SGST="+SGST+"&IGST="+IGST+"&bill_id="+bill_id+"&action=insertBillTaxDetails",
							success:function(data){
							try{
							
							
							
							$("#item_availability").html("Bill generated"+data);
							
							window.location.replace("showInvoice.php?bill_id="+bill_id);

							
							
							
							
							
							}catch(e){
							$("#item_availability").html("Item not available");
							
							}
							
                        }
                     });
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							}catch(e){
							$("#item_availability").html("Item not available");
							
							}
							
                        }
                     });
							
							
							}else{
								
							alert("bill insert failed");
							}
							}catch(e){
							$("#item_availability").html("Item not available");
							
							}
							
                        }
                     });	

        return false; 
		});


				
				
				
				
				
				
				
				
				
				
				
				
				
				
				$("#billdate").change(function(){
                    				
					 billdate=$("#billdate").val();
					
										
               });

				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				$("#bill_id_search").click(function(){
                     var bill_id=$("#bill_id").val();
					
					 var companydetails;
                     $.ajax({
                        type:"post",
                        url:"newInvoiceAction.php",
                        data:"bill_id="+bill_id+"&action=searchBillId",
                        success:function(data){
							bill = JSON.parse(data);
							$("#buyerdetails").show();
							
							$("#company_name").html("COMPANY NAME: "+bill["COMPANY"]["NAME"]);
							$("#address").html("ADDRESS : "+bill["COMPANY"]["ADDR"]);
							$("#city").html("CITY : "+bill["COMPANY"]["CITY"]);
							$("#state").html("STATE : "+bill["COMPANY"]["STATE"]);
							$("#gsttreatment").html("GST TREATMENT: "+bill["COMPANY"]["GSTTREATMENT"]);
							$("#gstn").html("GSTN: "+bill["COMPANY"]["GSTN"]);
							
							$("#bill_date").html(" "+bill["GSTN"]);
							$("#bill_due_date").html(" "+bill["GSTN"]);
							
							
							
							
                        }
                     });
               });

				
				
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

	
	
	<div class="companydetails" id="companydetail" >
	
	<center>NITIN TRADERS</center>
	<center>107, Gangaram Market</center>
	<center>Opp. Bhagwandar Hospital</center>
	<center>Ulhasnagar- 421005</center>
	<center>Mob: 9146962469</center>
	<center>GSTN: 33ACMPW56789E1ZV</center>
	
	
	
	
	</div>
	
	<div class="billdetails" id="billdetails">
	
	
	<input type="number" id="bill_id" name="bill_id">
	<button type="button" class="bill_id_search" width="10%" id="bill_id_search">Search</button>
	<p id="bill_id_label">   </p><button type="button" class="bill_id_search" width="10%" id="bill_id_delete">Delete Row</button>
	
	</div>
    
	
	
	
	
	<div class="buyerdetails" id="buyerdetails">
	<p id="company_name"> COMPANY NAME:  </p> 
	<p id="address">  ADDRESS:  </p> 
	<p id="city">  CITY:  </p> 
	<p id="state">  STATE:  </p> 
	<p id="gsttreatment">  GST TREATMENT:  </p> 
	<p id="gstn">  GSTN:  </p> 
	
	Bill Date: <p id="bill_date"> COMPANY NAME:  </p> 
	Due Date: <p id="bill_due_date"> COMPANY NAME:  </p> 
	
	
	</div>
    

	
      
		<div class="ItemsDetails" id="ItemsDetails" style="display:none;">
		<table >
           <tr>
                <th>Sr.</th>
                <th>Item Code</th>
                <th>Description</th>
				<th>HSN</th>
				<th>QUANTITY</th>
				<th>RATE</th>
				<th>AMOUNT</th>
				
			</tr>
			
			<tbody id="items_table_body">
			</tbody>
		</table>
			<button type="button" class="delete-row">Delete Row</button>
		
		<div class="ItemsDetails" id="ItemsDetailsInput">
		<input type="number" id="item_id" name="item_id" >
		<input type="text" id="description" name="quantityrec" value=-1>
    	<input type="number" id="quantity" name="quantity" >
		<input type="number" id="taxrate" name="taxrate" value=-1>
		<input type="number" id="rate" name="rate" value=-1></br>
		
		</br>
		<p id="item_availability">This is a paragraph.</p>
		</div>
				
		</div>
		
		
		
		
		
		<div class="TaxDetail" id="TaxDetail" style="display:none;">
		<table id="taxdetailtable">
           <tr>
                <th>Sr.</th>
                <th>Item Code</th>
                <th>Description</th>
				<th>HSN</th>
				<th>QUANTITY</th>
				<th>RATE</th>
				<th>AMOUNT</th>
				
			</tr>
			
			
		</table>
			
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

form {
  max-width: 300px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #f4f7f8;
  border-radius: 8px;
}

.companydetails {
  max-width: 800px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}

.billdetails {
  max-width: 800px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}

input[type="text"],

{
border: 2px solid rgb(173, 204, 204);
height: 31px;
width: 223px;
box-shadow: 0px 0px 27px rgb(204, 204, 204) inset;
transition:500ms all ease;
padding:3px 3px 3px 3px;
}


.buyerdetails {
  max-width: 800px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.buyerdetailst {
  max-width: 800px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.ItemsDetails {
  max-width: 800px;
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
  padding: 19px 39px 18px 39px;
  color: #FFF;
  background-color: #4bc970;
  font-size: 18px;
  text-align: center;
  font-style: normal;
  border-radius: 5px;
  width: 20%;
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
  width: 30px;
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




