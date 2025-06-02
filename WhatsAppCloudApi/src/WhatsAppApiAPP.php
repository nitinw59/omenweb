<?php



namespace WHATSAPPCLOUDAPI;


class WhatsAppApiAPP{
	
	
	
	public const APP_FROM_NUMBER_ID="";
	public const APP_ACCESS_CODE="";
	
	
	
	
	
	
	
	protected string $from_phone_number_id;
	
	protected string $access_token;
	
	
	
	public function __construct(?string $from_phone_number_id=null,?string $access_token=null){
		
	$this->from_phone_number_id=$from_phone_number_id??  self::APP_FROM_NUMBER_ID;
	$this->access_token=$access_token?? self::APP_ACCESS_CODE;
		
	}
	
	
	
	public function accessToken() : string{
		return $this->access_token;
	}
	
	
	public function fromPhoneNumberId(): string{
		return $this->from_phone_number_id;
	}
	
	
	
	
	
	
	
	
	
	
}



?>