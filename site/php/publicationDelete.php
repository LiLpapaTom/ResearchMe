<?php
  session_start();
  include "connect.php";
  error_reporting(0);

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    $publicationID = mysqli_real_escape_string($link,$_REQUEST['publicationid']);

    $stmt = $link->prepare("SELECT authorsID FROM publication WHERE publicationID = ?");
    $stmt->bind_param("i",$var);
    $var = intval($publicationID);
    $stmt->execute();
    $stmt->bind_result($authorsID);
    $stmt->fetch();$stmt->close();

    if(!empty($authorsID)){
      $stmt = $link->prepare("DELETE FROM publication WHERE publicationID = ?");
      $stmt->bind_param("i",$var);
      $var = intval($publicationID);
      $stmt->execute();

      $stmt = $link->prepare("DELETE FROM authors WHERE authorsID = ?");
      $stmt->bind_param("i",$authorsID);
      $stmt->execute();
      $stmt->close();
    }
  }
  echo "<script>alert('Post successfully deleted');
        window.location.replace('../index.php');
        </script>";
 ?>
