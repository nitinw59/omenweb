<?php
// * @param string $dest Destination where to send the document. It can take one of the following values:
//  <ul><li>I: send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.</li><li>
//D: send to the browser and force a file download with the name given by name.</li><li>
//F: save to a local server file with the name given by name.</li><li>
//S: return the document as a string (name is ignored).</li><li>
//FI: equivalent to F + I option</li><li>
//FD: equivalent to F + D option</li><li>
//E: return the document as base64 mime multi-part email attachment (RFC 2045)</li></ul>


include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");

require_once('../tcpdf_include.php');

require_once realpath("../WhatsAppCloudApi/vendor/autoload.php");

use WHATSAPPCLOUDAPI\WhatsAppApi;
use WHATSAPPCLOUDAPI\Message\Template\Component;


$bill_id=$_POST["bill_id"];

include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
include($_SERVER['DOCUMENT_ROOT']."/$omenNX/mysqlconnectdb.php");

$bill_details=json_decode(generatePDF($bill_id,$dbhandle),true);

$send_status= sendWhatsAppMessage($bill_details['mobile'],$bill_details['path'],$bill_details['lr'],$bill_details['parcels'],$bill_details['amount'],$bill_details['bill_no'],$bill_details['lr_date'],$bill_details['transport']);

if($send_status==true)
echo "\n Bill Sent Success.\n Check Message Log For Details";
















function sendWhatsAppMessage(string $phoneNo,string $filepath,string $lr, string $parcels, string $amount,string $bill_no,string $lr_date,string $transport): bool{
    if($lr==null)
    $lr="0";
    $api = new  WhatsAppApi(["graph_version"=>"v16.0",]);
    $response=$api->uploadMedia($filepath);
    $decoded_response=json_decode($response->getResponse()) ;
    
    $media_id=$decoded_response->id;
    

    $language="en_US";

    $template_name="bills";
    
    $header_param=["type"=>"document",
                    "document"=>["id"=>$media_id,"filename"=>"".$transport."-".$lr]
                ];
    $body_param=[
            ["type"=>"text",
                "text"=>$transport."-".$lr],
            ["type"=>"text",
                "text"=>$parcels],
            ["type"=>"text",
                "text"=>$bill_no],
            ["type"=>"text",
                "text"=>$amount],
            ["type"=>"text",
                "text"=>$lr_date]
            




            ];

           

    $component=new Component($header_param,$body_param);

            
    $response=$api->sendTemplate($phoneNo,$template_name,$language,$component);
           
    
    $decoded_response=json_decode($response->getResponse(),true);
            
    $receiver_no=$decoded_response['contacts'][0]['wa_id'];
    $wamid=$decoded_response['messages'][0]['id'];   
    
    $return_response=logMessage($receiver_no,$wamid,$lr,$amount,$bill_no);

    $response=$api->deleteMedia($media_id);

   
    return $return_response;


}


function logMessage(string $phoneNo,string $wamid,string $lr,  string $amount,string $bill_no): bool{
    global $dbhandle;
    $sqlquery="INSERT INTO WHATSAPPMESSAGE_LOGGER 
                (message_date,wamid,receiverno,bill_id,AMOUNT,LR)
                VALUES
                ('".date('Y/m/d')."','".$wamid."','".$phoneNo."','".$bill_no."',".$amount.",'".$lr."')";
                $STATUS=mysqli_query($dbhandle,$sqlquery);
                return $STATUS;

}



?>