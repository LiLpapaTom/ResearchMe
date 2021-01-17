<?php
    session_start();
    include "connect.php";
    error_reporting(0);

    if($_SERVER['REQUEST_METHOD'] == "POST"){
      #Get the common fields for all types of publications
    	$name = mysqli_real_escape_string($link, $_REQUEST['name']);
    	$surname = mysqli_real_escape_string($link, $_REQUEST['surname']);
      $name2 = mysqli_real_escape_string($link, $_REQUEST['name2']);
    	$surname2 = mysqli_real_escape_string($link, $_REQUEST['surname2']);
      $name3 = mysqli_real_escape_string($link, $_REQUEST['name3']);
    	$surname3 = mysqli_real_escape_string($link, $_REQUEST['surname3']);
      $name4 = mysqli_real_escape_string($link, $_REQUEST['name4']);
    	$surname4 = mysqli_real_escape_string($link, $_REQUEST['surname4']);
      $name5 = mysqli_real_escape_string($link, $_REQUEST['name5']);
    	$surname5 = mysqli_real_escape_string($link, $_REQUEST['surname5']);
    	$title = mysqli_real_escape_string($link, $_REQUEST['title']);
    	$year = mysqli_real_escape_string($link, $_REQUEST['year']);
      $month =mysqli_real_escape_string($link, $_REQUEST['month']);
      $note =mysqli_real_escape_string($link, $_REQUEST['note']);
      $url =mysqli_real_escape_string($link, $_REQUEST['url']);
      $key =mysqli_real_escape_string($link, $_REQUEST['key']);
      $type =mysqli_real_escape_string($link, $_REQUEST['type']);


      #Mandatory values
      if(empty($name) || empty($surname) || empty($title) || empty($year)){
        echo "<script>alert('Please fill every mandatory entry!');
              window.location.replace('../publication.php');
              </script>";
      }
      else{
        mysqli_autocommit($link, false);
        #Check which type of publication has been selected by the user and grab the rest of the corresponding fields (publication type specific)

        if($type == "Article"){
          $aJournal =mysqli_real_escape_string($link, $_REQUEST['aJournal']);
          $aVolume =mysqli_real_escape_string($link, $_REQUEST['aVolume']);
          $aNumber =mysqli_real_escape_string($link, $_REQUEST['aNumber']);
          $aPages =mysqli_real_escape_string($link, $_REQUEST['aPages']);
          $aDOI =mysqli_real_escape_string($link, $_REQUEST['aDOI']);
          #Mandatory value
          if(empty($aJournal)){
            echo "<script>alert('Please give Journal Name!');
                  window.location.replace('../publication.php');
                  </script>";
          }
          else{
            #Check if publication isnt already in the database
            $stmt = $link->prepare("SELECT title,publicationID FROM publication WHERE title = ? and type = ?");
            $stmt->bind_param("ss",$title,$type);
            $stmt->execute();
            $data = array();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $data = $row;
            }

            $stmt = $link->prepare("SELECT doi FROM article WHERE doi = ?");
            $stmt->bind_param("s",$aDOI);
            $stmt->execute();
            $stmt->bind_result($result2);
            $stmt -> fetch();
            mysqli_commit($link); $stmt->close();

            #if the result is null, add publication
            if(empty($data['title']) && empty($result2)){
              #Get authors' ID to add them as foreign key in authors array
              $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
              $stmt->bind_param("ss",$name,$surname);
              $stmt->execute();
              $stmt->bind_result($author);
              $stmt->fetch();
              mysqli_commit($link); $stmt->close();

              #echo $type,$authorID,$title,$year,$note,$month,$url,$key;
              $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
              $stmt->bind_param("ss",$name2,$surname2);
              $stmt->execute();
              $stmt->bind_result($author2);
              $stmt->fetch();
              mysqli_commit($link); $stmt->close();

              $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
              $stmt->bind_param("ss",$name3,$surname3);
              $stmt->execute();
              $stmt->bind_result($author3);
              $stmt->fetch();
              mysqli_commit($link); $stmt->close();

              $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
              $stmt->bind_param("ss",$name4,$surname4);
              $stmt->execute();
              $stmt->bind_result($author4);
              $stmt->fetch();
              mysqli_commit($link); $stmt->close();

              $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
              $stmt->bind_param("ss",$name5,$surname5);
              $stmt->execute();
              $stmt->bind_result($author5);
              $stmt->fetch();
              mysqli_commit($link); $stmt->close();

              #echo $author, " ",$author2, " ",$author3, " ",$author4, " ",$author5, " ";
              if(empty($author2)){$author2=$author;}if(empty($author3)){$author3=$author;}if(empty($author4)){$author4=$author;}if(empty($author5)){$author5=$author;}
              #add authors to DB
              $stmt = $link->prepare("SET foreign_key_checks = 0");
              $stmt = $link->prepare("INSERT INTO authors VALUES (NULL,?,?,?,?,?)");
              $stmt->bind_param("iiiii",$var2,$var3,$var4,$var5,$var6);
              $var2 = $author; $var3 = $author2; $var4 = $author3; $var5 = $author4; $var6 = $author5;
              $stmt->execute();
              $stmt = $link->prepare("SET foreign_key_checks = 1");

              $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? and author2 = ? and author3 = ? and author4 = ? and author5 = ?");
              $stmt->bind_param("iiiii",$author,$author2,$author3,$author4,$author5);
              $stmt->execute();
              $stmt->bind_result($authorsID);
              $stmt->fetch();
              #echo "AuthorsID: ", $authorsID,"huehue";

              #add publication to DB
              $stmt = $link->prepare("SET foreign_key_checks = 0");
              $stmt = $link->prepare("INSERT INTO publication VALUES (NULL,?,?,?,?,?,?,?,?)");
              $stmt->bind_param("sisisiss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8);
              $var = $type; $var2 = $authorsID; $var3 = $title; $var4 = $year; $var5 = $note; $var6 = $month; $var7 = $url; $var8 = $key;
              $stmt->execute();
              $stmt = $link->prepare("SET foreign_key_checks = 1");
              mysqli_commit($link); $stmt->close();

              #get publication's ID (primarykey) to add it in article array as foreign
              $stmt = $link->prepare("SELECT publicationID FROM publication WHERE title = ?");
              $stmt->bind_param("s",$title);
              $stmt->execute();
              $stmt->bind_result($apublicationID);
              $stmt->fetch();
              mysqli_commit($link); $stmt->close();
              #echo $authorsID," kek ",$apublicationID;

              #add article to DB
              $stmt = $link->prepare("SET foreign_key_checks = 0");
              $stmt = $link->prepare("INSERT INTO article VALUES (NULL,?,?,?,?,?,?)");
              $stmt->bind_param("siissi",$var,$var2,$var3,$var4,$var5,$var6);
              $var = $aJournal; $var2 = $aVolume; $var3 = $aNumber; $var4 = $aPages; $var5 = $aDOI; $var6 = $apublicationID;
              $stmt->execute();
              $stmt = $link->prepare("SET foreign_key_checks = 1");

              mysqli_commit($link);
              $stmt->close();
              mysqli_close($link);

              echo "<script>alert('Post successfully added!');
                    window.location.replace('../index.php');
                    </script>";
            }
            #else it already exists, refirect user
            else{
              echo "<script>alert('Post already exists!');
                    window.location.replace('../publication.php');
                    </script>";
            }
          }
        }
        else if($type == "Inproceedings"){
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
          #Mandatory value
          if(empty($bTitle)){
            echo "<script>alert('Please give Title!');
                  window.location.replace('../publication.php');
                  </script>";
          }
          else{
            if(!empty($bVolume) && !empty($bNumber)){
              echo "<script>alert('Please give only Volume or only Number!');
                    window.location.replace('../publication.php');
                    </script>";
            }
            else{
              #Check if publication isnt already in the database
              $stmt = $link->prepare("SELECT title,publicationID FROM publication WHERE title = ? and type = ?");
              $stmt->bind_param("ss",$title,$type);
              $stmt->execute();
              #$stmt->bind_result($result);
              #$stmt -> fetch();
              $data = array();
              $result = $stmt->get_result();
              while($row = $result->fetch_assoc()){
                $data = $row;
              }
              $stmt = $link->prepare("SELECT booktitle,doi FROM inproceedings,publication WHERE booktitle = ? and doi = ?");
              $stmt->bind_param("ii",$bTitle,$bDOI);
              $stmt->execute();
              $data2 = array();
              $result = $stmt->get_result();
              while($row = $result->fetch_assoc()){
                $data = $row;
              }
              mysqli_commit($link);
              #echo "this is bs ", $data['title']," ",$data['publicationID'], " ",$data2['booktitle'], " ", $data2['doi'];

              #if the result is null, add publication
              if( empty($data['title']) && ( empty($data['doi']) && empty($data['booktitle']) )){
                #Get authors' ID to add them as foreign key in authors array
                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name,$surname);
                $stmt->execute();
                $stmt->bind_result($author);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #echo $type,$authorID,$title,$year,$note,$month,$url,$key;
                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name2,$surname2);
                $stmt->execute();
                $stmt->bind_result($author2);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name3,$surname3);
                $stmt->execute();
                $stmt->bind_result($author3);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name4,$surname4);
                $stmt->execute();
                $stmt->bind_result($author4);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name5,$surname5);
                $stmt->execute();
                $stmt->bind_result($author5);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #echo $author, " ",$author2, " ",$author3, " ",$author4, " ",$author5, " ";
                if(empty($author2)){$author2=$author;}if(empty($author3)){$author3=$author;}if(empty($author4)){$author4=$author;}if(empty($author5)){$author5=$author;}
                #add authors to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO authors VALUES (NULL,?,?,?,?,?)");
                $stmt->bind_param("iiiii",$var2,$var3,$var4,$var5,$var6);
                $var2 = $author; $var3 = $author2; $var4 = $author3; $var5 = $author4; $var6 = $author5;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? and author2 = ? and author3 = ? and author4 = ? and author5 = ?");
                $stmt->bind_param("iiiii",$author,$author2,$author3,$author4,$author5);
                $stmt->execute();
                $stmt->bind_result($authorsID);
                $stmt->fetch();
                #echo "AuthorsID: ", $authorsID;

                #add publication to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO publication VALUES (NULL,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sisisiss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8);
                $var = $type; $var2 = $authorsID; $var3 = $title; $var4 = $year; $var5 = $note; $var6 = $month; $var7 = $url; $var8 = $key;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                #get publication's ID (primarykey) to add it in article array as foreign
                $stmt = $link->prepare("SELECT publicationID FROM publication WHERE title = ?");
                $stmt->bind_param("s",$title);
                $stmt->execute();
                $stmt->bind_result($bpublicationID);
                $stmt->fetch();
                #echo " ", $bpublicationID;
                #add article to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO inproceedings VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("ssiisssssss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$var9,$var10,$var11);
                $var = $bTitle; $var2 = $bEditor; $var3 = $bVolume; $var4 = $bNumber; $var5 = $bSeries; $var6 = $bPages; $var7 = $bAddress; $var8 = $bOrganization; $var9 = $bPublisher; $var10 = $bDOI; $var11 = $bpublicationID;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                mysqli_commit($link);
                $stmt->close();
                mysqli_close($link);

                echo "<script>alert('Post successfully added!');
                      window.location.replace('../index.php');
                      </script>";
              }
              #else it already exists, refirect user
              else{
                echo "<script>alert('Post already exists!');
                      window.location.replace('../publication.php');
                      </script>";
              }
            }
          }
        }
        else if($type == "Book"){
          $cPublisher =mysqli_real_escape_string($link, $_REQUEST['cPublisher']);
          $cVolume =mysqli_real_escape_string($link, $_REQUEST['cVolume']);
          $cNumber =mysqli_real_escape_string($link, $_REQUEST['cNumber']);
          $cSeries =mysqli_real_escape_string($link, $_REQUEST['cSeries']);
          $cAddress =mysqli_real_escape_string($link, $_REQUEST['cAddress']);
          $cEdition =mysqli_real_escape_string($link, $_REQUEST['cEdition']);
          $cMonth =mysqli_real_escape_string($link, $_REQUEST['cMonth']);
          $cISBN =mysqli_real_escape_string($link, $_REQUEST['cISBN']);
          $cISSN =mysqli_real_escape_string($link, $_REQUEST['cISSN']);
          #Mandatory value
          if(empty($cPublisher)){
            echo "<script>alert('Please give Publisher!');
                  window.location.replace('../publication.php');
                  </script>";
          }
          else{
            if(!empty($cVolume) && !empty($cNumber)){
              echo "<script>alert('Please give only Volume or only Number!');
                    window.location.replace('../publication.php');
                    </script>";
            }
            else{
              #Check if publication isnt already in the database
              $stmt = $link->prepare("SELECT title,publicationID FROM publication WHERE title = ? and type = ?");
              $stmt->bind_param("ss",$title,$type);
              $stmt->execute();
              $data = array();
              $result = $stmt->get_result();
              while($row = $result->fetch_assoc()){
                $data = $row;
              }
              #mysqli_commit($link);

              $stmt = $link->prepare("SELECT ISBN,ISSN FROM book WHERE ISBN = ? and ISSN = ?");
              $stmt->bind_param("ii",$cISBN,$cISSN);
              $stmt->execute();
              $data2 = array();
              $result2 = $stmt->get_result();
              while($row = $result2->fetch_assoc()){
                $data2 = $row;
              }
              #echo $data['title'], $data['publicationID'], $data2['ISSN'], $data2['ISBN'];

              if(empty($data['title']) || ( $data2['ISSN']!=$cISSN && $data2['ISBN']!=$cISBN) ){
                #Get authorsID to add it as foreign key in publication array
                #Get authors' ID to add them as foreign key in authors array
                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name,$surname);
                $stmt->execute();
                $stmt->bind_result($author);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #echo $type,$authorID,$title,$year,$note,$month,$url,$key;
                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name2,$surname2);
                $stmt->execute();
                $stmt->bind_result($author2);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name3,$surname3);
                $stmt->execute();
                $stmt->bind_result($author3);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name4,$surname4);
                $stmt->execute();
                $stmt->bind_result($author4);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name5,$surname5);
                $stmt->execute();
                $stmt->bind_result($author5);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #echo $author, " ",$author2, " ",$author3, " ",$author4, " ",$author5, " ";
                if(empty($author2)){$author2=$author;}if(empty($author3)){$author3=$author;}if(empty($author4)){$author4=$author;}if(empty($author5)){$author5=$author;}
                #add authors to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO authors VALUES (NULL,?,?,?,?,?)");
                $stmt->bind_param("iiiii",$var2,$var3,$var4,$var5,$var6);
                $var2 = $author; $var3 = $author2; $var4 = $author3; $var5 = $author4; $var6 = $author5;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? and author2 = ? and author3 = ? and author4 = ? and author5 = ?");
                $stmt->bind_param("iiiii",$author,$author2,$author3,$author4,$author5);
                $stmt->execute();
                $stmt->bind_result($authorsID);
                $stmt->fetch();
                #echo "AuthorsID: ", $authorsID;

                #add publication to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO publication VALUES (NULL,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sisisiss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8);
                $var = $type; $var2 = $authorsID; $var3 = $title; $var4 = $year; $var5 = $note; $var6 = $month; $var7 = $url; $var8 = $key;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                #get publication's ID (primarykey) to add it in article array as foreign
                $stmt = $link->prepare("SELECT publicationID FROM publication WHERE title = ?");
                $stmt->bind_param("s",$title);
                $stmt->execute();
                $stmt->bind_result($cpublicationID);
                $stmt->fetch();

                #add book to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO book VALUES (NULL,?,?,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("siisssissi",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$var9,$var10);
                $var = $cPublisher; $var2 = $cVolume; $var3 = $cNumber; $var4 = $cSeries; $var5 = $cAddress; $var6 = $cEdition; $var7 = $cMonth; $var8 = $cISBN; $var9 = $cISSN; $var10 = $cpublicationID;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                mysqli_commit($link);
                $stmt->close();
                mysqli_close($link);
                echo "<script>alert('Post successfully added!');
                      window.location.replace('../index.php');
                      </script>";
              }
              #else it already exists, refirect user
              else{
                echo "<script>alert('Post already exists!');
                      window.location.replace('../publication.php');
                      </script>";
              }
            }
          }
        }
        else if($type == "Inbook"){
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

          if( (empty($dPublisher) && empty($dChapter)) || (empty($dPublisher) && empty($dPages)) ){
            echo "<script>alert('Please fill every mandatory entry!');
                  window.location.replace('../publication.php');
                  </script>";
          }
          else{
            if(!empty($dVolume) && !empty($dNumber)){
              echo "<script>alert('Please give only Volume or only Number!');
                    window.location.replace('../publication.php');
                    </script>";
            }
            else{
              #Check if publication isnt already in the database
              $stmt = $link->prepare("SELECT title,publicationID FROM publication WHERE title = ? and type = ?");
              $stmt->bind_param("ss",$title,$type);
              $stmt->execute();
              $data = array();
              $result = $stmt->get_result();
              while($row = $result->fetch_assoc()){
                $data = $row;
              }

              $stmt = $link->prepare("SELECT ISBN,ISSN FROM inbook WHERE ISBN = ? and ISSN = ?");
              $stmt->bind_param("ii",$cISBN,$cISSN);
              $stmt->execute();
              $data2 = array();
              $result2 = $stmt->get_result();
              while($row = $result2->fetch_assoc()){
                $data2 = $row;
              }
              #echo $data['title'], $data['publicationID'], $data2['ISSN'], $data2['ISBN'];

              if(empty($data['title']) && ( $data2['ISSN']!=$dISSN && $data2['ISBN']!=$dISBN) ){
                #Get authorsID to add it as foreign key in publication array
                #Get authors' ID to add them as foreign key in authors array
                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name,$surname);
                $stmt->execute();
                $stmt->bind_result($author);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #echo $type,$authorID,$title,$year,$note,$month,$url,$key;
                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name2,$surname2);
                $stmt->execute();
                $stmt->bind_result($author2);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name3,$surname3);
                $stmt->execute();
                $stmt->bind_result($author3);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name4,$surname4);
                $stmt->execute();
                $stmt->bind_result($author4);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                $stmt->bind_param("ss",$name5,$surname5);
                $stmt->execute();
                $stmt->bind_result($author5);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #echo $author, " ",$author2, " ",$author3, " ",$author4, " ",$author5, " ";
                if(empty($author2)){$author2=$author;}if(empty($author3)){$author3=$author;}if(empty($author4)){$author4=$author;}if(empty($author5)){$author5=$author;}
                #add authors to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO authors VALUES (NULL,?,?,?,?,?)");
                $stmt->bind_param("iiiii",$var2,$var3,$var4,$var5,$var6);
                $var2 = $author; $var3 = $author2; $var4 = $author3; $var5 = $author4; $var6 = $author5;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? and author2 = ? and author3 = ? and author4 = ? and author5 = ?");
                $stmt->bind_param("iiiii",$author,$author2,$author3,$author4,$author5);
                $stmt->execute();
                $stmt->bind_result($authorsID);
                $stmt->fetch();
                #echo "AuthorsID: ", $authorsID;

                #add publication to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO publication VALUES (NULL,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sisisiss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8);
                $var = $type; $var2 = $authorsID; $var3 = $title; $var4 = $year; $var5 = $note; $var6 = $month; $var7 = $url; $var8 = $key;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                #get publication's ID (primarykey) to add it in article array as foreign
                $stmt = $link->prepare("SELECT publicationID FROM publication WHERE title = ?");
                $stmt->bind_param("s",$title);
                $stmt->execute();
                $stmt->bind_result($dpublicationID);
                $stmt->fetch();

                #add book to DB
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO inbook VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sssiissssssi",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var12,$var8,$var9,$var10,$var11);
                $var = $dPublisher; $var2 = $dChapter; $var3 = $dPages; $var4 = $dVolume; $var5 = $dNumber; $var6 = $dSeries;
                $var7 = $dType; $var12 = $dAddress; $var8 = $dEdition; $var9 = $dISBN; $var10 = $dISSN; $var11 = $dpublicationID;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");

                mysqli_commit($link);
                $stmt->close();
                mysqli_close($link);

                echo "<script>alert('Post successfully added!');
                      window.location.replace('../index.php');
                      </script>";

              }
              #else it already exists, refirect user
              else{
                echo "<script>alert('Post already exists!');
                      window.location.replace('../publication.php');
                      </script>";
              }
            }
          }
        }
      }
    }
?>
