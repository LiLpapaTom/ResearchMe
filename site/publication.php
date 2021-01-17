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
    <nav class='navbar navbar-expand-sm bg-dark navbar-dark sticky-top' id="navbar" style="padding:0;margin:0;border-collapse: collapse;">
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
        <div class="col-sm-3" id="myView" style="padding-right: 0%; padding-top:2%;">
          <div class="outerContainer">
        	<div class="container">
        	<div class="innerContainer">
            <form class="form-inline" action="php/publicationAdd.php" method="post">
              <!--Author 1-->
              <label for="author" class="col-sm-3 col-form-label"><b>Author</b></label>
              <div class="form-group row">
                <div class="col col-md-4">
                  <input type="text" class="form-control" name="name" id="author" placeholder="Name*" required>
                </div>
                <div class="col col-md-4">
                  <input type="text" class="offset-md-2 form-control" name="surname" id="author" placeholder="Surname*" required>
                </div>
                <div class="col-md-4">
                  <button onclick="addAuthorFields()" class='offset-md-4 btn btn-success'type="button" name="addAuthorButton">Add Author</button>
                </div>
              </div>
              <div id="authorsDiv"></div>

              <!--Publication Info-->
              <label class="col-sm-4 col-form-label" style="margin-top:5%"><b>Publication</b></label>
              <div class="form-group row">
                <div class="col">
                  <input type="text" class="form-control" name="title" placeholder="Title*" required>
                </div>
                <div class="col">
                  <input type="text" class="form-control" name="year" placeholder="Publication Year*">
                </div>
                <div class="col">
                  <input type="text" class="form-control" name="month" placeholder="Publication Month">
                </div>
              </div>
              <hr></hr>
              <div class="form-group row">
                <div class="col">
                  <input type="text" class="form-control" name="note" placeholder="Note">
                </div>
                <div class="col">
                  <input type="text" class="form-control" name="url" placeholder="URL">
                </div>
                <div class="col">
                  <input type="text" class="form-control" name="key" placeholder="Key">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 col-form-label">
                  <br>
                  <input type="radio" class="form-control" name="type" value="Article" checked> Article
                  <input type="radio" class="form-control" name="type" value="Inproceedings"> Inproceedings
                  <input type="radio" class="form-control" name="type" value="Book"> Book
                  <input type="radio" class="form-control" name="type" value="Inbook"> Inbook
                </div>
              </div>

              <!--Article Info-->
              <div class="formArticle form-inline">
                <label class="col-sm-3 col-form-label"><b>Article</b></label>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="aJournal" placeholder="Journal*" >
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="aVolume" placeholder="Volume">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="aNumber" placeholder="Number">
                  </div>
                </div>
                <hr></hr>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="aPages" placeholder="Pages">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="aDOI" placeholder="Document Object Identifier (DOI)">
                  </div>
                </div>
              </div>

              <!--Inproceedings Info-->
              <div class="formInproceedings form-inline" style="display:none;">
                <label class="col-sm-5 col-form-label"><b>Inproceedings</b></label>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="bTitle" placeholder="Booktitle*" >
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="bEditor" placeholder="Editor">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="bVolume" placeholder="Volume">
                  </div>
                </div>
                <hr></hr>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="bNumber" placeholder="Number">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="bSeries" placeholder="Series">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="bPages" placeholder="Pages">
                  </div>
                </div>
                <hr></hr>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="bAddress" placeholder="Publisher Address">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="bOrganization" placeholder="Organization">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="bPublisher" placeholder="Publisher">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col">
                    <br>
                    <input type="text" class="form-control" name="bDOI" placeholder="Document Object Identifier (DOI)">
                  </div>
                </div>
              </div>

              <!--Book Info-->
              <div class="formBook form-inline" style="display:none;">
                <label class="col-sm-3 col-form-label"><b>Book</b></label>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="cPublisher" placeholder="Publisher*">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="cVolume" placeholder="Volume">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="cNumber" placeholder="Number">
                  </div>
                </div>
                <hr></hr>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="cSeries" placeholder="Series">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="cAddress" placeholder="Publisher Address">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="cEdition" placeholder="Edition">
                  </div>
                </div>
                <hr></hr>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="cMonth" placeholder="Month">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="cISBN" placeholder="ISBN">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="cISSN" placeholder="ISSN">
                  </div>
                </div>
              </div>

              <!--Inbook Info-->
              <div class="formInbook form-inline" style="display:none;">
                <label class="col-sm-3 col-form-label"><b>Inbook</b></label>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="dPublisher" placeholder="Publisher*">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="dChapter" placeholder="Chapter*">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="dPages" placeholder="Pages*">
                  </div>
                </div>
                <hr></hr>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="dVolume" placeholder="Volume">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="dNumber" placeholder="Number">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="dSeries" placeholder="Series">
                  </div>
                </div>
                <hr></hr>
                <div class="form-group row">
                  <div class="col">
                    <input type="text" class="form-control" name="dType" placeholder="Types">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="dAddress" placeholder="Address">
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="dEdition" placeholder="Edition">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col">
                    <br>
                    <input type="text" class="form-control" name="dISBN" placeholder="ISBN">
                  </div>
                  <div class="col">
                    <br>
                    <input type="text" class="form-control" name="dISSN" placeholder="ISSN">
                  </div>
                </div>
              </div>

              <!--Submit-->
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
    <!--Script to change which form we display depending on which radio button the user has selected-->
    <script type="text/javascript">
      var authorCounter = 1;

      function addAuthorFields(){
        authorCounter++;
        if(authorCounter <= 5){
          document.getElementById("authorsDiv").innerHTML +=
          '<label class="col-sm-3 col-form-label""><b>Author.'+authorCounter+'</b></label><div class="form-group row"><div class="col-md-3"><input type="text" class="form-control" name="name'+authorCounter+'" placeholder="Name"></div><div class="col-md-2 offset-md-5"><input type="text" class="offset-md-3 form-control" name="surname'+authorCounter+'" placeholder="Surname"></div></div>'
        }
      }


      $(document).ready(function() {
        $("input[name=type]:radio").change(function() {
          var checkedBtn = this.value;

          document.querySelector('.formArticle').style.display = "none";
          document.querySelector('.formInproceedings').style.display = "none";
          document.querySelector('.formBook').style.display = "none";
          document.querySelector('.formInbook').style.display = "none";

          if(checkedBtn == "Article"){
            document.querySelector('.formArticle').style.display = "flex";
          }
          else if(checkedBtn == "Inproceedings"){
            document.querySelector('.formInproceedings').style.display = "flex";
          }
          else if(checkedBtn == "Book"){
            document.querySelector('.formBook').style.display = "flex";
          }
          else if(checkedBtn == "Inbook"){
            document.querySelector('.formInbook').style.display = "flex";
          }
        })
      })
    </script>
  </body>
</html>
