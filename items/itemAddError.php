<?php

$server_root="/omenwebNX";

include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");

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

.center2 {
  max-width: 400px;
  margin: 10px auto;
  padding: 10px 20px;
  background: Red;
  border-radius: 8px;
}
</style>

<body>
<?php
		

		include($_SERVER['DOCUMENT_ROOT']."$server_root/index.php");
		?>

<div class='center2'>
<font color='white' size='6'> Item Failed to Added.</font>
<font color='white' size='3'>
please varify all details added are correct and unique.</font>
</div>


 <script src="<?=$server_root?>/js/pushy.min.js"></script>

</body>