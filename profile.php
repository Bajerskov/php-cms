<?php
require_once('core/init.php');
$user = new User();
if(!$user->isLoggedIn()) {
  Session::flash('home','You need to be logged in to view profiles.');
  Redirect::to('index.php'); //evt. 500 error no permission
}
  if(!Input::exists('get')) {
    Redirect::to('index.php');
  } else {
    $profileuser = new User($username);
    if(!$profileuser->exists()) {
      Redirect::to(404);
    } else {
      $data = $profileuser->data();
    }
  }




 ?>
<h3><?php echo escape($data->username); ?></h3>
<p>Full Name: <?php echo escape($data->name) ?></p>
