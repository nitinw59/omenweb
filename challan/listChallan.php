
<html>

<?php
	
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
  
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
?>
	
	
	
	
	

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>List Challan</title>
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
		<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet" />
		<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
		
	<script type="text/javascript">
	

		$(document).ready(function() {
				
            

			var WEB_DIR = <?php echo json_encode($omenNX); ?>;
				
                     $.ajax({
                        type:"post",
                        url:"listChallanAction.php",
                        data:"action=listChallan",
                        success:function(data){
                          
						                    $("#listChallan").show();
						
                              

					            	        var challan_list = JSON.parse(data);
						                    var i=0;
						                    $.each(challan_list, function( index, challan ) {
                                    
							                      if(challan["itemQuantity"]==null)
								                        challan["itemQuantity"]=0;
                                        if(challan["bookingDate"]==null)
								                        challan["bookingDate"]="-";
                                        if(challan["LR_LOC"]==null)
								                        challan["LR_LOC"]="-";
                                        if(challan["LR"]==null)
								                        challan["LR"]="-";
                                        if(challan["transportParcels"]==null)
								                        challan["transportParcels"]="-";
                                        if(challan["transportName"]==null)
								                        challan["transportName"]="-";
                                        if(challan["BILL_LOC"]==null)
								                        challan["BILL_LOC"]="-1";
                                        if(challan["BILL_ID"]==null)
								                        challan["BILL_ID"]="-";
							                      
                                 var markup= "<tr><td><center>"+challan["challanNo"]+"</center></td>"
                                            +"<td><center>"+challan["DATE"]+"</center></td>"
                                            +"<td><center>"+challan["COMPANY_NAME"]+"</center></td>"
                                            +"<td><center>"+challan["itemQuantity"]+"</center></td>"
                                            +"<td><center><button type='submit' class='printChallan' value='"+challan["challanNo"]+"'>PRINT</button><center></td>"
                                            +"<td><center>"+challan["bookingDate"]+"</center></td>"
                                            +"<td><center><a href='/data/"+WEB_DIR+"/invoice/LR/"+challan["LR_LOC"]+"'>"+challan["LR"]+"-"+challan["transportParcels"]+"-"+challan["transportName"]+"</a></center></td>"
                                            +"<td><center><a href='../invoice/showInvoicesecond.php?bill_id="+challan["BILL_ID"]+"'>"+challan["BILL_ID"]+"</a></center></td>"
                                            +"<td><center><button type='submit' class='updateChallan' value='"+challan["challanNo"]+"'>UPDATE</button><center></td>";
                                            
                                            if(challan["BILL_ID"]!="-")
                                            markup += "<td><center><button type='submit' class='generateBill' value='-1'>GenerateBill</button><center></td>";
                                            else
                                            markup += "<td><center><button type='submit' class='generateBill' value='"+challan["challanNo"]+"'>GenerateBill</button><center></td>";

                                            //if(challan["send"])
                                           // markup +="<td><center><button type='submit' class='sendChallan' value='"+challan["challanNo"]+"'>ReSEND</button><center></td>";
                                            //else
                                            //markup +="<td><center><button type='submit' class='sendChallan' value='"+challan["challanNo"]+"'>SEND</button><center></td>";
                                     
							                   $("#challan_tbl").append(markup);
							
							                   });
                            
						             }
						
						
						
						
          });
						
                      $('#challan_tbl').on('click', '.updateChallan', function(){
                             
                        window.location.replace("updateChallan.php?challanNo="+$(this).val());					
                              
                      
                      
                      
                      
                      
                            });
                   
                      $('#challan_tbl').on('click', '.printChallan', function(){
                        var challanNo=$(this).val();
                        var filepath="";
                        $.ajax({
                              type:"post",
                              url:"generateChallanPDF.php",
                              data:"challanNo="+challanNo,
                              async: false,
                              success:function(data){
                                
                              }
                      });
                            //alert('data/'+WEB_DIR+'/challan/pdf/'+challanNo+'.pdf');
                            //var path='data/'+WEB_DIR+'/challan/pdf/'+challanNo+'.pdf';
                            //window.open("https://www.darkcarbon.in/"+path,"_blank");

                            printJS('data/'+WEB_DIR+'/challan/PDF/'+challanNo+'.pdf');
                      });
                            
                    
                      $('#challan_tbl').on('click', '.generateBill', function(){
                        if($(this).val()==-1)
                        alert("Already Bill Generated. please update in invoice.");
                        else
                        window.location.replace("../invoice/newInvoice.php?challanNo="+$(this).val());					

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
	
	<h1>CHALLAN LIST</h1>
    <div class="listChallan" id="listChallan">
	<table id="challan_tbl">
	<tr><th>CHALLAN ID </th><th> ******DATE****** </th><th>COMPANY NAME </th><th>**QUANTITY**</th><th>**PRINT**</th><th>**BOOKING DATE**</th><th>TRANSPORT--LR--PARCELS </th><th>**BILL ID ***</th><th>UPDATE CHALLAN</th><th>GENERATE BILL</th></tr>
	
	</table>
	
	</div>	
	
	
	
	
			<script src="/<?=$omenNX?>/js/pushy.min.js"></script>
			
    </body>

    <style>*, *:before, *:after {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}

/* CSS */
.printChallan {
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

.printChallan:hover {
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


.listChallan {
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
    overflow: auto;
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

