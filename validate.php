<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:*");
	header("Content-type: application/json");

	$acc = null;

	foreach($_POST as $post)
		$acc = $post;

	if($acc!=null)
	{
		$acc = json_decode($acc, true);

		$manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);
		$query = new MongoDB\Driver\Query(['email'=>$email]);

		$rows = $manager->executeQuery("pds.usuario",$query);

		$res = null;
		foreach($rows as $row)
			$res = $row;

		if($res!=null)
		{
			if($res['validate']==$acc['token'])
				echo json_encode(array('status'=>'pass'));
			else
				echo json_encode(array('status'=>'incorrect'));
		}
	}
	
?>