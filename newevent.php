<?php
require_once('core/init.php');

$user = new User();
$calendar = new Calendar();
$pages = new Page();
$articles = new Article();

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
    $validate = new Validate();
    $validation = $validate->check($_POST,array(
      'date' => array('required' => true,
                      'date' => true),
      'description' => array('required' => true)
    ));

    if($validation->passed()) {
      //does the page exist?
      $articleVal = new Article(Input::get('page_id'));

      if($articleVal->exists()) {
        $calendar->create(array(
          'dato' => Input::get('date'),
          'description' => Input::get('description'),
          'page_id' => Input::get('page_id'),
          'author' => $user->data()->id,
          'lastupdated' => date("Y-m-d H:i:s")
        ));
      }

      Session::flash('calendar', 'Event created');
      Redirect::to('calendar.php');

    } else {
      foreach ($validation->errors() as $error) {
        echo $error, '<br>';
      }
    }

  } else {
    Session::flash('newevent', 'Invalid token');
    Redirect::to('newevent.php');

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
  <link href="./css/main.css" rel="stylesheet">

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
      <a class="nav-item nav-link" href="files.php">Filer</a>
      <a class="nav-item nav-link active" href="calendar.php">Kalender <span class="sr-only">(current)</span></a>
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
          if(Session::exists('newevent')) {
            echo '<p>'.Session::flash('newevent').'</p>';
          }
          ?>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h3>Ny begivenhed</h3>
      </div>
    </div>

    <div class="row">
      <div class="col">


        <form action="newevent.php" method="post">

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="formdate">Dato</label>
                <input type="date" name="date" class="form-control" id="formdate">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="formlink">Link til artikel</label>
                <select class="form-control" name="page_id" id="formlink">
                  <option value="0">Ingen</option>
                  <?php

                    foreach ($articles->data() as $article) {
                      if ($article->newsletter == 0) {
                        echo "<option value='$article->id'>$article->name</option>";
                      }
                    }
                   ?>
                </select>
              </div>

            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="formtitle">Overskrift</label>
                <input type="text" name="description" class="form-control" id="formtitle" placeholder="Overskrift">
              </div>
            </div>
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
