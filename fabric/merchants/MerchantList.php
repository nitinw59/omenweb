
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

.updateCustomer {
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

.updateCustomer:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
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

#customerlist {
    width: 100%;
	height: 100%;
    overflow: scroll;
}	
</style>


<?php



// Attempt select query execution
$sql = "SELECT * FROM FABRIC_MERCHANTS_TBL ORDER BY FABRIC_MERCHANTS_ID DESC";

if($result = mysqli_query($dbhandle,$sql)){
    
	if(mysqli_num_rows($result) > 0){
		
        echo "<body>";
		include($_SERVER['DOCUMENT_ROOT']."/$omenNX/index.php");
	
		echo"<div id='customerlist'><table>";
            echo "<tr>";
                echo "<th>Merchant Id </th>";
                echo "<th>First Name</th>";
                
				echo "<th>Last Name</th>";
                echo "<th>COMPANY NAME</th>";
              
				echo "<th>GSTN</th>";
				echo "<th>ADDRESS</th>";
				echo "<th>CITY</th>";
				echo "<th>STATE </th>";
				echo "<th>ZIP </th>";
				echo "<th>MOBILE </th>";
				
				echo "<th>EMAIL </th>";
				echo "<th>Update </th>";
				
				
		



    
	echo "</tr>";
        while($row = mysqli_fetch_array($result)){
                echo "<td><center><label>" . $row['FABRIC_MERCHANTS_ID'] . "</label></center></td>";
                echo "<td><center><label>" . $row['FNAME'] . "</label></center></td>";
                echo "<td><center><label>" . $row['LNAME'] . "</label></center></td>";
                echo "<td><center><label>" . $row['COMPANY_NAME'] . "</label></center></td>";
               
				echo "<td><center><label>" . $row['ADDRESS'] . "</label></center></td>";
                echo "<td><center><label>" . $row['CITY'] . "</label></center></td>";
                
				echo "<td><center><label>".$row['STATE']."</label></center></td>";
				
				
				echo "<td><center><label>" . $row['ZIP'] . "</label></center></td>";
				echo "<td><center><label>" . $row['MOBILE'] . "</label></center></td>";
                echo "<td><center><label>" . $row['EMAIL'] . "</label></center></td>";
              
				
                echo "<td><center><label><button type='submit' class='updateCustomer'>Update</button></label></center></td>";
				

            echo "</tr>";
        }
        echo "</table>";
		
		

			echo "</br><script src='/$omenNX/js/pushy.min.js'></script>";
			echo "</div></body>";
        // Close result set
        mysqli_free_result($result);
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($dbhandle);
?>