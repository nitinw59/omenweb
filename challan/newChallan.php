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


	?>
	


  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?=$MY_COMPANY_NAME?></title>
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
	
	var companydetails = {company_name:"MANISH Garments", address:"gangaram", state:"Maharashtra"}; 
	
	var total_amount=0;
	
	var totalquntity=0;
	var today = new Date();
	
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	
	var billdate=yyyy+"-"+mm+"-"+dd+"";
	today.setDate(today.getDate() + 60);
	
	
			$(document).ready(function() {
				
				

				var buyernameArray = <?php echo json_encode($customercompanynames); ?>;
				
				$("#buyername").select2({
					
					data: buyernameArray
				});
				
				
				var items_array={};
				var items_count=0;
				
				$("#buyername").select2("open");
				
				$(".addItem").click(function(){
                    
						
						var quantity=$("#qty").val();
						var rate=140;
						var description="MIX LOT";
						
						var markup = "<tr><td><center>" 
									+ quantity + 
									"</center></td></tr>";

						
						
						
						$("#items_table_body").append(markup);
						total_amount += (rate*quantity);
						totalquntity+= parseInt(quantity);
						
						$("#totalamountlabel").html("₹"+total_amount);
						$("#totalquantitylabel").html(totalquntity);
						
						
						//alert(JSON.stringify(items_array));	
						$("#qty").val(null);
						
						$("#qty").focus();
						
					return false; // prevent the button click from happening
					
					
					
					
					
					
				});
				
				
				
				
				
		
		
		
		
		
		
		
		
		$(".generateBill").click(function(){		
			if (!$("#transportname").val() || !$("#transportparcels").val() ) {
						alert("Transort Moga!!");
			}
				else{
					var quantityList="";
					$("#items_table_body").find('tr').each(function(){	
						var quantity;
						
						$(this).find("td").each(function (colIndex, c) {
							quantity=c.textContent;
						});
						quantityList += quantity+"--";	
						
					});	
			
			var transportname=$("#transportname").val();
			var transportparcels=$("#transportparcels").val();
			
			var t_date=$("#t_date").val();
			
			$.ajax({
                        type:"post",
                        url:"newChallanAction.php",
                        data:"customer_id="+customerdetails['customer_id']+"&billdate="+billdate+"&transportname="+transportname+"&quantityList="+quantityList+"&transportparcels="+transportparcels+"&action=insertChallan",
                        success:function(data){
                          if(data)
                          alert("Challan No: "+data+" generated.");
                          else
                          alert("Failed.");
							            window.location.replace("newChallan.php");					
							
							
							
                        }
                     });
				

        		return false; 
			}
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
                    
					if((new Date())<(new Date($("#billdate").val()))) 
					alert("Mehrbani karke month change kar ");
					else	
					billdate=$("#billdate").val();
					
										
               });

				
				
			   
				


			  


				
				$("#buyername").change(function(){
					
                     var customercompanyname=$("#buyername").val();
					
					 
					 
                     $.ajax({
                        type:"post",
                        url:"newChallanAction.php",
                        data:"customercompanyname="+customercompanyname+"&action=fetchcustomerdetail",
                        success:function(data){
							
                           
							customerdetails = JSON.parse(data);
							$("#company_name").html(""+customerdetails["COMPANY_NAME"]);
							$("#city").html(""+customerdetails["CITY"]);
							
							
							
							
							
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
	<table>
	<tr>
	    <td width="50%">
	    <center><select id="buyername" style="width:300px;">
			<!-- Dropdown List Option -->
	    </select>
	    </center>
	    </td>
    </tr>
    
    <tr>
    
    <td width="25%">
	Bill Date: <input type="date" id="billdate" value="<?=$current_date?>"  >
	</td>
	
	</tr>
	
	</table>
	<div class="buyerdetails" id="buyerdetails">
	
    
    <table>
	<tr> 
	    <td><label id="company_name"> COMPANY NAME:  </label> </td>
	</tr>
	<tr>
		<td><label id="city">  CITY:  </label></td>
	</tr> 
	</table>
	
	</div></div>
    

	
      
		<div class="ItemsDetails" id="ItemsDetails">
		    <table >
                <tr>
                    <th>QUANTITY</th>
				    <th>RATE</th>
				</tr>
			<tbody id="items_table_body"></tbody>
		    </table>
		
        </br>
		
            <table>
                <tr>
			        <td  width="20%"><label id="totalquantitylabel">0</label></td>
			        <td width="20%"></td>
			        <td  width="20%"><label id="totalamountlabel"> ₹0</label></td>
			    </tr>
			</table>
		
		            <div class="ItemsDetails2" id="ItemsDetailsInput">
		                <table>

		                    <tr>
                                <td><center><input type="text" id="qty" name="qty" ></center></td>
                                <td><center><button type="button" class="addItem">Add</button></center></td>
                            </tr>
		                </table>

		</br>
		


		</div>
				
		</div>
		
		
		
		
		
		<div class="TaxDetail" id="TaxDetail">
		

            <table  class="TransportDetail" id="TransportDetail" >		
	            

	            <tr><td>Transport Name :</td></tr>
                <tr><td><input type="text" id="transportname" name="transportname" ></td></tr>
                <tr><td>Transport Parcels :</td></tr>
		        <tr><td><input type="number" id="transportparcels" name="transportparcels" ></td></tr>
		
            </table>	
		
		
		
		
		
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



.addItem {
  background-color: #c2fbd7;
  border-radius: 100px;
  box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,rgba(44, 187, 99, .15) 0 1px 2px,rgba(44, 187, 99, .15) 0 2px 4px,rgba(44, 187, 99, .15) 0 4px 8px,rgba(44, 187, 99, .15) 0 8px 16px,rgba(44, 187, 99, .15) 0 16px 32px;
  color: green;
  cursor: pointer;
  display: inline-block;
  font-family: CerebriSans-Regular,-apple-system,system-ui,Roboto,sans-serif;
  padding: 7px 20px;
  text-align: center;
  text-decoration: none;
  transition: all 250ms;
  border: 0;
  font-size: 16px;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.addItem:hover {
  box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,rgba(44,187,99,.25) 0 1px 2px,rgba(44,187,99,.25) 0 2px 4px,rgba(44,187,99,.25) 0 4px 8px,rgba(44,187,99,.25) 0 8px 16px,rgba(44,187,99,.25) 0 16px 32px;
  transform: scale(1.05) rotate(-1deg);
}


.generateBill {
  background-image: linear-gradient(-180deg, #37AEE2 0%, #1E96C8 100%);
  border-radius: .5rem;
  box-sizing: border-box;
  color: #FFFFFF;
  display: flex;
  font-size: 16px;
  justify-content: center;
  padding: 1rem 1.75rem;
  text-decoration: none;
  width: 100%;
  border: 0;
  cursor: pointer;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.generateBill:hover {
  background-image: linear-gradient(-180deg, #1D95C9 0%, #17759C 100%);
}

@media (min-width: 768px) {
  .generateBill {
    padding: 1rem 2rem;
  }
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

.cashdetail {
  max-width: 1100px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}


input[type="password"],
input[type="date"],
input[type="datetime"],
input[type="email"],

input[type="search"],
input[type="tel"],
input[type="time"],
input[type="url"],
textarea,
select {
  background: rgba(255,255,255,0.1);
  border: none;
  font-size: 16px;
  height: auto;
  margin: 0;
  outline: 0;
  padding: 15px;
  width: 100%;
  background-color: #F9F9F9 ;
  color: #8a97a0;
  box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
  margin-bottom: 30px;
}

.buyerdetailst {
  max-width: 450px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}
.transportdetail {
  max-width: 450px;
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


.buttonFile {
  
  color: #FFF;
  background-color: #4bc970;
  font-size: 18px;
  text-align: center;
  font-style: normal;
  border-radius: 5px;
  width: 50%;
  border: 1px solid #3ac162;
  border-width: 1px 1px 3px;
  box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
  margin-bottom: 10px;
}

.showButton {
  
  
  width: 10%;
  
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
    text-align: center;
    padding: 4px;
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




