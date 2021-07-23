<?php
define("themepath","includes/themes/default/");
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test</title>
    <link href="<?=themepath?>bootstrap/css/bootstrap.min.css" rel="stylesheet">

  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
       <div class="container">
         <div class="navbar-header">
           <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
             <span class="sr-only">Toggle navigation</span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
           </button>
           <a class="navbar-brand" href="#">Project name</a>
         </div>
         <div id="navbar" class="collapse navbar-collapse">
           <ul class="nav navbar-nav">
             <li class="active"><a href="#">Home</a></li>
             <li><a href="#about">About</a></li>
             <li><a href="#contact">Contact</a></li>
           </ul>
         </div><!--/.nav-collapse -->
       </div>
     </nav>



     <div class="container">

       <div class="starter-template">
         <h1>Bootstrap starter template</h1>
         <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
       </div>

       <h3><?=CMS_USERNAME?></h3>
       <hr>
       <div class="">
         <h4><?php
                cmsCast("1"
                  ,"<div>
                        <h3 id='article{$page->id}'> <a href='?id={$page->id}'>{$page->name}</a></h3>
                        <label for='article{$page->id}'>Forfatter: {$author->data()->name}</label>
                        <p>{$page->content}</p>
                        <a href='newpage.php?id={$page->id}'>Edit</a> - <a href='?delete={$page->id}'>Delete</a>
                      </div>  ");


          ?></h4>
       </div>

     </div><!-- /.container -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?=themepath?>bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
