<?php
    include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	


    
	

	$jobworker=$_POST['jobworker'];
	

 	$query_upload=" insert into JOBWORKER_TBL(name) VALUES('$jobworker');";
	echo $query_upload;
	
	
	

	$status=mysqli_query($dbhandle,$query_upload) ; 
	
	
	if($status!=1){
	   	$message=("error in adding jobworker call 8087978196.");
       		echo "<script type='text/javascript'>alert('$message');</script>";
	}

	$url="listAlljobworkerTypes.php";
		echo "<meta http-equiv='refresh' content='0;url=".$url."'>";

	
	
	



?>

