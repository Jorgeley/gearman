<?php

/**
 * this is the worker (php job server waiting for calls)
 * you have to run it from the command line: php worker.php
 * keep it running in a terminal, open another one and execute the client: php client.php
 * https://www.php.net/manual/en/book.gearman.php
 */

$worker = new GearmanWorker();

try{ //you can check that a socket is created, run in the command line: netstat
	$worker->addServer('127.0.0.1', 4730);
}catch(GearmanException $e){
	echo $e->getMessage();
}

//creates a job to simulate a high costly function
$worker->addFunction('fakeLongTask', function($job){
	echo "worker called\n"; //this is output if the client calls the job
	for ($i=1; $i<15; $i++){ //just faking a long task
		sleep(1);
		//this is sent to the client as numerator and denominator
		$job->sendStatus(15, $i);
	}
	echo "job done\n";
	return 'done';
});

while ($worker->work()); //start the worker and listen for clients
