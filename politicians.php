<?php
require('./config.inc'); # Neo4j, Slim & config
  

$app = new \Slim\Slim();


$app->get('/:item', function ($item){

    global $client;
    
    $queryString = "MATCH (a)-[:`CANIDATE_FOR`]->(b) WHERE b.title =~ '(?i).*{$item}.*' RETURN a,b LIMIT 25";
    $query = new Everyman\Neo4j\Cypher\Query($client, $queryString, array('item' => $item));
    $result = $query->getResultSet();
    
    foreach ($result as $key=>$row) {
    $title = $row['b']->getProperty('title');
    $pol[$title]['fname'] =  $row['a']->getProperty('firstName');
    $pol[$title]['lname'] = $row['a']->getProperty('lastName');
    $pol[$title]['state'] = $row['a']->getProperty('state');
    $pol[$title]['postcode'] = $row['a']->getProperty('Postcode');
    $pol[$title]['Positionid'] = $row['b']->getID();
    $pol[$title]['Polid'] = $row['a']->getID();; 	
    }
    if (isset($pol)) {
	   echo json_encode($pol);
    } else {
	   echo 'Nothing found';
	}

}

);

$app->run();
