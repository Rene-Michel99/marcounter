<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:*");
	header("Content-type: application/json");

	$json = null;

	foreach ($_POST as $post)
		$json = $post;

	if($json!=null)
	{
		$json = json_decode($json,true);

		$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);
		$query = new MongoDB\Driver\Query(['_id'=>$json['qrcode']]);

		$rows = $manager->execute("pds.activatedEvents",$query);

		$response = null;
		foreach ($rows as $row)
			$response = $row;

		if($response!=null)
		{
			$bulk = new MongoDB\Driver\BulkWrite;

			array_push($response->presences,$json['_id']);

			$bulk->update(['_id'=>$json['qrcode']],$response);

			$res = $manager->executeBulkWrite("pds.activatedEvents",$bulk);

			echo json_encode(array("status"=>"success"));
		}
		else
			echo json_encode(array("status"=>"none"));
	}
	else
		echo json_encode(array("status"=>"null"));
?>