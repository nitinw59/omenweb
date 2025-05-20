<?php

namespace WHATSAPPCLOUDAPI\Message;

use WHATSAPPCLOUDAPI\Message\Template\Component;

final class TemplateMessage extends Message{


    protected string $type="template";

    private string $name;

    private string $language;

    private ?Component $component;


    public function __construct(string $to,string $name,string $language="en_US",?Component $component=null){

        $this->name=$name;
        $this->language=$language;
        $this->component=$component;

        parent::__construct($to);
    }



    public function name(): string{
        return $this->name;
    }



    public function language(): string{
        return $this->language;
    }


    public function header():array{
        return $this->component?
                $this->component->header():[];
    }

    public function body():array{
        return $this->component?
                $this->component->body():[];
    }


    public function buttons(): array{
        return $this->component?
            $this->component->buttons():[];
    }


} 

?>