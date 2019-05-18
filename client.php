<?php

$client = new GearmanClient();

$client->addServer('localhost', 4730);

//call the server to run 'fakeLongTask' in background. (check the worker output)
$job = $client->doBackground('fakeLongTask', 'testing gearman');

do{	//[0]=>(boolean)job known, [1]=>(boolean)job running, [2]=>numerator, [3]=>denominator
	$status = $client->jobStatus($job); //retrieves the status sent by the server
	//calculates the percentage executed (check the worker SendStatus call)
	if (is_numeric($status[3]) && is_numeric($status[2]) && $status[2] != 0){
		echo round($status[3]/$status[2]*100, 1) . "%\n";
	}
}while($status[0]); //if job is still known, keeps getting status and outputting
