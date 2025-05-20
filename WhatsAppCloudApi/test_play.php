<?php


$result='{"messaging_product":"whatsapp","contacts":[{"input":"8087978196","wa_id":"918087978196"}],"messages":[{"id":"wamid.HBgMOTE4MDg3OTc4MTk2FQIAERgSOTYzMDZCNEFBMTcxMkFFN0U5AA=="}]}';



$decoded_response=json_decode($result,true) ;

print_r($decoded_response);

print_r($decoded_response['contacts'][0]['wa_id']);
print_r($decoded_response['messages'][0]['id']);



?>