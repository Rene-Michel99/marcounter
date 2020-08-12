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
		$query = new MongoDB\Driver\Query(['_id'=>$json['_id']]);
		$rows = $manager->executeQuery("pds.activatedEvents",$query);

		$response = null;
		$tipo = null;
		foreach($rows as $row)
		{
			$response = $row->presences;
			if($row->inicio=="")
				$tipo = "Entrada";
			else
				$tipo = "Saida";
		}

		if($response!=null)
		{
			$array = array();
			for($i=0; $i<count($response); $i++)
			{
				$query = new MongoDB\Driver\Query(['_id'=>$response[$i]]);
				$rows = $manager->executeQuery("pds.usuario",$query);

				foreach ($rows as $row)
				{
					$nome = $row->nome;
					$matricula = $row->matricula;
					$perf = array("nome"=>$nome,"matricula"=>$matricula);
					array_push($array,$perf);
				}
			}
			echo json_encode(array("presences"=>$array,"tipo"=>$tipo));
		}
		else
			echo json_encode(array("presences"=>array(),"tipo"=>$tipo));

	}
	else
		echo json_encode(array("status"=>"null"));
?>