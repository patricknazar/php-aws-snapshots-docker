<?php
require_once('snapshots.php');
require_once('functions.php');

chdir("/scripts");

$env = getOurEnv();
$volumes = $env['volumes'];

// prepare for aws calls
setAwsEnv();

$snapshots = new snapshots($volumes);
$snapshots->run();