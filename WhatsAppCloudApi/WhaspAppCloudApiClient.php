<?php

namespace WHATSAPPCLOUDAPI;


class WhatsAppCloudApiClient{

    protected string $from_phone_no_id;

    protected string $access_code;

    public function __construct(?string $from_phone_no_id=null, ?string $access_code=null){
        $this->$from_phone_no_id=$from_phone_no_id;
        $this->$access_code=$access_code;
    }

    
    
    public function getAccessCode(): string{
        return $this->$access_code;
    }


    public function getPhoneNoId(): string{
        return $this->$from_phone_no_id;
    }

}

?>