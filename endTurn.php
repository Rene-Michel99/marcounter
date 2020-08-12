<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:*");
	header("Content-type: application/json");

	$json = null;
	foreach($_POST as $post)
		$json = $post;

	if($json!=null)
	{
		$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);
		$query = new MongoDB\Driver\Query(['_id'=>$json['_id']]);
		$rows = $manager->executeQuery("pds.activatedEvents",$query);

		$response = null;
		foreach ($rows as $row)
			$response = $row;

		if($response!=null)
		{
			if($response->inicio=="")
				$response->inicio = "ended";
			else
				$response->fim = "ended";

			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->update(['_id'=>$response->_id],$response);

			$res = $manager->executeBulkWrite("pds.activatedEvents",$bulk);

			echo json_encode(array("status"=>"success"));
		}
		else
			echo json_encode(array("status"=>"none"));
	}
	else
		echo json_encode(array("status"=>"null"));
?>