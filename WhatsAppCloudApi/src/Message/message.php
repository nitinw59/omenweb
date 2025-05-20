<?php


namespace WHATSAPPCLOUDAPI\Message;

abstract class Message{

private String $messaging_product="whatsapp";
private string $to;
private string $recepient_type="indiviual";

protected string $type;


public function __construct(string $to) {
    $this->to=$to;

}


public function recepient_type (): string {
    return $this->recepient_type;
} 

public function to():string {
    return $this->to;
}


public function type():string {
    return $this->type;
}

public function messaging_product(): string {
    return $this->messaging_product;
}

}


?>