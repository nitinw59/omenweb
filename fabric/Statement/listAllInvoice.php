
<html>

<?php
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	
	
	
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
				
			var WEB_DIR = <?php echo json_encode($omenNX); ?>;
			
				
				$("#showBills").click(function(){
					
					
					
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					var total_amount=0;
					
					$.ajax({
                        type:"post",
                        url:"listAllInvoiceAction.php",
                        data:"&from_date="+from_date+"&to_date="+to_date+"&action=listStatement",
                        success:function(data){
							
						
						$("#listBills").show();
						
						
						var bills_list = JSON.parse(data);
						var i=0;
						$.each(bills_list, function( index, bill ) {
							
							
							
							var markup= "<tr id='"+bill["BILL_NO"]+"'><td><center><a href='/data/"+WEB_DIR+"/fabric/bills/"+bill["LOC"]+"'>"+bill["BILL_NO"]+"</center></td><td><center>"+bill["DATE"]+"</center></td><td><center>"+bill["COMPANY_NAME"]+"</center></td><td><center>"+bill["AMOUNT"]+"</center></td><td><center><button class='delete' value='"+bill["BILL_NO"]+"'>delete</button></center></td></tr>";
							
							total_amount+=Number(bill["AMOUNT"]);
							$("#bills_tbl").append(markup);
							
							
						});
						
						
						 markup= "<tr><td ><center><button class='print'>Print</button></center></td><td colspan='3'>TOTAL PURCHASE: "+total_amount+"</td><td ></td></tr>"
						$("#bills_tbl").append(markup);
						
										
                        }
                    });
					
				});
				
				
				
				
				$('#bills_tbl').on('click', '.print', function(){
				
					var company_name="";
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					
					
				
				window.open("printAllInvoice.php?"+"company_name="+company_name+"&from_date="+from_date+"&to_date="+to_date);
				
				});
				
				
				
				
				$('#bills_tbl').on('click', '.delete', function(){
					
				if (confirm("You really want to delete? ask nitin!")){
					var bill_no=$(this).val();
					
						$.ajax({
                        type:"post",
                        url:"listAllInvoiceAction.php",
                        data:"bill_no="+bill_no+"&action=deleteBill",
                        success:function(data){

							
							try{
								
							if(Number(data)>-1){
                $("#"+bill_no).remove();
							alert ("Deleted Successfuly...");
							
							}else{
								
							alert("Failed To Delete");
							}
							}catch(e){
							$("#item_availability").html("call nitin: Item not available");
							
							}
										
                        }
                    });
					
				
				}
				
				
				
				});
				
				
			});
	</script>
	
	
	</head>
	
	

    <body>
	
	<?php 
	
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	$current_date=date("Y-m-01", strtotime("first day of previous month"));
	
	$current_due_date=date("Y-m-t", strtotime("last day of previous month"));
	
	?>
    
	<div class="buyerdetailst" id="buyerdetailst">
	
	<center><h3>Supplier Monthly Bills</h3></center>
	
	
	From Date: <input type="date" id="from_date" value="<?=$current_date?>" >
	To Date: <input type="date" id="to_date" value="<?=$current_due_date?>">
	
	<button id="showBills" class="showBills">Show</button>
	
	</div>
    <div class="listBills" id="listBills" style="display:none;">
	
	<table id="bills_tbl">
	<tr><th>BILL ID </th><th width="15%">DATE </th><th>COMPANY NAME</th><th>AMOUNT</th><th>action</th></tr>
	
	</table>
	
	</div>	
	
	
	
	
			<script src="/<?=$omenNX?>/js/pushy.min.js"></script>
			
    </body>
	

<style>
	*, *:before, *:after {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}

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
  max-width: 1050px;
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
  max-width: 1050px;
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


@media screen and (min-width: 1080px) {

  form {
    max-width: 1080px;
  }

}

	</style>
	
	
	
</html>

