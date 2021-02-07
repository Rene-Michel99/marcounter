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

		$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB2']);
		$query = new MongoDB\Driver\Query(['_id'=>'001']);
		$rows = $manager->executeQuery("bot_translator.status",$query);

		$response = null;
		foreach ($rows as $row){
			$response = $row;
		}
		
		echo $response;
		
		
    		echo "success";
	}
	else
		echo "error";
?>
