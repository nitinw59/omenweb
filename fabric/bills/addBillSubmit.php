<?php
  	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
   
  $action=$_POST["action"];
  if($action=="addBill"){
	  
	$status=0;
		
	
		if(isset($_FILES['img_file']['name'])){
		$filename=$_FILES['img_file']['name'];
		//$filename = strtotime("now").".jpg";
		//$location = "SCN_$omenNX/".$filename;
		$dir=$_SERVER['DOCUMENT_ROOT']."/data/$omenNX/fabric/bills/";
		$uploadOk = 1;
		$imageFileType = pathinfo($filename,PATHINFO_EXTENSION);
		$valid_extensions = array("jpg","jpeg","png");
		
		if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
			$uploadOk = 0;
		}

				if($uploadOk == 0){
					$status=0;
				}else{
					if(!file_exists($dir))
					mkdir($dir,0777,true);
					$location=strtotime("now").".".$imageFileType;
					if(move_uploaded_file($_FILES['img_file']['tmp_name'],$dir.$location)){
						$merchant_name=$_POST['merchant_name'];
						$B_DATE=$_POST['B_DATE'];
						$meter=$_POST['meter'];
						$meterRate=$_POST['meterRate'];
						$amount=$_POST['amount'];
						$is_primary= $_POST['is_primary'];
						$jobworker_id=$_POST['jobworker_id'];
						if($is_primary=="true"){
							$sqlquery="INSERT INTO MERCHANT_BILLS_TBL(DATE,FABRIC_MERCHANTS_ID,AMOUNT,meter,meterRate,loc,PRIMARY_MATERIAL,JOBWORKER_ID)VALUES('".$B_DATE."',(SELECT fabric_merchants_id FROM fabric_merchants_tbl where COMPANY_NAME='".$merchant_name."'),$amount,'$meter','$meterRate','$location',$is_primary,$jobworker_id)";                                                           
						}else{
							$sqlquery="INSERT INTO MERCHANT_BILLS_TBL(DATE,FABRIC_MERCHANTS_ID,AMOUNT,meter,meterRate,loc,PRIMARY_MATERIAL)VALUES('".$B_DATE."',(SELECT fabric_merchants_id FROM fabric_merchants_tbl where COMPANY_NAME='".$merchant_name."'),$amount,'$meter','$meterRate','$location',$is_primary)";                                                           
						}
						$status=mysqli_query($dbhandle,$sqlquery); 
					}else{
					$status=0;
					}
				}
	
	
		}
	
	
	
	
	
	echo $status;
	
	}
	
	
  
?>
