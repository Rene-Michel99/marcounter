<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:*");
	header("Content-type: application/json");

	$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);
	$query = new MongoDB\Driver\Query([]);

	$rows = $manager->executeQuery("pds.eventos",$query);

	$eventos = [];
	foreach ($rows as $row)
		array_push($eventos,$row);

	echo json_encode(array("eventos"=>$eventos));
?>