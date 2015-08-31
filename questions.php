<?php
require('./config.inc'); # Neo4j, Slim & config
  
$app = new \Slim\Slim();

/*$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => $FaceBkAccess,
    "logger" => $log,
    "secure" => false, // Not on production    
]));*/

$app->get('/:item', function ($item){
   
    switch ($item) {
        case 'all':
            $content = listquestions();            
        break;
    }

    if (isset($content)) {
	   echo json_encode($content);
    } else {
	   echo 'no content';
	}

}

);

function listquestions() {

    global $client;

    $queryString = "MATCH (c:SiteUsers)<-[Asked_to]-(a:Questions)-[Asked_by]->(b) RETURN c,a,b LIMIT 50";
    $query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
    $result = $query->getResultSet();
    
    foreach ($result as $key=>$row) {
	    $title = $row['a']->getProperty('title');
        $pol[$title]['Pfirstname'] =  $row['b']->getProperty('firstName');
        $pol[$title]['Plastname'] = $row['b']->getProperty('lastName');
        $pol[$title]['state'] = $row['b']->getProperty('state');
        $pol[$title]['postcode'] = $row['b']->getProperty('Postcode');
        $pol[$title]['questionid'] = $row['a']->getID();
        $pol[$title]['description'] = $row['a']->getProperty('description');
        $pol[$title]['source'] = $row['a']->getProperty('source');
        $pol[$title]['userid'] = $row['c']->getID();
        $pol[$title]['username'] = $row['c']->getProperty('name');
    }
        return $pol;
}


/** 
 * 
 * {"title":"my questionwfqe ","description":"long description", "source":"s", "polid":"260", "user": "5578"}
 * Create a new question
 */
$app->post('/new', function () use ($app) {    
  try {
    global $client;
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body);  

    // Create a Question Node
    $qst = $client->makeNode();
    $qst->setProperty('title', $input->title) 
        ->setProperty('description', $input->description)
        ->setProperty('source', $input->source)
        ->save();

    $qstid = $qst->getID();

    // Link it to the politician
    $polid = $client->getNode($input->polid);
    $qst->relateTo($polid, 'Asked_to')
        ->setProperty('when', date ('c')) 
        ->save();

    // Link it to the user
    $userid = $client->getNode($input->user);
    $qst->relateTo($userid, 'Asked_by')
        ->setProperty('when', date ('c')) 
        ->save();

    $qstlabel = $client->makeLabel('Questions');
    $node = $client->getNode($qst->getID());
    $labels = $node->addLabels(array($qstlabel));

    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode($input);

  } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
  }

});

/** 
 * 
 *   Question Interacive feature set
 * 
 */
$app->put('/interact/:id', function ($id) use ($app) {    
  try {
  
    echo "NOT READY";
    exit;
    global $client;
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
   
    $cypherStr = "MATCH (n:SiteUsers) where n.email ='{$email}' Return n";
    $cypher = New Everyman\Neo4j\Cypher\Query($client, $cypherStr, array('email' => $email));
    $result = $cypher->getResultSet();

    foreach ($result as $key=>$row) {
	    $id = $row['n']->getID();
	    $useraccount =  $client->getNode($id);
    } 

    if (isset($useraccount)) {

      $useraccount->setProperty('name', $input->name)
        ->setProperty('email', $input->email)
        ->setProperty('password', $passwordHash)
        ->save();

      $useraccount = array("message", "Record updated");

    } else {
	$useraccount = array("message", "No user with that email exists");
    }
    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode($useraccount);
  } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
  }

});

/** 
 * POST requests to /user/login
 *
 * login
 */
 /*
$app->post('/user/', function () use ($app) {    
  try {
    global $client;
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
    
    $email = $input->email;
    $cypherStr = "MATCH (n:SiteUsers) where n.email ='{$email}' Return n";
    $cypher = New Everyman\Neo4j\Cypher\Query($client, $cypherStr, array('email' => $email));
    $result = $cypher->getResultSet();

    foreach ($result as $key=>$row) {
	    $id = $row['n']->getID();
    	$useraccount =  $client->getNode($id);
    	$passwordhash = $row['n']->getProperty('password');
    } 

    $token = bin2hex(openssl_random_pseudo_bytes(16));

    if (password_verify($input->password, $passwordhash)) {
        $res['status'] = 'OK'; // Success!
        $res['accessToken'] = $token; // Success!
      }
    else {
        $res['status'] = 'Failed'; // Success!
        // Invalid credentials
    }
    // {"name":"username","email":"test@example.com", "password":"password"}

    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode($res);
  } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
  }

});*/

$app->run();
