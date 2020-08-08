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
		$query = new MongoDB\Driver\Query(['email'=>$json['email']]);

		$rows = $manager->executeQuery("pds.usuario",$query);

		$acc = null;

		foreach($rows as $row)
			$acc = $row;

		if($acc->senha==$json['senha'])
		{
			$account = array(
				'_id'=>$acc->_id,
				'cargo'=>$acc->cargo,
				'nome'=>$acc->nome,
				'matricula'=>$acc->matricula,
				'email'=>$acc->email,
				'curso'=>$acc->curso,
				'ano'=>$acc->ano,
				'senha'=>$acc->senha,
				'horas'=>$acc->horas,
				'certificados'=>$acc->certificados,
				'validate'=>$acc->validate
			);
			echo json_encode($account);
		}
		else
			echo json_encode(array("status"=>"incorrect password"));
	}
	else
		echo json_encode(array("status"=>"null"));

?>