<?php
    include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");
 
  $action=$_POST["action"];
 
  if($action=="getAssets"){
	$debitorList;
	$debitorListCount=0;
    
    $sqlquery="select SUM(B.TOTAL_AMOUNT) AS TOTALAMOUNT,COMPANY_NAME FROM bills_tbl B, customers_tbl C WHERE B.customer_id=C.customer_id and c.archive_state=0 group by COMPANY_NAME;";
        $show=mysqli_query($dbhandle,$sqlquery);
        while($row=mysqli_fetch_array($show)){
                $debitor["COMPANY_NAME"]=$row["COMPANY_NAME"];
                $debitor["TOTALAMOUNT"]=$row["TOTALAMOUNT"];
                $debitor["TOTALPAYMENT"]=0;
                
                $debitorList[$debitorListCount]=$debitor;
                $debitorListCount++;
        }

	
	foreach($debitorList as &$debitor){
	    $sqlquery="select SUM(cr.AMOUNT) AS TOTALPAYMENT FROM credits_tbl cr,  customers_tbl C WHERE cr.customer_id=C.customer_id AND C.COMPANY_NAME='".$debitor["COMPANY_NAME"]."' ";
        $show=mysqli_query($dbhandle,$sqlquery);
  	    $row=mysqli_fetch_array($show);
	    if($row['TOTALPAYMENT']==null)
		    	$row['TOTALPAYMENT']=0;
		
        $debitor["TOTALPAYMENT"]=$row['TOTALPAYMENT'];
	  
	
   
    }
    echo json_encode($debitorList);
  }elseif($action=="getLiability"){
    $creditorList;
    $creditorListCount=0;
      
      $sqlquery="SELECT SUM(AMOUNT) as TOTALAMOUNT, m.COMPANY_NAME FROM merchant_bills_tbl MB , fabric_merchants_tbl M WHERE MB.FABRIC_MERCHANTS_ID=M.FABRIC_MERCHANTS_ID group by m.COMPANY_NAME;";
          $show=mysqli_query($dbhandle,$sqlquery);
          while($row=mysqli_fetch_array($show)){
                  $creditor["COMPANY_NAME"]=$row["COMPANY_NAME"];
                  $creditor["TOTALAMOUNT"]=$row["TOTALAMOUNT"];
                  $creditor["TOTALPAYMENT"]=0;
                  
                  $creditorList[$creditorListCount]=$creditor;
                  $creditorListCount++;
          }
  
    
    foreach($creditorList as &$creditor){
        $sqlquery="SELECT sum(AMOUNT) AS TOTALPAYMENT,M.COMPANY_NAME FROM debits_tbl D, fabric_merchants_tbl M WHERE M.FABRIC_MERCHANTS_ID=D.FABRIC_MERCHANTS_ID AND M.COMPANY_NAME='".$creditor["COMPANY_NAME"]."'; ";
          $show=mysqli_query($dbhandle,$sqlquery);
          $row=mysqli_fetch_array($show);
        if($row['TOTALPAYMENT']==null)
            $row['TOTALPAYMENT']=0;
      
          $creditor["TOTALPAYMENT"]=$row['TOTALPAYMENT'];
      
    
     
      }
      echo json_encode($creditorList);
    }
?>
