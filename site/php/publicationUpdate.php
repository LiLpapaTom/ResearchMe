<?php
    session_start();
    include "connect.php";
    error_reporting(0);

    if($_SERVER['REQUEST_METHOD'] == "POST"){
      #Get the common fields for all types of publications
      $publicationID = mysqli_real_escape_string($link,$_REQUEST['publicationid']);
    	$title = mysqli_real_escape_string($link, $_REQUEST['title']);
    	$year = mysqli_real_escape_string($link, $_REQUEST['year']);
      $month =mysqli_real_escape_string($link, $_REQUEST['month']);
      $note =mysqli_real_escape_string($link, $_REQUEST['note']);
      $url =mysqli_real_escape_string($link, $_REQUEST['url']);
      $key =mysqli_real_escape_string($link, $_REQUEST['key']);
      $type =mysqli_real_escape_string($link, $_REQUEST['type']);

      //Get authors ID from table if it exists
      $stmt = $link->prepare("SELECT * FROM publication WHERE publicationID = ?");
      $stmt->bind_param("i",$var);
      $var = intval($publicationID);
      $stmt->execute();
      $publication = array();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $publication = $row;
      }
      $stmt->close();
      //if post exists, we get a result
      if(!empty($publication) && $type==$publication['type']){
        #print_r($publication);

        //Update title, if not empty
        if(!empty($title)){
          $stmt = $link->prepare("UPDATE publication SET title = ? WHERE publicationID = ?");
          $stmt->bind_param("si",$title,$publication['publicationID']);
          $stmt->execute();
        }
        //Update year, if not empty
        if(!empty($year)){
          $stmt = $link->prepare("UPDATE publication SET year = ? WHERE publicationID = ?");
          $stmt->bind_param("ii",$year,$publication['publicationID']);
          $stmt->execute();
        }
        //Update year, if not empty
        if(!empty($month)){
          $stmt = $link->prepare("UPDATE publication SET month = ? WHERE publicationID = ?");
          $stmt->bind_param("ii",$month,$publication['publicationID']);
          $stmt->execute();
        }
        //Update note, if not empty
        if(!empty($month)){
          $stmt = $link->prepare("UPDATE publication SET note = ? WHERE publicationID = ?");
          $stmt->bind_param("si",$note,$publication['publicationID']);
          $stmt->execute();
        }
        //Update url, if not empty
        if(!empty($url)){
          $stmt = $link->prepare("UPDATE publication SET url = ? WHERE publicationID = ?");
          $stmt->bind_param("si",$url,$publication['publicationID']);
          $stmt->execute();
        }
        //Update key, if not empty
        if(!empty($key)){
          $stmt = $link->prepare("UPDATE publication SET pubKey = ? WHERE publicationID = ?");
          $stmt->bind_param("si",$key,$publication['publicationID']);
          $stmt->execute();
        }
        //Update Article
        if($type == 'Article'){
          $aJournal =mysqli_real_escape_string($link, $_REQUEST['aJournal']);
          $aVolume =mysqli_real_escape_string($link, $_REQUEST['aVolume']);
          $aNumber =mysqli_real_escape_string($link, $_REQUEST['aNumber']);
          $aPages =mysqli_real_escape_string($link, $_REQUEST['aPages']);
          $aDOI =mysqli_real_escape_string($link, $_REQUEST['aDOI']);
          //Update journal, if not empty
          if(!empty($aJournal)){
            $stmt = $link->prepare("UPDATE article SET journal = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$aJournal,$publication['publicationID']);
            $stmt->execute();
          }
          //Update journal, if not empty
          if(!empty($aVolume)){
            $stmt = $link->prepare("UPDATE article SET volume = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$aVolume,$publication['publicationID']);
            $stmt->execute();
          }
          //Update number, if not empty
          if(!empty($aNumber)){
            $stmt = $link->prepare("UPDATE article SET number = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$aNumber,$publication['publicationID']);
            $stmt->execute();
          }
          //Update pages, if not empty
          if(!empty($aPages)){
            $stmt = $link->prepare("UPDATE article SET pages = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$aPages,$publication['publicationID']);
            $stmt->execute();
          }
          //Update pages, if not empty and DOI doesnt already exist
          if(!empty($aDOI)){
            //Check if DOI already exists
            $stmt = $link->prepare("SELECT publicationID FROM article WHERE doi = ?");
            $stmt->bind_param("i",$aDOI);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = array();
            while($row = $result->fetch_assoc()){
              $data = $row;
            }

            if(empty($data)){
              $stmt = $link->prepare("UPDATE article SET doi = ? WHERE publicationID = ?");
              $stmt->bind_param("si",$aDOI,$publication['publicationID']);
              $stmt->execute();
            }
          }
        }
        else if($type == 'Inproceedings'){
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
          //Update booktitle, if not empty
          if(!empty($bTitle)){
            $stmt = $link->prepare("UPDATE inproceedings SET booktitle = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$bTitle,$publication['publicationID']);
            $stmt->execute();
          }
          //Update editor, if not empty
          if(!empty($bEditor)){
            $stmt = $link->prepare("UPDATE inproceedings SET booktitle = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$bEditor,$publication['publicationID']);
            $stmt->execute();
          }
          //Update volume, if not empty
          if(!empty($bVolume)){
            $stmt = $link->prepare("UPDATE inproceedings SET volume = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$bVolume,$publication['publicationID']);
            $stmt->execute();
          }
          //Update number, if not empty
          if(!empty($bNumber)){
            $stmt = $link->prepare("UPDATE inproceedings SET number = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$bNumber,$publication['publicationID']);
            $stmt->execute();
          }
          //Update series, if not empty
          if(!empty($bSeries)){
            $stmt = $link->prepare("UPDATE inproceedings SET series = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$bSeries,$publication['publicationID']);
            $stmt->execute();
          }
          //Update pages, if not empty
          if(!empty($bPages)){
            $stmt = $link->prepare("UPDATE inproceedings SET pages = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$bPages,$publication['publicationID']);
            $stmt->execute();
          }
          //Update address, if not empty
          if(!empty($bAddress)){
            $stmt = $link->prepare("UPDATE inproceedings SET address = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$bAddress,$publication['publicationID']);
            $stmt->execute();
          }
          //Update Organization, if not empty
          if(!empty($bOrganization)){
            $stmt = $link->prepare("UPDATE inproceedings SET organization = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$bOrganization,$publication['publicationID']);
            $stmt->execute();
          }
          //Update publisher, if not empty
          if(!empty($bPublisher)){
            $stmt = $link->prepare("UPDATE inproceedings SET organization = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$bPublisher,$publication['publicationID']);
            $stmt->execute();
          }
          //Update doi, if not empty
          if(!empty($bDOI)){
            //Check if DOI already exists
            $stmt = $link->prepare("SELECT * FROM inproceedings WHERE doi = ?");
            $stmt->bind_param("s",$bDOI);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = array();
            while($row = $result->fetch_assoc()){
              $data = $row;
            }
            if(empty($data)){
              $stmt = $link->prepare("UPDATE inproceedings SET doi = ? WHERE publicationID = ?");
              $stmt->bind_param("si",$bDOI,$publication['publicationID']);
              $stmt->execute();
            }
          }
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
          //Update publisher, if not empty
          if(!empty($cPublisher)){
            $stmt = $link->prepare("UPDATE book SET publisher = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$cPublisher,$publication['publicationID']);
            $stmt->execute();
          }
          //Update number, if not empty
          if(!empty($cNumber)){
            $stmt = $link->prepare("UPDATE book SET number = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$cNumber,$publication['publicationID']);
            $stmt->execute();
          }
          //Update volume, if not empty
          if(!empty($cVolume)){
            $stmt = $link->prepare("UPDATE book SET volume = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$cVolume,$publication['publicationID']);
            $stmt->execute();
          }
          //Update address, if not empty
          if(!empty($cAddress)){
            $stmt = $link->prepare("UPDATE book SET address = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$cAddress,$publication['publicationID']);
            $stmt->execute();
          }
          //Update edition, if not empty
          if(!empty($cEdition)){
            $stmt = $link->prepare("UPDATE book SET edition = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$cEdition,$publication['publicationID']);
            $stmt->execute();
          }
          //Update series, if not empty
          if(!empty($cSeries)){
            $stmt = $link->prepare("UPDATE book SET series = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$cSeries,$publication['publicationID']);
            $stmt->execute();
          }
          //Update month, if not empty
          if(!empty($cMonth)){
            $stmt = $link->prepare("UPDATE book SET month = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$cMonth,$publication['publicationID']);
            $stmt->execute();
          }
          //Update ISBN, if not empty
          if(!empty($cISBN)){
            //Check if ISBN already exists
            $stmt = $link->prepare("SELECT * FROM book WHERE ISBN = ?");
            $stmt->bind_param("s",$cISBN);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = array();
            while($row = $result->fetch_assoc()){
              $data = $row;
            }
            if(empty($data)){
              $stmt = $link->prepare("UPDATE book SET ISBN = ? WHERE publicationID = ?");
              $stmt->bind_param("ii",$cISBN,$publication['publicationID']);
              $stmt->execute();
            }
          }
          if(!empty($cISSN)){
            //Check if ISSN already exists
            $stmt = $link->prepare("SELECT * FROM book WHERE ISSN = ?");
            $stmt->bind_param("s",$cISSN);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = array();
            while($row = $result->fetch_assoc()){
              $data = $row;
            }
            if(empty($data)){
              $stmt = $link->prepare("UPDATE book SET ISSN = ? WHERE publicationID = ?");
              $stmt->bind_param("ii",$cISSN,$publication['publicationID']);
              $stmt->execute();
            }
          }
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
          //Update publisher, if not empty
          if(!empty($dPublisher)){
            $stmt = $link->prepare("UPDATE inbook SET publisher = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$dPublisher,$publication['publicationID']);
            $stmt->execute();
          }
          //Update chapter, if not empty
          if(!empty($dChapter)){
            $stmt = $link->prepare("UPDATE inbook SET chapter = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$dChapter,$publication['publicationID']);
            $stmt->execute();
          }
          //Update pages, if not empty
          if(!empty($dPages)){
            $stmt = $link->prepare("UPDATE inbook SET pages = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$dPages,$publication['publicationID']);
            $stmt->execute();
          }
          //Update volume, if not empty
          if(!empty($dVolume)){
            $stmt = $link->prepare("UPDATE inbook SET volume = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$dVolume,$publication['publicationID']);
            $stmt->execute();
          }
          //Update number, if not empty
          if(!empty($dNumber)){
            $stmt = $link->prepare("UPDATE inbook SET number = ? WHERE publicationID = ?");
            $stmt->bind_param("ii",$dNumber,$publication['publicationID']);
            $stmt->execute();
          }
          //Update series, if not empty
          if(!empty($dSeries)){
            $stmt = $link->prepare("UPDATE inbook SET series = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$dSeries,$publication['publicationID']);
            $stmt->execute();
          }
          //Update type, if not empty
          if(!empty($dType)){
            $stmt = $link->prepare("UPDATE inbook SET type = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$dType,$publication['publicationID']);
            $stmt->execute();
          }
          //Update address, if not empty
          if(!empty($dAddress)){
            $stmt = $link->prepare("UPDATE inbook SET address = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$dAddress,$publication['publicationID']);
            $stmt->execute();
          }
          //Update edition, if not empty
          if(!empty($dEdition)){
            $stmt = $link->prepare("UPDATE inbook SET edition = ? WHERE publicationID = ?");
            $stmt->bind_param("si",$dEdition,$publication['publicationID']);
            $stmt->execute();
          }
          if(!empty($dISBN)){
            //Check if ISBN already exists
            $stmt = $link->prepare("SELECT * FROM inbook WHERE ISBN = ?");
            $stmt->bind_param("s",$dISBN);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = array();
            while($row = $result->fetch_assoc()){
              $data = $row;
            }
            if(empty($data)){
              $stmt = $link->prepare("UPDATE inbook SET ISBN = ? WHERE publicationID = ?");
              $stmt->bind_param("ii",$dISBN,$publication['publicationID']);
              $stmt->execute();
            }
          }
          if(!empty($dISSN)){
            //Check if ISSN already exists
            $stmt = $link->prepare("SELECT * FROM inbook WHERE ISSN = ?");
            $stmt->bind_param("s",$dISSN);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = array();
            while($row = $result->fetch_assoc()){
              $data = $row;
            }
            if(empty($data)){
              $stmt = $link->prepare("UPDATE inbook SET ISSN = ? WHERE publicationID = ?");
              $stmt->bind_param("ii",$dISSN,$publication['publicationID']);
              $stmt->execute();
            }
          }
        }
      }
    }
    echo "<script>
          window.location.replace('../index.php');
          </script>";
?>
