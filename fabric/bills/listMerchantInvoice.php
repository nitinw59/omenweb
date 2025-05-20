
<html>

<?php
	
	
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");

	
	$sql = "SELECT COMPANY_NAME FROM FABRIC_MERCHANTS_TBL";
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
        <title>Merchant Statement</title>
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="stylesheet" href="/<?=$omenNX?>/css/normalize.css">
        <link rel="stylesheet" href="/<?=$omenNX?>/css/demo.css">
        <!-- Pushy CSS -->
        <link rel="stylesheet" href="/<?=$omenNX?>/css/pushy.css">
        
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        
	<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
		
	<script type="text/javascript">
	

		$(document).ready(function() {
				
			var buyernameArray = <?php echo json_encode($customercompanynames); ?>;
				$("#buyername").select2({
				  data: buyernameArray
				});
				
				$("#showBills").click(function(){
					
					
					var company_name=$("#buyername").val();
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					var total_amount=0;
					var total_payment=0;
					
					$.ajax({
                        type:"post",
                        url:"listMerchantInvoiceAction.php",
                        data:"company_name="+company_name+"&from_date="+from_date+"&to_date="+to_date+"&action=listMerchantInvoice",
                        success:function(data){
						
						
						$("#listBills").show();
						var bills_list = JSON.parse(data);
						$.each(bills_list, function( index, bill ) {
							if(bill["payment_description"]==null)
								bill["payment_description"]="";
							if(bill["payment_amount"]==null)
								bill["payment_amount"]=0;
							
							var markup= "<tr>"
										+"<td><center>"+bill["DATE"]+"</center></td>"
										+"<td><center>"+bill["meter"]+"</center></td>"
										+"<td><center>"+bill["meterRate"]+"</center></td>"
										+"<td><center>"+bill["AMOUNT"]+"</center></td>"
										+"<td><center><a href='"+bill["loc"]+"'>invoice</a></center></td>"
										+"<td><center><input type='date' value='"+bill["payment_date"]+"' id='pd"+bill["BILL_ID"]+"'  ></center></td>"
										+"<td><center><input type='number' value='"+bill["payment_amount"]+"' id='pamount"+bill["BILL_ID"]+"'/></center></td>"
										+"<td><center><input type='text' value='"+bill["payment_description"]+"' id='pdesc"+bill["BILL_ID"]+"'/></center></td>"
										+"<td><center><button class='makepayment' value='"+bill["BILL_ID"]+"'>Make Payment</button></center></td></form>";
							
							total_amount=total_amount+Number(bill["AMOUNT"]);
							total_payment=total_payment+Number(bill["payment_amount"]);
							
							$("#bills_tbl").append(markup);
						});
						
						
						$("#balance_label").attr("value",(total_amount-total_payment));
						$("#balance_label").html(total_amount-total_payment);
							
										
                        }
                    });
					
				});
				
				
				
				
				
				
				
				$('#bills_tbl').on('click', '.print', function(){
				
					var company_name=$("#buyername").val();
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					
					
				
				window.open("printListCustomerInvoice.php?"+"company_name="+company_name+"&from_date="+from_date+"&to_date="+to_date);
				
				});
				
				
				
				$('#bills_tbl').on('click', '.payment', function(){
				
				
				
				});
				
				$('#bills_tbl').on('click', '.makepayment', function(){
				if(confirm("confirm Payment? ask nitin!")){
				//window.open("showInvoice.php?bill_id="+$(this).val(),"_blank");
				
				
				
				
				
				
				var fd = new FormData();
					
				var update="updateSupplierBill";
					
					fd.append('bill_id',$(this).val());
					
					fd.append('payment_date',$("#pd"+$(this).val()).val());

					fd.append('payment_amount',$("#pamount"+$(this).val()).val());

					fd.append('payment_description',$("#pdesc"+$(this).val()).val());

					fd.append('action',update);

					
					
					$.ajax({
                        type:"post",
                        url:"listMerchantInvoiceAction.php",
                        data:fd,
						contentType: false,
						processData: false,
						success:function(data){
						
						if(Number(data)==1){
							
							alert("Update Made Successfuly.");
							
						}else{
							alert("Update Failed. Call 8087978196");
						}
										
                        }
                    });
				
				
				
				
				
				
				
				
				
				
				}
				
				
				
				});
				
				
				$('#bills_tbl').on('click', '.viewpayment', function(){
				
				
				window.open("../payments/listInvoicePayment.php?bill_id="+$(this).val(),"_blank");
				
				});
				
				
				
				
			});
	</script>
	
	
	</head>
	
	

    <body>
	
	<?php 
	
include($_SERVER['DOCUMENT_ROOT']."$server_root/index.php");
	$current_date=date('Y-m-01', time());
	$current_due_date=date('Y-m-t', time());
	?>
    
	<div class="buyerdetailst" id="buyerdetailst">
	
	
	<select id="buyername" style="width:300px;">
			<!-- Dropdown List Option -->
	</select>
	
	From Date: <input type="date" id="from_date" value="<?=$current_date?>">
	To Date: <input type="date" id="to_date" value="<?=$current_due_date?>">
	
	<button id="showBills" class="showBills">Show</button>
	<label class="danger">BALANCE: </label><label id="balance_label" value="0">Balance:0</label>
		
	</div>
    <div class="listBills" id="listBills" style="display:none;">
	
	<table id="bills_tbl">
	<tr><th>*****DATE***** </th><th>METER</th><th>Rate </th><th>AMOUNT</th><th>Bill_Loc</th><th>Payment Date </th><th>Payment Amount </th><th>Payment Description </th><th>Make Payment</th></tr>
	
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


.danger {background-color: #f44336;
	width: 95px;
	color: WHITE;
	} /* Red */

.showBills {
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
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.showBills:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}

.viewpayment {
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
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.viewpayment:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}


.viewbill {
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
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.viewbill:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}

body {
  font-family: 'Nunito', sans-serif;
  color: #384047;
}


.listBills {
  max-width:  90%;
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


.buyerdetailst {
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
#invoicelist {
    width: 100%;
	height: 100%;
    overflow: scroll;
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

