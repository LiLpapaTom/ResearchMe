<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Author Register</title>
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
    <div class="outerContainer">
  	<div class="container">
  	<div class="innerContainer">
  		<form class="myForm" action="php/authorRegister.php" method="post">
  			<!--Name-->
  			<div>
          <br>
  				<input class="form-control" type="text" name="name" placeholder="Name">
          <br>
  			</div>
  			<!--Surname-->
  			<div>
  				<input class="form-control" type="text" name="surname" placeholder="Surname">
          <br>
  			</div>
  			<!--Website-->
  			<div>
  				<input class="form-control" type="text" name="url" placeholder="Personal Website Url">
          <br>
  			</div>
  			<!--Orchid url-->
        <div>
  				<input class="form-control" type="text" name="orcid" placeholder="Orcid Url">
          <br>
  			</div>
        <div>
  				<input type="radio" name="role" value="Lecturer">Lecturer
  				<input type="radio" name="role" value="Researcher">Researcher
          <input type="radio" name="role" value="Technical Personnel">Technical Personnel
          <input type="radio" name="role" value=">Research Personnel">Research Personnel
          <input type="radio" name="role" value="None" checked> None
  			</div>
  			<button  style="margin-top:5%;margin-bottom:10%" class='btn btn-success' type="submit" name="submit">Submit</button>
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
