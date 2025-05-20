<?php

namespace WHATSAPPCLOUDAPI;

use WHATSAPPCLOUDAPI\Request\MediaRequest\UPloadMediaRequest;
use WHATSAPPCLOUDAPI\Request\MediaRequest\DeleteMediaRequest;
use WHATSAPPCLOUDAPI\Message\Template\Component;
use WHATSAPPCLOUDAPI\Request\MessageRequest\TemplateMessageRequest;



class WhatsAppApi{



protected  WhatsAppApiAPP $app;
protected Client $client;
protected ?int $timeout=60;


public function __construct(array $config){

$this->app= new WhatsAppApiAPP();

$this->client= new Client($config['graph_version'],null);


}



public function uploadMedia(string $filepath): Response{

    $request= new UPloadMediaRequest($filepath,
                                $this->app->fromPhoneNumberId(),
                                $this->app->accessToken(),
                                60);

    return $this->client->uploadMedia($request);


}



public function deleteMedia(string $media_id): Response{

    $request= new DeleteMediaRequest($media_id,
                                $this->app->accessToken(),
                                60);

    return $this->client->deleteMedia($request);


}



public function sendTemplate(string $to,string $template_name, string $language='en_US', ?Component $component=null): Response{
   
    $message= new Message\TemplateMessage($to,$template_name,$language,$component);
    $request= new TemplateMessageRequest($message,
                                                $this->app->accessToken(),
                                                $this->app->fromPhoneNumberId(),
                                                60);
    return $this->client->sendMessage($request);



}


}


?>