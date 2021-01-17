<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <title></title>
  </head>
  <body style="background-color: grey;">
    <nav class='navbar navbar-expand-sm bg-dark navbar-dark sticky-top' style="padding:0;margin:0;border-collapse: collapse;">
      <div class="container-fluid">
        <?php include 'php/menubar.php';?>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3" style="left:0px;background-color: grey;">
          <?php include 'php/sidebar.php';?>
        </div>


							<form class="myForm" action="" method="post" enctype="multipart/form-data">
								<div>
									<br>
									<label for="name" class="badge badge-warning" style="font-size:110%">Please enter author's information:</label>
									<input class="form-control" type="text" id="name" name="name" placeholder="Name*" required>
									<br>
								</div>
								<div>
									<input class="form-control" type="text" name="surname" placeholder="Surname*" required>
									<br>
								</div>
								<div>
									<input class="form-control" type="text" name="startYear" placeholder="Starting Year">
									<br>
								</div>
								<div>
									<input class="form-control" type="text" name="endYear" placeholder="Ending Year">
									<br>
								</div>
								<button  style="margin-top:5%;margin-bottom:10%" class='btn btn-success' type="submit" name="submit">Submit</button>
							</form>


						<div class="col-sm-6 offset-md-2" id="myView" style="padding-right: 0%; padding-top:0%;">
							<?php if(isset($_POST['submit'])){
									include 'php/statsAuthor.php';
								}
							?>
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
