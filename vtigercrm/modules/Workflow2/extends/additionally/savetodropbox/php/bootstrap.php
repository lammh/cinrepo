<?php
// Register a simple autoload function
spl_autoload_register(function($class){
	$class = str_replace('\\', '/', $class);

    if(!file_exists(dirname(__FILE__).'/lib/' . $class . '.php'))
        return;

    require_once(dirname(__FILE__).'/lib/' . $class . '.php');
});

// Set your consumer key, secret and callback URL
$key      = 'juythhl9d6mupch';
$secret   = 'glmulmlrs7wrioy';

// Check whether to use HTTPS and set the callback URL
$protocol = (!empty($_SERVER['HTTPS'])) ? 'https' : 'http';

$callback = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Instantiate the Encrypter and storage objects
$encrypter = new \Dropbox\OAuth\Storage\Encrypter('123456XXXXXXXafszXXXXXX6jgorlXXX');

// User ID assigned by your auth system (used by persistent storage handlers)
#$userID = 1;

$storage = new \Dropbox\OAuth\Storage\Filesystem($encrypter, $userID);
$storage->setDirectory(dirname(__FILE__).'/tokens');
#echo dirname(__FILE__).'/tokens';

$OAuth = new \Dropbox\OAuth\Consumer\Curl($key, $secret, $storage, $callback);
$dropbox = new \Dropbox\API($OAuth, "dropbox");

#var_dump($storage->get("request_token"));
#var_dump($storage->get("access_token"));