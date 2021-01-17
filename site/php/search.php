<?php
session_start();
include "connect.php";
error_reporting(0);
    /*
    if(isset($_POST)){
      if(empty($_POST['input'])){
        #echo "<script>window.location.replace('index.php');</script>";
        echo $_POST['input'];
      }
      else{
      */
        $q = $_REQUEST["q"];
        if(empty($q)){
          echo "<script>alert('No results found');
                window.location.replace('posts.php');
                </script>";
        }
        $input = $q;
        #echo $q;
        mysqli_autocommit($link, false);
        #split the user's input
        if (strpos($input, ' ') !== false) {
          $auth = explode(" ",$input);
          #echo $auth[0], " ", $auth[1];
        }
        #Firstly we will check if the search is an author
        #Get author by name (in case the keyword is about the authors name)
        $stmt = $link->prepare("SELECT * FROM author WHERE name LIKE ?");
        $stmt->bind_param("s",$var);
        $var = "%" . $input . "%";
        $stmt->execute();
        $author = array();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
          $author = $row;
          #print_r($row);
        }
        $stmt->close();
        #if author is empty, the keyword is maybe about the authors surname
        if(empty($author)){
          $stmt = $link->prepare("SELECT * FROM author WHERE surname LIKE ? ");
          $stmt->bind_param("s",$var);
          $var = "%" . $input . "%";
          $stmt->execute();
          $author = array();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()){
            $author = $row;
            #print_r($row);
          }
          $stmt->close();
        }
        #if the result is still null, that means the keywork is the authors whole name
        if(empty($author)){
          $stmt = $link->prepare("SELECT * FROM author WHERE name = ? OR surname = ? ");
          $stmt->bind_param("ss",$name,$surname);
          $name = $auth[0]; $surname = $auth[1];
          $stmt->execute();
          $author = array();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()){
            $author = $row;
            #print_r($row);
          }
          $stmt->close();
        }
        #Check if user's input matches the result, if it does print author/post
        $temp = $author['name'].' '.$author['surname'];
        $pos = strpos($temp,$input);
        if ($pos === false) {
            $flag = 0;
        } else {
            $flag = 1;
        }

        echo "<div style='background-color:white'>";
        #If flag=1 and result is not null, means that the user is looking for an existing author, else its a post
        if(!empty($result) && $flag == 1 ){
          echo "Author's name: ",$author['name'],$author['surname'],', ';
          echo "ID: ", $author['authorID'],'<br>';
          echo "Personal URL:",$author['personalURL'],'<br>';
          echo "orcID: ",$author['orcID'],'<br>';
          echo "<br><hr>Relevant posts<hr>";

          #Select the posts the author is mentioned, save those posts
          $stmt = $link->prepare("SELECT * FROM authors WHERE author = ? or author2 = ? or author3 = ? or author4 = ? or author5 = ?");
          $stmt->bind_param("iiiii",$author['authorID'],$author['authorID'],$author['authorID'],$author['authorID'],$author['authorID']);
          $stmt->execute();
          $data = array(); $posts = array();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()){
            $data = $row;
            array_push($posts,$data['authorsID']);
          }
          $stmt->close();
          #print_r($posts);
          $arrayLength = count($posts);
          $publications = array();
          $publicationInfo = array();
          $data2 = array();

          $i = 0;
          while($i<$arrayLength){
            #echo $posts[$i];
            $stmt = $link->prepare("SELECT * FROM publication WHERE authorsID = ?");
            $stmt->bind_param("i",$posts[$i]);
            $stmt->execute();
            unset($data); $data = array();
            $result = $stmt->get_result();

            while($row = $result->fetch_assoc()){
              $data = $row;
              #Print publications info
              array_push($publications,$data['publicationID'],$data['type'],$data['authorsID'],$data['title'],$data['note'],$data['month'],$data['year'],$data['url'],$data['pubKey']);
              echo "<br>Publication's ID: [",$data['publicationID'],'] ',$data['type'],'<br>';
              echo "URL: ",$data['url'],'<br>';
              echo "Title: <b>",$data['title'],'</b><br>';
              echo "Note: ",$data['note'],'<br>';
              echo "Published at: ",$data['month'],"/",$data['year'],'<br>';
              echo "PubKey: ",$data['pubKey'];

              #For those publicationsID search the relevant info depending on the post's type
              if($data['type']=='Book'){
                $stmt2 = $link->prepare("SELECT * FROM book WHERE publicationID = ?");
                $stmt2->bind_param("i",$data['publicationID']);
                $stmt2->execute();
                unset($data2); $data2 = array();
                $result2 = $stmt2->get_result();

                while($row2 = $result2->fetch_assoc()){
                  $data2 = $row2;
                  array_push($publicationInfo,$data2);
                  echo "<br>Book's ID: ",$data2['bookID'];
                  echo "<br>Publisher: ",$data2['publisher'];
                  echo "<br>Volume: ",$data2['volume'],", ",$data2['number']," Series: ",$data2['series'];
                  echo "<br>Address: ",$data2['address'];
                  echo "<br>Edition: ",$data2['edition'],", ",$data2['month'];
                  echo "<br>ISBN: ",$data2['ISBN'],", ISSN: ",$data2['ISSN'],"<br><br>";
                }
              }
              else if($data['type']=='Article'){
                  $stmt2 = $link->prepare("SELECT * FROM article WHERE publicationID = ?");
                  $stmt2->bind_param("i",$data['publicationID']);
                  $stmt2->execute();
                  unset($data2); $data2 = array();
                  $result2 = $stmt2->get_result();

                  while($row2 = $result2->fetch_assoc()){
                    $data2 = $row2;
                    array_push($publicationInfo,$data2);
                    echo "<br>Journal: ",$data2['journal'];
                    echo "<br>Volume: ",$data2['volume'],", ",$data2['number'],", ",$data2['pages']," pages";
                    echo "<br>DOI: ",$data2['doi'],"<br><br>";
                  }
              }
              else if($data['type']=='Inbook'){
                $stmt2 = $link->prepare("SELECT * FROM inbook WHERE publicationID = ?");
                $stmt2->bind_param("i",$data['publicationID']);
                $stmt2->execute();
                unset($data2); $data2 = array();
                $result2 = $stmt2->get_result();

                while($row2 = $result2->fetch_assoc()){
                  $data2 = $row2;
                  array_push($publicationInfo,$data2);
                  echo "<br>Inbook's ID: ",$data2['inbookID'];
                  echo "<br>Publisher: ",$data2['publisher'];
                  echo "<br>Volume: ",$data2['volume'],", ",$data2['number']," Series: ",$data2['series'],", Chapter: ",$data2['chapter'],$data2['pages']," pages";
                  echo "<br>Type: ",$data2['type'];
                  echo "<br>Address: ",$data2['address'];
                  echo "<br>Edition: ",$data2['edition'];
                  echo "<br>ISBN: ",$data2['ISBN'],", ISSN: ",$data2['ISSN'],"<br><br>";
                }
              }
              else if($data['type']=='Inproceedings'){
                $stmt2 = $link->prepare("SELECT * FROM inproceedings WHERE publicationID = ?");
                $stmt2->bind_param("i",$data['publicationID']);
                $stmt2->execute();
                unset($data2); $data2 = array();
                $result2 = $stmt2->get_result();

                while($row2 = $result2->fetch_assoc()){
                  $data2 = $row2;
                  array_push($publicationInfo,$data2);
                  echo "<br>Inproceeding's ID: ",$data2['inproceedingsID'];
                  echo "<br>Booktitle: ",$data2['booktitle'];
                  echo "<br>Publisher: ",$data2['publisher'];
                  echo "<br>Editor: ",$data2['editor'];
                  echo "<br>Volume: ",$data2['volume'],", ",$data2['number']," Series: ",$data2['series'],$data2['pages']," pages";
                  echo "<br>Organization: ",$data2['organization'];
                  echo "<br>Address: ",$data2['address'];
                  echo "<br>DOI: ",$data2['doi'],"<br><br>";
                }
              }
            }

            #$stmt->close();
            #$stmt2->close();
            $i++;
          }
        #Else the user's input is about the posts
        }else{
          $stmt = $link->prepare("SELECT * FROM publication WHERE title LIKE ?");
          $stmt->bind_param("s",$var);
          $var = "%".$input."%";
          $stmt->execute();
          $publication = array();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()){
            $publication = $row;
            echo "<br>Publication's ID: [",$publication['publicationID'],'] ',$publication['type'],'<br>';
            echo "URL: ",$publication['url'],'<br>';
            echo "Title: <b>",$publication['title'],'</b><br>';
            echo "Note: ",$publication['note'],'<br>';
            echo "Published at: ",$publication['month'],"/",$publication['year'],'<br>';
            echo "PubKey: ",$publication['pubKey'];

            $stm = $link->prepare("SELECT * FROM authors WHERE authorsID = ?");
            $stm->bind_param("i",$publication['authorsID']);
            $stm->execute();
            $authors = array();
            $result3 = $stm->get_result();
            while($row3 = $result3->fetch_assoc()){
              $authors = $row3;
            }
            $stm->close();

            #Get 1st author
            $stm = $link->prepare("SELECT name,surname FROM author WHERE authorID = ?");
            $stm->bind_param("i",$authors['author']);
            $stm->execute();
            $author = array();
            $result3 = $stm->get_result();
            while($row3 = $result3->fetch_assoc()){
              $author = $row3;
            }
            echo "<br>Authors: ",$author['surname']," ",$author['name'];
            #Get 2nd author
            $stm = $link->prepare("SELECT name,surname FROM author WHERE authorID = ?");
            $stm->bind_param("i",$authors['author2']);
            $stm->execute();
            $author2 = array();
            $result3 = $stm->get_result();
            while($row3 = $result3->fetch_assoc()){
              $author2 = $row3;
            }
            if($author2['name']!=$author['name'] && $author2['surname']!=$author['surname'])
              echo ", ",$author2['surname']," ",$author2['name'];

            #Get 3rd author
            $stm = $link->prepare("SELECT name,surname FROM author WHERE authorID = ?");
            $stm->bind_param("i",$authors['author3']);
            $stm->execute();
            $author3 = array();
            $result3 = $stm->get_result();
            while($row3 = $result3->fetch_assoc()){
              $author3 = $row3;
            }
            if($author3['name']!=$author['name'] && $author3['surname']!=$author['surname'])
              echo ", ",$author3['surname']," ",$author3['name'];

            #Get 4th author
            $stm = $link->prepare("SELECT name,surname FROM author WHERE authorID = ?");
            $stm->bind_param("i",$authors['author4']);
            $stm->execute();
            $author4 = array();
            $result3 = $stm->get_result();
            while($row3 = $result3->fetch_assoc()){
              $author4 = $row3;
            }
            if($author4['name']!=$author['name'] && $author4['surname']!=$author['surname'])
              echo ", ",$author4['surname']," ",$author4['name'];

            #Get 5th author
            $stm = $link->prepare("SELECT name,surname FROM author WHERE authorID = ?");
            $stm->bind_param("i",$authors['author5']);
            $stm->execute();
            $author5 = array();
            $result3 = $stm->get_result();
            while($row3 = $result3->fetch_assoc()){
              $author5 = $row3;
            }
            if($author5['name']!=$author['name'] && $author5['surname']!=$author['surname'])
              echo ", ",$author5['surname']," ",$author5['name'];
              echo '<br>';
          }
          #echo $publication['type'];
          #If author search is null, means the user's search is probably a post. If a post is null as well, exit
          if(empty($publication['publicationID'])){
            echo "<script>alert('No results found');
                  window.location.replace('posts.php');
                  </script>";
          }
          if($publication['type']=='Article'){
            $stmt = $link->prepare("SELECT * FROM article WHERE publicationID = ?");
            $stmt->bind_param("i",$publication['publicationID']);
            $stmt->execute();
            $article = array();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $article = $row;
              echo "<br>Journal: ",$article['journal'];
              echo "<br>Volume: ",$article['volume'],", ",$article['number'],", ",$article['pages']," pages";
              echo "<br>DOI: ",$article['doi'],"<br>";

            }
          }
          else if($publication['type']=='Book'){
            $stmt = $link->prepare("SELECT * FROM book WHERE publicationID = ?");
            $stmt->bind_param("i",$publication['publicationID']);
            $stmt->execute();
            $book = array();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
              $book = $row;
              echo "<br>Book's ID: ",$book['bookID'];
              echo "<br>Publisher: ",$book['publisher'];
              echo "<br>Volume: ",$book['volume'],", ",$book['number']," Series: ",$book['series'];
              echo "<br>Address: ",$book['address'];
              echo "<br>Edition: ",$book['edition'],", ",$book['month'];
              echo "<br>ISBN: ",$book['ISBN'],", ISSN: ",$book['ISSN'],"<br><br>";
            }
          }
          else if($publication['type']=='Inbook'){
            $stmt2 = $link->prepare("SELECT * FROM inbook WHERE publicationID = ?");
            $stmt2->bind_param("i",$publication['publicationID']);
            $stmt2->execute();
            $data2 = array();
            $result2 = $stmt2->get_result();
            $publicationInfo = array();
            while($row2 = $result2->fetch_assoc()){
              $data2 = $row2;
              array_push($publicationInfo,$data2);
              echo "<br>Inbook's ID: ",$data2['inbookID'];
              echo "<br>Publisher: ",$data2['publisher'];
              echo "<br>Volume: ",$data2['volume'],", ",$data2['number']," Series: ",$data2['series'],", Chapter: ",$data2['chapter'],$data2['pages']," pages";
              echo "<br>Type: ",$data2['type'];
              echo "<br>Address: ",$data2['address'];
              echo "<br>Edition: ",$data2['edition'];
              echo "<br>ISBN: ",$data2['ISBN'],", ISSN: ",$data2['ISSN'],"<br><br>";
            }
          }
          else if($publication['type']=='Inproceedings'){
            $stmt2 = $link->prepare("SELECT * FROM inproceedings WHERE publicationID = ?");
            $stmt2->bind_param("i",$publication['publicationID']);
            $stmt2->execute();
            $data2 = array();
            $result2 = $stmt2->get_result();
            echo "<br>Hello im in ", $publication['publicationID'];

            while($row2 = $result2->fetch_assoc()){
              $data2 = $row2;
              echo "<br>Inproceeding's ID: ",$data2['inproceedingsID'];
              echo "<br>Booktitle: ",$data2['booktitle'];
              echo "<br>Publisher: ",$data2['publisher'];
              echo "<br>Editor: ",$data2['editor'];
              echo "<br>Volume: ",$data2['volume'],", ",$data2['number']," Series: ",$data2['series'],$data2['pages']," pages";
              echo "<br>Organization: ",$data2['organization'];
              echo "<br>Address: ",$data2['address'];
              echo "<br>DOI: ",$data2['doi'],"<br><br>";
            }
          }
        }
      //}
      echo "</div>";
      mysqli_commit($link);
      mysqli_close($link);
    //}
?>
