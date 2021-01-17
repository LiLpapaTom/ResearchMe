<?php
  session_start();
  include "connect.php";
  error_reporting(0);

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name = mysqli_real_escape_string($link, $_REQUEST['name']);
    $surname = mysqli_real_escape_string($link, $_REQUEST['surname']);

    if(empty($name) || empty($surname)){
    	echo "<script>alert('Both fields are required.');
    				window.location.replace('../authorExportAll.php');
    				</script>";
    }
    else{
      mysqli_autocommit($link, false);
      #Get authorID from author table that corresponds to the name,surname given
      $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? AND surname LIKE ?");
      $stmt->bind_param("ss",$name,$surname);
      $surname = "%" . $surname . "%";
      $stmt->execute();
      $stmt->bind_result($authorID);
      $stmt->fetch();
      mysqli_commit($link); $stmt->close();


      #If author exists
      if(!empty($authorID)){
        $authorsIDArray = array(); #Array to hold all authorsID relevant to my author
        $publicationArray = array(); #Array to hold all publicationID relevant to all authorsID
        $inproceedingsArray = array(); #Array to hold all inproceedings
        $articleArray = array(); #Array to hold all articles
        $bookArray = array(); #Array to hold all books
        $inbookArray = array(); #Array to hold all inbooks
        $authorsArray = array(); #Hold all author IDs from authors table
        $authorDetailsArray = array(); #Hold name, surname of authors from authorsArray
        $x = 0; $xI = 0; $xA = 0; $xB = 0; $xIB = 0; $xAuth = 0;

        #Get all authorsID from authors table that include $authorID and add them to $authorsIDArray
        $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? OR author2 = ? OR author3 = ? OR author4 = ? OR author5 = ?");
        $stmt->bind_param("sssss",$authorID,$authorID,$authorID,$authorID,$authorID);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
          $authorsIDArray[] = $row;
        }
        mysqli_commit($link); $stmt->close();



        foreach($authorsIDArray as $row){
          foreach($row as $entry){
            #Get all author IDs for each author from the authors table entry
            $stmt = $link->prepare("SELECT author, author2, author3, author4, author5 FROM authors WHERE authorsID = ?");
            $stmt->bind_param("s",$entry);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row2 = $result->fetch_assoc()){
              array_push($authorsArray,$row2);
            }
            mysqli_commit($link); $stmt->close();


            #Get all columns from publication table that correspond to current authorsID($entry) and add them to $publicationIDArray
            $stmt = $link->prepare("SELECT * FROM publication WHERE authorsID = ?");
            $stmt->bind_param("i",$entry);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row2 = $result->fetch_assoc()){
              $publicationArray[$x] = array("publicationID"=>(String) $row2['publicationID']);
              $publicationArray[$x] += array("type"=>(String) $row2['type']);
              $publicationArray[$x] += array("authorsID"=>(String) $row2['authorsID']);
              $publicationArray[$x] += array("title"=>(String) $row2['title']);
              $publicationArray[$x] += array("year"=>(String) $row2['year']);
              $publicationArray[$x] += array("note"=>(String) $row2['note']);
              $publicationArray[$x] += array("month"=>(String) $row2['month']);
              $publicationArray[$x] += array("url"=>(String) $row2['url']);
              $publicationArray[$x] += array("pubKey"=>(String) $row2['pubKey']);
              $x++;
            }
            mysqli_commit($link); $stmt->close();
          }
        }

        $tmpArray = array();
        #Get Author details (name,surname, orcid) for each author in the $authorsArray
        foreach($authorsArray as $Author){
          foreach($Author as $auth){
            $stmt = $link->prepare("SELECT name, surname, orcid FROM author WHERE authorID = ?");
            $stmt->bind_param("s",$auth);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row2 = $result->fetch_assoc()){
              if($xAuth >= 5){
                array_push($authorDetailsArray,$tmpArray);
                $xAuth = 0;
                $tmpArray = array();
              }
              else{
                array_push($tmpArray, $row2);
                $xAuth++;
              }
            }
            mysqli_commit($link); $stmt->close();
          }
        }
        #Remove duplicates per inner array of authorDetailsArray
        $xAuth = 0;
        foreach($authorDetailsArray as $authorDetails){
          $authorDetailsArray[$xAuth] = array_unique($authorDetails,SORT_REGULAR);
          $xAuth++;
        }

        foreach($publicationArray as $key=>$row){
#INPROC.  #Get all columns from inproceedings table that correspond to a publicationID from the publicationArray
          $stmt = $link->prepare("SELECT * FROM inproceedings WHERE publicationID = ?");
          $stmt->bind_param("i",$row['publicationID']);
          $stmt->execute();
          $result = $stmt->get_result();
          while($row2 = $result->fetch_assoc()){
            $inproceedingsArray[$xI] = array("inproceedingsID"=>(String) $row2['inproceedingsID']);
            $inproceedingsArray[$xI] += array("booktitle"=>(String) $row2['booktitle']);
            $inproceedingsArray[$xI] += array("editor"=>(String) $row2['editor']);
            $inproceedingsArray[$xI] += array("volume"=>(String) $row2['volume']);
            $inproceedingsArray[$xI] += array("number"=>(String) $row2['number']);
            $inproceedingsArray[$xI] += array("series"=>(String) $row2['series']);
            $inproceedingsArray[$xI] += array("pages"=>(String) $row2['pages']);
            $inproceedingsArray[$xI] += array("address"=>(String) $row2['address']);
            $inproceedingsArray[$xI] += array("organization"=>(String) $row2['organization']);
            $inproceedingsArray[$xI] += array("publisher"=>(String) $row2['publisher']);
            $inproceedingsArray[$xI] += array("doi"=>(String) $row2['doi']);
            $inproceedingsArray[$xI] += array("publicationID"=>(String) $row2['publicationID']);
            $xI++;
          }
          mysqli_commit($link); $stmt->close();



#ARTICLE  #Get all columns from article table that correspond to a publicationID from the publicationArray
          $stmt = $link->prepare("SELECT * FROM article WHERE publicationID = ?");
          $stmt->bind_param("i",$row['publicationID']);
          $stmt->execute();
          $result = $stmt->get_result();
          while($row2 = $result->fetch_assoc()){
            $articleArray[$xA] = array("articleID"=>(String) $row2['articleID']);
            $articleArray[$xA] += array("journal"=>(String) $row2['journal']);
            $articleArray[$xA] += array("volume"=>(String) $row2['volume']);
            $articleArray[$xA] += array("number"=>(String) $row2['number']);
            $articleArray[$xA] += array("pages"=>(String) $row2['pages']);
            $articleArray[$xA] += array("doi"=>(String) $row2['doi']);
            $articleArray[$xA] += array("publicationID"=>(String) $row2['publicationID']);
            $xA++;
          }
          mysqli_commit($link); $stmt->close();



#BOOK     #Get all columns from book table that correspond to a publicationID from the publicationArray
          $stmt = $link->prepare("SELECT * FROM book WHERE publicationID = ?");
          $stmt->bind_param("i",$row['publicationID']);
          $stmt->execute();
          $result = $stmt->get_result();
          while($row2 = $result->fetch_assoc()){
            $bookArray[$xB] = array("bookID"=>(String) $row2['bookID']);
            $bookArray[$xB] += array("publisher"=>(String) $row2['publisher']);
            $bookArray[$xB] += array("volume"=>(String) $row2['volume']);
            $bookArray[$xB] += array("number"=>(String) $row2['number']);
            $bookArray[$xB] += array("series"=>(String) $row2['series']);
            $bookArray[$xB] += array("address"=>(String) $row2['address']);
            $bookArray[$xB] += array("edition"=>(String) $row2['edition']);
            $bookArray[$xB] += array("month"=>(String) $row2['month']);
            $bookArray[$xB] += array("ISBN"=>(String) $row2['ISBN']);
            $bookArray[$xB] += array("ISSN"=>(String) $row2['ISSN']);
            $bookArray[$xB] += array("publicationID"=>(String) $row2['publicationID']);
            $xB++;
          }
          mysqli_commit($link); $stmt->close();



#INBOOK   #Get all columns from inbook table that correspond to a publicationID from the publicationArray
          $stmt = $link->prepare("SELECT * FROM inbook WHERE publicationID = ?");
          $stmt->bind_param("i",$row['publicationID']);
          $stmt->execute();
          $result = $stmt->get_result();
          while($row2 = $result->fetch_assoc()){
            $inbookArray[$xIB] = array("inbookID"=>(String) $row2['inbookID']);
            $inbookArray[$xIB] += array("publisher"=>(String) $row2['publisher']);
            $inbookArray[$xIB] += array("chapter"=>(String) $row2['chapter']);
            $inbookArray[$xIB] += array("pages"=>(String) $row2['pages']);
            $inbookArray[$xIB] += array("volume"=>(String) $row2['volume']);
            $inbookArray[$xIB] += array("number"=>(String) $row2['number']);
            $inbookArray[$xIB] += array("series"=>(String) $row2['series']);
            $inbookArray[$xIB] += array("type"=>(String) $row2['type']);
            $inbookArray[$xIB] += array("address"=>(String) $row2['address']);
            $inbookArray[$xIB] += array("edition"=>(String) $row2['edition']);
            $inbookArray[$xIB] += array("ISBN"=>(String) $row2['ISBN']);
            $inbookArray[$xIB] += array("ISSN"=>(String) $row2['ISSN']);
            $inbookArray[$xIB] += array("publicationID"=>(String) $row2['publicationID']);
            $xIB++;
          }
          mysqli_commit($link); $stmt->close();

        }

        #Write arrays to XML file
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0','UTF-8');
        $xml->setIndent(true);
        $xml->startElement('dblpperson');

        foreach($publicationArray as $publication){
          $index = -1;
          #Create the mdate value in the desired format.
          $mdate = $publication['month'];
          if($publication['month'] < 10){
            $mdate = "0" . $publication['month'];
          }
          $mdate = $publication['year'] . "-" . $mdate . "-00";

          $xml->startElement('r');
            #INPROCEEDINGS
            if($publication['type'] == "Inproceedings"){

              foreach($inproceedingsArray as $keyI=>$inproceeding){
                if($publication['publicationID'] == $inproceeding['publicationID']){
                  $index = $keyI;
                  break;
                }
              }
              $xml->startElement('inproceedings');
              $xml->writeAttribute('key',$publication['pubKey']);
              $xml->writeAttribute('mdate',$mdate);

              foreach($authorDetailsArray[$index] as $authorDetails){
                $xml->startElement('author');
                $xml->writeAttribute('orcid', $authorDetails['orcid']);
                $xml->text($authorDetails['name'] . " " . $authorDetails['surname']);
                $xml->endElement(); #author end
              }

              $xml->startElement('title');
                $xml->text($publication['title']);
              $xml->endElement(); #title end

              $xml->startElement('pages');
                $xml->text($inproceedingsArray[$index]['pages']);
              $xml->endElement(); #pages end

              $xml->startElement('year');
                $xml->text($publication['year']);
              $xml->endElement(); #year end

              $xml->startElement('booktitle');
                $xml->text($inproceedingsArray[$index]['booktitle']);
              $xml->endElement(); #booktitle end

              $xml->startElement('ee');
                $xml->text($inproceedingsArray[$index]['doi']);
              $xml->endElement(); #ee end

              $xml->startElement('url');
                $xml->text($publication['url']);
              $xml->endElement(); #url end

              $xml->endElement(); #inproceedings end
            }
            #ARTICLES
            else if($publication['type'] == "Article"){
              foreach($articleArray as $keyI=>$article){
                if($publication['publicationID'] == $article['publicationID']){
                  $index = $keyI;
                  break;
                }
              }

              $xml->startElement('article');
              $xml->writeAttribute('key',$publication['pubKey']);
              $xml->writeAttribute('mdate',$mdate);

              foreach($authorDetailsArray[$index] as $authorDetails){
                $xml->startElement('author');
                $xml->writeAttribute('orcid', $authorDetails['orcid']);
                $xml->text($authorDetails['name'] . " " . $authorDetails['surname']);
                $xml->endElement(); #author end
              }

              $xml->startElement('title');
                $xml->text($publication['title']);
              $xml->endElement(); #title end

              $xml->startElement('pages');
                $xml->text($articleArray[$index]['pages']);
              $xml->endElement(); #pages end

              $xml->startElement('year');
                $xml->text($publication['year']);
              $xml->endElement(); #year end

              $xml->startElement('volume');
                $xml->text($articleArray[$index]['volume']);
              $xml->endElement(); #volume end

              $xml->startElement('journal');
                $xml->text($articleArray[$index]['journal']);
              $xml->endElement(); #journal end

              $xml->startElement('ee');
                $xml->text($articleArray[$index]['doi']);
              $xml->endElement(); #ee end

              $xml->startElement('url');
                $xml->text($publication['url']);
              $xml->endElement(); #url end

              $xml->endElement(); #article end
            }
            #BOOKS
            else if($publication['type'] == "Book"){
              foreach($bookArray as $keyI=>$book){
                if($publication['publicationID'] == $book['publicationID']){
                  $index = $keyI;
                  break;
                }
              }

              $xml->startElement('book');
              $xml->writeAttribute('key',$publication['pubKey']);
              $xml->writeAttribute('mdate',$mdate);

              foreach($authorDetailsArray[$index] as $authorDetails){
                $xml->startElement('author');
                $xml->writeAttribute('orcid', $authorDetails['orcid']);
                $xml->text($authorDetails['name'] . " " . $authorDetails['surname']);
                $xml->endElement(); #author end
              }

              $xml->startElement('title');
                $xml->text($publication['title']);
              $xml->endElement(); #title end

              $xml->startElement('year');
                $xml->text($publication['year']);
              $xml->endElement(); #year end

              $xml->startElement('publisher');
                $xml->text($bookArray[$index]['publisher']);
              $xml->endElement(); #publisher end

              $xml->startElement('volume');
                $xml->text($bookArray[$index]['volume']);
              $xml->endElement(); #volume end

              $xml->startElement('number');
                $xml->text($bookArray[$index]['number']);
              $xml->endElement(); #number end

              $xml->startElement('series');
                $xml->text($bookArray[$index]['series']);
              $xml->endElement(); #series end

              $xml->startElement('address');
                $xml->text($bookArray[$index]['address']);
              $xml->endElement(); #address end

              $xml->startElement('edition');
                $xml->text($bookArray[$index]['edition']);
              $xml->endElement(); #edition end

              $xml->startElement('month');
                $xml->text($bookArray[$index]['month']);
              $xml->endElement(); #month end

              $xml->startElement('ISBN');
                $xml->text($bookArray[$index]['ISBN']);
              $xml->endElement(); #ISBN end

              $xml->startElement('ISSN');
                $xml->text($bookArray[$index]['ISSN']);
              $xml->endElement(); #ISSN end

              $xml->startElement('url');
                $xml->text($publication['url']);
              $xml->endElement(); #url end

              $xml->endElement(); #book end
            }
            #INBOOKS
            else if($publication['type'] == "Inbook"){
              foreach($inbookArray as $keyI=>$inbook){
                if($publication['publicationID'] == $inbook['publicationID']){
                  $index = $keyI;
                  break;
                }
              }

              $xml->startElement('inbook');
              $xml->writeAttribute('key',$publication['pubKey']);
              $xml->writeAttribute('mdate',$mdate);

              foreach($authorDetailsArray[$index] as $authorDetails){
                $xml->startElement('author');
                $xml->writeAttribute('orcid', $authorDetails['orcid']);
                $xml->text($authorDetails['name'] . " " . $authorDetails['surname']);
                $xml->endElement(); #author end
              }

              $xml->startElement('title');
                $xml->text($publication['title']);
              $xml->endElement(); #title end

              $xml->startElement('year');
                $xml->text($publication['year']);
              $xml->endElement(); #year end

              $xml->startElement('publisher');
                $xml->text($inbookArray[$index]['publisher']);
              $xml->endElement(); #publisher end

              $xml->startElement('chapter');
                $xml->text($inbookArray[$index]['chapter']);
              $xml->endElement(); #chapter end

              $xml->startElement('pages');
                $xml->text($inbookArray[$index]['pages']);
              $xml->endElement(); #pages end

              $xml->startElement('volume');
                $xml->text($inbookArray[$index]['volume']);
              $xml->endElement(); #volume end

              $xml->startElement('number');
                $xml->text($inbookArray[$index]['number']);
              $xml->endElement(); #number end

              $xml->startElement('series');
                $xml->text($inbookArray[$index]['series']);
              $xml->endElement(); #series end

              $xml->startElement('type');
                $xml->text($inbookArray[$index]['type']);
              $xml->endElement(); #type end

              $xml->startElement('address');
                $xml->text($inbookArray[$index]['address']);
              $xml->endElement(); #address end

              $xml->startElement('edition');
                $xml->text($inbookArray[$index]['edition']);
              $xml->endElement(); #edition end

              $xml->startElement('ISBN');
                $xml->text($inbookArray[$index]['ISBN']);
              $xml->endElement(); #ISBN end

              $xml->startElement('ISSN');
                $xml->text($inbookArray[$index]['ISSN']);
              $xml->endElement(); #ISSN end

              $xml->endElement(); #Inbook end
            }


          $xml->endElement(); #r end
        }


        $xml->endElement(); #dblpperson end
        $xml->endDocument();
        $xmlToWrite = $xml->outputMemory();

        $myFile = fopen("../assets/authorAllPublications.xml", "w") or die("Cannot open file!");
        fwrite($myFile, $xmlToWrite);
        fclose($myFile);

        echo "<script>alert('File successfully exported!');
      				window.location.replace('../authorExportAll.php');
      				</script>";
      }
      #Else author not found
      else{
        echo "<script>alert('Author not found.');
      				window.location.replace('../authorExportAll.php');
      				</script>";
      }

    }
  }

?>
