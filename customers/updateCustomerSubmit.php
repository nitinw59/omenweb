 
<?php
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");



	


	
	

	$Fname=$_POST['Fname'];
	$Lname=$_POST['Lname'];
	$companyname=$_POST['companyname'];
	$oldcompanyname=$_POST['oldcompanyname'];
	$address=$_POST['address'];
	$city=$_POST['city'];
	$state=$_POST['state'];
	$zip=$_POST['zip'];
	$mobile=$_POST['mobile'];
	$email=$_POST['email'];
	
 	$query_upload=" update customers_tbl set 
						FNAME='$Fname',
						LNAME='$Lname',
						COMPANY_NAME='$companyname',
						EMAIL='$email',
						MOBILE='$mobile',
						ADDRESS='$address',
						CITY='$city',
						STATE='$state',
						ZIP='$zip' 
						where COMPANY_NAME='$oldcompanyname' ;";
	echo $query_upload;
	
	
	

	$status=mysqli_query($dbhandle,$query_upload) ; 
	
	
	
	if($status==1){
	$url="customerDisplay.php?companyname=".$companyname;
	echo "ok";
	}else{
	$url="customerAddError.php";
	echo "error";
	
	}
	
	
	echo "<meta http-equiv='refresh' content='0;url=".$url."'>";
	



?>

