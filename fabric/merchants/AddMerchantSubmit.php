 
<?php
include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
    


	


	
	

	$Fname=$_POST['Fname'];
	$Lname=$_POST['Lname'];
	$companyname=$_POST['companyname'];
	$address=$_POST['address'];
	$city=$_POST['city'];
	$state=$_POST['state'];
	$zip=$_POST['zip'];
	$mobile=$_POST['mobile'];
	$email=$_POST['email'];
	
 	$query_upload=" insert into FABRIC_MERCHANTS_TBL(FNAME,LNAME,COMPANY_NAME,EMAIL,MOBILE,ADDRESS,CITY,STATE,ZIP) VALUES('".$Fname."','".$Lname."','".$companyname."','".$email."',".$mobile.",'".$address."','".$city."','".$state."','".$zip."');";
	echo $query_upload;
	
	
	

	$selected = mysqli_select_db($dbhandle,$dbname) 
  or die("Could not select database1");

	$status=mysqli_query($dbhandle,$query_upload) ; 
	
	
	if($status==1){
	$url="MerchantDisplay.php?mobile=".$mobile;
	}else{
	$url="AddMerchantError.php";
	
	}
	
	
	echo "<meta http-equiv='refresh' content='0;url=".$url."'>";
	



?>

