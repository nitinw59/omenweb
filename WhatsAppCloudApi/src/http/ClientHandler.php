<?php

namespace WHATSAPPCLOUDAPI\http;




class ClientHandler{
	
	public function postJsonData(string $url, array $body, array $header, int $timeout) : RawResponse{
        $header=array_merge(["Content-Type: application/JSON"],$header);
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($body));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER ,true);
        
        
        $result=curl_exec($ch);
       
      
        curl_close($ch);
        if ($result==null)
            $result="0";
       
        return new RawResponse($result);

        


    }
	
	public function postFormData(string $url, array $form, array $header, int $timeout):RawResponse{
        
        
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$form);
        $result=curl_exec($ch);
        curl_close($ch);
        if ($result==null)
            $result="0";
        return new RawResponse($result);


    }
	
	public function get_delete(string $url, array $header, int $timeout): RawResponse{
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER ,true);

        $result=curl_exec($ch);
       
        curl_close($ch);
        return new RawResponse($result);



    }

}

?>