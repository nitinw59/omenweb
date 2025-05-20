<?php



namespace WHATSAPPCLOUDAPI\Request\MediaRequest;


use WHATSAPPCLOUDAPI\Request;


final class DeleteMediaRequest extends Request{


	private string $media_id;
	
			
	public function __construct(string $media_id, string $access_token, ?int $timeout = null){
		$this->media_id=$media_id;
		parent::__construct($access_token,$timeout);
	}



	

	public function nodePath(): string {
		
		return "$this->media_id/";
	}

	

}



?>