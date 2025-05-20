<?php

/* Include the `fusioncharts.php` file that contains functions	to embed the charts. */

	
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/fusioncharts.php");
	
	
	
	$Qtype= $_GET['Qtype'];
	$from_date=$_GET['from_date'];
	$to_date=$_GET['to_date'];
	
	
	
?>

<html>
   <head>

    <link  rel="stylesheet" type="text/css" href="css/style.css" />

  	<!-- You need to include the following JS file to render the chart.
  	When you make your own charts, make sure that the path to this JS file is correct.
  	Else, you will get JavaScript errors. -->

  <script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
  <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>monthly Sales</title>
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
			
 
 </head>

   <body>
  	<?php
  
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");

     	// Form the SQL query that returns the top 10 most populous countries
     	if($Qtype==$reporttype[0]){
		$strQuery = "select C.COMPANY_NAME AS NAME , SUM(b.TOTAL_AMOUNT) AS AMOUNT from bills_tbl B, customers_tbl C WHERE  B.customer_id=C.customer_id AND B.DATE>='$from_date' AND B.DATE<='$to_date' GROUP BY C.customer_id order by AMOUNT desc";
		}else if($Qtype==$reporttype[1]){
		$strQuery = "SELECT sum(quantity) as AMOUNT , c.COMPANY_NAME AS NAME FROM bill_items_tbl bi , bills_tbl b, customers_tbl c where b.BILL_ID=bi.BILL_ID and b.customer_id=c.customer_id  AND B.DATE>='$from_date' AND B.DATE<='$to_date'  group by b.customer_id order by AMOUNT desc";
		}
		// Execute the query, or else return the error message.
     	$result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
	
     	// If the query returns a valid response, prepare the JSON string
     	if ($result) {
			
        	// The `$arrData` array holds the chart attributes and data
        	$arrData = array(
        	    "chart" => array(
                  "caption" => "Top 10 Customers",
                  "showValues" => "0",
                  "theme" => "zune"
              	)
           	);

        	$arrData["data"] = array();

			$total=0;
	// Push the data into the array
        	while($row = mysqli_fetch_array($result)) {
           	array_push($arrData["data"], array(
              	"label" => $row["NAME"],
              	"value" => $row["AMOUNT"]
              	)
           	);
        	
			$total+=$row["AMOUNT"];
			
			}

        	/*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

        	$jsonEncodedData = json_encode($arrData);

	/*Create an object for the column chart using the FusionCharts PHP class constructor. Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, "div id to render the chart", "data format", "data source")`. Because we are using JSON data to render the chart, the data format will be `json`. The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the data source parameter of the constructor.*/

        	$columnChart = new FusionCharts("column2D", "myFirstChart" , 1200, 600, "chart-1", "json", $jsonEncodedData);

        	// Render the chart
        	$columnChart->render();

        	// Close the database connection
        	$dbhandle->close();
     	}

		echo $Qtype.": ".$total;
  	?>

  	<div id="chart-1"><!-- Fusion Charts will render here--></div>
	  <script src="/<?=$omenNX?>/js/pushy.min.js"></script>
   </body>

</html>