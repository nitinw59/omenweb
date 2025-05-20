<?php



namespace WHATSAPPCLOUDAPI;


class WhatsAppApiAPP{
	
	
	
	public const APP_FROM_NUMBER_ID="115321454828526";
	public const APP_ACCESS_CODE="EAAQ6kxBNYfkBAGvkBAeYrJQO34EY96kVmZAPLRJTtqXc5zE7y3aUZCvnlV1yfWlmdv5AJrKZCWZAmcxUOZAZA80SDysUIV4BsqpqL9mBP5N7sDP2c4LZCRQehvo8oS17F1bOaUIqCkl4KlwxJITCz5dfgkUsPB3dn4sMp8d1vCUWi0yBqTsbwVU";
	
	
	
	
	
	
	
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