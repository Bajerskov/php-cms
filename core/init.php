<?php
session_start();

$json_string = file_get_contents("core/package.json");
$settingsJSON = json_decode($json_string, true);

$GLOBALS['config'] = array(
  'mysql' => array(
    'host' => '',
    'username' => '',
    'password' => '',
    'database' => ''
  ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiery' => '60480'
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'settings' => $settingsJSON
  );




spl_autoload_register(function($class){
  require_once('classes/' . $class . '.php');
});

require_once('core/constants.php');
require_once('functions/sanitize.php');



 if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
   $hash = Cookie::get(Config::get('remember/cookie_name'));
   $hashCheck = DB::getInstance()->get('user_session', array('hash', '=', $hash));
   if($hashCheck->count()) {
     $user = new User($hashCheck->first()->user_id);
     $user->login();
   }
 }
