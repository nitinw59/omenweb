<?php




namespace WHATSAPPCLOUDAPI;

use WHATSAPPCLOUDAPI\http\ClientHandler;
use WHATSAPPCLOUDAPI\Message\Template\Component;




class Client{
	
	
	public const BASE_GRAPH_URL="https://graph.facebook.com";
	
	
	protected ClientHandler $handler;
	protected string $graph_version;
	
	public function __construct(string $graph_version,?ClientHandler $handler=null){
		$this->handler=$handler ?? $this->defaultHandler();
		$this->graph_version=$graph_version;
		
		
	}
	
	
	
	
	
	
	public function sendMessage(Request $request) : Response{
		$raw_response= $this->handler->postJsonData(
		$this->buildRequestUri($request->nodePath()),
		$request->body(),
		$request->headers(),
		$request->timeout()		
		);
		
		return $raw_response;
		
	}
	
	
	
	public function uploadMedia(Request\MediaRequest\UploadMediaRequest $request) : Response{
		$raw_response= $this->handler->postFormData(
		$this->buildRequestUri($request->nodePath()),
		$request->form(),
		$request->headers(),
		$request->timeout()		
		);
		
		return $raw_response;
		
	}
	

	
	public function deleteMedia(Request\MediaRequest\DeleteMediaRequest $request) : Response{
		$raw_response= $this->handler->get_delete(
		$this->buildRequestUri($request->nodePath()),
		$request->headers(),
		$request->timeout()		
		);
		
		return $raw_response;
		
	}
	
	
	
	
	private function defaultHandler(): ClientHandler{
		return new ClientHandler();
	}
	
	
	private function buildBaseUri(): string{
		return self::BASE_GRAPH_URL ."/". $this->graph_version;
	}
	
	
	private function buildRequestUri(string $nodepath): string{
	
		return $this->buildBaseUri() ."/". $nodepath; 
	
	}
	
	
	
}


?>