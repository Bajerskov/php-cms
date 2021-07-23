<?php
require_once('core/init.php');

//echo Session::get(Config::get('session/session_name'));
$user = new User();
//echo $user->data()->username;
  if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
  }

  if(Input::exists('get')) {

    if(Input::get('permissions') == 'Admin') {
      try {
        $user->update(array(
          'group' => 1,
          
        ));
      } catch (Exception $e) {
        die($e->getMessage());
      }
      
    } else {
      try {
        $user->update(array(
          'group' => 2,
          
        ));
      } catch (Exception $e) {
        die($e->getMessage());
      }
      Redirect::to('index.php');
    }

  }


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
         <div class="alert alert-primary" role="alert">
           Velkommen tilbage <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?> </a>
         </div>
         <?php
           if(Session::exists('home')) {
             echo '<div class="alert alert-primary" role="alert">'.Session::flash('home').'</div>';
           }
           ?>
         <div class="alert alert-warning" role="alert">
           <?php 
            $permission;
              if ($user->hasPermission('moderator')) 
                $permission = "Admin";
              else 
              $permission = "Standard";
           ?>
           Account currently has <?=$permission;?> capabillities. <a href='?permissions=<?=$permission;?>'>Change permissions.</a>
         </div>
       </div>
     </div>
     <div class="row mb-4">
       <div class="col-md">
         <div class="card" style="width: 18rem;">
           <div class="card-body">
             <h5 class="card-title"><i class="fal fa-comment-alt-lines"></i> Opret nyhed</h5>
             <p class="card-text">Nyheder vises i seneste nyt siden og på forsiden.</p>
             <a href="newnews.php" class="card-link">Opret nyhed.</a>
             <a href="news.php" class="card-link">Vis nyheder.</a>
           </div>
         </div>
       </div>
       <div class="col-md">
         <div class="card" style="width: 18rem;">
           <div class="card-body">
             <h5 class="card-title"><i class="fal fa-newspaper"></i>  Upload nyhedsbrev </h5>
             <p class="card-text">Læg et nyhedsbrev eller andet læsemateriale op på siden.</p>

             <a href="newfile.php" class="card-link">Upload fil.</a>
             <a href="files.php" class="card-link">Vis filer.</a>
           </div>
         </div>
       </div>
     </div>
     <div class="row mb-4">
       <div class="col-md">
         <div class="card" style="width: 18rem;">
           <div class="card-body">
             <h5 class="card-title"><i class="fal fa-file-alt"></i>  Rediger sider </h5>
             <p class="card-text">Rediger indholdet på de eksisterende sider.</p>

             <a href="pages.php" class="card-link">Vis sider.</a>

           </div>
         </div>
       </div>

       <div class="col-md">
         <div class="card" style="width: 18rem;">
           <div class="card-body">
             <h5 class="card-title"><i class="fal fa-calendar-alt"></i>  Ret kalender </h5>
             <p class="card-text">Tilføj, fjern eller ændre i kalender oversigten.</p>

             <a href="calendar.php" class="card-link">Ret kalender.</a>
           </div>
         </div>
       </div>

     </div>
     <div class="row justify-content-center">
       <div class="col-8">
         <h3>Besøgende</h3>
         <canvas id="myChart"></canvas>

       </div>
     </div>
   </div>
   <script>
   var ctx = document.getElementById("myChart").getContext('2d');
   var myChart = new Chart(ctx, {
       type: 'bar',
       data: {
           labels: ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"],
           datasets: [{
               label: 'Antal besøgende',
               data: [12, 19, 3, 5, 2, 3, 7, 6, 9, 5, 6, 7],
               backgroundColor: [
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(54, 162, 235, 0.2)'
               ],
               borderColor: [
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(54, 162, 235, 1)'
               ],
               borderWidth: 1
           }]
       },
       options: {
         responsive:true,
           scales: {
               yAxes: [{
                   ticks: {
                       beginAtZero:true
                   }
               }]
           }
       }
   });
   </script>
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

 </body>
 </html>
