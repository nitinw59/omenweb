<?php

namespace WHATSAPPCLOUDAPI\Message\Template;



final class Component{


private array $header;
private array $body;
private array $buttons;

public function __construct(array $header=[], array $body=[], array $buttons=[]){
    $this->header=$header;
    $this->body=$body;
    $this->buttons=$buttons;
}


public function header(): array{
    return $this->header;
}

public function body():array{
    return $this->body;
}

public function buttons(): array{
    return $this->buttons;
} 


}

?>