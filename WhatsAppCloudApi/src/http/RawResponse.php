<?php

namespace WHATSAPPCLOUDAPI\http;

use WHATSAPPCLOUDAPI\Response;

class RawResponse extends Response{


    public function __construct(?string $response=null){
        parent::__construct($this->response=$response); 
    }




}

?>