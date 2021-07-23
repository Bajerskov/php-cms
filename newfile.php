<?php
require_once('core/init.php');

$user = new User();
$dlFiles = new Downloads();

if (!$user->isLoggedIn()) {
  Session::flash('home', NeedToBeloggedin);
  Redirect::to('index.php');
}

if (!$user->hasPermission('moderator')) {
  Session::flash('home', NoPermission);
  Redirect::to('index.php');
}


if(Input::exists()) {
  if(Token::check(Input::get('token'))) {

    $target_dir = "downloads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = true;
    $filetype = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if file already exists
        if (file_exists($target_file)) {
          $uploadOk = false;
          Session::flash('newfile', "File was not uploaded. File already exists.");
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
              try {
                $dlFiles->insertFile(array(
                  'path' => basename( $_FILES["fileToUpload"]["name"]),
                  'name' => Input::get('filename'),
                  'date' => date("Y-m-d H:i:s"),
                  'size' => $_FILES["fileToUpload"]["size"],
                  'type' => Input::get('type'),
                  'uploader'=> $user->data()->id
                ));

                //upload complete and successfully
                //Make a new article about it.
                if(Input::get('type') == 'medlemsblad') {
                  $articles = new Article();
                  try {
                    $articles->create(array(
                      'name' => 'Nyt medlemsblad tilgængeligt',
                      'content' => 'Et nyt medlemsblad er blevet tilføjet til arkivet. Det kan læses her: <br><a href="/cms/downloads/'.basename( $_FILES["fileToUpload"]["name"]).'">'.Input::get('filename').'</a>',
                      'date' => date("Y-m-d H:i:s"),
                      'author' => $user->data()->id,
                      'public' => 1,
                      'newsletter' => 1
                    ));

                  } catch (Exception $e) {
                    echo $e->getMessage();
                  }


                }

                Session::flash('downloads', 'File uploaded successfully');
                Redirect::to('files.php');

              } catch (Exception $e) {
                echo $e->getMessage();
              }
            } else {
              Session::flash('newfile', 'An unknown error ocured. File was not uploaded.');
              Redirect::to('newfile.php');
            }
        }


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
      <a class="nav-item nav-link" href="index.php">Start</a>
      <a class="nav-item nav-link" href="news.php">Nyheder</a>
      <a class="nav-item nav-link active" href="files.php">Filer  <span class="sr-only">(current)</span></a>
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
        <!-- ALERTS  -->
        <?php
        if(Session::exists('newfile')) {
          echo '<p>'.Session::flash('newfile').'</p>';
        }
         ?>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h3>Ny fil</h3>

               <form action="newfile.php" method="post" enctype="multipart/form-data">
                 <div class="form-group">
                   <label for="fileToUpload">Vælg fil til upload</label>
                   <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload" required>
                 </div>

                 <div class="form-group">
                   <label for="filename">Fil navn</label>
                   <input type="text" class="form-control" id="filename" name="filename" required>
                 </div>
                <div class="form-group">
                  <select class="form-control" name="type" required>
                    <option value="file">Almindelig fil</option>
                    <option value="medlemsblad">Medlemsblad</option>
                  </select>
                </div>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <button type="submit" class="btn btn-primary">Upload</button>
               </form>


      </div>

    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
