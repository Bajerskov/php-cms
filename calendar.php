<?php
require_once('core/init.php');

$user = new User();
$calendar = new Calendar();
$pages = new Page();
$artcle = new Article();

if (!$user->isLoggedIn()) {
  Session::flash('home', NeedToBeloggedin);
  Redirect::to('index.php');
}

if (!$user->hasPermission('moderator')) {
  Session::flash('home', NoPermission);
  Redirect::to('index.php');
}


if(Input::exists('get')) {
  if (Token::check(Input::get('token'))) {
    $calendar->delete(Input::get('delete'));
    Session::flash('calendar', 'Event deleted');
    Redirect::to('calendar.php');
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
          if(Session::exists('calendar')) {
            echo '<p>'.Session::flash('calendar').'</p>';
          }
          ?>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h3>Kalender</h3>
      </div>
    </div>

    <div class="row mb-3 mt-3">

      <div class="col">
        <div class="input-group">
          <a href="newevent.php" class="btn btn-primary">Opret ny begivenhed</a>
        </div>
      </div>
      <div class="col-4">

        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <label class="input-group-text" for="inputGroupSelect01">Sorter efter: </label>
          </div>
          <select class="custom-select" id="inputGroupSelect01">
            <option onclick="sortTable(0)" selected>Nummer: Stigende</option>
            <option onclick="sortTable(0, 'desc')">Nummer: Faldende</option>
            <option onclick="sortTable(1)">Titel</option>
            <option onclick="sortTable(2)">Dato: Nyeste først</option>
            <option onclick="sortTable(2, 'desc')">Dato: Ældeste først</option>
            <option onclick="sortTable(4)">Oprettet af</option>
          </select>

        </div>

      </div>
    </div>
    <div class="row">
      <div class="col">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Titel</th>
              <th scope="col">Dato</th>
              <th scope="col">Link</th>
              <th scope="col">Oprettet af</th>
              <th scope="col">Slet</th>
            </tr>
          </thead>
          <tbody>
            <?php

          if ($calendar->exists()) {
            foreach($calendar->data() as $calentry) {
              $author = new User(intval($calentry->author));
              $var1 = strtotime($calentry->dato);
              $date = date("d/m-Y", $var1);
              $article = new Article($calentry->page_id);
              echo "<tr>
                      <th scope='row'>$calentry->id</th>
                      <td>$calentry->description</td>
                      <td>$date</td>
                      <td><a href='?id=$calentry->page_id'>{$article->data()->name}</a></td>
                      <td>{$author->data()->username}</td>
                      <td class='delete-button'> <a href='?delete=$calentry->id&token=$token'> <i class='fal fa-minus-circle'></i> </a> </td>

                    </tr>";
            }
          }
            ?>
          </tbody>
        </table>
      </div>
    </div>


  </div>


  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
