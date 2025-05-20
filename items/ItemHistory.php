
<html>

<?php
	
$server_root="/omenwebNX";

include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");

	$sql = "SELECT ITEMS_ID FROM ITEMS_tbl";
	$item_nos = array();
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
		$item_nos[$count]="select";
		$count++;
		while($row = mysqli_fetch_array($result)) {
		$item_nos[$count] = $row['ITEMS_ID'];
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
        
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        
	<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
		
	<script type="text/javascript">
	

		$(document).ready(function() {
				
			var buyernameArray = <?php echo json_encode($item_nos); ?>;
				$("#items_list").select2({
				  data: buyernameArray
				});
				
				$("#showBills").click(function(){
					
					
					var item_no=$("#items_list").val();
					
					
					$.ajax({
                        type:"post",
                        url:"ItemHistoryAction.php",
                        data:"item_no="+item_no+"&action=listItemHistory",
                        success:function(data){
						
						$("#listBills").show();
						var bills_list = JSON.parse(data);
						$.each(bills_list, function( index, value ) {
							
							
							var markup= "<tr><td><center>"+value+"</center></td></tr>";
							
							
							
							$("#bills_tbl").append(markup);
						});
						
										
                        }
                    });
					
				});
				
				
				
				
				$('#bills_tbl').on('click', '.print', function(){
				
					var company_name=$("#buyername").val();
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					
					
				
				window.open("printListCustomerInvoice.php?"+"company_name="+company_name+"&from_date="+from_date+"&to_date="+to_date);
				
				});
				
				
				
				$('#bills_tbl').on('click', '.viewbill', function(){
				
				
				window.open("showInvoice.php?bill_id="+$(this).val(),"_blank");
				
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
	
	?>
    
	<div class="buyerdetailst" id="buyerdetailst">
	
	
	<select id="items_list" style="width:300px;">
			<!-- Dropdown List Option -->
	</select>
	
	
	<button id="showBills" class="showBills">Show</button>
	
	</div>
    <div class="listBills" id="listBills" style="display:none;">
	
	<table id="bills_tbl">
	<tr><th>BILL ID </th></tr>
	
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
  max-width: 850px;
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

