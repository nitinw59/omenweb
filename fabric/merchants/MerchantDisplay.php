<?php
include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
?>

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Pushy - Off-Canvas Navigation Menu</title>
        <meta name="description" content="Pushy is an off-canvas navigation menu for your website.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="stylesheet" href="/<?=$omenNX?>/css/normalize.css">
        <link rel="stylesheet" href="/<?=$omenNX?>/css/demo.css">
        <!-- Pushy CSS -->
        <link rel="stylesheet" href="/<?=$omenNX?>/css/pushy.css">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    </head>

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

.center {
  max-width: 400px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #f4f7f8;
  border-radius: 8px;
}



.center1 {
  max-width: 350px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #FDFEFE;
  border-radius: 8px;
}


.center2 {
  max-width: 100%;
  margin: 10px auto;
  padding: 10px 20px;
  background: Red;
  border-radius: 8px;
}

h1 {
  margin: 0 0 30px 0;
  text-align: center;
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
  background-color: #e8eeef;
  color: #8a97a0;
  box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
  margin-bottom: 30px;
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
  padding: 19px 39px 18px 39px;
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
  width: 30px;
  display: inline-block;
  font-size: 0.8em;
  margin-right: 4px;
  line-height: 30px;
  text-align: center;
  text-shadow: 0 1px 0 rgba(255,255,255,0.2);
  border-radius: 100%;
}

@media screen and (min-width: 480px) {

  form {
    max-width: 480px;
  }

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


	</style>
<?php



include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");


// Attempt select query execution
$sql = "SELECT  *  FROM fabric_merchants_tbl where mobile='".$_GET["mobile"]."'";

if($result = mysqli_query($dbhandle,$sql) ){
	if(mysqli_num_rows($result) > 0){
		
		$row = mysqli_fetch_array($result);
		
		$FABRIC_MERCHANTS_ID = $row['FABRIC_MERCHANTS_ID'] ;
        $FNAME =   $row['FNAME'] ;
        $LNAME =  $row['LNAME'];
        $COMPANY_NAME =    $row['COMPANY_NAME'];
        $EMAIL = $row['EMAIL'] ;
        $MOBILE  = $row['MOBILE'] ;
       
		$GSTN=$row['GSTN'];
		$ADDRESS=$row['ADDRESS'];
		$CITY=$row['CITY'];
		$STATE=$row['STATE'];
		$ZIP=$row['ZIP'];
		
			
			
	}
}  
?>
<body>

<div class='center'>
<font color='Green' size='6'> Supplier Successfuly Added.</font>
</div>


<div class='center'>
<div class='center1'>


<table>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'> Customer Id: </font> </td> <td><?= $FABRIC_MERCHANTS_ID; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  First Name </font></td> <td><?= $FNAME; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  Last Name</font> </td> <td><?= $LNAME; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  Company Name </font> </td> <td><?= $COMPANY_NAME; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  Email </font> </td> <td><?= $EMAIL; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  Mobile</font> </td> <td><?= $MOBILE; ?></a></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'> <font color='white'> GSTN </font> </td> <td><?= $GSTN; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  Address</font> </td> <td><?= $ADDRESS; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  City</font> </td> <td><?= $CITY; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  State</font> </td> <td><?= $STATE; ?></td></tr>
<tr><td bgcolor='#4CAF50' align='right' width='50%'><font color='white'>  Zip</font> </td> <td><?= $ZIP; ?></td></tr>


</table>
</div>
</div>
     


	<!-- Pushy JS -->
        <script src="/<?=$omenNX?>/js/pushy.min.js"></script>


</body>




