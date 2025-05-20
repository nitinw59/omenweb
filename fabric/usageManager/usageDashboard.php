
<html>

<?php
	
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
	$sql = "SELECT FABRIC_MERCHANTS_ID,COMPANY_NAME FROM FABRIC_MERCHANTS_TBL";
	$suppliercompanynames = array();
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
		$suppliercompanynames[$count]=["id"=>-1,"text"=>"All"];
		$count++;
		while($row = mysqli_fetch_array($result)) {
		$suppliercompanynames[$count] = ["id"=>$row['FABRIC_MERCHANTS_ID'],"text"=>$row['COMPANY_NAME']];
		$count++;
		}
		
		
	}
	
	

    
  $sql = "SELECT JOBWORKER_ID,NAME FROM JOBWORKER_TBL";
  $jobberNameList = array();
  if($result = mysqli_query($dbhandle,$sql) ){
      $count=0; 
      while($row = mysqli_fetch_array($result)) {
      $jobberNameList[$count] = ["id"=>$row['JOBWORKER_ID'],"text"=>$row['NAME']];
      $count++;
      }
      
      
  }
	
	?>
	
	
	
	
	
	

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
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
				
			var suppliercompanynames = <?php echo json_encode($suppliercompanynames); ?>;
			var webdir = <?php echo json_encode($omenNX); ?>;

				$("#supplierListDrop").select2({
				  data: suppliercompanynames
				});


			var jobberNameList = <?php echo json_encode($jobberNameList); ?>;
				$("#jobberListDrop").select2({
				  data: jobberNameList
			});




				$("#addJobberBillButton").click(function(){
					
					window.open("addJobberBill.php");
													
					
				});


				$("#addPackerBillButton").click(function(){
					
					window.open("addPackerBill.php");
													
					
				});
				
				$("#addDamageBillButton").click(function(){
					
					window.open("addDamageBill.php");
													
					
				});
				
				$("#showBills").click(function(){
					
					var supplierID=$("#supplierListDrop").val();
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					
					
					$.ajax({
                        type:"post",
                        url:"usageDashboardAction.php",
                        data:"supplierID="+supplierID+"&from_date="+from_date+"&to_date="+to_date+"&action=listSupplierStatement",
                        success:function(data){
						
						var counter=0;
						$("#listBills").show();
						
						var bills_list = JSON.parse(data);
						alert(data);
						var i=0;
						$.each(bills_list, function( index, bill ) {
							
							markup="";
							markup= "<tr>"
									+"<td><center><a href='/data/"+webdir+"/fabric/bills/"+bill["loc"]+"'>"+bill["id"]+"</a></center></td>"
									+"<td><center>"+bill["meters"]+"</center></td>";
							
							


							
							
							if(bill["jobber_bill_list"]!=null){
							
							if(bill["jobber_bill_list"].length>3)			
							markup+= "<td><div style='height:100px; overflow-y:scroll; display:none;';  class='jobber_bill_list'; id='jobber_bill_list-"+bill["id"]+"'> ";
							else
							markup+= "<td><div style='height:100px; overflow:hidden;  display:none;'; class='jobber_bill_list'; id='jobber_bill_list-"+bill["id"]+"'>";

							markup+= "<table>";
							
							
							
							$.each(bill["jobber_bill_list"], function( index, jobber_bill ) {
								//alert(jobber_bill);
								markup+="<tr>";
								markup+="<td><a href='/data/"+webdir+"/fabric/usageManager/Jobber/"+jobber_bill['LOC']+"'>"+jobber_bill['id']+"</a></td><td>"+jobber_bill['used_meters']+"</td>";
								markup+="</tr>";

							
							});	
								markup+= "</table></div><button value='jobber_bill_list-"+bill['id']+"' class='bt_jobber_bill_list' >"+bill['jobber_total_used_meters']+"</button></td></tr>";
							}else{
								markup+= "<td><div style='height:100px; overflow:hidden; display:none;';  class='jobber_bill_list'; id='jobber_bill_list-"+bill["id"]+"'></div><button value='jobber_bill_list-"+bill['id']+"' class='bt_jobber_bill_list' >"+bill['jobber_total_used_meters']+"</button></td></tr>";
							}
							



							//alert(markup);
							$("#bills_tbl").append(markup);
							
							
						});
						
						
						
										
                        }
                    });
					
				});
				

				$('#bills_tbl').on('click', '.bt_jobber_bill_list', function(){
					alert($(this).val());

					$("#"+$(this).val()).show();
					//alert ($("#"+$(this).val()).is(":visible"));
						
													
					
				});
				
				$('#bills_tbl').on('keyup', '.payment_enter', function(event){
					//alert("event");
					
					if(event.keyCode==13){
                     	//alert("eneterkeyup"+$(this).attr("id"));
                     	//alert("eneterkeyup"+$(this).val());
						var company_name=$("#buyername").val();
						var paymentamount=$(this).val();
						var billId=$(this).attr("id");
						
						var creditsAvailable=0;
						
						
									$.ajax({
										async: false,
										type:"post",
										url:"SupplierStatementAction.php",
										data:"company_name="+company_name+"&action=getSupplierCredits",
										success:function(data){
										creditsAvailable=Number(data);	
										},
										error: function (jqXHR, exception) {
											alert(jqXHR);
										}
									});
						
						if(creditsAvailable>=paymentamount){
						
									$.ajax({
										async: false,
										type:"post",
										url:"SupplierStatementAction.php",
										data:"billId="+billId+"&paymentamount="+paymentamount+"&creditsAvailable="+creditsAvailable+"&action=makePayment",
										success:function(data){
										
										if(data==1)
											alert("Bill Paid.");
											//$("#availableCredits").html(creditsAvailable-paymentamount);
										},
										error: function (jqXHR, exception) {
											alert(jqXHR);
										}
									});
						
						
						
								$.ajax({
										async: false,
										type:"post",
										url:"SupplierStatementAction.php",
										data:"company_name="+company_name+"&action=getSupplierCredits",
										success:function(data){
										creditsAvailable=Number(data);
										alert(creditsAvailable);			
										$("#availableCredits").html(creditsAvailable);
										$(this).css('background-color', 'blue');
										},
										error: function (jqXHR, exception) {
											alert(jqXHR);
										}
									});
						
						
						}else{
							alert("Insufficient Credits. ("+creditsAvailable+")");
						}


					
					}
					
				//alert(creditsAvailable);	
					
				});
				
				
				$('#bills_tbl').on('click', '.print', function(){
				
					var company_name=$("#buyername").val();
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					
					
				
				window.open("printSupplierStatement.php?"+"company_name="+company_name+"&from_date="+from_date+"&to_date="+to_date);
				
				});
				
				
				
				$('#bills_tbl').on('click', '.viewbill', function(){
				
				
				window.open("showInvoice.php?bill_id="+$(this).val(),"_blank");
				
				});
				
				
				$('#bills_tbl').on('click', '.viewpayment', function(){
				
				
				window.open("../payments/listInvoicePayment.php?bill_id="+$(this).val(),"_blank");
				
				});
				
				
				$(document).ajaxStart(function(){
					$("#wait").css("display", "block");
				});
			
				$(document).ajaxComplete(function(){
					$("#wait").css("display", "none");
				});
				
			});
	</script>
	
	
	</head>
	
	<style>*, *:before, *:after {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}



.label1 {
  color: white;
  padding: 8px;
  font-family: Arial;
}
.success {background-color: #04AA6D;} /* Green */
.info {background-color: #2196F3;} /* Blue */
.warning {background-color: #ff9800;} /* Orange */
.danger {background-color: #f44336;} /* Red */ 
.other {background-color: #e7e7e7; color: black;} /* Gray */ 




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

.showAllBills {
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

.showAllBills:hover {
	background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
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

.print{
	
	color: #FFF;
  background-color: #4bc970;
  font-size: 18px;
  text-align: center;
  font-style: normal;
  border-radius: 5px;
  width: 100%;
  border: 1px solid #3ac162;
  border-width: 1px 1px 3px;
  box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
  margin-bottom: 10px;
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
  max-width: 1150px;
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
  max-width: 1150px;
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

.fixed_bills_statement_tbl {
	table-layout: fixed;
	border-collapse: collapse;
    
}

.fixed_bills_statement_tbl tbody{
	display: block;
	width: 100%;
	overflow: auto;
	height: 550px;
}

.fixed_bills_statement_tbl thead tr {
   display: block;
}

.fixed_bills_statement_tbl thead {
  background: black;
  color:#fff;
}

.fixed_bills_statement_tbl th, .fixed_bills_statement_tbl td {
  padding: 5px;
  text-align: left;
  width: 200px;
}


tr:nth-child(even){background-color: #f2f2f2}


@media screen and (min-width: 480px) {

  form {
    max-width: 480px;
  }

}

	</style>

    <body>
	
	<?php 
	
include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	$from_date=date('Y-m-01', time());
	$to_date=date('Y-m-d', time());
	?>
    <div id="wait" style="display:none;width:690px;height:890px;position:absolute;top:30%;left:30%;padding:2px;">
				<img src='reload.gif' width='320px' height='320px'>
	</div>

	<div>

	&nbsp&nbsp&nbsp<button id="addJobberBillButton" class="addJobberBillButton" >Add Jobber Bill </button>
	&nbsp&nbsp&nbsp<button id="addPackerBillButton" class="addPackerBillButton" >Add Packer Bill </button>
	&nbsp&nbsp&nbsp<button id="addDamageBillButton" class="addDamageBillButton" >Add Damage Bill </button>

	<div class="sorterDetails" id="sorterDetails">
	<center><h3>Usage Dashboard</h3></center>
	
	
	<select id="supplierListDrop" style="width:300px;">
			<!-- Dropdown List Option -->
	</select>

	
	<select id="jobberListDrop" style="width:300px;">
			<!-- Dropdown List Option -->
	</select>
	
	From Date: <input type="date" id="from_date" value="<?=$from_date?>" >
	To Date: <input type="date" id="to_date" value="<?=$to_date?>">
	
	&nbsp&nbsp&nbsp<button id="showBills" class="showBills" >Statement</button>
	
	</div>
	</div>


    <div class="listBills" id="listBills" style="display:none;">
	
	<table id="bills_tbl" class="fixed_bills_statement_tbl">
		<thead>
			<tr><th>BILL ID </th><th>**ITEMS METERS**</th><th>**DAMAGE METERS**</th><th>JOBBER USED</th><th>PACKER RECEIVED </th><th>****PENDING METER**** </th></tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	
	</div>	
	
	
	
	
			<script src="/<?=$omenNX?>/js/pushy.min.js"></script>
			
    </body>


</html>

