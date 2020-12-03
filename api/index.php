<?php

require 'vendor/autoload.php';
$app = new Slim\App();

// Register routes
require __DIR__ . '/routes.php';

$app->run();