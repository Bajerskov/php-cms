<?php
require_once('core/init.php');

if(Input::exists()) {
  if(Token::check(Input::get('token'))) {
    $validate = new Validate();
    $validation = $validate->check($_POST,array(
      'email' => array('required' => true,
                       'email' => true),
      //'username' => array('required' => true),
      'password' => array('required' => true)
    ));
    if($validation->passed()) {
      //log user in
      $user = new User();

      $remember = (Input::get('remember') === "on") ? true : false;
      $login = $user->login(Input::get('email'), Input::get('password'), $remember);
      if($login) {
        Redirect::to('index.php');
      } else {
        Session::flash('login', 'Email or password incorrect!');
      }
    } else {
      foreach ($validation->errors() as $error) {
        echo $error, '<br>';
      }
    }
  } else {
    Session::flash('login', 'Invalid session token!');
  }
}
 ?>

 <!doctype html>
 <html lang="da">
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <title>Login til Kontrolpanel</title>

   <!-- Bootstrap core CSS -->
   <link href="./css/bootstrap.min.css" rel="stylesheet">

   <style media="screen">
   .logo {
     width:175px;
     height:175px;
     background: #003871;
     border-radius: 25px;
     border: 2px solid darkblue;
   }
   .inline {
     display: inline;
   }
 </style>
 </head>

 <body>
   <form class="form-signin" action="login.php" method="post">
   <div class="container">
     <div class="row justify-content-center">
       <div class="col-3 text-center">
         <h1 class="h3 mb-3 font-weight-normal">Login</h1>

         <?php
           if(Session::exists('login')) {
           echo '<div class="alert alert-warning" role="alert">'.Session::flash('login').'</div>';
         }
         ?>
         <div class="alert alert-primary" role="alert">
           Username: test@account.com  Password: password
         </div>
       </div>
     </div>

     <div class="row justify-content-center">
       <div class="col-6">
         <div class="form-group">
           <label for="inputEmail" class="sr-only">Email adresse</label>
           <input type="email" id="email" name="email" class="form-control" placeholder="Email adresse" required autofocus>
         </div>

         <div class="form-group">
           <label for="inputPassword" class="sr-only">Kodeord</label>
           <input type="password" id="password" name="password" class="form-control" placeholder="Kodeord" required>
         </div>

         <div class="checkbox mb-3">
           <label>
             <input id="remember" type="checkbox" name="remember" value="Husk mig"> Husk mig
           </label>
         </div>

       </div>
     </div>
     <div class="row justify-content-center">
       <div class="col-3">
         <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
         <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login">Login</button>

       </div>
       <div class="col-3">
           <a class="btn btn-lg btn-primary btn-block" href="register.php">Registrer</a>
       </div>
     </div>
   </div>

 </form>
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

 </body>
 </html>
