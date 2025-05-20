<?php
 
$server_root="/omenwebNX";
include($_SERVER['DOCUMENT_ROOT']."$server_root/mysqlconnectdb.php");
include($_SERVER['DOCUMENT_ROOT']."$server_root/var.php");

   
  $action=$_POST["action"];

  if($action=="getChallan"){

    $challanNo=$_POST["challanNo"];
    $COMPANY_NAME;
	$challanDate;
	$itemList;
	$transportname;
	$transportparcels;
	
    $sqlquery="select COMPANY_NAME, ch.DATE, transport_name, transport_parcels 
                from 
                challan_tbl ch, customers_tbl c ,challan_transport_tbl CHT
                    WHERE 
                    ch.challan_no = $challanNo
                        AND 
                    ch.customer_id=c.customer_id
                        AND 
                    CHT.challan_no=ch.challan_no;";                                                           
	
	$show=mysqli_query($dbhandle,$sqlquery);
	
    $row=mysqli_fetch_array($show);
	$COMPANY_NAME=$row["COMPANY_NAME"];
	$challanDate=$row["DATE"];
	$transportname=$row["transport_name"];
	$transportparcels=$row["transport_parcels"];

    
	$sqlquery = "select 
    challan_items_id,quantity,RATE,DESCRIPTION
    from
    challan_items_tbl chi
    where 
    chi.challan_no=$challanNo";

    $show=mysqli_query($dbhandle,$sqlquery);
    $challanItems;
    $count=0;
        while($row=mysqli_fetch_array($show)){
        $item;
        $item["challan_items_id"]=$row["challan_items_id"];
        $item["quantity"]=$row["quantity"];
        $item["RATE"]=$row["RATE"];
        $item["DESCRIPTION"]=$row["DESCRIPTION"];
        $challanItems[$count]=$item;    
        $count++;
    }
    

	
	$challan["DATE"]=$challanDate;
	$challan["transportName"]=$transportname;
	$challan["noOfParcel"]=$transportparcels;
	$challan["COMPANY_NAME"]=$COMPANY_NAME;
	$challan["itemList"]=$challanItems;

     
    echo json_encode($challan);

	
    }elseif($action=="deleteChallanItem"){

        $challanItemId=$_POST["challanItemId"];


        $sqlquery = "delete from challan_items_tbl
                    where 
                    challan_items_id=$challanItemId";

        $show=mysqli_query($dbhandle,$sqlquery);
        echo $show;

    }elseif($action=="addChallanItem"){

        $challanNo=$_POST["challanNo"];
        $itemQty=$_POST["quantity"];
        $sqlquery="INSERT INTO challan_items_tbl
						(challan_no,quantity,RATE,DESCRIPTION)
						VALUES
						($challanNo,$itemQty,140,'mix lot')";
						
		$show=mysqli_query($dbhandle,$sqlquery);
        echo $show;
    }elseif($action=="updateCustomer"){

        $challanNo=$_POST["challanNo"];
        $customercompanyname=$_POST["customercompanyname"];
        $sqlquery="UPDATE challan_tbl
						SET customer_id = (SELECT customer_id from customers_tbl where COMPANY_NAME= '$customercompanyname') 
                        WHERE 
                        challan_no = $challanNo;
                        
                        ";
						
		$show=mysqli_query($dbhandle,$sqlquery);
        echo $show;

    }elseif($action=="updateChallanTransport"){

        
        $challanNo=$_POST["challanNo"];
        $transportname=$_POST["transportname"];
        $transportparcels=$_POST["transportparcels"];

        $sqlquery="UPDATE challan_transport_tbl
						SET transport_name = '$transportname',
                        transport_parcels = '$transportparcels' 
                        WHERE 
                        challan_no = $challanNo;
                        
                        ";
						
		$show=mysqli_query($dbhandle,$sqlquery);
        echo $show;
    }
	

  
  function generatePDF(string $challanNo,mysqli $dbhandle,string $server_root): int{
    if (!file_exists('path/to/directory')) {
        mkdir('path/to/directory', 0777, true);
    }
    global $omenNX;

	$challanDetail=getchallanDetail($challanNo,$dbhandle);
	$challanItems=getchallanItems($challanNo,$dbhandle);
	$challanTransport=getchallanTransport($challanNo,$dbhandle);

	$filepath=$_SERVER['DOCUMENT_ROOT'] ."data/omenwebnx/challan/PDF/$challanNo.pdf";
    if(!file_exists($_SERVER['DOCUMENT_ROOT'] ."data/omenwebnx/challan/PDF"))
        mkdir($_SERVER['DOCUMENT_ROOT'] ."data/omenwebnx/challan/PDF",777,true);

        $challanDetail["type"]="(Original copy)";
        $challanHtmlOG=generateHtmlData($challanDetail,$challanItems,$challanTransport);
        $challanDetail["type"]="(Duplicate copy)";
        $challanHtmlCopy=generateHtmlData($challanDetail,$challanItems,$challanTransport);
    




        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        $pdf->AddPage();


            // Set some content to print

            // Print text using writeHTMLCell()
       // $pdf->writeHTML(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $pdf->writeHTML($challanHtmlOG, true, false, true, false, '');
        $pdf->AddPage();
        $pdf->writeHTML($challanHtmlCopy, true, false, true, false, '');
        
        $pdf->Output($filepath, 'F');
   
        return json_encode(1);

}

function generateHtmlData(array $challanDetail,array $challanItems,array $challanTransport): string{
       
    $html='<p style="text-align:right;">'.$challanDetail["type"].'</p>';
    
	
    $html.='<table   style="border: 1px solid black;border-collapse: collapse;width:100%;" >
            <tr> 
            <td style="text-align:center;"><font size="16" ><br>MANISH GARMENTS</font><font size="8px" ><br>Main Bazaar,ULHASNAGAR-421005.</font> </td>
            </tr>
            </table>';
    
    
    

    $html.='<table style="border: 1px solid black;border-collapse: collapse;width:100%;align:center;" >
            <tr>
            <td style="border: 1px solid black;text-align:left;height:60px;" cellpadding="10"  colspan="2"> <br><br>To, M/s <b>'.$challanDetail["COMPANY_NAME"] .'<br>                  '.$challanDetail["city"].'-'.$challanDetail["zip"].'</b></td>
            <td style="border: 1px solid black;text-align:center;height:60px;"><font size="10" ><br>Challan No. C-'.$challanDetail["challanNo"].' <br> <br>Date: '.date('d/m/Y',strtotime($challanDetail["DATE"])).'</font></td>
            </tr>
            </table>';


            
    $html.="<br>";
    $html.="<br>";
   

    $html.='<table style="border: 1px solid black;border-collapse: collapse;width:100%;" >
            <tr>
            <th style="border: 1px solid black;text-align:center;" width="7%"><font size="10" ><b>Sr.</b></font></th>
            <th style="border: 1px solid black;text-align:center;" width="55%"><font size="10" ><b>Description Of Goods </b></font></th>
	
            <th style="border: 1px solid black;text-align:center;" width="10%"><font size="10" ><b>Qty</b></font></th>
            <th style="border: 1px solid black;text-align:center;" width="10%"><font size="10" ><b>Rate</b></font></th>
            
            <th style="border: 1px solid black;text-align:center;" width="18%"><font size="10" ><b>Amount</b></font></th>
	
	
            </tr>';
            
   

       $total_quantity=0;
	    $total_amount=0;
        $row_count=1;
              foreach($challanItems as $item){
                    

	                $amount=0;

    
	                $html.= '<tr >';
                    $html.= '<td>'.$row_count.'</td>';
                    $html.= '<td style="border-left: 1px solid black;text-align:left;"><font size="10" >Lot No.'. $item["DESCRIPTION"].' </font></td>';
	
                    $html.= '<td style="border-left: 1px solid black;text-align:left;">'.$item["quantity"].'</td>';
	
	                $html.= '<td style="border-left: 1px solid black;text-align:left;">'.$item["RATE"].'</td>';
	               
	                $amount=$item["quantity"]*$item["RATE"];
	                $html.= '<td style="border-left: 1px solid black;text-align:left;">'.$amount.'</td>';
	                $html.= '</tr>';

    
	                $total_quantity += $item["quantity"];
	                $total_amount+=$amount;
                    $row_count++;	
                    }
                   


                    $dummy_row=(8-$row_count)*2;
	


	 
                        while($dummy_row>-1){
	                             $html.= '<tr >';
                                 $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
                                 $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	                             $html.= '<td style="border-left: 1px solid black;text-align:left;"> </td>';
                                 $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	                            
	                             $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	                             $html.= '</tr>';
	                            $dummy_row--;
                            }

                            

                    $html.= '<tr >';
                    $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
                    $html.= '<td style="border-left: 1px solid black;text-align:left;">';
                    $html.='<table>';
			
                    $html.='<tr><td><font size="14" ><b>Transport :'. $challanTransport["transport_name"].'</b></font></td></tr>';
				
				    $html.='<tr><td><font size="14" ><b>Parcels :'. $challanTransport["transport_parcels"].'</b></font></td></tr>';
				    
			
				
				    $html.='</table></td>';
	                $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
                    $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	
	                $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	                $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	                $html.= '</tr>';
	
	
	
	 
                    
 
	
                    $html.= '<tr height="10px">';
                    $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
                    $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	                $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
                    $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	                $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	
	                $html.= '<td style="border-left: 1px solid black;text-align:left;"></td>';
	                $html.= '</tr>';
	                $row_count++;



                    $html.='<tr>
                    <th  style="border: 1px solid black;text-align:left;" colspan="2"> Total</th>
          
                    <th style="border: 1px solid black;text-align:left;"   align="left">'.$total_quantity.'</th>
                    <th style="border: 1px solid black;text-align:left;"></th>
                    <th style="border: 1px solid black;text-align:left;" > Rs. '.$total_amount.'</th>
          
          
                    </tr>';            
	                $row_count++;
                    
	                $html.= '</table>';
  
               
                                        

  
     $html.='<table  style="width:100%" align=center>


            <tr>
            <td width="90%">Amount In Words<b> Indian Rupee(s) ';
  
  $f = new NumberFormatter('en-IN', NumberFormatter::SPELLOUT);

   $html.= $f->format(($total_amount));
  
   $html.=" Only. </b></td>";
   $html.="<td align='right'>E&OE</td>";
  
  
  
   $html.="</tr>";
   $html.="</table>";

    
    return $html;


}

function getchallanDetail(string $challanNo,mysqli $dbhandle): array{

	$sqlquery = "select 
					challan_no,BILL_ID,DATE,c.COMPANY_NAME,c.city,c.zip 
					from
					challan_tbl ch,customers_tbl c
					where 
					ch.challan_no=$challanNo
					AND
					ch.customer_id=c.customer_id";

    $show=mysqli_query($dbhandle,$sqlquery);
	$challanDetail;
    while($row=mysqli_fetch_array($show)){
	$challanDetail["challanNo"]=$row["challan_no"];
	$challanDetail["BILL_ID"]=$row["BILL_ID"];
	$challanDetail["DATE"]=$row["DATE"];
	$challanDetail["COMPANY_NAME"]=$row["COMPANY_NAME"];
	$challanDetail["city"]=$row["city"];
	$challanDetail["zip"]=$row["zip"];
	

	}
	return($challanDetail);

}
function getchallanItems(string $challanNo,mysqli $dbhandle): array{
    

	$sqlquery = "select 
    challan_items_id,quantity,RATE,DESCRIPTION
    from
    challan_items_tbl chi
    where 
    chi.challan_no=$challanNo";

    $show=mysqli_query($dbhandle,$sqlquery);
    $challanItems;
    $count=0;
        while($row=mysqli_fetch_array($show)){
        $item;
        $item["challan_items_id"]=$row["challan_items_id"];
        $item["quantity"]=$row["quantity"];
        $item["RATE"]=$row["RATE"];
        $item["DESCRIPTION"]=$row["DESCRIPTION"];
        $challanItems[$count]=$item;    
        $count++;
    }
return($challanItems);
}
function getchallanTransport(string $challanNo,mysqli $dbhandle): array{
        
	$sqlquery = "select 
    challan_transport_id,DATE,LR,transport_name,LR_LOC,transport_parcels
    from
    challan_transport_tbl ct
    where 
    ct.challan_no=$challanNo";

    $show=mysqli_query($dbhandle,$sqlquery);
    $challanTransport;
    while($row=mysqli_fetch_array($show)){
        $challanTransport["challan_transport_id"]=$row["challan_transport_id"];
        $challanTransport["LR"]=$row["LR"];
        $challanTransport["DATE"]=$row["DATE"];
        $challanTransport["LR_LOC"]=$row["LR_LOC"];
        $challanTransport["transport_name"]=$row["transport_name"];
        $challanTransport["transport_parcels"]=$row["transport_parcels"];
        
        

    }
return($challanTransport);
}
?>
