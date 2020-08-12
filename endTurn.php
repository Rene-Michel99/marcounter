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

			//atribuir as horas a todos os usuarios presentes
			$query = new MongoDB\Driver\Query(['_id'=>$response->_id]);
			$rows = $manager->executeQuery("pds.eventos",$query);

			$event = null;
			foreach ($rows as $row)
				$event = $row;

			for($i=0; $i<count($response->presences); $i++)
			{
				$query = new MongoDB\Driver\Query(['_id'=>$response->presences[$i]]);

				$res = $manager->executeQuery("pds.usuario",$query);
				$perf = null;
				foreach ($res as $r)
					$perf = $r;

				$perf->horas = (int)$perf->horas + (int)$event->horas;
				$perf->horas = (string)$perf->horas;
				$bulk = new MongoDB\Driver\BulkWrite;
				$bulk->update(['_id'=>$perf->_id],$perf);
				$res = $manager->executeBulkWrite("pds.usuario",$bulk);
			}

			echo json_encode(array("status"=>"success"));
		}
		else
			echo json_encode(array("status"=>"none"));
	}
	else
		echo json_encode(array("status"=>"null"));
?>