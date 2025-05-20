<?php

/* Include the `fusioncharts.php` file that contains functions	to embed the charts. */

	include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/fusioncharts.php");
	include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/omenwebNX/var.php");
	
	$server_root="/omenweb";

	$from_date=$_GET['from_date'];
	$to_date=$_GET['to_date'];
	
	
	
?>

<html>
   <head>
  	
<title>
 O-menNX

	</title>
    <link  rel="stylesheet" type="text/css" href="css/style.css" />

  	<!-- You need to include the following JS file to render the chart.
  	When you make your own charts, make sure that the path to this JS file is correct.
  	Else, you will get JavaScript errors. -->

  <script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
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
			
 
 </head>

   <body>
  	<?php
  
include($_SERVER['DOCUMENT_ROOT']."$server_root/index.php");

     	// Form the SQL query that returns the top 10 most populous countries
     	$strQuery = "SELECT SUM(AMOUNT+TAX_AMOUNT) AS AMOUNT , m.COMPANY_NAME as NAME FROM `merchant_bills_tbl` mb,fabric_merchants_tbl m WHERE date>='$from_date' and date<='$to_date' and m.FABRIC_MERCHANTS_ID=mb.FABRIC_MERCHANTS_ID GROUP BY m.COMPANY_NAME";
		
		
     	// Execute the query, or else return the error message.
     	$result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
	
     	// If the query returns a valid response, prepare the JSON string
     	if ($result) {
			
        	// The `$arrData` array holds the chart attributes and data
        	$arrData = array(
        	    "chart" => array(
                  "caption" => "Top 10 Most Populous Countries",
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

        	$columnChart = new FusionCharts("column2D", "myFirstChart" , 1200, 500, "chart-1", "json", $jsonEncodedData);

        	// Render the chart
        	$columnChart->render();

        	// Close the database connection
        	$dbhandle->close();
     	}
		echo $total;

  	?>

  	<div id="chart-1"><!-- Fusion Charts will render here--></div>
<script src="<?=$server_root?>/js/pushy.min.js"></script>
	
   </body>

</html>