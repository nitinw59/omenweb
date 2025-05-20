 
<?php
$server_root="/omenwebNX";

include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");

    function GetImageExtension($imagetype)
   	 {
       if(empty($imagetype)) return false;
       switch($imagetype)
       {
           case 'image/bmp': return '.bmp';
           case 'image/gif': return '.gif';
           case 'image/jpeg': return '.jpg';
           case 'image/png': return '.png';
           default: return false;
       }
     }


	


if (!empty($_FILES["uploadedimage"]["name"])) {
	
	
	

	$items_id=$_POST['items_id'];
	$description=$_POST['description'];
	$maker=$_POST['maker'];
	$Size=$_POST['Size'];
	$quantity=$_POST['quantity'];
	$quantityrec=$_POST['quantityrec'];
	$quantityalt=$_POST['quantityalt'];
	$rate= (int)$_POST['rate'];
	$taxrate= (int)$_POST['taxrate'];
	
	



	$file_name=$_FILES["uploadedimage"]["name"];
	$temp_name=$_FILES["uploadedimage"]["tmp_name"];
	$imgtype=$_FILES["uploadedimage"]["type"];
	$ext= GetImageExtension($imgtype);
	$imagename=date("d-m-Y")."-".time()."-".rand(10,1000).$ext;
	$target_path = "images/".$imagename;
	
	
if(move_uploaded_file($temp_name, $target_path)) {

 	$query_upload="insert into items_tbl (items_id,DESCRIPTION,MAKER,QUANTITY,SIZE,QUANTITY_RECEIVED,QUANTITY_ALTER,RATE,TAX_RATE,images_path) values(".$items_id.",'".$description."','".$maker."',".$quantity.",'".$Size."',".$quantityrec.",".$quantityalt.",".$rate.",".$taxrate.",'".$target_path."')";
	
	
	

	$selected = mysqli_select_db($dbhandle,$dbname) 
  or die("Could not select database1");

	$status=mysqli_query($dbhandle,$query_upload); 
	
	
	if($status==1){
	$url="showItem.php?path=".$target_path;
	}else{
	
	$url="itemAddError.php";
	
	}
	
	
	echo "<meta http-equiv='refresh' content='0;url=".$url."'>";
	}
 
	
}else{

   exit("Error While uploading image on the server");
} 



?>

