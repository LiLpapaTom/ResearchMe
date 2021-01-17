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
    <style media="screen">
    .back {
        background-color: #16a085;
        border-radius: 25px;
        color: white;
        margin: 2%;
        padding: 2%;
        }
    </style>
  </head>
  <body style="background-color: grey;">
    <nav class='navbar navbar-expand-sm bg-dark navbar-dark' style="padding:0;margin:0;border-collapse: collapse;">
      <div class="container-fluid">
        <?php include 'php/menubar.php';?>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 nav" style="left:0px;background-color: grey;">
          <?php include 'php/sidebar.php';?>
        </div>
        <div class="col-sm-6 back" id="myView" style="margin-right: 5%;margin-top:5%" >
          <p>Η εργασία αυτή αποτελεί την απαλλακτική εργασία του μαθήματος<br> "Προγραμματισμός στο Διαδίκτυο", για το ακαδημαϊκό έτος 2019-2020.</p><br><br>
          <p1><b>Υλοποιήθηκε από τους:</b></p1><br><br>
          <p2>Γρεβενίτης Ιωάννης, icsd13045@icsd.aegean.gr</p2><br><br>
          <p3>Παπαλουκάς Θωμάς, icsd14155@icsd.aegean.gr</p3>
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
