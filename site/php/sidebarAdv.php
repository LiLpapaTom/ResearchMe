<?php
error_reporting(0);
  if(isset($_SESSION['username'])){
    echo"
  <html>
  <head>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <style>
  body {
    margin: 0;
    font-family: 'Lato', sans-serif;
  }

  .sidebar {
    margin: 0;
    padding: 0;
    width: 200px;
    height:100%;
    background-color: #f1f1f1;
    position: fixed;
    height: 100%;
    overflow: hidden;
  }

  .sidebar a {
    display: block;
    color: black;
    padding: 16px;
    text-decoration: none;
  }

  .sidebar a.active {
    background-color: #fdbb28;
    color: white;
  }

  .sidebar a:hover:not(.active) {
    background-color: #555;
    color: white;
  }

  div.content {
    margin-left: 200px;
    padding: 1px 16px;
    height: 1000px;
  }

  @media screen and (max-width: 700px) {
    .sidebar {
      width: 100%;
      height: auto;
      position: relative;
    }
    .sidebar a {float: left;}
    div.content {margin-left: 0;}
  }

  @media screen and (max-width: 400px) {
    .sidebar a {
      text-align: center;
      float: none;
    }
  }
  </style>
  </head>
  <body>

  <div class='sidebar' style='left: 0px;'>
    <a class='active' href='../index.php'>Home</a>
    <a href='../publication.php'>Add Publication</a>
    <a href='../publications.php'>Add Multiple Publications</a>
    <a href='../author.php'>Add Author</a>
    <a href='../update.php'>Update Post</a>
    <a href='../delete.php'>Delete Post</a>
    <a href='../authorExportAll.php'>Export Posts</a>
    <a href='../statistics.php'>Statistics</a>
    <a href='about.php'>About</a>
  </div>

  </body>
  </html>
  ";
  }
  else{
    echo"
  <html>
  <head>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <style>
  body {
    margin: 0;
    font-family: 'Lato', sans-serif;
  }

  .sidebar {
    margin: 0;
    padding: 0;
    width: 200px;
    background-color: #f1f1f1;
    position: fixed;
    height: 100%;
    overflow: auto;
  }

  .sidebar a {
    display: block;
    color: black;
    padding: 16px;
    text-decoration: none;
  }

  .sidebar a.active {
    background-color: #fdbb28;
    color: white;
  }

  .sidebar a:hover:not(.active) {
    background-color: #555;
    color: white;
  }

  div.content {
    margin-left: 200px;
    padding: 1px 16px;
    height: 1000px;
  }

  @media screen and (max-width: 700px) {
    .sidebar {
      width: 100%;
      height: auto;
      position: relative;
    }
    .sidebar a {float: left;}
    div.content {margin-left: 0;}
  }

  @media screen and (max-width: 400px) {
    .sidebar a {
      text-align: center;
      float: none;
    }
  }
  </style>
  </head>
  <body>

  <div class='sidebar' style='left: 0px;'>
    <a class='active' href='../index.php'>Home</a>
    <a href='../authorExportAll.php'>Export Posts</a>
    <a href='../statistics.php'>Statistics</a>
    <a href='about.php'>About</a>
  </div>

  </body>
  </html>
  ";
}
 ?>
