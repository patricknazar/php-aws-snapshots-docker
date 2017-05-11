<?php
require_once('snapshots.php');
require_once('functions.php');

$env = getEnv();
$volumes = $env['volumes'];

// prepare for aws calls
setAwsEnv();

$snapshots = new snapshots($volumes);
$snapshots->run();