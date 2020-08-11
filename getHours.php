<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:*");
	header("Content-type: application/json");

	$json = null;

	foreach ($_POST as $post)
		$json = $post;

	if($json!==null)
	{
		$json = json_decode($json,true);

		$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);
		$query = new MongoDB\Driver\Query(['_id'=>$json['_id']]);

		$rows = $manager->executeQuery("pds.usuario",$query);

		$certificados = null;

		foreach ($rows as $row)
			$certificados = $row->certificados;

		if($certificados!=null)
		{
			$response = [];
			foreach ($item as $certificados)
			{
				if($item->categoria==$json['categoria'])
					array_push($response,$item);
			}

			echo json_encode($response);
		}
		else
			echo json_encode(array("status"=>"none"));
	}
	else
		echo json_encode(array("status"=>"null"));
?>