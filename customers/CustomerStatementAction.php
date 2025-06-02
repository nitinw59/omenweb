<?php
  
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	require_once ('../tcpdf_include.php');
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");

	
	require_once realpath("../WhatsAppCloudApi/vendor/autoload.php");

	use WHATSAPPCLOUDAPI\WhatsAppApi;
	use WHATSAPPCLOUDAPI\Message\Template\Component;

   
  $action=$_POST["action"];
 
   if($action=="listCustomerStatement"){
	$company_name=$_POST["company_name"];
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	
	
		
		//echo $old_bill_amount;
		//echo $sqlquery;
		
		echo json_encode(getStatementList($company_name,$from_date,$to_date,$legacy_v1_date,$dbhandle));
		
  }elseif($action=="generateStatementPDF"){
		$company_name=$_POST["company_name"];
		$from=$_POST["from_date"];
		$to=$_POST["to_date"];
		$billList=getStatementList($company_name,$from,$to,$legacy_v1_date,$dbhandle);
		$html= (generateStatementHtml($billList,$company_name,$from,$to));
		
		//echo $html;

		///$html='<table style="border: 1px solid black;border-collapse: collapse;width:100%;align:center;" >
		//<tr>
		//<td style="border: 1px solid black;text-align:left;height:60px;" cellpadding="10"  colspan="2"> <br><br>To, M/s company name </td>
		//<td style="border: 1px solid black;text-align:center;height:60px;"><font size="10" ><br>Invoice No. B-bill id <br> <br>Date: date</font></td>
		//</tr>
		//</table>';


		$loc= generateStatementPDF($html,"data/$omenNX/statements/$company_name",$from,$to,$company_name);

  }elseif($action="sendStatementPDF"){
	
		$company_name=$_POST["company_name"];
		$from=$_POST["from_date"];
		$to=$_POST["to_date"];
		$MOBILE="";

		$sql = "SELECT  *  FROM customers_tbl where company_name='".$company_name."' ORDER BY  company_name DESC";

		if($result = mysqli_query($dbhandle,$sql) ){
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_array($result);
				$customer_id = $row['customer_id'] ;
				$MOBILE = $row['MOBILE'] ;
					
			}
		}  

		echo "---".$MOBILE;
		$billList=getStatementList($company_name,$from,$to,$legacy_v1_date,$dbhandle);
		$html= (generateStatementHtml($billList,$company_name,$from,$to));
		$loc= generateStatementPDF($html,"data/$omenNX/statements/$company_name",$from,$to,$company_name);
		
		echo (sendWhatsAppMessage($MOBILE,$loc,"currentstatement",$from,$to));
	
  }
  
  function sendWhatsAppMessage(string $phoneNo,string $filepath,string $filename,$from,$to): bool{
    
    $api = new  WhatsAppApi(["graph_version"=>"v16.0",]);
    $response=$api->uploadMedia($filepath);
    $decoded_response=json_decode($response->getResponse()) ;
    
    $media_id=$decoded_response->id;
    

    $language="en_US";

    $template_name="customer_statement";
    
    $header_param=["type"=>"document",
                    "document"=>["id"=>$media_id,"filename"=>"".$filename]
                ];
    $body_param=[
            ["type"=>"text",
                "text"=>$from],
            ["type"=>"text",
                "text"=>$to]
            ];

           

    $component=new Component($header_param,$body_param);

            
    $response=$api->sendTemplate($phoneNo,$template_name,$language,$component);
           
    
    $decoded_response=json_decode($response->getResponse(),true);
            
    $receiver_no=$decoded_response['contacts'][0]['wa_id'];
    $wamid=$decoded_response['messages'][0]['id'];   
    

	print_r ($decoded_response);
    //$return_response=logMessage($receiver_no,$wamid,$lr,$amount,$bill_no);

    $response=$api->deleteMedia($media_id);

   
    //return $return_response;

	return true;

}

function generateStatementPDF($html,$dir,$from,$to,$company_name){



	if(!file_exists($_SERVER['DOCUMENT_ROOT'].$dir)){
		echo "direcory:".$_SERVER['DOCUMENT_ROOT']."/".$dir;
		mkdir($_SERVER['DOCUMENT_ROOT']."/".$dir,755,true);
	}


	$filepath=$_SERVER['DOCUMENT_ROOT'].$dir."/$company_name"."_".date('d-m-Y',strtotime($from))."_".date('d-m-Y',strtotime($to))."_".date('H-i-s',time()).".pdf";
	
	
	 
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->AddPage('L',"A3");
	$pdf->writeHTML($html, true, false, true, false, '');

	$pdf->Output("nitin.pdf", 'D');
	
	//echo json_encode(array("success"=> true, "filename"=>"nitin.pdf"));

}

function generateStatementHtml($billList,$company_name,$from,$to){
	//print_r($billList);

	$html="";
	$htmlt="";

	
	$htmlt.="<h3>For: M/s. $company_name </h3>";
		$htmlt.='<table style="border: 1px solid black;border-collapse: collapse;width:100%;align:center;">';
		//$html.="<caption>Invoice Statement From:     ".date('d/m/Y', strtotime($from))."       To: ".date('d/m/Y', strtotime($to))." </caption>";
		
		$htmlt.='<tr>';
				$htmlt.='<th style="border: 1px solid black; border-collapse: collapse;">BILL_ID</th>';
				$htmlt.='<th style="border: 1px solid black;border-collapse: collapse;">DATE</th>';
				$htmlt.='<th style="border: 1px solid black;border-collapse: collapse;">TRANSPORT</th>';
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">BOOKING</th>';
				
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">LR</th>';
				
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">PARCELS</th>';
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">QUANTITY</th>';
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">RATE</th>';
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">DESC</th>';
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">BILL AMOUNT</th>';
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">PAYMENT</th>';
				$htmlt.='<th style="border: 1px solid black;text-align:left;border-collapse: collapse;">BALANCE</th>';
				
				
				$htmlt.='</tr>';
		
				$old_balance=0;
				$initialBalance=0;
				$totalDebits=0;
				$totalCredits=0;
		foreach($billList as $bill){

		$old_balance=$old_balance+$bill['BILL AMOUNT']-$bill['PAYMENT AMOUNT'];
		
		 if($bill["PAYMENT AMOUNT"]==0){
			$bill["PAYMENT AMOUNT"]="-";
			$bill["PAYMENT DESCRIPTION"]="-";
			$totalDebits+=$bill['BILL AMOUNT'];
			
								
		}else{
			$bill['BILL AMOUNT']="-";
			$bill["BILL ID"]="-";
			$bill["transport_name"]="-";
			$bill["LR"]="-";
			$bill["transport_parcels"]="-";
			$bill["ITEM_QUANTITY"]="-";
			$bill["ITEM_RATE"]="-";
			$totalCredits+=$bill['PAYMENT AMOUNT'];
		}
				$htmlt.='<tr>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['BILL ID'].'</td>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['DATE'].'</td>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['transport_name'].'</td>';
			
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['t_date'].'</td>';
				
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['LR'].'</td>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['transport_parcels'].'</td>';
			
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['ITEM_QUANTITY'].'</td>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['ITEM_RATE'].'</td>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['PAYMENT DESCRIPTION'].'</td>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['BILL AMOUNT'].'</td>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$bill['PAYMENT AMOUNT'].'</td>';
				$htmlt.='<td style="border: 1px solid black;text-align:left;border-collapse: collapse;">'.$old_balance.'</td>';				
				
				
				$htmlt.='</tr>';
			}		
				
		
		
		
		$htmlt.='<tr><td colspan="11" style="text-align:center;">Total Balance Till Date: '.date('d/m/Y', strtotime($to)).'</td><td>'.$old_balance.'</td></tr>';
		
		
		$htmlt.='</table>';


		
		$htmlt.="<br>TOTAL CREDITS:$totalCredits</br>";
		$htmlt.="<br>TOTAL DEBITS:$totalDebits</br>";
		 
		
		return $htmlt;



}


function getStatementList($company_name,$from_date,$to_date,$legacy_v1_date,$dbhandle){
	

	$legacyDateObj=new DateTime($legacy_v1_date);
	$fromDateObj=new DateTime($from_date);
	$toDateObj=new DateTime($to_date);


	
	$flag=0;	//1.all new 	2.old and new	3.old
	
	if($fromDateObj>$legacyDateObj)
		$flag=1;	
	else {
		if($toDateObj>$legacyDateObj)
		$flag=2;
		else
		$flag=3;
		
	}





	$sqlquery="select SUM(B.TOTAL_AMOUNT) AS TOTALAMOUNT FROM bills_tbl B,  customers_tbl C 
				WHERE B.customer_id=C.customer_id AND C.COMPANY_NAME='".$company_name."'  AND B.DATE<'".$from_date."' order by  B.BILL_ID desc";
    $show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	$old_bill_amount=$row['TOTALAMOUNT'];
	
	
	
	
	$sqlquery="select SUM(cr.AMOUNT) AS TOTALPAYMENT FROM credits_tbl cr,  customers_tbl C WHERE cr.customer_id=C.customer_id AND C.COMPANY_NAME='".$company_name."'  AND cr.DATE<'".$from_date."' ";
    $show=mysqli_query($dbhandle,$sqlquery);
	$row=mysqli_fetch_array($show);
	if($row['TOTALPAYMENT']==null)
			$row['TOTALPAYMENT']=0;
		
	$old_payment=$row['TOTALPAYMENT'];
	$old_balance=$old_bill_amount-$old_payment;




	
	if($flag==2){  //old and new both
		

		$sqlquerylegacy="select 
			B.BILL_ID as 'BILL ID',
			B.DATE as 'DATE',
			(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=B.BILL_ID) as 'ITEM_QUANTITY'  ,
			(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=B.BILL_ID) as 'ITEM_RATE'  ,
			(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
			DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
			tr.LR as LR,
			LR_LOC as LR_LOC,
			tr.transport_name as transport_name,
			tr.transport_parcels as transport_parcels,
			0 as 'PAYMENT AMOUNT',	
			0 as 'PAYMENT DESCRIPTION'
	
				FROM bills_tbl B,
					transport_tbl tr,
					customers_tbl C 
		
				WHERE 
	
				B.BILL_ID=tr.BILL_ID AND
				B.customer_id=C.customer_id AND
				C.COMPANY_NAME='$company_name' AND
				B.DATE>='$from_date' AND B.DATE<='$legacy_v1_date' 
	
	
			UNION ALL
	
			SELECT 
	
			0 as 'BILL ID',
			c.date AS 'DATE',
			0 as 'ITEM_QUANTITY',
			0 as 'ITEM_RATE',
			0 as 'BILL AMOUNT',
			'' as t_date,
			'' as LR,
			'' as LR_LOC,
			'' as transport_name,
			0 as transport_parcels,
			c.amount as 'PAYMENT AMOUNT',
			c.DESCRIPTION as 'PAYMENT DESCRIPTION'
	
	
			FROM credits_tbl c,
			customers_tbl Cr 
	
			WHERE
			Cr.customer_id=c.customer_id 
			AND Cr.COMPANY_NAME='$company_name' 
			AND c.DATE>='$from_date' 
			AND c.DATE<='$legacy_v1_date'
		
			order by date asc";
			
			
			
		$sqlqueryNew="select 
		B.BILL_ID as 'BILL ID',
		B.DATE as 'DATE',
		(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=B.BILL_ID) as 'ITEM_QUANTITY'  ,
		(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=B.BILL_ID) as 'ITEM_RATE'  ,
		(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
		DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
		tr.LR as LR,
		LR_LOC as LR_LOC,
		tr.transport_name as transport_name,
		tr.transport_parcels as transport_parcels,
		0 as 'PAYMENT AMOUNT',	
		0 as 'PAYMENT DESCRIPTION'
	
		FROM bills_tbl B,
		challan_transport_tbl tr,
		challan_tbl ch,	
		customers_tbl C 
		
		WHERE 
	
		B.BILL_ID=ch.BILL_ID AND
		ch.challan_no=tr.challan_no AND
	
		B.customer_id=C.customer_id AND
		C.COMPANY_NAME='$company_name' AND
		B.DATE>='$legacy_v1_date' AND B.DATE<='$to_date' 
	
	
		UNION ALL
	
	
	
		SELECT 
	
		0 as 'BILL ID',
		c.date AS 'DATE',
		0 as 'ITEM_QUANTITY',
		0 as 'ITEM_RATE',
		0 as 'BILL AMOUNT',
		'' as t_date,
		'' as LR,
		'' as LR_LOC,
		'' as transport_name,
		0 as transport_parcels,
		c.amount as 'PAYMENT AMOUNT',
		c.DESCRIPTION as 'PAYMENT DESCRIPTION'
	
	
		FROM credits_tbl c,
		customers_tbl cr 
	
		WHERE
		cr.customer_id=c.customer_id 
		AND cr.COMPANY_NAME='$company_name' 
		AND c.DATE>'$legacy_v1_date' 
		AND c.DATE<='$to_date'
		
		order by date asc";	
			
		}elseif($flag==1){// all new
			
	
			
		$sqlqueryNew="select 
		B.BILL_ID as 'BILL ID',
		B.DATE as 'DATE',
		(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=B.BILL_ID) as 'ITEM_QUANTITY'  ,
		(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=B.BILL_ID) as 'ITEM_RATE'  ,
		(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
		DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
		tr.LR as LR,
		LR_LOC as LR_LOC,
		tr.transport_name as transport_name,
		tr.transport_parcels as transport_parcels,
		0 as 'PAYMENT AMOUNT',	
		0 as 'PAYMENT DESCRIPTION'
	
		FROM bills_tbl B,
		challan_transport_tbl tr,
		challan_tbl ch,	
		customers_tbl C 
		
		WHERE 
	
		B.BILL_ID=ch.BILL_ID AND
		ch.challan_no=tr.challan_no AND
	
		B.customer_id=C.customer_id AND
		C.COMPANY_NAME='$company_name' AND
		B.DATE>='$from_date' AND B.DATE<='$to_date' 
	
	
		UNION ALL
	
	
	
		SELECT 
	
		0 as 'BILL ID',
		c.date AS 'DATE',
		0 as 'ITEM_QUANTITY',
		0 as 'ITEM_RATE',
		0 as 'BILL AMOUNT',
		'' as t_date,
		'' as LR,
		'' as LR_LOC,
		'' as transport_name,
		0 as transport_parcels,
		c.amount as 'PAYMENT AMOUNT',
		c.DESCRIPTION as 'PAYMENT DESCRIPTION'
	
	
		FROM credits_tbl c,
		customers_tbl cr 
	
		WHERE
		cr.customer_id=c.customer_id 
		AND cr.COMPANY_NAME='$company_name' 
		AND c.DATE>='$from_date' 
		AND c.DATE<='$to_date'
		
		order by date asc";	
		}elseif($flag==3){// all old legacy
			
	
			$sqlquerylegacy="select 
			B.BILL_ID as 'BILL ID',
			B.DATE as 'DATE',
			(select GROUP_CONCAT(bi.quantity) from bill_items_tbl bi where bi.BILL_ID=B.BILL_ID) as 'ITEM_QUANTITY'  ,
			(select GROUP_CONCAT(bi.rate) from bill_items_tbl bi where bi.BILL_ID=B.BILL_ID) as 'ITEM_RATE'  ,
			(B.TOTAL_AMOUNT) AS 'BILL AMOUNT',
			DATE_FORMAT(tr.DATE, '%d/%m/%Y') as t_date,		
			tr.LR as LR,
			LR_LOC as LR_LOC,
			tr.transport_name as transport_name,
			tr.transport_parcels as transport_parcels,
			0 as 'PAYMENT AMOUNT',	
			0 as 'PAYMENT DESCRIPTION'
	
				FROM bills_tbl B,
					transport_tbl tr,
					customers_tbl C 
		
				WHERE 
	
				B.BILL_ID=tr.BILL_ID AND
				B.customer_id=C.customer_id AND
				C.COMPANY_NAME='$company_name' AND
				B.DATE>='$from_date' AND B.DATE<='$to_date' 
	
	
			UNION ALL
	
			SELECT 
	
			0 as 'BILL ID',
			c.date AS 'DATE',
			0 as 'ITEM_QUANTITY',
			0 as 'ITEM_RATE',
			0 as 'BILL AMOUNT',
			'' as t_date,
			'' as LR,
			'' as LR_LOC,
			'' as transport_name,
			0 as transport_parcels,
			c.amount as 'PAYMENT AMOUNT',
			c.DESCRIPTION as 'PAYMENT DESCRIPTION'
	
	
			FROM credits_tbl c,
			customers_tbl cr 
	
			WHERE
			cr.customer_id=c.customer_id 
			AND cr.COMPANY_NAME='$company_name' 
			AND c.DATE>='$from_date' 
			AND c.DATE<='$to_date'
		
			order by date asc";
			
		
		}





	
			
	$billList;	

	$row_count=0;
	
	// first row initial amount
	$bill;
        
		$bill['BILL ID']=0;
		$bill['DATE']='';
		$bill['BILL AMOUNT']=$old_balance;
		$bill['t_date']='';
		$bill['LR']=0;
		$bill['LR_LOC']='';
		$bill['transport_name']='';
		$bill['transport_parcels']=0;
		$bill['PAYMENT AMOUNT']=0;
		$bill['PAYMENT DESCRIPTION']='';
		$bill['ITEM_QUANTITY']='';
		$bill['ITEM_RATE']='';
		
		
		$billList[$row_count]=$bill;
	
	$row_count++;
	
	
	
	

		
	if($flag==2 or $flag==3){		
	$show=mysqli_query($dbhandle,$sqlquerylegacy);
			
				while($row=mysqli_fetch_array($show)){
				$bill;
				
				$bill['BILL ID']=$row['BILL ID'];
				$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
				$bill['BILL AMOUNT']=$row['BILL AMOUNT'];
				$bill['t_date']=$row['t_date'];
				$bill['LR']=$row['LR'];
				$bill['transport_name']=$row['transport_name'];
				$bill['transport_parcels']=$row['transport_parcels'];
				$bill['PAYMENT AMOUNT']=$row['PAYMENT AMOUNT'];
				$bill['PAYMENT DESCRIPTION']=$row['PAYMENT DESCRIPTION'];
				$bill['ITEM_QUANTITY']=$row['ITEM_QUANTITY'];
				$bill['ITEM_RATE']=$row['ITEM_RATE'];
				$billList[$row_count]=$bill;
				$row_count++;
				}
	

	}

	if($flag==2 or $flag==1){
		$show=mysqli_query($dbhandle,$sqlqueryNew);
			
				while($row=mysqli_fetch_array($show)){
				$bill;
				
				$bill['BILL ID']=$row['BILL ID'];
				$bill['DATE']=date('d/m/Y', strtotime($row['DATE']));
				$bill['BILL AMOUNT']=$row['BILL AMOUNT'];
				$bill['t_date']=$row['t_date'];
				$bill['LR']=$row['LR'];
				$bill['transport_name']=$row['transport_name'];
				$bill['transport_parcels']=$row['transport_parcels'];
				$bill['PAYMENT AMOUNT']=$row['PAYMENT AMOUNT'];
				$bill['PAYMENT DESCRIPTION']=$row['PAYMENT DESCRIPTION'];
				$bill['ITEM_QUANTITY']=$row['ITEM_QUANTITY'];
				$bill['ITEM_RATE']=$row['ITEM_RATE'];
				$billList[$row_count]=$bill;
				$row_count++;
				}


	}

	return $billList;

}


?>
