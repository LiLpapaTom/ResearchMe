<?php
  include "connect.php";
  error_reporting(0);

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name = mysqli_real_escape_string($link, $_REQUEST['name']);
    $surname = mysqli_real_escape_string($link, $_REQUEST['surname']);
    $startYear = mysqli_real_escape_string($link, $_REQUEST['startYear']);
    $endYear = mysqli_real_escape_string($link, $_REQUEST['endYear']);

    if(empty($name) || empty($surname)){
      echo "<script>alert('Both fields are required.');
            window.location.replace('statistics.php');
            </script>";
    }
    else{
      mysqli_autocommit($link, false);
      #Get authorID from author table that corresponds to the name,surname given
      $stmt = $link->prepare("SELECT authorID FROM author WHERE name = ? AND surname = ?");
      $stmt->bind_param("ss",$name,$surname);
      $stmt->execute();
      $stmt->bind_result($authorID);
      $stmt->fetch();
      mysqli_commit($link); $stmt->close();

      #If author exists
      if(!empty($authorID)){
        $authorsIDArray = array(); #Array to hold all authorsID relevant to my author

        #Get all authorsID from authors table that include $authorID and add them to $authorsIDArray
        $stmt = $link->prepare("SELECT authorsID FROM authors WHERE author = ? OR author2 = ? OR author3 = ? OR author4 = ? OR author5 = ?");
        $stmt->bind_param("sssss",$authorID,$authorID,$authorID,$authorID,$authorID);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
          $authorsIDArray[] = $row;
        }
        mysqli_commit($link); $stmt->close();

        $tmpCounter = 0;
        $totalPublications = 0; $totalArticles = 0; $totalInproceedings = 0; $totalBooks = 0; $totalInbooks = 0;
        $perYearArray= array();
        $artPercent = 0; $inprPercent = 0; $bookPercent = 0; $inbookPercent = 0; $totalPercent =0;

        foreach($authorsIDArray as $row){
          foreach($row as $entry){
            #Count total publications
            $stmt = $link->prepare("SELECT COUNT(publicationID) FROM publication WHERE authorsID = ?");
            $stmt->bind_param("i",$entry);
            $stmt->execute();
            $stmt->bind_result($tmpCounter);
            $stmt->fetch();
            mysqli_commit($link); $stmt->close();
            $totalPublications += $tmpCounter;

            #Count total Articles
            $stmt = $link->prepare("SELECT COUNT(publicationID) FROM publication WHERE authorsID = ? AND type = 'Article'");
            $stmt->bind_param("i",$entry);
            $stmt->execute();
            $stmt->bind_result($tmpCounter);
            $stmt->fetch();
            mysqli_commit($link); $stmt->close();
            $totalArticles += $tmpCounter;

            #Count total Inproceedings
            $stmt = $link->prepare("SELECT COUNT(publicationID) FROM publication WHERE authorsID = ? AND type = 'Inproceedings'");
            $stmt->bind_param("i",$entry);
            $stmt->execute();
            $stmt->bind_result($tmpCounter);
            $stmt->fetch();
            mysqli_commit($link); $stmt->close();
            $totalInproceedings += $tmpCounter;

            #Count total Books
            $stmt = $link->prepare("SELECT COUNT(publicationID) FROM publication WHERE authorsID = ? AND type = 'Book'");
            $stmt->bind_param("i",$entry);
            $stmt->execute();
            $stmt->bind_result($tmpCounter);
            $stmt->fetch();
            mysqli_commit($link); $stmt->close();
            $totalBooks += $tmpCounter;

            #Count total Inbooks
            $stmt = $link->prepare("SELECT COUNT(publicationID) FROM publication WHERE authorsID = ? AND type = 'Inbook'");
            $stmt->bind_param("i",$entry);
            $stmt->execute();
            $stmt->bind_result($tmpCounter);
            $stmt->fetch();
            mysqli_commit($link); $stmt->close();
            $totalInbooks += $tmpCounter;


            #Total per year
            $stmt = $link->prepare("SELECT year FROM publication WHERE authorsID = ?");
            $stmt->bind_param("i",$entry);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row2 = $result->fetch_assoc()){
              if(isset($perYearArray[$row2['year']])){
                $perYearArray[$row2['year']] += 1;
              }
              else{
                $perYearArray[$row2['year']] = 1;
              }
            }
            mysqli_commit($link); $stmt->close();

            if($startYear != null && $endYear != null){
              #Percentage per genre in specified range of years
              $stmt = $link->prepare("SELECT type FROM publication WHERE authorsID = ? AND year BETWEEN ? AND ?");
              $stmt->bind_param("iii",$entry,$startYear,$endYear);
              $stmt->execute();
              $stmt->bind_result($type);
              $stmt->fetch();
              mysqli_commit($link); $stmt->close();
              $totalPercent++;
              if($type == "Article"){$artPercent++;}
              else if($type == "Inproceedings"){$inprPercent++;}
              else if($type == "Book"){$bookPercent++;}
              else if($type == "Inbook"){$inbookPercent++;}
            }

          }
        }

        ksort($perYearArray);

        echo "Art".$artPercent." Inpr".$inprPercent." ".$bookPercent." ".$inbookPercent." ".$totalPercent;


        $dataPoints = array();
        foreach($perYearArray as $key=>$value){
          array_push($dataPoints, array("label"=>$key,"y"=>$value));
        }

        echo "<h2>Results for " . $name . " " . $surname . "</h2><br>
              <p style='background-color: white;'>Total Publications: " . $totalPublications . "<br>Total Articles: " . $totalArticles . "<br>
              Total Inproceedings: " . $totalInproceedings . "<br>Total Books: " . $totalBooks . "<br>Total Inbooks: " . $totalInbooks . "</p><br>
              <div id='chartDiv1' style='height:400px;'></div>
              <!--Canvas.js-->
              <script src='https://canvasjs.com/assets/script/canvasjs.min.js'></script>
              <script type='text/javascript'>
                var chart = new CanvasJS.Chart('chartDiv1', {
                  animationEnabled: true,
                  exportEnabled: true,
                  theme: 'light1',
                  title:{
                    text: '" . $name . " ". $surname . "'
                  },
                  data: [{
                    type: 'column', //change type to bar, line, area, pie, etc
                    indexLabel: '{y}', //Shows y value on all Data Points
                    indexLabelFontColor: '#5A5757',
                    indexLabelPlacement: 'outside',
                    dataPoints:" . json_encode($dataPoints, JSON_NUMERIC_CHECK) . "
                  }]
                });
                chart.render();
                </script>
              ";

        if($startYear != null && $endYear != null){
          $dataPoints2 = array();
          if($artPercent > 0){
            $a = $artPercent/$totalPercent;
            array_push($dataPoints2,array("label"=>"Articles","y"=>$a));
          }
          if($inprPercent > 0){
            $b = $inprPercent/$totalPercent;
            array_push($dataPoints2,array("label"=>"Inproceedings","y"=>$b));
          }
          if($bookPercent > 0){
            $c = $bookPercent/$totalPercent;
            array_push($dataPoints2,array("label"=>"Book","y"=>$c));
          }
          if($inbookPercent > 0){
            $d = $inbookPercent/$totalPercent;
            array_push($dataPoints2,array("label"=>"Inbook","y"=>$d));
          }


          echo "<br><div id='chartDiv2' style='height:400px;'></div><br><p></p>
                <script type='text/javascript'>
                var chart2 = new CanvasJS.Chart('chartDiv2', {
                	theme: 'light1',
                	animationEnabled: true,
                	title: {
                		text: '" . $name . " " . $surname . " " . $startYear . "-" . $endYear . "'
                	},
                	data: [{
                		type: 'pie',
                		indexLabel: '{y}',
                		yValueFormatString: '#,##0.00\"%\"',
                		indexLabelPlacement: 'inside',
                		indexLabelFontColor: '#36454F',
                		indexLabelFontSize: 18,
                		indexLabelFontWeight: 'bolder',
                		showInLegend: true,
                		legendText: '{label}',
                		dataPoints: " . json_encode($dataPoints2, JSON_NUMERIC_CHECK) . "
                	}]
                });
                chart2.render();
                </script>
                ";
        }

      }
      else{
        echo "<script>alert('Author doesn't exist.');
              window.location.replace('statistics.php');
              </script>";
      }
    }
    }
?>
