
<?php
	
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");

    $sql = "SELECT COMPANY_NAME FROM customers_tbl";
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
        
    
     <?php
    $server_root="/omenwebNX";
    
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
            <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
            
        <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
            
            
            
        <script type="text/javascript">
        
            
        
        var today = new Date();
        
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        var today_date_str=yyyy+"-"+mm+"-"+dd+"";
       
        var arc_state=0;
        var arc_balance=0;
	
        
                $(document).ready(function() {
                    
                    var buyernameArray = <?php echo json_encode($customercompanynames); ?>;
                    $("#buyername").select2({
                      data: buyernameArray
                    });
                    
                    
                    
                    $("#arc_checkbox").click(function(){
                        
                        
                        if($(this).is(":checked")){
                          if($("#buyername").val()=="Select Customer"){
                            alert("Please Select Customer Name.");
                            $("#arc_date").hide();
                            $("#arc_name").hide();
                            $("#arc_button").hide();
                            $("#arc_state").hide();
                            $("#arc_balance").hide();
                            $(this).prop('checked', false);
                          }else{
                            $("#arc_date").val(today_date_str);
                          
                            $("#arc_date").show();
                            $("#arc_name").show();
                            $("#arc_button").show();
                            $("#arc_state").show();
                            $("#arc_balance").show();
                            if(arc_state==1)
                            $("#arc_state").html("ARCHIVED");
                            else
                            $("#arc_state").html("NOT ARCHIVED");
                            $("#arc_balance").html("Rs."+arc_balance);

                            $("#arc_name").val("ZARCHIVED_"+$("#buyername").val()+"_"+$("#arc_date").val());
                          }
                        }
                        else{
                          $("#arc_date").hide();
                          $("#arc_name").hide();
                          $("#arc_button").hide();

                        }
                        
                        
                    });
                    

                    $("#arc_complete_checkbox").click(function(){
                        
                        
                        if($(this).is(":checked")){
                          if($("#buyername").val()=="Select Customer"){
                            alert("Please Select Customer Name.");
                            $("#arc_date").hide();
                            $("#arc_name").hide();
                            $("#arc_button").hide();
                            $("#arc_complete_button").hide();
                            $("#restore_button").hide();
                           

                            $(this).prop('checked', false);
                          }else{
                            $("#arc_date").hide();
                            $("#arc_name").hide();
                            $("#arc_button").hide();
                            $("#arc_checkbox").prop('checked', false);
                            $("#restore_checkbox").prop('checked', false);
                         
                            $("#arc_complete_button").show();
                            
                            }
                        }
                        else{
                          $("#arc_date").hide();
                            $("#arc_name").hide();
                            $("#arc_button").hide();
                            $("#arc_complete_button").hide();
                            $("#restore_button").hide();
                           
                        }
                        
                        
                    });
              
    
                    
                    
                  $("#buyername").change(function(){
                            $("#arc_date").hide();
                            $("#arc_name").hide();
                            $("#arc_button").hide();
                            $("#arc_state").hide();
                            $("#arc_balance").hide();
                            $("arc_checkbox").prop('checked', true);
                         var customercompanyname=$("#buyername").val();
                         $.ajax({
                            type:"post",
                            url:"updateCustomerAction.php",
                            data:"COMPANY_NAME="+customercompanyname+"&action=getCustomerArchiveState",
                            success:function(data){
                              var customer_state=JSON.parse(data);
                              arc_state=customer_state["archive_state"];
                              arc_balance=customer_state["archive_balance"];
                            }
                         });

                        
                   });
    
                   $("#arc_date").change(function(){
                        var arc_name=$("#arc_name").val();
                        var arc_name_list=arc_name.split("_");
                        var arc_name_new=arc_name_list[0]+"_"+arc_name_list[1]+"_"+$("#arc_date").val();
                        $("#arc_name").val(arc_name_new);
                  });
   
            
            $("#arc_button").click(function(){
                    
                    if($("#arc_name").val()!=""){
                      var COMPANY_NAME=$("#buyername").val();
                      var ARC_COMPANY_NAME=$("#arc_name").val();
                    $.ajax({
                            type:"post",
                            url:"updateCustomerAction.php",
                            data:"COMPANY_NAME="+COMPANY_NAME+"&ARC_COMPANY_NAME="+ARC_COMPANY_NAME+"&action=archiveCustomer",
                            success:function(data){
                                try{
                                   
                                if(data>-1){
                                alert ("archieved Successfuly.");
                                location.reload();
                                }else{
                                    
                                alert("Failed To archieve.");
                                }
                                }catch(e){
                                $("#item_availability").html("Item not available");
                                
                                }
                                
                            }
                         });	
          
          }else{
            alert("please enter arcieve name.");
          }
    
                
            return false; 
            });
            
         



            
       $("#arc_complete_button").click(function(){
                    alert(5);
                   
                      var COMPANY_NAME=$("#buyername").val();
                     
                    $.ajax({
                            type:"post",
                            url:"updateCustomerAction.php",
                            data:"COMPANY_NAME="+COMPANY_NAME+"&action=arcCompleteCustomer",
                            success:function(data){
                                try{
                                   
                                if(data>-1){
                                alert ("Fully archieved Successfuly.");
                                
                                }else{
                                    
                                alert("Failed To archieve.");
                                }
                                }catch(e){
                                $("#item_availability").html("Item not available");
                                
                                }
                                
                            }
                         });	
          
         
                
            return false; 
            });







                    
       });//document ready closed
                
                


            
            
         
       


                
                
                $.extend({
                getUrlVars: function(){
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for(var i = 0; i < hashes.length; i++)
                    {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                    }
                return vars;
                },
                getUrlVar: function(name){
                return $.getUrlVars()[name];
                }
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
    
        <div class="companydetails" id="companydetail" style="display:none;">
        
        <center>NITIN TRADERS</center>
        <center>107, Gangaram Market</center>
        <center>Opp. Bhagwandar Hospital</center>
        <label id="companystate">Maharashtra</label>
        <center>Ulhasnagar- 421005</center>
        <center>Mob: 9146962469</center>
        <center>GSTN: 33ACMPW56789E1ZV</center>
        
        
        
        
        </div>
        
        <div class="buyerdetailst" id="buyerdetailst">
        
        
        <select id="buyername" style="width:300px;">
                <!-- Dropdown List Option -->
        </select><br><br><br>
        <table>
          <tr>
        <td><input type="checkbox" id="arc_checkbox" name="arc_checkbox" > Archieve</td>
        <td><input type='date' id='arc_date'  hidden ></td>
        <td><input type='text' id='arc_name'  size="60" hidden ></td>
        <td><span class='label1 success' id='arc_state' hidden>nk</span></td>
        <td><span class='label1 success' id='arc_balance' hidden>nk</span></td>
        <td><left><button type="button" class="arc_button" id="arc_button" hidden>Archieve</button></left></td>
        </tr>

        
        
      </table>
        
       </div>
        
    
        
          
           
            
            
            
            
            
            
            
            
            
            
            
            </br>
           
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
    
    
    
    .companydetails {
      max-width:  90%;
      margin: 10px auto;
      padding: 10px 20px;
      background: #e8e8df;
      border-radius: 8px;
    }
    
    
    .buyerdetails {
      max-width: 90%;
      margin: 10px auto;
      padding: 10px 20px;
      background: #e8e8df;
      border-radius: 8px;
    }
    .buyerdetailst {
      max-width:  90%;
      margin: 10px auto;
      padding: 10px 20px;
      background: #e8e8df;
      border-radius: 8px;
    }
    .ItemsDetails {
      max-width:  90%;
      margin: 10px auto;
      padding: 10px 20px;
      background: #e8e8df;
      border-radius: 8px;
    }
    
    .TaxDetail{
         max-width: 90%;
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
      
      color: #FFF;
      background-color: #4bc970;
      font-size: 18px;
      text-align: center;
      font-style: normal;
      border-radius: 5px;
      width: 30%;
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
    
    
    
    
    