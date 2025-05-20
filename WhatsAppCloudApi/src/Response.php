<?php

namespace WHATSAPPCLOUDAPI;

class Response{


    protected string $response;

    public function __construct(?string $response=null){
        $this->response=$response; 
    }



    public function getResponse(): string {
        return $this->response;
    }


}

?>