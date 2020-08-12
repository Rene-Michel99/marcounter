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

		$evento = array(
			'_id'=>uniqid(),
			'tipo'=>$json['tipo'],
			'cadastradoPor'=>$json['cadastradoPor'],
			'tema'=>$json['tema'],
			'descricao'=>$json['descricao'],
			'cursos'=>$json['cursos'],
			'horas'=>$json['horas'],
			'inicio'=>$json['inicio'],
			'fim'=>$json['fim'],
			'data'=>$json['data']
		);

		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->insert($evento);

		$res = $manager->executeBulkWrite("pds.eventos",$bulk);
		echo json_encode(array("status"=>"success"));
	}
	else
		echo json_encode(array("status"=>"null"));
?>