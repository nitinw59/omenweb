<?php
  include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
  $action=$_POST["action"];
 
   if($action=="listSupplierStatement"){
	
	
	$to_date=$_POST["to_date"];
	$from_date=$_POST["from_date"];
	
	$sqlquery="select 
                 B.BILL_ID as 'BILL ID',
                  B.meter as 'meter',
				  B.loc as 'LOC'  

                    FROM MERCHANT_BILLS_TBL B

                    WHERE 
                    B.date>='$from_date'

                    and 

                    B.date<='$to_date';
                    ";
	
	$show_bill_list=mysqli_query($dbhandle,$sqlquery);
	$bills_list;
	$row_count=0;
    while($row=mysqli_fetch_array($show_bill_list)){
       
		$bills_list[$row_count]=["id"=>$row['BILL ID'],"meters"=>$row['meter'],"loc"=>$row['LOC'],"jobber_total_used_meters"=>0,"jobber_bill_list"=>null,"packer_total_used_meters"=>0,"packer_bill_list"=>null,"damage_total_used_meters"=>0,"damage_bill_list"=>null];
       
		$row_count++;
		}
		
		//echo $old_bill_amount;
		//echo $sqlquery;
		foreach($bills_list as &$bill){
            
            //for jobber bills

            $bill_id=$bill['id'];
            $sqlquery="select 
            B.JOBBER_CHALLAN_ID as 'BILL ID',
            B.USED_METERS as 'meter' , 
            B.LOC as 'LOC'  
    
             FROM JOBBER_CHALLAN_TBL B
        
                     WHERE 

                     B.BILL_ID=$bill_id";
	        
	        $show_jobber_bill_list=mysqli_query($dbhandle,$sqlquery);
            $jobber_bills_list=null;
	        $row_count=0;
			$jobber_total_used_meter=0.0;
            while($row=mysqli_fetch_array($show_jobber_bill_list)){
       
		            $jobber_bills_list[$row_count]=["id"=>$row['BILL ID'],"used_meters"=>$row['meter'],"LOC"=>$row['LOC']];
					$jobber_total_used_meter+=floatval($row['meter']);
		            $row_count++;
		    }
           
            $bill["jobber_bill_list"]=$jobber_bills_list;
            $bill["jobber_total_used_meters"]=$jobber_total_used_meter;



            //for packer bill list

            
            
            $sqlquery="select 
            B.packer_CHALLAN_ID as 'BILL ID',
            B.USED_METERS as 'meter' , 
            B.LOC as 'LOC'  
    
             FROM PACKER_CHALLAN_TBL B
        
                     WHERE 

                     B.BILL_ID=$bill_id";
	        
	        $show_packer_bill_list=mysqli_query($dbhandle,$sqlquery);
            $packer_bills_list=null;
	        $row_count=0;
			$packer_total_used_meter=0.0;
            while($row=mysqli_fetch_array($show_packer_bill_list)){
       
		            $packer_bills_list[$row_count]=["id"=>$row['BILL ID'],"used_meters"=>$row['meter'],"LOC"=>$row['LOC']];
					$packer_total_used_meter+=floatval($row['meter']);
		            $row_count++;
		    }
           
            $bill["packer_bill_list"]=$packer_bills_list;
            $bill["packer_total_used_meters"]=$packer_total_used_meter;


            //for damage bill list

            
            $sqlquery="select 
            B.DAMAGE_CHALLAN_ID as 'BILL ID',
            B.DAMAGE_METERS as 'meter' , 
            B.LOC as 'LOC'  
    
             FROM DAMAGE_CHALLAN_TBL B
        
                     WHERE 

                     B.BILL_ID=$bill_id";
	       
	        $show_damage_bill_list=mysqli_query($dbhandle,$sqlquery);
            $damage_bills_list=null;
	        $row_count=0;
            while($row=mysqli_fetch_array($show_damage_bill_list)){
       
		            $damage_bills_list[$row_count]=["id"=>$row['BILL ID'],"damage_meters"=>$row['meter'],"LOC"=>$row['LOC']];
       
		            $row_count++;
		    }
           
            $bill["damage_bill_list"]=$damage_bills_list;









        }
		
		echo json_encode($bills_list);
	
	//}
		
  }elseif($action=="fetchFromDateBillList"){
	$from_date=$_POST["from_date"];
	$sqlquery="select 
				B.BILL_ID as 'BILL ID',
				B.meter as 'meter'  
		
			FROM MERCHANT_BILLS_TBL B
			
			WHERE 

			B.date='$from_date' ";
		
	
	$show_bill_list=mysqli_query($dbhandle,$sqlquery);
	$bills_list;
	$row_count=0;
    while($row=mysqli_fetch_array($show_bill_list)){
		$bills_list[$row_count]=["id"=>$row['BILL ID'],"text"=>$row['meter']];
		$row_count++;
		}
		
		//echo $old_bill_amount;
		//echo $sqlquery;
		
		echo json_encode($bills_list);
	
	//}
		
  }elseif($action=="addJobberBill"){
	
	$status=0;
	$location="";	
	
		if(isset($_FILES['img_file']['name'])){
				
				$loc_dir=$_SERVER['DOCUMENT_ROOT']."/data/$omenNX/fabric/usageManager/Jobber/";
				$filename = $_FILES['img_file']['name'];
				$filename = time().'_'.$_POST['bill_id'].".jpg";
				if(!file_exists($loc_dir))
					mkdir($loc_dir,0777,true);
				
				$location = $loc_dir.$filename;
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
									$bill_id=$_POST['bill_id'];
									$usedMeter=$_POST['usedMeter'];
									$query_upload="insert into JOBBER_CHALLAN_TBL (BILL_ID,USED_METERS,loc) VALUES(".$bill_id." ,".$usedMeter.",'".$filename."');";
									$status=mysqli_query($dbhandle,$query_upload); 
									//$status=1;
								}else{
								$status=0;
								$location="";
								}
							}
	
	
		}
	
	
	
	
	echo $status;
	

  }elseif($action=="addPackerBill"){
	
	$status=0;
	$location="";	
	
		if(isset($_FILES['img_file']['name'])){
				
				$loc_dir=$_SERVER['DOCUMENT_ROOT']."/data/$omenNX/fabric/usageManager/Packer/";
				$filename = $_FILES['img_file']['name'];
				$filename = time().'_'.$_POST['bill_id'].".jpg";
				if(!file_exists($loc_dir))
					mkdir($loc_dir,0777,true);
				
				$location = $loc_dir.$filename;
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
									$bill_id=$_POST['bill_id'];
									$usedMeter=$_POST['usedMeter'];
									$query_upload="insert into PACKER_CHALLAN_TBL (BILL_ID,USED_METERS,loc) VALUES(".$bill_id." ,".$usedMeter.",'".$filename."');";
									$status=mysqli_query($dbhandle,$query_upload); 
									//$status=1;
								}else{
								$status=0;
								$location="";
								}
							}
	
	
		}
	
	
	
	
	echo $status;
	

  }elseif($action=="addDamageBill"){
	
	$status=0;
	$location="";	
	
		if(isset($_FILES['img_file']['name'])){
				
				$loc_dir=$_SERVER['DOCUMENT_ROOT']."/data/$omenNX/fabric/usageManager/Damage/";
				$filename = $_FILES['img_file']['name'];
				$filename = time().'_'.$_POST['bill_id'].".jpg";
				if(!file_exists($loc_dir))
					mkdir($loc_dir,0777,true);
				
				$location = $loc_dir.$filename;
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
									$bill_id=$_POST['bill_id'];
									$damageMeter=$_POST['damageMeter'];
									$query_upload="insert into DAMAGE_CHALLAN_TBL (BILL_ID,DAMAGE_METERS,loc) VALUES(".$bill_id." ,".$damageMeter.",'".$filename."');";
									$status=mysqli_query($dbhandle,$query_upload); 
									//$status=1;
								}else{
								$status=0;
								$location="";
								}
							}
	
	
		}
	
	
	
	
	echo $status;
	

  }
  
?>
