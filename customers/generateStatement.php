<?php




include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");

require_once('../tcpdf_include.php');



function generateHtmlData(int $bill_id,string $COMPANY_NAME,string $BILL_DATE,string $transport_name,string $transport_date,string $lr,string $transport_parcels): string{
    global $omenNX;
    global $dbhandle;
    
    $html="";
    
	
    $html.='<table   style="border: 1px solid black;border-collapse: collapse;width:100%;" >
            <tr> 
            <td><img src="../lh.png" border="0" height="41" width="41" /> </td>
            <td style="text-align:center;"><font size="21px" face="Bedrock"><br>O-men</font><font size="8px" ><br>Jeans & Casuals</font> </td>
            <td style="text-align:right;"><img src="../dc.png" border="0" height="41" width="41" align="left" /> </td>
            </tr>
            </table>';
    
    
    

    $html.='<table style="border: 1px solid black;border-collapse: collapse;width:100%;align:center;" >
            <tr>
            <td style="border: 1px solid black;text-align:left;height:60px;" cellpadding="10"  colspan="2"> <br><br>To, M/s '.$COMPANY_NAME .'</td>
            <td style="border: 1px solid black;text-align:center;height:60px;"><font size="10" ><br>Invoice No. B-'.$bill_id.' <br> <br>Date: '.date('d/m/Y',strtotime($BILL_DATE)).'</font></td>
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
            
    $sqlquery="select  bi.items_id,bi.quantity,bi.rate,bi.description as 'bi_desc' from bill_items_tbl bi where bi.bill_id='.$bill_id.' ;";

    $show=mysqli_query($dbhandle,$sqlquery);
  

    $row_count=0;
	    $total_quantity=0;
	    $total_amount=0;
              while($row=mysqli_fetch_array($show)){
                    $row_count++;
		            $ratebrate=	$row['rate'];
                    $item_id=$row['items_id'];
                    
                    $item_bill_description=$row['bi_desc'];
                    
                    $item_quantity=$row['quantity'];
	                $amount=0;

    
	                $html.= '<tr >';
                    $html.= '<td>'.$row_count.'</td>';
                    $html.= '<td style="border-left: 1px solid black;text-align:left;"><font size="10" >Lot No.'. $item_id .' '. $item_bill_description.' </font></td>';
	
                    $html.= '<td style="border-left: 1px solid black;text-align:left;">'.$item_quantity.'</td>';
	
	                $html.= '<td style="border-left: 1px solid black;text-align:left;">'.$ratebrate.'</td>';
	               
	                $amount=$item_quantity*$ratebrate;
	                $html.= '<td style="border-left: 1px solid black;text-align:left;">'.$amount.'</td>';
	                $html.= '</tr>';

    
	                $total_quantity += $item_quantity;
	                $total_amount+=$amount;	
                    }
                   


                    $dummy_row=(4-$row_count)*2;
	


	 
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
			
                    $html.='<tr><td><font size="10" ><b>Transport :'. $transport_name.'</b></font></td></tr>';
				
				    $html.='<tr><td><font size="10" ><b>Parcels :'. $transport_parcels.'</b></font></td></tr>';
				    $html.='<tr><td><font size="10" ><b>LR :'. $lr.'</b></font></td></tr>';
				    $html.='<tr><td><font size="10" ><b>Booking Date : '.date('d/m/Y',strtotime($transport_date)).'</b></font></td></tr >';
				
			
				
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
                    <th style="border: 1px solid black;text-align:left;" > '.$total_amount.'</th>
          
          
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



function generatePDF(string $billNo,mysqli $dbhandle): string{
    
    global $omenNX;

    
    $sqlquery = "SELECT t.date as t_date,
    t.LR, 
    b.date,
    b.due_date,
    b.total_amount,
    c.company_name,
    c.mobile,
    t.transport_name,
    t.transport_parcels,
    t.lr,
    t.date as 'transport_date',
    b.BILL_ID
    FROM bills_tbl b,customers_tbl c,challan_transport_tbl t,challan_tbl ch 
    where b.customer_id=c.customer_id and 
          t.challan_no=ch.challan_no and 
          b.BILL_ID=ch.BILL_ID and
          ch.BILL_ID=".$billNo." ORDER BY  b.bill_id DESC";


    $show=mysqli_query($dbhandle,$sqlquery);

    
    while($row=mysqli_fetch_array($show)){
        $bill_id=0;
        $bill_id=$row['BILL_ID'];

        if(!file_exists($_SERVER['DOCUMENT_ROOT'] ."data/omenwebnx/invoice/PDF"))
                mkdir($_SERVER['DOCUMENT_ROOT'] ."data/omenwebnx/invoice/PDF",777,true);


        $filepath=$_SERVER['DOCUMENT_ROOT'] ."data/omenwebnx/invoice/PDF/$bill_id.pdf";

    $bill_details=array(
                    "bill_no"=>$bill_id,
                    "lr"=>$row['LR'],
                    "parcels"=>$row['transport_parcels'],
                    "amount"=>$row['total_amount'],
                    "path"=>$filepath,
                    "mobile"=>$row['mobile'],
                    "transport"=>$row['transport_name'],
                    "lr_date"=>$row['transport_date']
                    );
                    $bill_id=$row['BILL_ID'];
                   
                    $html = generateHtmlData($bill_id,$row['company_name'],$row['date'],$row['transport_name'],$row['transport_date'],$row['LR'],$row['transport_parcels'],);
    
    
                }

        echo $filepath;
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        $pdf->AddPage();


        // Set some content to print

        // Print text using writeHTMLCell()
        // $pdf->writeHTML(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->writeHTML($html, true, false, true, false, '');
        
        $pdf->Output($filepath, 'F');
   
        return json_encode($bill_details);

}

?>