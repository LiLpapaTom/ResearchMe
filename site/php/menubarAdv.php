<?php
  error_reporting(0);
  
  if(isset($_SESSION['username'])){
    echo"
      <nav class='navbar navbar-expand-sm bg-dark navbar-dark>
        <div class='container-fluid'>
          <div class='navbar-header'>
            <h1><a class='navbar-brand' href='../index.php' style='font-size:90%'>ResearchMe</a></h1>
          </div>
          <div>
            <form class='form-inline' action=''>
              <input class='form-control mr-sm-2' type='text' id='input' placeholder='Search' style='width:80%;margin-left:10%;'>
              <input type='image' id='s' src='../assets/images/search.jpg' alt='Search' onclick='loadResults(event)' style='width:3%;height:5%;margin-left:2%;'>
            </form>
          </div>
          <div>
            <a href='../advancedsearch.php'>Advanced Search</a>
          </div>
          <p class='text-muted' style='font-size:110%;margin-top:1.3%;margin-left:30px;'>Welcome ".$_SESSION['username']."</p>
          <form action='logout.php' method='post'>
            <button class='btn btn-success' type='submit' name='logoutButton' style='margin-left:30%;margin-right:0'>Logout</button>
          </form>
          </div>
        </div>
      </nav>
    ";
  }
  else{
    echo"
    <nav class='navbar navbar-expand-sm bg-dark navbar-dark>
      <div class='container-fluid'>
        <div class='navbar-header'>
          <h1><a class='navbar-brand' href='../index.php' style='font-size:90%'>ResearchMe</a></h1>
        </div>
        <div>
          <form class='form-inline' action=''>
            <input class='form-control mr-sm-2' type='text' id='input' placeholder='Search' style='width:80%;margin-left:10%;'>
            <input type='image' id='s' src='assets/images/search.jpg' alt='Search' onclick='loadResults(event)' style='width:3%;height:5%;margin-left:2%;'>
            <p><p>
          </form>
        </div>
        <div>
          <a href='../advancedsearch.php'>Advanced Search</a>
        </div>
        <form action='login.html' style='margin-left:160px;margin-right:0;'>
          <button class='btn btn-success' type='submit' name='login'>Sign In</button>
        </form
        </div>
      </div>
    </nav>
    ";
  }
?>
