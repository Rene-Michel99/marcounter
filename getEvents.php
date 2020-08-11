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
		$query = new MongoDB\Driver\Query(['tipo'=>$json['tipo']]);

		$rows = $manager->executeQuery("pds.eventos",$query);

		$eventos = [];
		foreach ($rows as $row)
			array_push($eventos,$row);

		echo json_encode(array("eventos"=>$eventos));
	}
	else
		echo json_encode(array("status"=>"null"));
?>