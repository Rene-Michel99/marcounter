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
		
		echo $json["status"];

		$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);
		
		$response = [
		"_id"=>"001",
		"status"=>$json["status"],
		"activity_now"=>json["now"],
		"list_success"=>json["list_suc"],
		"fails_list"=>json["list_fails"],
		"fails_translations"=>json["fail_tr"],
		"success_translations"=>json["suc_tr"]
		];
    
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->update(['_id'=>$response->_id],$response);

		$res = $manager->executeBulkWrite("bot_translator.status",$bulk);
    		echo "success";
	}
	else
		echo "error";
?>
