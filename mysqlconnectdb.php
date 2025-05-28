<?php
$username = "u101648997_shopwebNX";
$password = "UNR@home1015";
$hostname = "www.darkcarbon.in";
$dbname= "u101648997_shopwebNX";

//connection to the database
$dbhandle = mysqli_connect($hostname, $username, $password,$dbname) 
  or die(mysql_error());

  $username = "root";
  $password = "";
  $hostname = "localhost";
  $dbname= "stockmanager";
  
  //connection to the database
  //$dbhandle_stockmanager = mysqli_connect($hostname, $username, $password,$dbname) 
    //or die(mysql_error());
  
?>