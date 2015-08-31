<?php

require('./config.inc');

global $client;


/**
	Setup User & unique constraint on email
*/
$user = $client->makeNode();

$hash = password_hash('defaultadmin', PASSWORD_DEFAULT);

$user->setProperty('name', 'defaultadmin')
	->setProperty('email', 'root@localhost.localdomain')
	->setProperty('password', $hash)
	->save();

$userlabel = $client->makeLabel('SiteUsers');
$node = $client->getNode($user->getID());
$labels = $node->addLabels(array($userlabel));

$cypherStr = 'CREATE CONSTRAINT ON (n:SiteUsers) Assert n.email is UNIQUE';
$cypher = new Everyman\Neo4j\Cypher\Query($client, $cypherStr);
$result = $cypher->getResultSet();
