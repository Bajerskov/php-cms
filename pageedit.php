<?php
require_once('core/init.php');

$user = new User();
$pages;

$name;
$content;

if (!$user->isLoggedIn()) {
  Session::flash('home', NeedToBeloggedin);
  Redirect::to('index.php');
}

if (!$user->hasPermission('moderator')) {
  Session::flash('home', NoPermission);
  Redirect::to('index.php');
}

if(Input::get('id')) {
  $pages = new Page(escape(Input::get('id')));
  if(!$pages->exists() || escape(Input::get('id')) == 0) {
    Redirect::to('pages.php');
  }
}


$token = Token::generate();

$name = $pages->name;
$content = $pages->content;


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
   <script src="includes/ckeditor/ckeditor.js"></script>
 </head>

 <body>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
   <a class="navbar-brand" href="index.php">Kontrolpanel</a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
   </button>
   <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
     <div class="navbar-nav">
       <a class="nav-item nav-link" href="index.php">Start <span class="sr-only">(current)</span></a>
       <a class="nav-item nav-link" href="news.php">Nyheder</a>
       <a class="nav-item nav-link" href="files.php">Filer</a>
       <a class="nav-item nav-link active" href="pages.php">Sider</a>
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
         <?php
           if(Session::exists('page')) {
             echo '<div class="alert alert-primary" role="alert">'.Session::flash('page').'</div>';
           }
           ?>

       </div>
     </div>

     <div class="row">
       <div class="col">
         <h3>Rediger side</h3>
       </div>
     </div>
     <form action="newnews.php<?php echo "?id=".escape(Input::get('id')); ?>" method="post" accept-charset="utf-8">

           <div class="row mb-5">
             <div class="col">

              <div class="form-group">
                <label for="name">Overskrift</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Overskrift" value="<?php echo $name; ?>">
              </div>

               <div class="form-group">
                 <label for="indhold">Indhold</label>
                 <textarea id="articleContent" name="content" rows="8" cols="40"><?php echo $content; ?></textarea>
               </div>
               <input type="hidden" name="token" value="<?php echo $token; ?>">
               <button type="submit" name="submit" value="SAVE" class="btn btn-primary">Gem</button>
               <button type="submit" name="submit" value="SAVEANDPUBLIC" class="btn btn-primary">Gem og publicer</button>
               <a href="#" class="btn btn-primary">Vis eksempel</a>

             </div>
           </div>
     </form>

   </div>

   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <script>
       // Replace the <textarea id="editor1"> with a CKEditor
       // instance, using default configuration.
       CKEDITOR.replace( 'articleContent' );
   </script>
 </body>
 </html>
