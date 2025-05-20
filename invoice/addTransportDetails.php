
<?php
	
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
	
	
	$sql = "SELECT challan_no FROM challan_tbl";
	$customercompanynames = array();
	if($result = mysqli_query($dbhandle,$sql) ){
		$count=0;
		$customercompanynames[$count]="select";
		$count++;
		while($row = mysqli_fetch_array($result)) {
		$customercompanynames[$count] = $row['challan_no'];
		$count++;
		}
		
		
	}
	
	
	
	?>
	

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Add Transport</title>
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
				
				

				var buyernameArray = <?php echo json_encode($customercompanynames); ?>;
				$("#bill_id_drop").select2({
				  data: buyernameArray
				});
				
				
				
				

				
				
				
				
				
				
				$("#bill_id_drop").change(function(){
					
                     var challanNo=$("#bill_id_drop").val();
						$("#paymentdetail").show();
					
					
					 
                     $.ajax({
                        type:"post",
                        url:"addTransportDetailsSubmit.php",
                        data:"challanNo="+challanNo+"&action=fetchTransportDetails",
                        success:function(data){
							transportDetails= JSON.parse(data);
							$("#LR").val(transportDetails["LR"]);
							$("#LR_PARCELS").val(transportDetails["transport_parcels"]);
							$("#LR_TRANSPORT").val(transportDetails["transport_name"]);
							$("#LR_DATE").val(transportDetails["DATE"]);
							$("#LR_COMPANY_NAME").val(transportDetails["COMPANY_NAME"]);
							
							
							}
                     });
				});

				
				
				
				
				$("#updateTransportDetails").click(function(){
					
					var bookingDateOk=(new Date())>=(new Date($("#LR_DATE").val()));
					var LR=$("#LR").val();
					var LR_DATE=$("#LR_DATE").val();
					var LR_TRANSPORT=$("#LR_TRANSPORT").val();
					var LR_PARCELS=$("#LR_PARCELS").val();
					var update="updateTransportDetails";
					
					if($('#file').get(0).files.length > 0 && bookingDateOk){
					
							if(($('#file')[0].files[0].size)/1000 < 20000){
								var img_file= $('#file')[0].files[0];
								var fd = new FormData();
								fd.append('challanNo',$("#bill_id_drop").val());
								fd.append('LR_DATE',LR_DATE);
								fd.append('LR',LR);
								fd.append('LR_TRANSPORT',LR_TRANSPORT);
								fd.append('LR_PARCELS',LR_PARCELS);
								fd.append('img_file',img_file);
								fd.append('action',update);

					
					
								$.ajax({
									type:"post",
									url:"addTransportDetailsSubmit.php",
									data:fd,
									contentType: false,
									processData: false,
									success:function(data){
											if(Number(data)==1){
												
												window.location.replace("verifyInvoice.php?bill_id="+$("#bill_id_drop").val());					
												
												
											}else{
												alert("Update Failed. Call 8087978196");
											}
										
									}
								});
							}else{alert("file size larger than 2MB");}
				
					}else if(bookingDateOk){
					
						
						var fd = new FormData();
					
						fd.append('challan_no',$("#bill_id_drop").val());
						fd.append('LR_DATE',LR_DATE);
						fd.append('LR',LR);
						fd.append('LR_TRANSPORT',LR_TRANSPORT);
						fd.append('LR_PARCELS',LR_PARCELS);
						fd.append('img_file',"");
						fd.append('action',update);

					
					
					$.ajax({
                        type:"post",
                        url:"addTransportDetailsSubmit.php",
                        data:fd,
						contentType: false,
						processData: false,
						success:function(data){
						if(Number(data)==1){
							
										
							window.location.replace("verifyInvoice.php?bill_id="+$("#bill_id_drop").val());					
					
							
						}else{
							alert("Update Failed. Call 8087978196");
						}
										
                        }
                    });
						
						
						
						
				
				}else
					alert("check Booking Date");
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



    <body>
	
	<?php
    
include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
$current_date=date('Y-m-d', time());
	?>
				<div id="wait" style="display:none;width:690px;height:890px;position:absolute;top:30%;left:30%;padding:2px;">
				<img src='reload.gif' width='220px' height='220px'>
				</div>
				
				
	<div class="buyerdetailst" id="buyerdetailst">
	
	<h3 align='center'>ADD TRANSPORT</h3>
	<select id="bill_id_drop" style="width:300px;">
			<!-- Dropdown List Option -->
	</select>
	
	
	
	
	
	</div>
    
	
		
	<div class="transportdetail" id="paymentdetail" style="display:none;">
	
	COMPANY_NAME<input type="text" id="LR_COMPANY_NAME" name="LR_COMPANY_NAME" >
	LR<input type="number" id="LR" name="LR" >
	DATE<input type="date" id="LR_DATE" value="<?=$current_date?>"  >
	Parcel/s<input type="number" id="LR_PARCELS" name="LR_PARCELS" >
	TRANSPORT NAME <input type="text" id="LR_TRANSPORT" name="LR_TRANSPORT" >
	
	
	
	<div class="container">
    <form method="post" action="" enctype="multipart/form-data" id="myform">
        <div class='preview'>
            <img src="" id="img" width="100" height="100">
        </div>
        <div >
            <input type="file" id="file" name="file" /></br></br></br>
			<input type="button" class="buttonFile" value="Upload" id="updateTransportDetails"> 
			
        </div>
    </form>
	</div>
	
	
	
	
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

input[type="text"],
input[type="password"],
input[type="date"],
input[type="datetime"],
input[type="email"],
input[type="number"],
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




