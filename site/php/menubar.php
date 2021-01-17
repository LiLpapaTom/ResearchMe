<?php
  session_start();
  include "php/connect.php";
  include "php/getpublications.php";
  error_reporting(0);

  if(isset($_SESSION['username'])){
    echo"
      <nav class='navbar navbar-expand-sm bg-dark navbar-dark>
        <div class='container-fluid'>
          <div class='navbar-header'>
            <h1><a class='navbar-brand' href='index.php' style='font-size:90%'>ResearchMe</a></h1>
          </div>
          <div>
            <form class='form-inline' action=''>
              <input class='form-control mr-sm-2' type='text' id='input' placeholder='Search' style='width:80%;margin-left:10%;'>
              <input type='image' id='s' src='assets/images/search.jpg' alt='Search' onclick='loadResults(event)' style='width:3%;height:5%;margin-left:2%;'>
            </form>
          </div>
          <div>
            <a href='advancedsearch.php'>Advanced Search</a>
          </div>
          <p class='text-muted' style='font-size:110%;margin-top:1.3%;margin-left:30px;'>Welcome ".$_SESSION['username']."</p>
          <form action='php/logout.php' method='post'>
            <button class='btn btn-success' type='submit' name='logoutButton' style='margin-left:30%;margin-right:0'>Logout</button>
          </form>
          </div>
        </div>
      </nav>
      <script type='text/javascript'>
        function loadResults(e){
          e.preventDefault();
          var str = document.getElementById('input').value;
          xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById('myFrame').src = 'php/search.php?q='+str;
            }
          };
          xhttp.open('GET', 'php/search.php?q='+".htmlspecialchars(str,ENT_NOQUOTES,'UTF-8').", true);
          xhttp.send();
        }
      </script>
      <script>
      window.onscroll = function() {myFunction()};

      var navbar = document.getElementById('navbar');
      var sticky = navbar.offsetTop;

      function myFunction() {
        if (window.pageYOffset >= sticky) {
          navbar.classList.add('sticky')
        } else {
          navbar.classList.remove('sticky');
        }
      }
      </script>
    ";
  }
  else{
    echo"
    <nav class='navbar navbar-expand-sm bg-dark navbar-dark>
      <div class='container-fluid'>
        <div class='navbar-header'>
          <h1><a class='navbar-brand' href='index.php' style='font-size:90%'>ResearchMe</a></h1>
        </div>
        <div>
          <form class='form-inline' action=''>
            <input class='form-control mr-sm-2' type='text' id='input' placeholder='Search' style='width:80%;margin-left:10%;'>
            <input type='image' id='s' src='assets/images/search.jpg' alt='Search' onclick='loadResults(event)' style='width:3%;height:5%;margin-left:2%;'>
            <p><p>
          </form>
        </div>
        <div>
          <a href='advancedsearch.php'>Advanced Search</a>
        </div>
        <form action='login.html' style='margin-left:160px;margin-right:0;'>
          <button class='btn btn-success' type='submit' name='login'>Sign In</button>
        </form
        </div>
      </div>
    </nav>
      <script type='text/javascript'>
        function loadResults(e){
          e.preventDefault();
          var str = document.getElementById('input').value;
          xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById('myFrame').src = 'php/search.php?q='+str;
            }
          };
          xhttp.open('GET', 'php/search.php?q='+".htmlspecialchars(str,ENT_NOQUOTES,'UTF-8').", true);
          xhttp.send();
        }
      </script>
    ";
  }
?>
