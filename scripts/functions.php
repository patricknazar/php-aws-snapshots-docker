<?php

define("ENV_FILE", "env.dat");
global $AWS_VARS;
$AWS_VARS = ['AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', 'AWS_DEFAULT_REGION'];

// parse input for validity and save for later use
function parseEnv() {
	global $AWS_VARS;

	// check aws vars
	foreach ($AWS_VARS as $name) {
		if ( !isset($_SERVER[$name]) ) {
			echo "Please provide all aws vars\n";
			exit(1);
		}
	}

	$volumes = [];

	// get conf vars
	foreach($_SERVER as $key => $value) {
		if ( preg_match('/^VOL_(.*)$/', $key, $matches) ) {
			$parts = explode(',', $value);
			if ( count($parts) != 3 ) {
				echo "Error in definition for $key = $value\n";
				exit(1);
			} else {
				$volid = $matches[1];
				echo "Reading volume '$volid': \n";
				var_dump($parts);
				$volumes[$volid] = array(
					'snapshots' => $parts[0],
					'interval' => $parts[1],
					'description' => $parts[2]
					);
			}
		}
	}

	if ( !count($volumes) ) {
		echo "Please specify at least on volume with VOL_{aws-volume-id}=snapshots,interval,description\n";
		exit(1);
	}

	$save = ['env' => $_SERVER, 'volumes' => $volumes];

	file_put_contents( ENV_FILE , serialize($save) );

	return $volumes;

}

// return env vars from file
function getOurEnv() {
	return unserialize( file_get_contents( ENV_FILE ) );
}

// set AWS vars from file
function setAwsEnv() {
	global $AWS_VARS;
	$env = getOurEnv();

	foreach ($AWS_VARS as $name) {
		if ( isset($env['env'][$name]) ) putenv($name.'='.$env['env'][$name]);
	}
}