<?php
require_once('core/init.php');

$user = new User();

if (!$user->isLoggedIn()) {
  Session::flash('home', NeedToBeloggedin);
  Redirect::to('index.php');
}

if(Input::exists()){
  if(Token::check(Input::get('token'))) {

    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'name' => array(
        'required' => true,
        'min' => 2,
        'max' => 50
      ),
      'email' => array(
          'required' => true,
          'max' => 254,
          'email' => true,
       ),
       'emailrepeat' => array(
           'required' => true,
           'max' => 254,
           'email' => true,
           'matches' => 'email' //unique to the users table
        )
    ));

    if($validate->passed()) {
      try {
        $user->update(array(
          'name' => Input::get('name'),
          'email' => Input::get('email')
        ));

        Session::flash('home', 'Dine Oplysninger er blevet opdateret!');
        Redirect::to('index.php');
      } catch (Exception $e) {
        die($e->getMessage());
      }

    } else {
      $error_msg;
      foreach ($validation->errors() as $error) {
        $error_msg .= $error." <br>";
      }
      Session::flash('updateuser', 'Error: '. $error_msg);
    }
  } else {
    Session::flash('updateuser', 'Ugyldig certificat');
  }
}

$token = Token::generate();

 ?>

 <!doctype html>
 <html lang="da">
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <title>Kontrolpanel</title>
   <link rel="stylesheet" href="css/all.min.css">
   <!-- Bootstrap core CSS -->
   <link href="./css/bootstrap.min.css" rel="stylesheet">

   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js" charset="utf-8"></script>
   <script src="js/main.js" charset="utf-8"></script>
 </head>

 <body>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
   <a class="navbar-brand" href="index.php">Kontrolpanel</a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
   </button>
   <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
     <div class="navbar-nav">
       <a class="nav-item nav-link active" href="index.php">Start <span class="sr-only">(current)</span></a>
       <a class="nav-item nav-link" href="news.php">Nyheder</a>
       <a class="nav-item nav-link" href="files.php">Filer</a>
       <a class="nav-item nav-link" href="calendar.php">Kalender</a>
     </div>
   </div>
   <span class="navbar-text">

     <div class="dropdown show">
       <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         <img style="width:50px; height:50px;" src="https://i1.wp.com/www.winhelponline.com/blog/wp-content/uploads/2017/12/user.png?resize=256%2C256&quality=100&ssl=1" alt="">
       </a>

       <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
         <a class="dropdown-item" href="updateuser.php"><i class="fal fa-user-edit"></i> Opdater profil detaljer</a>
         <a class="dropdown-item" href="updatepassword.php"><i class="fal fa-key"></i> Skift kode</a>
         <div class="dropdown-divider"></div>
         <a class="dropdown-item" href="logout.php"><i class="fal fa-sign-out"></i> Log ud</a>
       </div>
     </div>

   </span>
 </nav>


   <div class="container">
     <div class="row">
       <div class="col">
         <!-- ALERT -->
         <?php
           if(Session::exists('updateuser')) {
             echo '<div class="alert alert-primary" role="alert">'.Session::flash('updateuser').'</div>';
           }
           ?>
       </div>
     </div>
     <div class="row">
      <div class="col">
        <h3>Opdater oplysninger</h3>

         <form action="" method="post">
           <div class="form-group">
             <label for="name">Fornavn</label>
             <input type="text" class="form-control" name="name" id="name" aria-describedby="emailHelp" placeholder="Skriv navn" value="<?php echo escape($user->data()->name);?>">
           </div>

           <div class="form-group">
             <label for="email">E-mail(Nuværende eller ny e-mail)</label>
             <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Skriv email" value="<?php echo escape($user->data()->email);?>">
           </div>

           <div class="form-group">
             <label for="emailrepeat">Gentag e-mail</label>
             <input type="email" name="emailrepeat" class="form-control" id="emailrepeat" aria-describedby="emailHelp" placeholder="Gentag email" required>
           </div>
           <input type="hidden" name="token" value="<?php echo $token; ?>">
           <button type="submit" class="btn btn-primary">Gem</button>
         </form>

      </div>
     </div>

   </div>
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

 </body>
 </html>
