<?php




namespace WHATSAPPCLOUDAPI;


abstract class Request
{
	
	
	public const DEFAULT_REQUEST_TIMEOUT=60;
	
	private string $access_token;
	
	private int $timeout;
	
	
	
	public function __construct(string $access_token,?int $timeout=null){
		$this->access_token=$access_token;
		$this->timeout=$timeout ?? static::DEFAULT_REQUEST_TIMEOUT;
	}
	
	
	public function headers() : array{
	
	return array("Authorization:  Bearer $this->access_token");
	
	}
	
	
	
	public function timeout() : int{
		
		return $this->timeout;
	}
	
	
}








?>