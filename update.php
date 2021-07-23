<?php
  require_once('core/init.php');
  $user = new User();
  if(!$user->isLoggedIn()) {
    echo Input::dump($user->isLoggedIn());
    die();
    //Redirect::to('index.php');
  }

  if(Input::exists()){
    if(Token::check(Input::get('token'))) {

      $validate = new Validate();
      $validation = $validate->check($_POST, array(
        'name' => array(
          'required' => true,
          'min' => 2,
          'max' => 50
        )
      ));

      if($validate->passed()) {
        try {
          $user->update(array(
            'name' => Input::get('name')
          ));

          Session::flash('home', 'Your details have been updated!');
          Redirect::to('index.php');
        } catch (Exception $e) {
          die($e->getMessage());
        }

      } else {
        foreach ($validation->errors() as $error) {
          echo $error, '<br>';
        }
      }
    }
  }

 ?>


 <form action="" method="post">
   <div>
     <label for="name">Name </label>
     <input type="text" name="name" value="<?php echo escape($user->data()->name);?>" placeholder="Name">
   </div>
   <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
   <input type="submit" name="submit" value="Update">



 </form>
