<?php
    session_start();
    include "connect.php";

    mysqli_autocommit($link, false);

    $stmt = $link->prepare("SELECT userID FROM user WHERE username = ?");
    $stmt->bind_param("s",$var);
    $var = $_SESSION['username'];
    $stmt->execute();
    $stmt->bind_result($currentUserID);
    $stmt->fetch();
    mysqli_commit($link); $stmt->close();

    $volCounter =1;

    #directory to upload to
    $uploadDir = '../assets/uploads/';

    if(isset($_POST['submit'])){
      $name = $_FILES['file']['name'];
      $temp_name = $_FILES['file']['tmp_name'];

      $targetFile = $uploadDir.basename($_FILES["file"]["name"]);
      $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

        if(isset($name) and !empty($name)){
          #Check if file already exists
          if(file_exists($targetFile)) {
            echo "<script>alert('File already exists.');
                    window.location.replace('../publications.php');
                    </script>";
          }
          #Check if filetype is .xml
          else if($fileType != "xml"){
            echo "<script>alert('Please upload an .xml file.');
                    window.location.replace('../publications.php');
                    </script>";
          }
          #If the file has been successfully uploaded
          else if(move_uploaded_file($temp_name, $uploadDir.$name)){
              $xml = simplexml_load_file($uploadDir.$name);

#IMPORT INPROCEEDINGS
              $result = $xml->xpath("/dblpperson/r/inproceedings");
              $array = array();

              foreach($result as $key => $node){
                foreach($node as $element){
                  if($element->getName() == "author"){
                    if(isset($array[$key])){
                      $array[$key]["Author"][] .= (String) $element;
                    }
                    else{
                      $array[$key] = array("Author"=>array((String) $element));
                    }
                    /*Author ORCID Field START*/
                    if($element['orcid']){
                      $array[$key]["ORCID"][] = (String) $element['orcid'];
                    }
                    else{
                      $array[$key]["ORCID"][] = "-";
                    }
                    /*Author ORCID Field END*/
                  }
                  else if($element->getName() == "title"){
                    $array[$key] += array("Title"=>(String) $element);
                  }
                  else if($element->getName() == "pages"){
                    $array[$key] += array("Pages"=>(String) $element);
                  }
                  else if($element->getName() == "year"){
                    $array[$key] += array("Year"=>(String) $element);
                  }
                  else if($element->getName() == "booktitle"){
                    $array[$key] += array("booktitle"=>(String) $element);
                  }
                  else if($element->getName() == "ee"){
                    $array[$key] += array("doi"=>(String) $element);
                  }
                  else if($element->getName() == "url"){
                    $array[$key] += array("url"=>(String) $element);
                  }
                }

                foreach($node->attributes() as $attr => $attrValue){
                  if($attr == "mdate"){
                    #calculate month from corresponding attribute
                    $firstHyphen = strpos($attrValue, "-");
                    $tmpStr = substr($attrValue,$firstHyphen);
                    $secondHyphen = strripos($tmpStr,"-");
                    $month = substr($tmpStr,1,$secondHyphen-1);
                    $array[$key] += array("Month"=>(String) $month);
                  }
                  if($attr == "key"){
                    $array[$key] += array("pubKey"=>(String) $attrValue);
                  }
                }
              }

              $last = count($array) - 1;

              foreach ($array as $i => $row)
              {
                $tmpAuthors = array();
                $isFirst = ($i == 0);
                $isLast = ($i == $last);
                $j=0;
                foreach($array[$i]["Author"] as $aRow){
                  if(isset($tmpAuthors[$j])){
                    $tmpAuthors[$j] .= $aRow;
                  }
                  else{
                    $tmpAuthors[$j] = $aRow;
                  }
                  $j++;
                }

                /*ORCID ARRAY START*/
                $tmpOrcid = array();
                $z=0;
                foreach($array[$i]["ORCID"] as $aRow){
                  if(isset($tmpOrcid[$z])){
                    $tmpOrcid[$z] .= $aRow;
                  }
                  else{
                    $tmpOrcid[$z] = $aRow;
                  }
                  $z++;
                }
                /*ORCID ARRAY END*/

                $authorIDArray = array();

                for($x = 0; $x < $j; $x++){
                  $separator = strpos($tmpAuthors[$x], " ");
                  $surname = substr($tmpAuthors[$x], $separator+1);
                  $name = substr($tmpAuthors[$x], 0, $separator);

                  #Get author from Author table if he already exists
                  $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                  $stmt->bind_param("ss",$name,$surname);
                  $stmt->execute();
                  $stmt->bind_result($aAuthorID);
                  $stmt->fetch();
                  mysqli_commit($link); $stmt->close();

                  #If the author doesnt exist
                  if(empty($aAuthorID)){
                    #Add author to the author table
                    $stmt = $link->prepare("SET foreign_key_checks = 0");
                    $stmt = $link->prepare("INSERT INTO author VALUES (NULL,?,?,?,?,?,?)");
                    $stmt->bind_param("sssssi",$var,$var2,$var3,$var4,$var5,$var6);
                    $var = $name;
                    $var2 = $surname;
                    $var3 = $tmpOrcid[$x];  #ORCID
                    $var4 = "-";
                    $var5 = "-";
                    $var6 = $currentUserID;
                    $stmt->execute();
                    $stmt = $link->prepare("SET foreign_key_checks = 1");
                    mysqli_commit($link); $stmt->close();

                    #Get author id for current author from the author table and add it to the $authorIDArray
                    $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                    $stmt->bind_param("ss",$var,$var2);
                    $var = $name;
                    $var2 = $surname;
                    $stmt->execute();
                    $stmt->bind_result($authorID);
                    $stmt->fetch();
                    mysqli_commit($link); $stmt->close();

                    array_push($authorIDArray, $authorID);
                  }
                  #Else add the authorID in the authorIDArray
                  else{
                    array_push($authorIDArray, $aAuthorID);
                    /*UPDATE AUTHOR ORCID START*/
                    if($tmpOrcid[$x] != "-"){
                      $stmt = $link->prepare("SET foreign_key_checks = 0");
                      $stmt = $link->prepare("UPDATE author SET orcID = ? WHERE authorID = ?");
                      $stmt->bind_param("si",$var1,$var2);
                      $var1 = $tmpOrcid[$x];
                      $var2 = $aAuthorID;
                      $stmt->execute();
                      $stmt = $link->prepare("SET foreign_key_checks = 1");
                      mysqli_commit($link); $stmt->close();
                    }
                    /*UPDATE AUTHOR ORCID END*/
                  }
                  #Reset aAuthorID variable
                  $aAuthorID = null;
                }

                $noOfAuthors = count($authorIDArray);
                if($noOfAuthors < 5){
                  for($y = $noOfAuthors; $y < 5; $y++){
                    array_push($authorIDArray, $authorIDArray[0]);
                  }
                }

                #Add authors for current publication to the authors table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO authors VALUES (NULL,?,?,?,?,?)");
                $stmt->bind_param("iiiii",$auth1,$auth2,$auth3,$auth4,$auth5);
                $auth1 = $authorIDArray[0];
                $auth2 = $authorIDArray[1];
                $auth3 = $authorIDArray[2];
                $auth4 = $authorIDArray[3];
                $auth5 = $authorIDArray[4];
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                #Get authorsID from authors table for current publication authors
                $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? and author2 = ? and author3 = ? and author4 = ? and author5 = ?");
                $stmt->bind_param("iiiii",$auth1,$auth2,$auth3,$auth4,$auth5);
                $auth1 = $authorIDArray[0];
                $auth2 = $authorIDArray[1];
                $auth3 = $authorIDArray[2];
                $auth4 = $authorIDArray[3];
                $auth5 = $authorIDArray[4];
                $stmt->execute();
                $stmt->bind_result($authorsID);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #Add publication to the publication table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO publication VALUES (NULL,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sisisiss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8);
                $var = "Inproceedings";
                $var2 = $authorsID;
                $var3 = $row['Title'];
                $var4 = $row['Year'];
                $var5 = "-";
                if(isset($row['Month'])){
                  $var6 = $row['Month'];
                }
                else{
                  $var6 = 0;
                }
                $var7 = $row['url'];
                if(isset($row['pubKey'])){
                  $var8 = $row['pubKey'];
                }
                else{
                  $var8 = "-";
                }
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                #Get publicationID we just added
                $stmt = $link->prepare("SELECT publicationID FROM publication WHERE title = ?");
                $stmt->bind_param("s",$var);
                $var = $row['Title'];
                $stmt->execute();
                $stmt->bind_result($publicationID);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #Add publication to Inproceedings table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO inproceedings VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("ssiissssssi",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$var9,$var10,$var11);
                $var = $row['booktitle'];
                $var2 = "-";
                $var3 = $volCounter; #volume
                $var4 = $volCounter; #number
                $var5 = "-";
                if(isset($row['Pages'])){
                  $var6 = $row['Pages'];
                }
                else{
                  $var6 = "-";
                }
                $var7 = "-";
                $var8 = "-";
                $var9 = "-";
                if(isset($row['doi'])){
                  $var10 = $row['doi'];
                }
                else{
                  $var10 = "-";
                }
                $var11 = $publicationID;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                $volCounter++;
                unset($publicationID);
                unset($authorIDArray);
                unset($tmpOrcid);
              }

#IMPORT Article
              $result = $xml->xpath("/dblpperson/r/article");
              $array = array();

              foreach($result as $key => $node){
                foreach($node as $element){
                  if($element->getName() == "author"){
                    if(isset($array[$key])){
                      $array[$key]["Author"][] .= (String) $element;
                    }
                    else{
                      $array[$key] = array("Author"=>array((String) $element));
                    }
                    /*Author ORCID Field START*/
                    if($element['orcid']){
                      $array[$key]["ORCID"][] = (String) $element['orcid'];
                    }
                    else{
                      $array[$key]["ORCID"][] = "-";
                    }
                    /*Author ORCID Field END*/
                  }
                  else if($element->getName() == "title"){
                    $array[$key] += array("Title"=>(String) $element);
                  }
                  else if($element->getName() == "pages"){
                    $array[$key] += array("Pages"=>(String) $element);
                  }
                  else if($element->getName() == "year"){
                    $array[$key] += array("Year"=>(String) $element);
                  }
                  else if($element->getName() == "volume"){
                    $array[$key] += array("Volume"=>(String) $element);
                  }
                  else if($element->getName() == "journal"){
                    $array[$key] += array("Journal"=>(String) $element);
                  }
                  else if($element->getName() == "ee"){
                    $array[$key] += array("doi"=>(String) $element);
                  }
                  else if($element->getName() == "url"){
                    $array[$key] += array("url"=>(String) $element);
                  }
                }

                foreach($node->attributes() as $attr => $attrValue){
                  if($attr == "mdate"){
                    #calculate month from corresponding attribute
                    $firstHyphen = strpos($attrValue, "-");
                    $tmpStr = substr($attrValue,$firstHyphen);
                    $secondHyphen = strripos($tmpStr,"-");
                    $month = substr($tmpStr,1,$secondHyphen-1);
                    $array[$key] += array("Month"=>(String) $month);
                  }
                  if($attr == "key"){
                    $array[$key] += array("pubKey"=>(String) $attrValue);
                  }
                }
              }

              $last = count($array) - 1;

              foreach ($array as $i => $row)
              {
                $tmpAuthors = array();
                $isFirst = ($i == 0);
                $isLast = ($i == $last);
                $j=0;
                foreach($array[$i]["Author"] as $aRow){
                  if(isset($tmpAuthors[$j])){
                    $tmpAuthors[$j] .= $aRow;
                  }
                  else{
                    $tmpAuthors[$j] = $aRow;
                  }
                  $j++;
                }

                /*ORCID ARRAY START*/
                $tmpOrcid = array();
                $z=0;
                foreach($array[$i]["ORCID"] as $aRow){
                  if(isset($tmpOrcid[$z])){
                    $tmpOrcid[$z] .= $aRow;
                  }
                  else{
                    $tmpOrcid[$z] = $aRow;
                  }
                  $z++;
                }
                /*ORCID ARRAY END*/

                $authorIDArray = array();

                for($x = 0; $x < $j; $x++){
                  $separator = strpos($tmpAuthors[$x], " ");
                  $surname = substr($tmpAuthors[$x], $separator+1);
                  $name = substr($tmpAuthors[$x], 0, $separator);

                  #Get author from Author table if he already exists
                  $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                  $stmt->bind_param("ss",$name,$surname);
                  $stmt->execute();
                  $stmt->bind_result($aAuthorID);
                  $stmt->fetch();
                  mysqli_commit($link); $stmt->close();

                  #If the author doesnt exist
                  if(empty($aAuthorID)){
                    #Add author to the author table
                    $stmt = $link->prepare("SET foreign_key_checks = 0");
                    $stmt = $link->prepare("INSERT INTO author VALUES (NULL,?,?,?,?,?,?)");
                    $stmt->bind_param("sssssi",$var,$var2,$var3,$var4,$var5,$var6);
                    $var = $name;
                    $var2 = $surname;
                    $var3 = $tmpOrcid[$x];  #ORCID
                    $var4 = "-";
                    $var5 = "-";
                    $var6 = $currentUserID;
                    $stmt->execute();
                    $stmt = $link->prepare("SET foreign_key_checks = 1");
                    mysqli_commit($link); $stmt->close();

                    #Get author id for current author from the author table and add it to the $authorIDArray
                    $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                    $stmt->bind_param("ss",$var,$var2);
                    $var = $name;
                    $var2 = $surname;
                    $stmt->execute();
                    $stmt->bind_result($authorID);
                    $stmt->fetch();
                    mysqli_commit($link); $stmt->close();

                    array_push($authorIDArray, $authorID);
                  }
                  #Else add the authorID in the authorIDArray
                  else{
                    array_push($authorIDArray, $aAuthorID);
                    /*UPDATE AUTHOR ORCID START*/
                    if($tmpOrcid[$x] != "-"){
                      $stmt = $link->prepare("SET foreign_key_checks = 0");
                      $stmt = $link->prepare("UPDATE author SET orcID = ? WHERE authorID = ?");
                      $stmt->bind_param("si",$var1,$var2);
                      $var1 = $tmpOrcid[$x];
                      $var2 = $aAuthorID;
                      $stmt->execute();
                      $stmt = $link->prepare("SET foreign_key_checks = 1");
                      mysqli_commit($link); $stmt->close();
                    }
                    /*UPDATE AUTHOR ORCID END*/
                  }
                  #Reset aAuthorID variable
                  $aAuthorID = null;
                }

                $noOfAuthors = count($authorIDArray);
                if($noOfAuthors < 5){
                  for($y = $noOfAuthors; $y < 5; $y++){
                    array_push($authorIDArray, $authorIDArray[0]);
                  }
                }

                #Add authors for current publication to the authors table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO authors VALUES (NULL,?,?,?,?,?)");
                $stmt->bind_param("iiiii",$auth1,$auth2,$auth3,$auth4,$auth5);
                $auth1 = $authorIDArray[0];
                $auth2 = $authorIDArray[1];
                $auth3 = $authorIDArray[2];
                $auth4 = $authorIDArray[3];
                $auth5 = $authorIDArray[4];
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                #Get authorsID from authors table for current publication authors
                $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? and author2 = ? and author3 = ? and author4 = ? and author5 = ?");
                $stmt->bind_param("iiiii",$auth1,$auth2,$auth3,$auth4,$auth5);
                $auth1 = $authorIDArray[0];
                $auth2 = $authorIDArray[1];
                $auth3 = $authorIDArray[2];
                $auth4 = $authorIDArray[3];
                $auth5 = $authorIDArray[4];
                $stmt->execute();
                $stmt->bind_result($authorsID);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #Add publication to the publication table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO publication VALUES (NULL,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sisisiss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8);
                $var = "Article";
                $var2 = $authorsID;
                $var3 = $row['Title'];
                $var4 = $row['Year'];
                $var5 = "-";
                if(isset($row['Month'])){
                  $var6 = $row['Month'];
                }
                else{
                  $var6 = 0;
                }
                $var7 = $row['url'];
                if(isset($row['pubKey'])){
                  $var8 = $row['pubKey'];
                }
                else{
                  $var8 = "-";
                }
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                #Get publicationID we just added
                $stmt = $link->prepare("SELECT publicationID FROM publication WHERE title = ?");
                $stmt->bind_param("s",$var);
                $var = $row['Title'];
                $stmt->execute();
                $stmt->bind_result($publicationID);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #Add publication to Article table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO article VALUES (NULL,?,?,?,?,?,?)");
                $stmt->bind_param("siissi",$var,$var2,$var3,$var4,$var5,$var6);
                $var = $row['Journal'];
                if(isset($row['Volume'])){
                  $var2 = $row['Volume'];
                }
                else{
                  $var2 = $volCounter;
                }
                if(isset($row['Number'])){
                  $var3 = $row['Number'];
                }
                else{
                  $var3 = $volCounter;
                }
                if(isset($row['Pages'])){
                  $var4 = $row['Pages'];
                }
                else{
                  $var4 = "-";
                }
                if(isset($row['doi'])){
                  $var5 = $row['doi'];
                }
                else{
                  $var5 = "-";
                }
                $var6 = $publicationID;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                $volCounter++;
                unset($publicationID);
                unset($authorIDArray);
                unset($tmpOrcid);
              }

#IMPORT Book
              $result = $xml->xpath("/dblpperson/r/book");
              $array = array();

              foreach($result as $key => $node){
                foreach($node as $element){
                  if($element->getName() == "author"){
                    if(isset($array[$key])){
                      $array[$key]["Author"][] .= (String) $element;
                    }
                    else{
                      $array[$key] = array("Author"=>array((String) $element));
                    }
                    /*Author ORCID Field START*/
                    if($element['orcid']){
                      $array[$key]["ORCID"][] = (String) $element['orcid'];
                    }
                    else{
                      $array[$key]["ORCID"][] = "-";
                    }
                    /*Author ORCID Field END*/
                  }
                  else if($element->getName() == "title"){
                    $array[$key] += array("Title"=>(String) $element);
                  }
                  else if($element->getName() == "year"){
                    $array[$key] += array("Year"=>(String) $element);
                  }
                  else if($element->getName() == "url"){
                    $array[$key] += array("url"=>(String) $element);
                  }
                  else if($element->getName() == "publisher"){
                    $array[$key] += array("Publisher"=>(String) $element);
                  }
                  else if($element->getName() == "volume"){
                    $array[$key] += array("Volume"=>(String) $element);
                  }
                  else if($element->getName() == "number"){
                    $array[$key] += array("Number"=>(String) $element);
                  }
                  else if($element->getName() == "series"){
                    $array[$key] += array("Series"=>(String) $element);
                  }
                  else if($element->getName() == "address"){
                    $array[$key] += array("Address"=>(String) $element);
                  }
                  else if($element->getName() == "edition"){
                    $array[$key] += array("Edition"=>(String) $element);
                  }
                  else if($element->getName() == "isbn"){
                    $array[$key] += array("ISBN"=>(String) $element);
                  }
                  else if($element->getName() == "issn"){
                    $array[$key] += array("ISSN"=>(String) $element);
                  }
                }

                foreach($node->attributes() as $attr => $attrValue){
                  if($attr == "mdate"){
                    #calculate month from corresponding attribute
                    $firstHyphen = strpos($attrValue, "-");
                    $tmpStr = substr($attrValue,$firstHyphen);
                    $secondHyphen = strripos($tmpStr,"-");
                    $month = substr($tmpStr,1,$secondHyphen-1);
                    $array[$key] += array("Month"=>(String) $month);
                  }
                  if($attr == "key"){
                    $array[$key] += array("pubKey"=>(String) $attrValue);
                  }
                }
              }

              $last = count($array) - 1;

              foreach ($array as $i => $row)
              {
                $tmpAuthors = array();
                $isFirst = ($i == 0);
                $isLast = ($i == $last);
                $j=0;
                foreach($array[$i]["Author"] as $aRow){
                  if(isset($tmpAuthors[$j])){
                    $tmpAuthors[$j] .= $aRow;
                  }
                  else{
                    $tmpAuthors[$j] = $aRow;
                  }
                  $j++;
                }

                /*ORCID ARRAY START*/
                $tmpOrcid = array();
                $z=0;
                foreach($array[$i]["ORCID"] as $aRow){
                  if(isset($tmpOrcid[$z])){
                    $tmpOrcid[$z] .= $aRow;
                  }
                  else{
                    $tmpOrcid[$z] = $aRow;
                  }
                  $z++;
                }
                /*ORCID ARRAY END*/

                $authorIDArray = array();

                for($x = 0; $x < $j; $x++){
                  $separator = strpos($tmpAuthors[$x], " ");
                  $surname = substr($tmpAuthors[$x], $separator+1);
                  $name = substr($tmpAuthors[$x], 0, $separator);

                  #Get author from Author table if he already exists
                  $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                  $stmt->bind_param("ss",$name,$surname);
                  $stmt->execute();
                  $stmt->bind_result($aAuthorID);
                  $stmt->fetch();
                  mysqli_commit($link); $stmt->close();

                  #If the author doesnt exist
                  if(empty($aAuthorID)){
                    #Add author to the author table
                    $stmt = $link->prepare("SET foreign_key_checks = 0");
                    $stmt = $link->prepare("INSERT INTO author VALUES (NULL,?,?,?,?,?,?)");
                    $stmt->bind_param("sssssi",$var,$var2,$var3,$var4,$var5,$var6);
                    $var = $name;
                    $var2 = $surname;
                    $var3 = $tmpOrcid[$x];  #ORCID
                    $var4 = "-";
                    $var5 = "-";
                    $var6 = $currentUserID;
                    $stmt->execute();
                    $stmt = $link->prepare("SET foreign_key_checks = 1");
                    mysqli_commit($link); $stmt->close();

                    #Get author id for current author from the author table and add it to the $authorIDArray
                    $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                    $stmt->bind_param("ss",$var,$var2);
                    $var = $name;
                    $var2 = $surname;
                    $stmt->execute();
                    $stmt->bind_result($authorID);
                    $stmt->fetch();
                    mysqli_commit($link); $stmt->close();

                    array_push($authorIDArray, $authorID);
                  }
                  #Else add the authorID in the authorIDArray
                  else{
                    array_push($authorIDArray, $aAuthorID);
                    /*UPDATE AUTHOR ORCID START*/
                    if($tmpOrcid[$x] != "-"){
                      $stmt = $link->prepare("SET foreign_key_checks = 0");
                      $stmt = $link->prepare("UPDATE author SET orcID = ? WHERE authorID = ?");
                      $stmt->bind_param("si",$var1,$var2);
                      $var1 = $tmpOrcid[$x];
                      $var2 = $aAuthorID;
                      $stmt->execute();
                      $stmt = $link->prepare("SET foreign_key_checks = 1");
                      mysqli_commit($link); $stmt->close();
                    }
                    /*UPDATE AUTHOR ORCID END*/
                  }
                  #Reset aAuthorID variable
                  $aAuthorID = null;
                }

                $noOfAuthors = count($authorIDArray);
                if($noOfAuthors < 5){
                  for($y = $noOfAuthors; $y < 5; $y++){
                    array_push($authorIDArray, $authorIDArray[0]);
                  }
                }

                #Add authors for current publication to the authors table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO authors VALUES (NULL,?,?,?,?,?)");
                $stmt->bind_param("iiiii",$auth1,$auth2,$auth3,$auth4,$auth5);
                $auth1 = $authorIDArray[0];
                $auth2 = $authorIDArray[1];
                $auth3 = $authorIDArray[2];
                $auth4 = $authorIDArray[3];
                $auth5 = $authorIDArray[4];
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                #Get authorsID from authors table for current publication authors
                $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? and author2 = ? and author3 = ? and author4 = ? and author5 = ?");
                $stmt->bind_param("iiiii",$auth1,$auth2,$auth3,$auth4,$auth5);
                $auth1 = $authorIDArray[0];
                $auth2 = $authorIDArray[1];
                $auth3 = $authorIDArray[2];
                $auth4 = $authorIDArray[3];
                $auth5 = $authorIDArray[4];
                $stmt->execute();
                $stmt->bind_result($authorsID);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #Add publication to the publication table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO publication VALUES (NULL,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sisisiss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8);
                $var = "Book";
                $var2 = $authorsID;
                $var3 = $row['Title'];
                $var4 = $row['Year'];
                $var5 = "-";
                if(isset($row['Month'])){
                  $var6 = $row['Month'];
                }
                else{
                  $var6 = 0;
                }
                $var7 = $row['url'];
                if(isset($row['pubKey'])){
                  $var8 = $row['pubKey'];
                }
                else{
                  $var8 = "-";
                }
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                #Get publicationID we just added
                $stmt = $link->prepare("SELECT publicationID FROM publication WHERE title = ?");
                $stmt->bind_param("s",$var);
                $var = $row['Title'];
                $stmt->execute();
                $stmt->bind_result($publicationID);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #Add publication to Book table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO book VALUES (NULL,?,?,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("siisssissi",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$var9,$var10);
                $var = $row['Publisher'];
                if(isset($row['Volume'])){
                  $var2 = $row['Volume'];
                }
                else{
                  $var2 = $volCounter;
                }
                if(isset($row['Number'])){
                  $var3 = $row['Number'];
                }
                else{
                  $var3 = $volCounter;
                }
                if(isset($row['Series'])){
                  $var4 = $row['Series'];
                }
                else{
                  $var4 = "-";
                }
                if(isset($row['Address'])){
                  $var5 = $row['Address'];
                }
                else{
                  $var5 = "-";
                }
                if(isset($row['Edition'])){
                  $var6 = $row['Edition'];
                }
                else{
                  $var6 = "-";
                }
                if(isset($row['Month'])){
                  $var7 = $row['Month'];
                }
                else{
                  $var7 = 0;
                }
                if(isset($row['ISBN'])){
                  $var8 = $row['ISBN'];
                }
                else{
                  $var8 = "-";
                }
                if(isset($row['ISSN'])){
                  $var9 = $row['ISSN'];
                }
                else{
                  $var9 = "-";
                }
                $var10 = $publicationID;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                $volCounter++;
                unset($publicationID);
                unset($authorIDArray);
                unset($tmpOrcid);
              }

#IMPORT Inbook
              $result = $xml->xpath("/dblpperson/r/inbook");
              $array = array();

              foreach($result as $key => $node){
                foreach($node as $element){
                  if($element->getName() == "author"){
                    if(isset($array[$key])){
                      $array[$key]["Author"][] .= (String) $element;
                    }
                    else{
                      $array[$key] = array("Author"=>array((String) $element));
                    }
                    /*Author ORCID Field START*/
                    if($element['orcid']){
                      $array[$key]["ORCID"][] = (String) $element['orcid'];
                    }
                    else{
                      $array[$key]["ORCID"][] = "-";
                    }
                    /*Author ORCID Field END*/
                  }
                  else if($element->getName() == "title"){
                    $array[$key] += array("Title"=>(String) $element);
                  }
                  else if($element->getName() == "year"){
                    $array[$key] += array("Year"=>(String) $element);
                  }
                  else if($element->getName() == "url"){
                    $array[$key] += array("url"=>(String) $element);
                  }
                  else if($element->getName() == "publisher"){
                    $array[$key] += array("Publisher"=>(String) $element);
                  }
                  else if($element->getName() == "chapter"){
                    $array[$key] += array("Chapter"=>(String) $element);
                  }
                  else if($element->getName() == "pages"){
                    $array[$key] += array("Pages"=>(String) $element);
                  }
                  else if($element->getName() == "volume"){
                    $array[$key] += array("Volume"=>(String) $element);
                  }
                  else if($element->getName() == "number"){
                    $array[$key] += array("Number"=>(String) $element);
                  }
                  else if($element->getName() == "series"){
                    $array[$key] += array("Series"=>(String) $element);
                  }
                  else if($element->getName() == "type"){
                    $array[$key] += array("Type"=>(String) $element);
                  }
                  else if($element->getName() == "address"){
                    $array[$key] += array("Address"=>(String) $element);
                  }
                  else if($element->getName() == "edition"){
                    $array[$key] += array("Edition"=>(String) $element);
                  }
                  else if($element->getName() == "isbn"){
                    $array[$key] += array("ISBN"=>(String) $element);
                  }
                  else if($element->getName() == "issn"){
                    $array[$key] += array("ISSN"=>(String) $element);
                  }
                }

                foreach($node->attributes() as $attr => $attrValue){
                  if($attr == "mdate"){
                    #calculate month from corresponding attribute
                    $firstHyphen = strpos($attrValue, "-");
                    $tmpStr = substr($attrValue,$firstHyphen);
                    $secondHyphen = strripos($tmpStr,"-");
                    $month = substr($tmpStr,1,$secondHyphen-1);
                    $array[$key] += array("Month"=>(String) $month);
                  }
                  if($attr == "key"){
                    $array[$key] += array("pubKey"=>(String) $attrValue);
                  }
                }
              }

              $last = count($array) - 1;

              foreach ($array as $i => $row)
              {
                $tmpAuthors = array();
                $isFirst = ($i == 0);
                $isLast = ($i == $last);
                $j=0;
                foreach($array[$i]["Author"] as $aRow){
                  if(isset($tmpAuthors[$j])){
                    $tmpAuthors[$j] .= $aRow;
                  }
                  else{
                    $tmpAuthors[$j] = $aRow;
                  }
                  $j++;
                }

                /*ORCID ARRAY START*/
                $tmpOrcid = array();
                $z=0;
                foreach($array[$i]["ORCID"] as $aRow){
                  if(isset($tmpOrcid[$z])){
                    $tmpOrcid[$z] .= $aRow;
                  }
                  else{
                    $tmpOrcid[$z] = $aRow;
                  }
                  $z++;
                }
                /*ORCID ARRAY END*/

                $authorIDArray = array();

                for($x = 0; $x < $j; $x++){
                  $separator = strpos($tmpAuthors[$x], " ");
                  $surname = substr($tmpAuthors[$x], $separator+1);
                  $name = substr($tmpAuthors[$x], 0, $separator);

                  #Get author from Author table if he already exists
                  $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                  $stmt->bind_param("ss",$name,$surname);
                  $stmt->execute();
                  $stmt->bind_result($aAuthorID);
                  $stmt->fetch();
                  mysqli_commit($link); $stmt->close();

                  #If the author doesnt exist
                  if(empty($aAuthorID)){
                    #Add author to the author table
                    $stmt = $link->prepare("SET foreign_key_checks = 0");
                    $stmt = $link->prepare("INSERT INTO author VALUES (NULL,?,?,?,?,?,?)");
                    $stmt->bind_param("sssssi",$var,$var2,$var3,$var4,$var5,$var6);
                    $var = $name;
                    $var2 = $surname;
                    $var3 = $tmpOrcid[$x];  #ORCID
                    $var4 = "-";
                    $var5 = "-";
                    $var6 = $currentUserID;
                    $stmt->execute();
                    $stmt = $link->prepare("SET foreign_key_checks = 1");
                    mysqli_commit($link); $stmt->close();

                    #Get author id for current author from the author table and add it to the $authorIDArray
                    $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? and surname = ?");
                    $stmt->bind_param("ss",$var,$var2);
                    $var = $name;
                    $var2 = $surname;
                    $stmt->execute();
                    $stmt->bind_result($authorID);
                    $stmt->fetch();
                    mysqli_commit($link); $stmt->close();

                    array_push($authorIDArray, $authorID);
                  }
                  #Else add the authorID in the authorIDArray
                  else{
                    array_push($authorIDArray, $aAuthorID);
                    /*UPDATE AUTHOR ORCID START*/
                    if($tmpOrcid[$x] != "-"){
                      $stmt = $link->prepare("SET foreign_key_checks = 0");
                      $stmt = $link->prepare("UPDATE author SET orcID = ? WHERE authorID = ?");
                      $stmt->bind_param("si",$var1,$var2);
                      $var1 = $tmpOrcid[$x];
                      $var2 = $aAuthorID;
                      $stmt->execute();
                      $stmt = $link->prepare("SET foreign_key_checks = 1");
                      mysqli_commit($link); $stmt->close();
                    }
                    /*UPDATE AUTHOR ORCID END*/
                  }
                  #Reset aAuthorID variable
                  $aAuthorID = null;
                }

                $noOfAuthors = count($authorIDArray);
                if($noOfAuthors < 5){
                  for($y = $noOfAuthors; $y < 5; $y++){
                    array_push($authorIDArray, $authorIDArray[0]);
                  }
                }

                #Add authors for current publication to the authors table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO authors VALUES (NULL,?,?,?,?,?)");
                $stmt->bind_param("iiiii",$auth1,$auth2,$auth3,$auth4,$auth5);
                $auth1 = $authorIDArray[0];
                $auth2 = $authorIDArray[1];
                $auth3 = $authorIDArray[2];
                $auth4 = $authorIDArray[3];
                $auth5 = $authorIDArray[4];
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                #Get authorsID from authors table for current publication authors
                $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? and author2 = ? and author3 = ? and author4 = ? and author5 = ?");
                $stmt->bind_param("iiiii",$auth1,$auth2,$auth3,$auth4,$auth5);
                $auth1 = $authorIDArray[0];
                $auth2 = $authorIDArray[1];
                $auth3 = $authorIDArray[2];
                $auth4 = $authorIDArray[3];
                $auth5 = $authorIDArray[4];
                $stmt->execute();
                $stmt->bind_result($authorsID);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #Add publication to the publication table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO publication VALUES (NULL,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sisisiss",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8);
                $var = "Article";
                $var2 = $authorsID;
                $var3 = $row['Title'];
                $var4 = $row['Year'];
                $var5 = "-";
                if(isset($row['Month'])){
                  $var6 = $row['Month'];
                }
                else{
                  $var6 = 0;
                }
                $var7 = $row['url'];
                if(isset($row['pubKey'])){
                  $var8 = $row['pubKey'];
                }
                else{
                  $var8 = "-";
                }
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                #Get publicationID we just added
                $stmt = $link->prepare("SELECT publicationID FROM publication WHERE title = ?");
                $stmt->bind_param("s",$var);
                $var = $row['Title'];
                $stmt->execute();
                $stmt->bind_result($publicationID);
                $stmt->fetch();
                mysqli_commit($link); $stmt->close();

                #Add publication to Inbook table
                $stmt = $link->prepare("SET foreign_key_checks = 0");
                $stmt = $link->prepare("INSERT INTO inbook VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sssiissssssi",$var,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$var9,$var10,$var11,$var12);
                $var = $row['Publisher'];
                if(isset($row['Chapter'])){
                  $var2 = $row['Chapter'];
                }
                else{
                  $var2 = 0;
                }
                if(isset($row['Pages'])){
                  $var3 = $row['Pages'];
                }
                else{
                  $var3 = "-";
                }
                if(isset($row['Volume'])){
                  $var4 = $row['Volume'];
                }
                else{
                  $var4 = $volCounter;
                }
                if(isset($row['Number'])){
                  $var5 = $row['Number'];
                }
                else{
                  $var5 = $volCounter;
                }
                if(isset($row['Series'])){
                  $var6 = $row['Series'];
                }
                else{
                  $var6 = "-";
                }
                if(isset($row['Type'])){
                  $var7 = $row['Type'];
                }
                else{
                  $var7 = "-";
                }
                if(isset($row['Address'])){
                  $var8 = $row['Address'];
                }
                else{
                  $var8 = "-";
                }
                if(isset($row['Edition'])){
                  $var9 = $row['Edition'];
                }
                else{
                  $var9 = "-";
                }
                if(isset($row['ISBN'])){
                  $var10 = $row['ISBN'];
                }
                else{
                  $var10 = "-";
                }
                if(isset($row['ISSN'])){
                  $var11 = $row['ISSN'];
                }
                else{
                  $var11 = "-";
                }
                $var12 = $publicationID;
                $stmt->execute();
                $stmt = $link->prepare("SET foreign_key_checks = 1");
                mysqli_commit($link); $stmt->close();

                $volCounter++;
                unset($publicationID);
                unset($authorIDArray);
                unset($tmpOrcid);
              }
              echo "<script>alert('Contents have been successfully uploaded.');
                      window.location.replace('../publications.php');
                      </script>";

          }
        }
    }

?>
