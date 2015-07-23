<?php
require('./config.inc'); # Neo4j, Slim & config
  

$app = new \Slim\Slim();


$app->get('/hello/:name', function ($name){

    global $client;
    
    $queryString = "MATCH (n:SiteUsers) Where n.name = '{$name}' Return n";
    $query = new Everyman\Neo4j\Cypher\Query($client, $queryString, array('name' => $name));
    $result = $query->getResultSet();
    
    foreach ($result as $key=>$row) {
	$id = $row['n']->getID(); 	
	$userarray[$id]['name'] = $row['n']->getProperty('name'); 	
	$userarray[$id]['email'] = $row['n']->getProperty('email'); 	
//        print_r ($row['n']);
    }
    
    echo json_encode($userarray);

});



/** 
 * POST requests to /user
 *
 * Create a new user
 */
$app->post('/user', function () use ($app) {    
  try {
    global $client;
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    $passwordHash = password_hash($input->password, PASSWORD_DEFAULT);
    // {"name":"username","email":"test@example.com", "password":"password"}
 
    $useraccount =  $client->makeNode();

    $useraccount->setProperty('name', $input->name)
        ->setProperty('email', $input->email)
        ->setProperty('password', $input->password)
        ->save();

$userlabel = $client->makeLabel('SiteUsers');
$node = $client->getNode($useraccount->getID());

$labels = $node->addLabels(array($userlabel));

    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode($input);
    echo json_encode($useraccount->getID);
  } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
  }

});

/** 
 * POST requests to /user
 *
 * Create a new user
 */
$app->post('/user/update/:email', function ($email) use ($app) {    
  try {
    global $client;
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    $passwordHash = password_hash($input->password, PASSWORD_DEFAULT);
    // {"name":"username","email":"test@example.com", "password":"password"}

    $cypherStr = "MATCH (n:SiteUsers) where n.email ='{$email}' Return n";
    $cypher = New Everyman\Neo4j\Cypher\Query($client, $cypherStr, array('email' => $email));
    $result = $cypher->getResultSet();


    foreach ($result as $key=>$row) {
	$id = $row['n']->getID();
	$useraccount =  $client->getNode($id);
    } 

    $useraccount->setProperty('name', $input->name)
        ->setProperty('email', $input->email)
        ->setProperty('password', $passwordHash)
        ->save();

    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode($id);
    echo json_encode($useraccount);
  } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
  }

});

$app->run();
