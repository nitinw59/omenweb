<?php
 include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
 
  $action=$_POST["action"];
 
  if($action=="fetchTransportDetails"){
	 
	 $challanNo=$_POST["challanNo"];
	 $sqlquery="Select 
	 				t.LR,
					t.transport_name,
					t.transport_parcels,
					t.DATE,
					c.COMPANY_NAME
					from 
						challan_transport_tbl t,
						customers_tbl c,
						challan_tbl ch 
						where 
							ch.challan_no=t.challan_no 
							and 
							c.customer_id=ch.customer_id 
							and 
							t.challan_no=".$challanNo;
     $show=mysqli_query($dbhandle,$sqlquery);
 
     while($row=mysqli_fetch_array($show)){
        $transportDetails['LR']=$row['LR'];
		$transportDetails['transport_name']=$row['transport_name'];
		$transportDetails['transport_parcels']=$row['transport_parcels'];
		$transportDetails['DATE']=$row['DATE'];
		$transportDetails['COMPANY_NAME']=$row['COMPANY_NAME'];
		
		echo json_encode($transportDetails);
		
		
		
		
     }
  }
  else if($action=="updateTransportDetails"){
	
	$status=0;
	$location="";	
	
		if(isset($_FILES['img_file']['name'])){
		
				$filename = $_FILES['img_file']['name'];
				$filename = $_POST['challanNo'].".jpg";
				if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/data/$omenNX/invoice/LR/"))
       				 mkdir($_SERVER["DOCUMENT_ROOT"]."/data/$omenNX/invoice/LR/",777,true);

				$location = $_SERVER["DOCUMENT_ROOT"]."/data/$omenNX/invoice/LR/".$filename;
				$uploadOk = 1;
				$imageFileType = pathinfo($location,PATHINFO_EXTENSION);

				$valid_extensions = array("jpg","jpeg","png");
					if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
						$uploadOk = 0;
						$location="";
					}

							if($uploadOk == 0){
								$status=0;
								$location="";
							}else{
								if(move_uploaded_file($_FILES['img_file']['tmp_name'],$location)){
								$status=1;
								}else{
								$status=0;
								$location="";
								}
							}
	
	
		}

	$challanNo=$_POST['challanNo'];
	$LR_DATE=$_POST['LR_DATE'];
	$LR_TRANSPORT=$_POST['LR_TRANSPORT'];
	$LR_PARCELS=$_POST['LR_PARCELS'];
	$LR=$_POST['LR'];
	
	if (strcmp($location, "") !== 0)
	$query_upload="update challan_transport_tbl set LR_LOC='$filename',date='".$LR_DATE."' ,lr=".$LR.",transport_name='".$LR_TRANSPORT."',transport_parcels=".$LR_PARCELS." where challan_no=".$challanNo.";";
	else
	$query_upload="update challan_transport_tbl set date='".$LR_DATE."' ,lr=".$LR.",transport_name='".$LR_TRANSPORT."',transport_parcels=".$LR_PARCELS." where challan_no=".$challanNo.";";
	
	$status=mysqli_query($dbhandle,$query_upload); 
	
	echo $status;
	
	}
	
	




















	
  
  
?>
