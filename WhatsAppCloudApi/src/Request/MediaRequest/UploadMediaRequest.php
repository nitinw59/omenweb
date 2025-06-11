<?php



namespace WHATSAPPCLOUDAPI\Request\MediaRequest;


use WHATSAPPCLOUDAPI\Request;


final class UploadMediaRequest extends Request{


	private string $file_path;
	
	private string $phone_number_id;
	
	
	public function __construct(string $file_path, string $phone_number_id, string $access_token, ?int $timeout = null){
		$this->file_path=$file_path;
		$this->phone_number_id=$phone_number_id;
		
		parent::__construct($access_token,$timeout);
		
	}



		
	public function form() : array{

		$file_type=mime_content_type($this->file_path);
		if(function_exists('curl_file_create'))
			$file=curl_file_create($this->file_path,$file_type,"B-46633");

		return ["file"=>$file,
				"type"=>$file_type,
				"messaging_product"=>"whatsapp"];
	}


	public function nodePath(): string {
		
		return $this->phone_number_id . "/media";
	}

	

}



?>