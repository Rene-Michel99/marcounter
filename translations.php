<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:*");
	header("Content-type: application/json");

	$json = null;
	foreach($_POST as $post)
		$json = $post;

	if($json!=null)
	{
		$json = json_decode($json,true);

		$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);
		$query = new MongoDB\Driver\Query(['_id'=>'001']);
		$rows = $manager->executeQuery("bot_translator.status",$query);

		$response = null;
		foreach ($rows as $row)
			$response = $row;
		
		echo $response;
		
		$response["status"] = $json["status"];
		$response["activity_now"] = json["now"];
		$response["list_success"] = json["list_suc"];
		$response["fails_list"] = json["list_fails"];
		$response["fails_translations"] = json["fail_tr"];
		$response["success_translations"] = json["suc_tr"];
    
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->update(['_id'=>$response->_id],$response);

		$res = $manager->executeBulkWrite("bot_translator.status",$bulk);
    		echo "success";
	}
	else
		echo "error";
?>
