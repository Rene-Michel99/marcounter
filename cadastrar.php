
<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:*");
	header("Content-type: application/json");

	session_start();

	$json = $_POST['json'];

	$nome = $json['nome'];
	$matricula = $json['matricula'];
	$email = $json['email'];
	$curso = $json['curso'];
	$ano = $json['ano'];
	$senha = $json['senha'];
	$token = uniqid();

	if($nome && $matricula && $email && $curso && $ano && $senha)
	{
		//$manager = new MongoDB\Driver\Manager("mongodb+srv://marcounter:s9y7ht5T7s1m7ihY@pds.twdrk.mongodb.net/pds?retryWrites=true&w=majority");
		$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);

		//checa se o email já existe
		$query = new MongoDB\Driver\Query(['email'=>$email]);
		//checa se a matricula já existe
		$query2 = new MongoDB\Driver\Query(['matricula'=>$matricula]);

		$rows = $manager->executeQuery("pds.usuario",$query);
		$rows2 = $manager->executeQuery("pds.usuario",$query2);
		$count = 0;
		foreach ($rows as $row)
			$count++;
		foreach ($rows2 as $row)
			$count++;

		if($count==0)
		{
			//$input = 'python Send.py "'.$email.'" "'.$nome.'" "'.$token.'"';
			$output = "email sent";
			//exec($input,$output);
			//$output = implode($output);
			if ($output=="email sent")
			{
				//echo json_encode("status"=>"Email enviado com sucesso!");

				$bulk = new MongoDB\Driver\BulkWrite;
				$account = array(
					'_id'=>uniqid(),
					'nome'=>$nome,
					'cargo'=>"aluno",
					'matricula'=>$matricula,
					'email'=>$email,
					'curso'=>$curso,
					'ano'=>$ano,
					'senha'=>$senha,
					'horas'=>0,
					'certificados'=>[],
					'validate'=>$token);
				$bulk->insert($account);

				$res = $manager->executeBulkWrite("pds.usuario",$bulk);
				$_SESSION['acc'] = $account;
			}
		}
		else
			echo "Email ou matricula ja cadastrado!";
	}
	else
		echo "Campos não preenchidos";	
?>