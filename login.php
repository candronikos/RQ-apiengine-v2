<?php

session_start();

$config = 'config.php';
require_once("Hybrid/Auth.php");

try{
	$hauth = new Hybrid_Auth( $config );
	$twitter = $hauth->authenticate( "Google");

	$twitter_userp = $twitter->getUserProfile();
	print_r ($twitter_userp);
	
	$account_settings = $twitter->api()->get('account/settings.json');

	$twitter->logout();
}
catch (Exception $e) {
	echo $e->getMessage();
}
