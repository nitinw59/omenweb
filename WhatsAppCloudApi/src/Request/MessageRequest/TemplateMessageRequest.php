<?php


namespace WHATSAPPCLOUDAPI\Request\MessageRequest;
 

use WHATSAPPCLOUDAPI\Request\MessageRequest;



final class TemplateMessageRequest extends MessageRequest{

    public function body(): array{


        $body =[
            "messaging_product"=> $this->message->messaging_product(),
            "to"=>$this->message->to(),
            "type"=>$this->message->type(),
            "template"=>[
                "name"=>$this->message->name(),
                "language"=>["code"=>$this->message->language()],
                "components"=>[]

            ]
        ];



        
        if($this->message->header()){
            $body["template"]["components"][]=[
                "type"=>"header",
                "parameters"=>[$this->message->header()],
            ];
        }


        if($this->message->body()){
            $body["template"]["components"][]=[
                "type"=>"body",
                "parameters"=>$this->message->body(),
            ];
        }
        
        
        return $body;


    }




}

?>