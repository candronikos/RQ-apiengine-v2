<?php

  require_once('vendor/autoload.php');

  $dotenv = new Dotenv\Dotenv(__DIR__);
  $dotenv->load(); 

  $n4host = getenv('n4host');
  $n4port = getenv('n4port');
  $n4user = getenv('n4user');
  $n4password = getenv('n4password');

  $FaceBkAccess = getenv('fbaccess');

  $app_id = getenv('app_id');
  $app_secret = getenv('app_secret');

$client = New Everyman\Neo4j\Client($n4host, $n4port);
$client->getTransport()
        ->setAuth($n4user, $n4password);


  use Monolog\Logger;
  use Monolog\Handler\StreamHandler;

  // create a log channel
  $log = new Logger('name');
  $log->pushHandler(new StreamHandler('/tmp/log', Logger::WARNING));
 
  /*  spl_autoload_register(function ($class) {
        include_once 'HttpJsonClass.php';
    });*/
  
