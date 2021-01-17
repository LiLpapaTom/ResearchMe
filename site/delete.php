<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Add Publication</title>
  	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<!-- Bootstrap CSS -->
  	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  </head>
  <body style="background-color: grey;">
    <nav class='navbar navbar-expand-sm bg-dark navbar-dark' id="navbar" style="padding:0;margin:0;border-collapse: collapse;">
      <div class="container-fluid">
        <?php
          include 'php/menubar.php';
          if(empty($_SESSION['username'])){
          header("Location: login.html");
          die("Redirecting to login");
        }?>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="form-row">
        <div class="col-sm-3" style="left:0px;background-color: grey;">
          <?php include 'php/sidebar.php';?>
        </div>
        <div class="col-sm-3" id="myView" style="padding-right: 0%; padding-top:5%;">
          <div class="outerContainer">
        	<div class="container">
        	<div class="innerContainer">
            <form class="form-inline" action="php/publicationDelete.php" method="post">
              <!--Author 1-->
              <div class="">
                <input type="text" class="form-control" name="publicationid" id="pubid" placeholder="Publication's ID">
                <br><hr>
              </div>
            </form>
        </div>
      </div>

    </div>
  	<!-- Optional JavaScript -->
  	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
  	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
