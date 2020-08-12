<?php
	date_default_timezone_set('America/Sao_Paulo');
    echo date('d/m/Y')."<br>";
    echo date('H:i');

    $manager = new MongoDB\Driver\Manager($_ENV['URL_MONGODB']);
    $query = new MongoDB\Driver\Query([]);

    $rows = $manager->executeQuery("pds.eventos",$query);

    $response = [];
    $today = date('d/m/Y');
    $actual_hour = date('H:i');

    foreach ($rows as $row)
    	array_push($response,$row);

    if($response!=[])
    {
    	foreach ($response as $res)
    	{
    		if($res->data==$today && $res->inicio==$actual_hour)
    		{
    			$bulk = new MongoDB\Driver\BulkWrite;
    			
    			$event = array(
    				'_id'=>$res->_id,
    				'cadastradoPor'=>$res->cadastradoPor,
    				'presences'=>[],
    				'inicio'=>"",
    				'fim'=>""
    			);

    			$bulk->insert($event)

    			$res = $manager->executeBulkWrite("pds.activatedEvents",$bulk);

    			echo "Event: ".$res->tema." initialized";
    		}
    	}
    }
?>