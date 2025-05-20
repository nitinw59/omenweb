<?php

namespace WHATSAPPCLOUDAPI\Request;



use WHATSAPPCLOUDAPI\Message\Message;
use WHATSAPPCLOUDAPI\Request;


abstract class MessageRequest extends Request{



protected Message $message;


private string $from_number_id;




public function __construct(Message $message, string $access_token, string $from_number_id, ?int $timeout=null){


$this->message=$message;
$this->from_number_id=$from_number_id;

parent::__construct($access_token,$timeout);

}


public function nodePath():string {
    return $this->from_number_id."/messages";
}


}




?>