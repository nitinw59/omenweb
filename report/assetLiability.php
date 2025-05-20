
<html>

<?php
	
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	
	
	
	
	
	
	?>
	
	
	
	

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Asset-Liability</title>
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
	

		$(document).ready(function() {
			
				
				$("#showPayments").click(function(){
					
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					var total_asset=0;
					var total_liability=0;
					
					
					$.ajax({
                        type:"post",
                        url:"assetLiabilityAction.php",
                        data:"from_date="+from_date+"&to_date="+to_date+"&action=getAssets",
                        success:function(data){
							          
						              $("#asset").show();
						              var debitor_list = JSON.parse(data);
						              $.each(debitor_list, function( index, debitor ) {
						              	var markup= "<tr><td><center>"+debitor["COMPANY_NAME"]+"</center></td><td><center>"+debitor["TOTALAMOUNT"]+"</center></td><td><center>"+debitor["TOTALPAYMENT"]+"</center></td><td><center>"+(parseInt(debitor["TOTALAMOUNT"])-parseInt(debitor["TOTALPAYMENT"]))+"</center></td></tr>"
						              	$("#asset_tbl").append(markup);
                            total_asset+=(parseInt(debitor["TOTALAMOUNT"])-parseInt(debitor["TOTALPAYMENT"]));
						              });
                          $("#DEBITOR_LB").html(total_asset);
                          $("#NET_LB").html(total_asset-total_liability);

                               
                        }
                  });

                  $.ajax({
                        type:"post",
                        url:"assetLiabilityAction.php",
                        data:"from_date="+from_date+"&to_date="+to_date+"&action=getLiability",
                        success:function(data){
							            
						                $("#liability").show();
						                var creditor_list = JSON.parse(data);
						                    $.each(creditor_list, function( index, creditor ) {
							                      var markup= "<tr><td><center>"+creditor["COMPANY_NAME"]+"</center></td><td><center>"+creditor["TOTALAMOUNT"]+"</center></td><td><center>"+creditor["TOTALPAYMENT"]+"</center></td><td><center>"+(parseInt(creditor["TOTALAMOUNT"])-parseInt(creditor["TOTALPAYMENT"]))+"</center></td></tr>"
						              	        $("#liability_tbl").append(markup);
                                    total_liability+=(parseInt(creditor["TOTALAMOUNT"])-parseInt(creditor["TOTALPAYMENT"]));

						                    });
                           
                            $("#CREDITOR_LB").html(total_liability);
                            $("#NET_LB").html(total_asset-total_liability);
                           }
                        });
                       
					
				});
				$("#showAllPayments").click(function(){
					window.open("listAllCredits.php");
													
					
				});
				$('#payments_tbl').on('click', '.delete', function(){
				if (confirm("You really want to delete? ask nitin!")){
					var credits_id=$(this).val();
					
						$.ajax({
                        type:"post",
                        url:"creditsAction.php",
                        data:"credits_id="+credits_id+"&action=deletePayment",
                        success:function(data){
							
							try{
								
							if(Number(data)==1){
							alert ("Deleted Successfuly.");
							
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
				
				
				
				$('#payments_tbl').on('click', '.print', function(){
				
					var company_name=$("#buyername").val();
					var from_date=$("#from_date").val();
					var to_date=$("#to_date").val();
					
					
				
				window.open("printListPayment.php?"+"company_name="+company_name+"&from_date="+from_date+"&to_date="+to_date);
				
				});
				
				
				
				
				
			});
	</script>
	
	
	</head>
	
	

    <body>
	
	<?php
    
		include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	
		$current_date=date('2017-07-01', time());
		$current_due_date=date('Y-m-t', time());
	
	?>
    
	<div class="buyerdetailst" id="buyerdetailst">
	<center><h3>Asset/Liability</h3></center>
	
	        <table>
	          <tr>
	
	                  <td>
	                  From Date: <input type="date" id="from_date" value="<?=$current_date?>" >
	                  </td>
	                  <td>
	                  To Date: <input type="date" id="to_date" value="<?=$current_due_date?>">
	                  </td>
                    <td >
	                  <button id="showPayments">Show</button>
                    </td>
                    <td>
                                                 <table>
					                                                <tr>
					                                                	<td>DEBITOR(ASSET)</td>
					                                                  <td><span class='label1 success' id='DEBITOR_LB'>0</span></td>
					                                                </tr>
				                                                 	<tr>
					                                                	<td>CREDITOR(LIABILITY)</td>
					                                                	<td><span class='label1 DANGER' id='CREDITOR_LB'>0</span></td>
					                                                </tr>
					                                                <tr>
						                                                <td>NET</td>
					                                                  <td><span class='label1 info' id='NET_LB'>0</span></td>
					                                                </tr>
					                                                
				                                          </table>
                    </td>
	          </tr>
	        </table>
	</div>

  <table>
    <tr>
	  <td>
      <div class="asset" id="asset" style="display:none;">
	          <table id="asset_tbl">
	          <tr><th>COMPANY NAME </th><th>TOTAL AMOUNT </th><th>TOTAL PAYMENTS </th><th>BALANCE </th></tr>
	         </table>
	    </div>	
    </td>
    <td>
      <div class="liability" id="liability" style="display:none;">
            <table id="liability_tbl">
	          <tr><th>COMPANY NAME </th><th>TOTAL AMOUNT </th><th>TOTAL PAYMENTS </th><th>BALANCE </th></tr>
	          </table>
	
	    </div>	
    </td>
    </tr>
    </table>
	
	
	<script src="/<?=$omenNX?>/js/pushy.min.js"></script>
	
	
    </body>
	
	<style>
	

  
.label1 {
  color: white;
  padding: 8px;
  font-family: Arial;
}
.success {background-color: #04AA6D;} /* Green */
.info {background-color: #2196F3;} /* Blue */
.warning {background-color: #02c2f7;} /* Orange */
.danger {background-color: #f44336;} /* Red */ 
.other {background-color: #e7e7e7; color: black;} /* Gray */ 


.asset {
  
  max-width: 800px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #88bf7e;
  border-radius: 8px;
  
}
.liability {
  
  max-width: 800px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #fa646b;
  border-radius: 8px;
}

.buyerdetailst {
  max-width: 1200px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #e8e8df;
  border-radius: 8px;
}

table {
    border-collapse: collapse;
    width: 100%;
}

td {
    text-align: left;
    padding: 8px;
    color: white;
    

}

th {
    background-color: #4CAF50;
    color: white;
}

	</style>
</html>

