<?php


namespace WHATSAPPCLOUDAPI\http;



interface clientHandlerInterface {
	
	
	
	
	
	
	
	public function postJsonData(string $url, array $body, array $header, int $timeout) : RawResponse;
	
	
	public function postFormData(string $url, array $form, array $header, int $timeout):RawResponse;
	
	
	public function get(string $url, array $header, int $timeout): RawResponse;
	
	
	
}







?>