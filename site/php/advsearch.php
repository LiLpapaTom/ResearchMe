<?php
    session_start();
    include "connect.php";
    error_reporting(0);
    echo"<!DOCTYPE html>
    <html lang='en' dir='ltr'>
      <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <!-- Bootstrap CSS -->
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css' integrity='sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk' crossorigin='anonymous'>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js'></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js'></script>
        <title></title>
      </head>
      <body style='background-color:grey'>
        <nav class='navbar navbar-expand-sm bg-dark navbar-dark' style='padding:0;margin:0;border-collapse: collapse;'>
          <div class='container-fluid'>";
            include 'menubarAdv.php';
          echo "
          </div>
        </nav>
        <div class='container-fluid'>
          <div class='row'>
            <div class='col-sm-3 nav' style='left:0px;background-color: grey;'>";
          include 'sidebarAdv.php';
          echo"  </div>
            <div class='col-sm-6' id='myView' style='padding-right: 0%; padding-top:2%; style='background-color: lightblue;' '>";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
    	$title = mysqli_real_escape_string($link, $_REQUEST['title']);
    	$year = mysqli_real_escape_string($link, $_REQUEST['year']);
      $month =mysqli_real_escape_string($link, $_REQUEST['month']);
      $note =mysqli_real_escape_string($link, $_REQUEST['note']);
      $url =mysqli_real_escape_string($link, $_REQUEST['url']);
      $key =mysqli_real_escape_string($link, $_REQUEST['key']);
      $type =mysqli_real_escape_string($link, $_REQUEST['type']);
      }
      mysqli_autocommit($link, false);
      #echo $title," ",$type;
      #echo "<br>~~~<br>";
      $publication = array();$i=0;
      $empty_publication = 0;

      #If publication form is empty
      if(empty($title)&&empty($year)&&empty($month)&&empty($note)&&empty($url)&&empty($key)){
        #echo 'Publication form is empty!';
        $empty_publication = 1;
      }
      else{
        #Missing month
        #If publication form not empty, get publication
        $stmt = $link->prepare("SELECT * FROM publication WHERE type = ? AND (
                                    title = ? OR ((title IS NOT NULL OR title IS NULL) AND ? IS NULL) OR
                                    year = ? OR ((year IS NOT NULL OR year IS NULL) AND ? IS NULL) OR
                                    note = ? OR ((note IS NOT NULL OR note IS NULL) AND ? IS NULL) OR
                                    url = ? OR ((url IS NOT NULL OR url IS NULL) AND ? IS NULL) OR
                                    pubKey = ? OR ((pubKey IS NOT NULL OR pubKey IS NULL) AND ? IS NULL))
                              ");
        $stmt->bind_param("sssiissssss",$type,$title,$title,$year,$year,$note,$note,$url,$url,$key,$key);
        $stmt->execute();
        $result = $stmt->get_result();
        #echo empty($resutl);
        while($row = $result->fetch_assoc()){
            $publication = $row;
            print_r($row);echo '<br>?';
        }
        mysqli_commit($link); $stmt->close();$i=0;
        #print_r($publication);
      }

      if($type == "Inproceedings"){
        $bTitle =mysqli_real_escape_string($link, $_REQUEST['bTitle']);
        $bEditor =mysqli_real_escape_string($link, $_REQUEST['bEditor']);
        $bVolume =mysqli_real_escape_string($link, $_REQUEST['bVolume']);
        $bNumber =mysqli_real_escape_string($link, $_REQUEST['bNumber']);
        $bSeries =mysqli_real_escape_string($link, $_REQUEST['bSeries']);
        $bPages =mysqli_real_escape_string($link, $_REQUEST['bPages']);
        $bAddress =mysqli_real_escape_string($link, $_REQUEST['bAddress']);
        $bOrganization =mysqli_real_escape_string($link, $_REQUEST['bOrganization']);
        $bPublisher =mysqli_real_escape_string($link, $_REQUEST['bPublisher']);
        $bDOI =mysqli_real_escape_string($link, $_REQUEST['bDOI']);
        #echo "<br>Post is inproceeding!<br><br>";

        #If publication form is empty
        if($empty_publication == 1){
          #if inproceedings form is empty as well
          if(empty($bTitle) && empty($bEditor) &&empty($bVolume) &&empty($bNumber) &&empty($bSeries)
          &&empty($bPages) &&empty($bAddress) &&empty($bAddress) &&empty($bPublisher) && empty($bDOI) && empty($bOrganization)){
            echo "<script>alert('No results found!');
                  window.location.replace('../index.php');
                  </script>";
          }
          #if inproceedings form is not Empty
          else{
            $stmt = $link->prepare("SELECT * FROM inproceedings WHERE
                                        publisher = ? OR ((publisher IS NOT NULL OR publisher IS NULL) AND ? IS NULL) OR
                                        organization = ? OR ((booktitle IS NOT NULL OR organization IS NULL) AND ? IS NULL) OR
                                        booktitle = ? OR ((booktitle IS NOT NULL OR booktitle IS NULL) AND ? IS NULL) OR
                                        pages = ? OR ((pages IS NOT NULL OR pages IS NULL) AND ? IS NULL) OR
                                        doi = ? OR ((doi IS NOT NULL OR doi IS NULL) AND ? IS NULL) OR
                                        editor = ? OR ((editor IS NOT NULL OR editor IS NULL) AND ? IS NULL) OR
                                        series = ? OR ((series IS NOT NULL OR series IS NULL) AND ? IS NULL) OR
                                        address = ? OR ((address IS NOT NULL OR address IS NULL) AND ? IS NULL)
                                  ");
            $stmt->bind_param("ssssssssssssssss",$bPublisher,$bPublisher,$bOrganization,$bOrganization,$bTitle,$bTitle,$bPages,$bPages,$bDOI,$bDOI,$bEditor,$bEditor,$bSeries,$bSeries,$bAddress,$bAddress);
            $stmt->execute();
            $result = $stmt->get_result();
            $inproceedings = array();
            while($row = $result->fetch_assoc()){
              $inproceedings = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);echo "<br><br>";
            #echo $inproceedings['publicationID'];

            #Get publication info based on searched Inproceeding
            $stmt = $link->prepare("SELECT * FROM publication WHERE publicationID = ?");
            $stmt->bind_param("i",$inproceedings['publicationID']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $publication = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($publication);
          }
        }
        #If publication form is not empty
        else{
          #If Inpro form is empty
          if(empty($bTitle) && empty($bEditor) &&empty($bVolume) &&empty($bNumber) &&empty($bSeries)
          &&empty($bPages) &&empty($bAddress) &&empty($bAddress) &&empty($bPublisher) && empty($bDOI) && empty($bOrganization)){
            #echo "Inproceedings form is empty";
            #Based on the publication, get inproceedings info
            $stmt = $link->prepare("SELECT * FROM inproceedings WHERE publicationID = ?");
            $stmt->bind_param("i",$publication['publicationID']);
            $stmt->execute();
            $inproceedings = array();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $inproceedings = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);
            if(empty($inproceedings)){
              echo "<script>alert('No results found!');
                    window.location.replace('../index.php');
                    </script>";
            }
          }
          #if inpro form is not empty
          else{
            #echo "Inproceedings form is not empty<br>";
            $stmt = $link->prepare("SELECT * FROM inproceedings WHERE
                                        publisher = ? OR ((publisher IS NOT NULL OR publisher IS NULL) AND ? IS NULL) OR
                                        organization = ? OR ((booktitle IS NOT NULL OR organization IS NULL) AND ? IS NULL) OR
                                        booktitle = ? OR ((booktitle IS NOT NULL OR booktitle IS NULL) AND ? IS NULL) OR
                                        pages = ? OR ((pages IS NOT NULL OR pages IS NULL) AND ? IS NULL) OR
                                        doi = ? OR ((doi IS NOT NULL OR doi IS NULL) AND ? IS NULL) OR
                                        editor = ? OR ((editor IS NOT NULL OR editor IS NULL) AND ? IS NULL) OR
                                        series = ? OR ((series IS NOT NULL OR series IS NULL) AND ? IS NULL) OR
                                        address = ? OR ((address IS NOT NULL OR address IS NULL) AND ? IS NULL)
                                  ");
            $stmt->bind_param("ssssssssssssssss",$bPublisher,$bPublisher,$bOrganization,$bOrganization,$bTitle,$bTitle,$bPages,$bPages,$bDOI,$bDOI,$bEditor,$bEditor,$bSeries,$bSeries,$bAddress,$bAddress);
            $stmt->execute();
            $result = $stmt->get_result();
            $inproceedings = array();
            while($row = $result->fetch_assoc()){
              $inproceedings = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);echo "<br><br>";

            if($inproceedings['publicationID'] == $publication['publicationID']){
              if((empty($publication)||empty($inproceedings)))
                #echo "Kaching $$$";
                echo "<script>alert('No results found!');
                      window.location.replace('../index.php');
                      </script>";
            }
            else{
              echo "<script>alert('No results found!');
                    window.location.replace('../index.php');
                    </script>";
            }
          }
        }
        #Print the result
        echo "<br>Publication's ID: [",$publication['publicationID'],'] ',$publication['type'],'<br>';
        echo "URL: ",$publication['url'],'<br>';
        echo "Title: <b>",$publication['title'],'</b><br>';
        echo "Note: ",$publication['note'],'<br>';
        echo "Published at: ",$publication['month'],"/",$publication['year'],'<br>';
        echo "PubKey: ",$publication['pubKey'];
        echo "<br>Inproceeding's ID: ",$inproceedings['inproceedingsID'];
        echo "<br>Booktitle: ",$inproceedings['booktitle'];
        echo "<br>Publisher: ",$inproceedings['publisher'];
        echo "<br>Editor: ",$inproceedings['editor'];
        echo "<br>Volume: ",$inproceedings['volume'],", ",$inproceedings['number']," Series: ",$inproceedings['series'],$inproceedings['pages']," pages";
        echo "<br>Organization: ",$inproceedings['organization'];
        echo "<br>Address: ",$inproceedings['address'];
        echo "<br>DOI: ",$inproceedings['doi'],"<br>";
      }
      else if($type == 'Article'){
        $aJournal =mysqli_real_escape_string($link, $_REQUEST['aJournal']);
        $aVolume =mysqli_real_escape_string($link, $_REQUEST['aVolume']);
        $aNumber =mysqli_real_escape_string($link, $_REQUEST['aNumber']);
        $aPages =mysqli_real_escape_string($link, $_REQUEST['aPages']);
        $aDOI =mysqli_real_escape_string($link, $_REQUEST['aDOI']);
        $article = array();
        #echo "<br>Post is article!<br><br>";

        #If publication form is empty
        if($empty_publication == 1){
          #if inproceedings form is empty as well
          if(empty($aJournal) && empty($aVolume) &&empty($aNumber) &&empty($aPages) &&empty($aDOI)){
            echo "<script>alert('No results found!');
                  window.location.replace('../index.php');
                  </script>";
          }
          #if article form is not Empty
          else{
            $stmt = $link->prepare("SELECT * FROM article WHERE
                                        journal = ? OR ((journal IS NOT NULL OR journal IS NULL) AND ? IS NULL) OR
                                        volume = ? OR ((volume IS NOT NULL OR volume IS NULL) AND ? IS NULL) OR
                                        number = ? OR ((number IS NOT NULL OR number IS NULL) AND ? IS NULL) OR
                                        pages = ? OR ((pages IS NOT NULL OR pages IS NULL) AND ? IS NULL) OR
                                        doi = ? OR ((doi IS NOT NULL OR doi IS NULL) AND ? IS NULL)
                                  ");
            $stmt->bind_param("ssiiiissss",$aJournal,$aJournal,$aVolume,$aVolume,$aNumber,$aNumber,$aPages,$aPages,$aDOI,$aDOI);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $article = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($article);echo "<br><br>";
            #echo $article['publicationID'];

            #Get publication info based on searched Inproceeding
            $stmt = $link->prepare("SELECT * FROM publication WHERE publicationID = ?");
            $stmt->bind_param("i",$article['publicationID']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $publication = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($publication);
          }
        }
        #If publication form is not empty
        else{
          #If Inpro form is empty
          if(empty($aJournal) && empty($aVolume) &&empty($aNumber) &&empty($aPages) &&empty($aDOI)){
            #echo "Inproceedings form is empty";
            #Based on the publication, get articles info
            $stmt = $link->prepare("SELECT * FROM article WHERE publicationID = ?");
            $stmt->bind_param("i",$publication['publicationID']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $article = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);
            if(empty($article)){
              echo "<script>alert('No results found!');
                    window.location.replace('../index.php');
                    </script>";
            }
          }
          #if inpro form is not empty
          else{
            #echo "Inproceedings form is not empty<br>";
            $stmt = $link->prepare("SELECT * FROM article WHERE
                                        journal = ? OR ((journal IS NOT NULL OR journal IS NULL) AND ? IS NULL) OR
                                        volume = ? OR ((volume IS NOT NULL OR volume IS NULL) AND ? IS NULL) OR
                                        number = ? OR ((number IS NOT NULL OR number IS NULL) AND ? IS NULL) OR
                                        pages = ? OR ((pages IS NOT NULL OR pages IS NULL) AND ? IS NULL) OR
                                        doi = ? OR ((doi IS NOT NULL OR doi IS NULL) AND ? IS NULL)
                                  ");
            $stmt->bind_param("ssiiiissss",$aJournal,$aJournal,$aVolume,$aVolume,$aNumber,$aNumber,$aPages,$aPages,$aDOI,$aDOI);
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $article = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);echo "<br><br>";

            if($article['publicationID'] == $publication['publicationID']){
              if((empty($publication)||empty($article)))
                #echo "Kaching $$$";
                echo "<script>alert('No results found!');
                      window.location.replace('../index.php');
                      </script>";
            }
            else{
              echo "<script>alert('No results found!');
                    window.location.replace('../index.php');
                    </script>";
            }
          }
        }
        #Print the result
        echo "<br>Publication's ID: [",$publication['publicationID'],'] ',$publication['type'],'<br>';
        echo "URL: ",$publication['url'],'<br>';
        echo "Title: <b>",$publication['title'],'</b><br>';
        echo "Note: ",$publication['note'],'<br>';
        echo "Published at: ",$publication['month'],"/",$publication['year'],'<br>';
        echo "PubKey: ",$publication['pubKey'];
        echo "<br>Journal: ",$article['journal'];
        echo "<br>Volume: ",$article['volume'],", ",$article['number'],", ",$article['pages']," pages";
        echo "<br>DOI: ",$article['doi'],"<br><br>";
      }
      else if($type == 'Book'){
        $cPublisher =mysqli_real_escape_string($link, $_REQUEST['cPublisher']);
        $cVolume =mysqli_real_escape_string($link, $_REQUEST['cVolume']);
        $cNumber =mysqli_real_escape_string($link, $_REQUEST['cNumber']);
        $cSeries =mysqli_real_escape_string($link, $_REQUEST['cSeries']);
        $cAddress =mysqli_real_escape_string($link, $_REQUEST['cAddress']);
        $cEdition =mysqli_real_escape_string($link, $_REQUEST['cEdition']);
        $cMonth =mysqli_real_escape_string($link, $_REQUEST['cMonth']);
        $cISBN =mysqli_real_escape_string($link, $_REQUEST['cISBN']);
        $cISSN =mysqli_real_escape_string($link, $_REQUEST['cISSN']);
        $book = array();

        #If publication form is empty
        if($empty_publication == 1){
          #if inproceedings form is empty as well
          if(empty($cPublisher) && empty($cVolume) &&empty($cNumber) &&empty($cSeries) &&empty($cAddress) &&
              empty($cEdition) && empty($cMonth) &&empty($cISBN) &&empty($cISSN)){
            echo "<script>alert('No results found!');
                  window.location.replace('../index.php');
                  </script>";
          }
          #if article form is not Empty
          else{
            $stmt = $link->prepare("SELECT * FROM book WHERE
                                        publisher = ? OR ((publisher IS NOT NULL OR publisher IS NULL) AND ? IS NULL) OR
                                        series = ? OR ((journal IS NOT NULL OR series IS NULL) AND ? IS NULL) OR
                                        volume = ? OR ((volume IS NOT NULL OR volume IS NULL) AND ? IS NULL) OR
                                        number = ? OR ((number IS NOT NULL OR number IS NULL) AND ? IS NULL) OR
                                        address = ? OR ((address IS NOT NULL OR address IS NULL) AND ? IS NULL) OR
                                        edition = ? OR ((edition IS NOT NULL OR edition IS NULL) AND ? IS NULL) OR
                                        month = ? OR ((month IS NOT NULL OR month IS NULL) AND ? IS NULL) OR
                                        ISBN = ? OR ((ISBN IS NOT NULL OR ISBN IS NULL) AND ? IS NULL) OR
                                        ISSN = ? OR ((ISSN IS NOT NULL OR ISSN IS NULL) AND ? IS NULL) OR
                                  ");
            $stmt->bind_param("ssssiiiissssiissss",$cPublisher,$cPublisher,$cSeries,$cSeries,$cVolume,$cVolume,$cNumber,$cNumber,$cAddress,$cAddress,$cEdition,$cEdition,$cMonth,$cMonth,$cISBN,$cISBN,$cISSN,$cISSN);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $book = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($article);echo "<br><br>";
            #echo $article['publicationID'];

            #Get publication info based on searched Inproceeding
            $stmt = $link->prepare("SELECT * FROM publication WHERE publicationID = ?");
            $stmt->bind_param("i",$book['publicationID']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $publication = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($publication);
          }
        }
        #If publication form is not empty
        else{
          #If Inpro form is empty
          if(empty($cPublisher) && empty($cVolume) &&empty($cNumber) &&empty($cSeries) &&empty($cAddress) &&
              empty($cEdition) && empty($cMonth) &&empty($cISBN) &&empty($cISSN)){
            #echo "Book form is empty";
            #Based on the publication, get articles info
            $stmt = $link->prepare("SELECT * FROM book WHERE publicationID = ?");
            $stmt->bind_param("i",$publication['publicationID']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $book = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);
            if(empty($book)){
              echo "<script>alert('No results found!');
                    window.location.replace('../index.php');
                    </script>";
            }
          }
          #if inpro form is not empty
          else{
            #echo "Book form is not empty<br>";
            $stmt = $link->prepare("SELECT * FROM book WHERE
                                        publisher = ? OR ((publisher IS NOT NULL OR publisher IS NULL) AND ? IS NULL) OR
                                        series = ? OR ((journal IS NOT NULL OR series IS NULL) AND ? IS NULL) OR
                                        volume = ? OR ((volume IS NOT NULL OR volume IS NULL) AND ? IS NULL) OR
                                        number = ? OR ((number IS NOT NULL OR number IS NULL) AND ? IS NULL) OR
                                        address = ? OR ((address IS NOT NULL OR address IS NULL) AND ? IS NULL) OR
                                        edition = ? OR ((edition IS NOT NULL OR edition IS NULL) AND ? IS NULL) OR
                                        month = ? OR ((month IS NOT NULL OR month IS NULL) AND ? IS NULL) OR
                                        ISBN = ? OR ((ISBN IS NOT NULL OR ISBN IS NULL) AND ? IS NULL) OR
                                        ISSN = ? OR ((ISSN IS NOT NULL OR ISSN IS NULL) AND ? IS NULL)
                                  ");
            $stmt->bind_param("ssssiiiissssiissss",$cPublisher,$cPublisher,$cSeries,$cSeries,$cVolume,$cVolume,$cNumber,$cNumber,$cAddress,$cAddress,$cEdition,$cEdition,$cMonth,$cMonth,$cISBN,$cISBN,$cISSN,$cISSN);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $book = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);echo "<br><br>";

            if($book['publicationID'] == $publication['publicationID']){
              if((empty($publication)||empty($book)))
                #echo "Kaching $$$";
                echo "<script>alert('No results found!');
                      window.location.replace('../index.php');
                      </script>";
            }
            else{
              echo "<script>alert('No results found!');
                    window.location.replace('../index.php');
                    </script>";
            }
          }
        }
        #Print the result
        echo "<br>Publication's ID: [",$publication['publicationID'],'] ',$publication['type'],'<br>';
        echo "URL: ",$publication['url'],'<br>';
        echo "Title: <b>",$publication['title'],'</b><br>';
        echo "Note: ",$publication['note'],'<br>';
        echo "Published at: ",$publication['month'],"/",$publication['year'],'<br>';
        echo "PubKey: ",$publication['pubKey'];
        echo "<br>Book's ID: ",$book['bookID'];
        echo "<br>Publisher: ",$book['publisher'];
        echo "<br>Volume: ",$book['volume'],", ",$book['number']," Series: ",$book['series'];
        echo "<br>Address: ",$book['address'];
        echo "<br>Edition: ",$book['edition'],", ",$book['month'];
        echo "<br>ISBN: ",$book['ISBN'],", ISSN: ",$book['ISSN'],"<br><br>";
      }
      else if($type == 'Inbook'){
        $dPublisher =mysqli_real_escape_string($link, $_REQUEST['dPublisher']);
        $dChapter =mysqli_real_escape_string($link, $_REQUEST['dChapter']);
        $dPages =mysqli_real_escape_string($link, $_REQUEST['dPages']);
        $dVolume =mysqli_real_escape_string($link, $_REQUEST['dVolume']);
        $dNumber =mysqli_real_escape_string($link, $_REQUEST['dNumber']);
        $dSeries =mysqli_real_escape_string($link, $_REQUEST['dSeries']);
        $dType =mysqli_real_escape_string($link, $_REQUEST['dType']);
        $dAddress =mysqli_real_escape_string($link, $_REQUEST['dAddress']);
        $dEdition =mysqli_real_escape_string($link, $_REQUEST['dEdition']);
        $dISBN =mysqli_real_escape_string($link, $_REQUEST['dISBN']);
        $dISSN =mysqli_real_escape_string($link, $_REQUEST['dISSN']);
        $inbook = array();

        #If publication form is empty
        if($empty_publication == 1){
          #if inproceedings form is empty as well
          if(empty($dPublisher) && empty($dChapter) &&empty($dPages) &&empty($dVolume) &&empty($dNumber) &&
              empty($dSeries) && empty($dType) &&empty($dAddress) &&empty($dEdition) && empty($dISBN) &&empty($dISSN)){
            echo "<script>alert('No results found!');
                  window.location.replace('../index.php');
                  </script>";
          }
          #if article form is not Empty
          else{
            $stmt = $link->prepare("SELECT * FROM inbook WHERE
                                        publisher = ? OR ((publisher IS NOT NULL OR publisher IS NULL) AND ? IS NULL) OR
                                        chapter = ? OR ((chapter IS NOT NULL OR chapter IS NULL) AND ? IS NULL) OR
                                        pages = ? OR ((pages IS NOT NULL OR pages IS NULL) AND ? IS NULL) OR
                                        type = ? OR ((type IS NOT NULL OR type IS NULL) AND ? IS NULL) OR
                                        series = ? OR ((journal IS NOT NULL OR series IS NULL) AND ? IS NULL) OR
                                        volume = ? OR ((volume IS NOT NULL OR volume IS NULL) AND ? IS NULL) OR
                                        number = ? OR ((number IS NOT NULL OR number IS NULL) AND ? IS NULL) OR
                                        address = ? OR ((address IS NOT NULL OR address IS NULL) AND ? IS NULL) OR
                                        edition = ? OR ((edition IS NOT NULL OR edition IS NULL) AND ? IS NULL) OR
                                        ISBN = ? OR ((ISBN IS NOT NULL OR ISBN IS NULL) AND ? IS NULL) OR
                                        ISSN = ? OR ((ISSN IS NOT NULL OR ISSN IS NULL) AND ? IS NULL)
                                  ");
            $stmt->bind_param("ssssssssssiiiissssssss",$dPublisher,$dPublisher,$dChapter,$dChapter,$dPages,$dPages,$dType,$dType,$dSeries,$dSeries,$dVolume,$dVolume,$dNumber,$dNumber,$dAddress,$dAddress,$dEdition,$dEdition,$dISBN,$dISBN,$dISSN,$dISSN);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $inbook = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($article);echo "<br><br>";
            #echo $article['publicationID'];

            #Get publication info based on searched Inproceeding
            $stmt = $link->prepare("SELECT * FROM publication WHERE publicationID = ?");
            $stmt->bind_param("i",$inbook['publicationID']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $publication = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($publication);
          }
        }
        #If publication form is not empty
        else{
          #If Inpro form is empty
          if(empty($dPublisher) && empty($dChapter) &&empty($dPages) &&empty($dVolume) &&empty($dNumber) &&
              empty($dSeries) && empty($dType) &&empty($dAddress) &&empty($dEdition) && empty($dISBN) &&empty($dISSN)){
            #echo "Book form is empty";
            #Based on the publication, get articles info
            $stmt = $link->prepare("SELECT * FROM inbook WHERE publicationID = ?");
            $stmt->bind_param("i",$publication['publicationID']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $inbook = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);
            if(empty($inbook)){
              echo "<script>alert('No results found!');
                    window.location.replace('../index.php');
                    </script>";
            }
          }
          #if inpro form is not empty
          else{
            #echo "Book form is not empty<br>";
            $stmt = $link->prepare("SELECT * FROM inbook WHERE
                                        publisher = ? OR ((publisher IS NOT NULL OR publisher IS NULL) AND ? IS NULL) OR
                                        chapter = ? OR ((chapter IS NOT NULL OR chapter IS NULL) AND ? IS NULL) OR
                                        pages = ? OR ((pages IS NOT NULL OR pages IS NULL) AND ? IS NULL) OR
                                        type = ? OR ((type IS NOT NULL OR type IS NULL) AND ? IS NULL) OR
                                        series = ? OR ((journal IS NOT NULL OR series IS NULL) AND ? IS NULL) OR
                                        volume = ? OR ((volume IS NOT NULL OR volume IS NULL) AND ? IS NULL) OR
                                        number = ? OR ((number IS NOT NULL OR number IS NULL) AND ? IS NULL) OR
                                        address = ? OR ((address IS NOT NULL OR address IS NULL) AND ? IS NULL) OR
                                        edition = ? OR ((edition IS NOT NULL OR edition IS NULL) AND ? IS NULL) OR
                                        ISBN = ? OR ((ISBN IS NOT NULL OR ISBN IS NULL) AND ? IS NULL) OR
                                        ISSN = ? OR ((ISSN IS NOT NULL OR ISSN IS NULL) AND ? IS NULL)
                                  ");
            $stmt->bind_param("ssssssssssiiiissssssss",$dPublisher,$dPublisher,$dChapter,$dChapter,$dPages,$dPages,$dType,$dType,$dSeries,$dSeries,$dVolume,$dVolume,$dNumber,$dNumber,$dAddress,$dAddress,$dEdition,$dEdition,$dISBN,$dISBN,$dISSN,$dISSN);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $inbook = $row;
              #print_r($row);
            }
            mysqli_commit($link); $stmt->close();
            #print_r($inproceedings);echo "<br><br>";

            if($inbook['publicationID'] == $publication['publicationID']){
              if((empty($publication)||empty($inbook)))
                #echo "Kaching $$$";
                echo "<script>alert('No results found!');
                      window.location.replace('../index.php');
                      </script>";
            }
            else{
              echo "<script>alert('No results found!');
                    window.location.replace('../index.php');
                    </script>";
            }
          }
        }
        #Print the result
        echo "<br>Publication's ID: [",$publication['publicationID'],'] ',$publication['type'],'<br>';
        echo "URL: ",$publication['url'],'<br>';
        echo "Title: <b>",$publication['title'],'</b><br>';
        echo "Note: ",$publication['note'],'<br>';
        echo "Published at: ",$publication['month'],"/",$publication['year'],'<br>';
        echo "PubKey: ",$publication['pubKey'];
        echo "<br>Inbook's ID: ",$inbook['inbookID'];
        echo "<br>Publisher: ",$inbook['publisher'];
        echo "<br>Volume: ",$inbook['volume'],", ",$inbook['number']," Series: ",$inbook['series'],", Chapter: ",$inbook['chapter'],$inbook['pages']," pages";
        echo "<br>Type: ",$inbook['type'];
        echo "<br>Address: ",$inbook['address'];
        echo "<br>Edition: ",$inbook['edition'];
        echo "<br>ISBN: ",$inbook['ISBN'],", ISSN: ",$inbook['ISSN'],"<br><br>";
      }

## PRINT AUTHORS OF PUBLICATION
      if(empty($publication)){
        echo "<script>alert('No results found!');
              window.location.replace('../index.php');
              </script>";
      }
      $stmt = $link->prepare("SELECT * FROM authors WHERE authorsID = ?");
      $stmt->bind_param("i",$publication['authorsID']);
      $stmt->execute();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $publication = $row;
      }
      $stmt = $link->prepare("SELECT author,author2,author3,author4,author5 FROM authors WHERE authorsID = ?");
      $stmt->bind_param("i",$publication['authorsID']);
      $stmt->execute();
      $authorIDlist = array();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $authorIDlist = $row;
      }
      $stmt->close();

      if($authorIDlist['author']==$authorIDlist['author2']){
        $authorIDlist['author2'] = NULL;
      }if($authorIDlist['author']==$authorIDlist['author3']){
        $authorIDlist['author3'] = NULL;
      }if($authorIDlist['author']==$authorIDlist['author4']){
        $authorIDlist['author4'] = NULL;
      }if($authorIDlist['author']==$authorIDlist['author5']){
        $authorIDlist['author5'] = NULL;
      }
      #Author 1 information
      $stmt = $link->prepare("SELECT * FROM author WHERE authorID = ?");
      $stmt->bind_param("i",$authorIDlist['author']);
      $stmt->execute();
      $authorinfo = array();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $authorinfo = $row;
      }
      $stmt->close();
      #Author 2 information
      $stmt = $link->prepare("SELECT * FROM author WHERE authorID = ?");
      $stmt->bind_param("i",$authorIDlist['author2']);
      $stmt->execute();
      $author2info = array();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $author2info = $row;
      }
      $stmt->close();
      #Author 3 information
      $stmt = $link->prepare("SELECT * FROM author WHERE authorID = ?");
      $stmt->bind_param("i",$authorIDlist['author3']);
      $stmt->execute();
      $author3info = array();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $author3info = $row;
      }
      $stmt->close();
      #Author 4 information
      $stmt = $link->prepare("SELECT * FROM author WHERE authorID = ?");
      $stmt->bind_param("i",$authorIDlist['author4']);
      $stmt->execute();
      $author4info = array();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $author4info = $row;
      }
      $stmt->close();
      #Author 5 information
      $stmt = $link->prepare("SELECT * FROM author WHERE authorID = ?");
      $stmt->bind_param("i",$authorIDlist['author5']);
      $stmt->execute();
      $author5info = array();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $author5info = $row;
      }
      $stmt->close();
      echo '<br>',"Authors' ID: ",$authorIDlist['author']," ",$authorIDlist['author2']," ",$authorIDlist['author3']," ",$authorIDlist['author4']," ",$authorIDlist['author5'];

      echo '<br>',"Authors: ";

      if(!empty($authorinfo['name'])){
        echo $authorinfo['name'], " ",$authorinfo['surname'];
      }
      if(!empty($author2info['name'])){
        echo ', ',$author2info['name'], " ",$author2info['surname'];
      }
      if(!empty($author3info['name'])){
        echo ', ',$author3info['name'], " ",$author3info['surname'];
      }
      if(!empty($author4info['name'])){
        echo ', ',$author4info['name'], " ",$author4info['surname'];
      }
      if(!empty($author5info['name'])){
        echo ', ',$author5info['name'], " ",$author5info['surname'];
      }
      echo '<br>';
      echo "  </div>
            </div>
          </div>
          <!-- Optional JavaScript -->
          <!-- jQuery first, then Popper.js, then Bootstrap JS -->
          <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js' integrity='sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj' crossorigin='anonymous'></script>
          <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js' integrity='sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo' crossorigin='anonymous'></script>
          <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js' integrity='sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI' crossorigin='anonymous'></script>
        </body>
      </html>";
?>
