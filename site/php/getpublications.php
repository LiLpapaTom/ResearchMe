<?php
  function loadPosts(){
    session_start();
    error_reporting(0);
    include "connect.php";
    #Load the document
    $doc = new DOMDocument;
    $doc->loadHtmlFile('posts.php');

    #Get the body node
    $parent = $doc->getElementsByTagName('body')->item(0);
    #Create div and set class attribute
    $childDiv = $doc->createElement('div');
    $childDiv->setAttribute('class','container');
    #Add div to body
    $parent->appendChild($childDiv);
    #Create child element
    $child = $doc->createElement('p');
    $child->nodeValue=getpublications();
    #Append child to the parent div node
    $childDiv->appendChild( $child);

    #Save the resulting document
    echo $doc->saveHTML();
  }

  function getpublications(){
    include "connect.php";
    error_reporting(0);
    mysqli_autocommit($link, false);

    $counter = 1;

    $query = "SELECT * FROM (
                  SELECT * FROM publication ORDER BY publicationID DESC LIMIT 10
              ) sub
              ORDER BY publicationID ASC";
    $result = mysqli_query($link, $query);
    $id_array = array();
    while($row = mysqli_fetch_assoc($result)){
      #echo $row['publicationID'], " ";
      array_push($id_array, $row['publicationID']);
    }
    if(empty($result)){
      echo "There are no publications posted";
    }
    else{
      #echo $publicationCounter;
      for($i=0;$i<count($id_array);$i++){
        $stmt = $link->prepare("SELECT * FROM publication WHERE publicationID = ?");
        $stmt->bind_param("i",$id_array[$i]);
        $stmt->execute();
        $publication = array();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
          $publication = $row;
        }
        $stmt->close();

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
        #Publication info
        echo "[",$counter,"] ","Publication's ID: ", $publication['publicationID'],", ",$publication['type'],", ID: ",$publication['authorsID'],", <b>'",$publication['title'],"</b>', ",$publication['month'],"/",
             $publication['year'],'<br>',"URL: ",$publication['url'],", ",$publication['pubKey']," ";

        #Get authors' info using their IDs
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

        if($publication['type']=='Article'){
          $stmt = $link->prepare("SELECT * FROM article,publication WHERE publication.publicationID = ? and article.publicationID = ?");
          $stmt->bind_param("ii",$publication['publicationID'],$publication['publicationID']);
          $stmt->execute();
          $article = array();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()){
            $article = $row;
          }
          $stmt->close();
          echo "Article's ID: ",$article['articleID'],"<br>Journal: ",$article['journal'],", Volume ",$article['volume'],", ",$article['number'],", ",$article['pages']," pages, DOI:",$article['doi']," ";
          echo "<hr style='height:1px;border-width:0;color:gray;background-color:gray'>";
          $counter++;
        }
        else if($publication['type']=='Inbook'){
          $stmt = $link->prepare("SELECT * FROM inbook,publication WHERE publication.publicationID = ? and inbook.publicationID = ?");
          $stmt->bind_param("ii",$publication['publicationID'],$publication['publicationID']);
          $stmt->execute();
          $inbook = array();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()){
            $inbook = $row;
          }
          $stmt->close();
          echo "Inbook's ID: ",$inbook['inbookID'],"<br>Publisher: ",$inbook['publisher'],"<br>Chaper: ",$inbook['chapter'],", ",$inbook['pages']," pages, Volume: ",$inbook['volume'],", ",$inbook['number'],", Series ",
               $inbook['series'],", ",$inbook['type'],"<br>Address: ",$inbook['address'],"<br>Edition: ",$inbook['edition'],", ",$inbook['ISBN'],"(ISBN), ",$inbook['ISSN'],"(ISSN) ";
          echo "<hr style='height:1px;border-width:0;color:gray;background-color:gray'>";
          $counter++;
        }
        else if($publication['type']=='Book'){
          $stmt = $link->prepare("SELECT * FROM book,publication WHERE publication.publicationID = ? and book.publicationID = ?");
          $stmt->bind_param("ii",$publication['publicationID'],$publication['publicationID']);
          $stmt->execute();
          $book = array();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()){
            $book = $row;
          }
          $stmt->close();
          echo "Book ID: ",$book['bookID'],"<br>Publisher: ",$book['publisher'],", Volume: ",$book['volume'],", ",$book['number'],", Series: ",$book['series'],"<br>Address: ",$book['address'],"<br>Note: ",$book['note'],
          "<br>Edition:  ",$book['edition'],", Month: ",$book['month']," ",$book['ISBN'],"(ISBN) ",$book['ISSN'],"(ISSN) ";
          echo "<hr style='height:1px;border-width:0;color:gray;background-color:gray'>";
          $counter++;
        }
        else if($publication['type']=='Inproceedings'){
          $stmt = $link->prepare("SELECT * FROM inproceedings,publication WHERE publication.publicationID = ? and inproceedings.publicationID = ?");
          $stmt->bind_param("ii",$publication['publicationID'],$publication['publicationID']);
          $stmt->execute();
          $inpro = array();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()){
            $inpro = $row;
          }
          $stmt->close();
          echo "Inproceedings ID: ",$inpro['inproceedingsID'],"<br>Publisher:   ",$inpro['publisher'],"<br>Book's title:  ",$inpro['booktitle'],", Editor: ",$inpro['editor'],", Volume: ",$inpro['volume'],", ",$inpro['number'],", Series: ",$inpro['series'],", ",
               $inpro['pages']," pages<br>Address: ",$inpro['address'],"<br>Organization: ",$inpro['organization'],", DOI: ",$inpro['doi'];
          echo "<hr style='height:1px;border-width:0;color:gray;background-color:gray'>";
          $counter++;
        }
      }
    }
    mysqli_commit($link);
    mysqli_close($link);
  }
?>
