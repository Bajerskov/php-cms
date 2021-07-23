<?php
require_once('core/init.php');

  if(Input::exists()){
    if(Token::check(Input::get('token'))) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'username' => array(
          'required' => true,
          'min' => 2,
          'max' => 20,
          'unique' => 'users' //unique to the users table
       ),
       'email' => array(
           'required' => true,
           'max' => 254,
           'email' => true,
           'unique' => 'users' //unique to the users table
        ),
        'emailAgain' => array(
            'required' => true,
            'max' => 254,
            'email' => true,
            'matches' => 'email' //unique to the users table
         ),
        'password' => array(
          'required' => true,
          'min' => 6
        ),
        'passwordAgain' => array(
          'required' => true,
          'matches' => 'password'
        ),
        'name' => array(
          'required' => true,
          'min' => 2,
          'max' => 50
        )
    ));

    if($validation->passed()) {
      $user = new User();

      $salt = Hash::salt(32);

      try{
        $user->create(array(
          'username' => Input::get('username'),
          'email' => Input::get('email'),
          'password' => Hash::make(Input::get('password'), $salt),
          'salt' => $salt,
          'name' => Input::get('name'),
          'joined' => date("Y-M-D H:i:s"),
          'group' => 1
        ));

        Session::put('home', 'Du er nu oprettet og kan logge ind!');
        Redirect::to('index.php');
      } catch(Exception $e) {
        die($e->getMessage());
      }

    } else {
     foreach ($validation->errors() as $error) {
       Session::put('register', $erorr);
     }
    }
  }
}
 ?>
 <!doctype html>
 <html lang="da">
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <title>Opret konto til Kontrolpanel</title>

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
   </style>
 </head>

 <body>
   <form method="post">
     <div class="container">
       <div class="row">
         <div class="col-md mt-3 text-center">
           <h1 class="h3 font-weight-normal">Registrer</h1>

           <?php
           if(Session::exists('login')) {
           echo '<div class="alert alert-warning" role="alert">'.Session::flash('register').'</div>';
         }
         ?>


     </div>
   </div>
   <div class="row">
     <div class="col-md-6">

       <div class="form-group">
         <label for="email">Email adresse</label>
         <input type="email" id="email" name="email" class="form-control" placeholder="Udfyld e-mail adresse" required autofocus>

       </div>

       <div class="form-group">

         <label for="email-repeat">Gentag Email adresse</label>
         <input type="email" id="emailAgain" name="emailAgain" class="form-control" placeholder="Genudfyld e-mail adresse" required>

       </div>


       <div class="form-group">

         <label for="username">Brugernavn</label>
         <input type="text" id="username" name="username" value="" class="form-control" placeholder="Udfyld brugernavn" required>

       </div>

     </div>
     <div class="col-md-6">
       <div class="form-group">

         <label for="password">Kodeord</label>
         <input type="password" id="password" name="password" class="form-control" placeholder="Udfyld kodeord" required>

       </div>

       <div class="form-group">

         <label for="password-repeat">Gentag Kodeord</label>
         <input type="password" id="passwordAgain" name="passwordAgain" class="form-control" placeholder="Genudfyld kodeord" required>

       </div>

       <div class="form-group">

         <label for="name">Fornavn</label>
         <input type="text" id="name" name="name" class="form-control" placeholder="Fornavn" required>

       </div>
     </div>
   </div>
   <div class="row justify-content-center">
     <div class="col-6">
       <div class="form-group">
         <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
         <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login">Registrer</button>

       </div>
     </div>
   </div>
 </div>
 </form>


 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


 </body>
 </html>
