
<html>

<?php
	
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
  
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
?>
	
	
	
	
	

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>List Invoice</title>
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
				
            $.ajax({
                        type:"post",
                        url:"listInvoiceAction.php",
                        data:"action=listInvoice",
                        success:function(data){
						
						                    $("#listBills").show();
						
						
					            	        var bills_list = JSON.parse(data);
						                    var i=0;
						                    $.each(bills_list, function( index, bill ) {
							
							                      if(bill["BILL AMOUNT"]==null)
								                        bill["BILL AMOUNT"]=0;
							                      if(bill["PAYMENT AMOUNT"]==null)
								                        bill["PAYMENT AMOUNT"]="0";
							
                                 var markup= "<tr><td><center><a href='/"+WEB_DIR+"/invoice/showInvoicesecond.php?bill_id="+bill["BILL ID"]+"'>"+bill["BILL ID"]+"</a></center></td><td><center>"+bill["DATE"]+"</center></td><td><center>"+bill["COMPANY_NAME"]+"</center></td><td><center>"+bill["ITEM_QUANTITY"]+"</center></td><td><center>"+bill["ITEM_RATE"]+"</center></td><td><center>"+bill["BILL AMOUNT"]+"</center></td><td><center><a href='/data/"+WEB_DIR+"/invoice/LR/"+bill["LR_LOC"]+"'>"+bill["LR"]+"-"+bill["transport_parcels"]+"</a></center></td><td><center><button type='submit' class='updateInvoice' value='"+bill["BILL ID"]+"'>UPDATE/VIEW</button><center></td><td><center><button type='submit' class='sendInvoice' value='"+bill["BILL ID"]+"'>SEND</button><center></td>";
							
							                   $("#bills_tbl").append(markup);
							
							                   });
                            
						             }
						
						
						
						
          });
						
                
                   
                
                    $('#bills_tbl').on('click', '.updateInvoice', function(){
                            
		                      	window.location.replace("updateInvoice.php?bill_id="+$(this).val());
		                });
                    
                      
                    $('#bills_tbl').on('click', '.sendInvoice', function(){
                                
                              $.ajax({
                        							type:"post",
                       								url:"sendInvoice.php",
                        							data:"bill_id="+$(this).val(),
                        							success:function(data){
																               alert(data);
							
                       								}
                    							});

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
	

    
	<?php 
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	
	$current_date=date('Y-m-01', time());
	$current_due_date=date('Y-m-d', time());
	?>
    <body>
	
	
    <div class="listBills" id="listBills">
	<table id="bills_tbl">
	<tr><th>BILL ID </th><th> ******DATE****** </th><th>COMPANY NAME </th><th>**BILL ITEMS**</th><th>**RATE**</th><th>BILL_AMOUNT </th><th>LR </th<th>PARCELS</th><th>UPDATE</th><th>SEND</th></tr>
	
	</table>
	
	</div>	
	
	
	
	
			<script src="/<?=$omenNX?>/js/pushy.min.js"></script>
			
    </body>

    <style>*, *:before, *:after {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}



.sendInvoice {
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

.sendInvoice:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}


.updateInvoice {
  background: #FFFF82;
  background-image: -webkit-linear-gradient(top, #FFFF82, #FFFF82);
  background-image: -moz-linear-gradient(top, #FFFF82, #FFFF82);
  background-image: -ms-linear-gradient(top, #FFFF82, #FFFF82);
  background-image: -o-linear-gradient(top, #FFFF82, #FFFF82);
  background-image: linear-gradient(to bottom, #FFFF82, #FFFF82);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #000000;
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.updateInvoice:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;

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
  max-width: 1200px;
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
  max-width: 1200px;
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
    overflow: auto;
}

tbody{
 1` Q
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

