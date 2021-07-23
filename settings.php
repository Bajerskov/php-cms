<?php
require_once('core/init.php');
$user = new User();
$datalal = $user->data();
$settings = new Settings();
if(!$user->isLoggedIn()) {
  Session::flash('home','You need to be logged in to view  or edit settings.');
  Redirect::to('index.php'); //evt. 500 error no permission
}

//find all .zip files in themes folder return array

$template_uri = "includes/themes/default/";

if(Config::get('settings/theme') == null OR Config::get('settings/theme') == "default") {
  $packDefault = $settings->find();
  //print_r($packDefault);
  $packageSettings = file_get_contents($packDefault[0]);
  //echo "<pre>";
  //print_r($packageSettings);
  //echo "</pre>";

  include("includes/themes/default/index.php");

} else {
  echo "costum";
}

?>

</body>
</html>
