<?php

require_once ('./config.inc');

$fb = new Facebook\Facebook([
	'app_id' => $app_id,
	'app_secret' => $app_secret,
	'default_graph_version' => 'v2.4',
	]);

$helper = $fb->getJavaScriptHelper();

 try {
#    $accessToken = $helper->getAccessToken();
    $accessToken ='';
    $response = $fb->get('/me', $accessToken);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
	echo 'Graph error: ' . $e->getMessage();
	exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
	echo 'SDK error: ' . $e->getMessage();
	exit;
    } 
    
    if (! isset($accessToken)) {
	echo "No OAuth";
	exit;
    }

$me = $response->getGraphUser();
echo 'Logged in as ' . $me->getName();


// define your POST parameters (replace with your own values)
$params = array(
  "access_token" => $accessToken, // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
  "message" => $_POST['title'] .
 	       " " . 
	      _POST['description'] .
		"#rqau",
//  "message" => 'Q2 
//	  	Lorem Ipsum Q2 
//		#rqau' ,
//  "link" => "http://www.pontikis.net/blog/auto_post_on_facebook_with_php",
//  "picture" => "http://i.imgur.com/lHkOsiH.png",
//  "name" => "How to Auto Post on Facebook with PHP",
//  "caption" => "",
//  "description" => "RQ - Descr"
);

try {
  $ret = $fb->post('/900515886705930/feed', $params, $accessToken);
  echo 'Successfully posted to Facebook';
} catch(Exception $e) {
  echo $e->getMessage();
}

?>
