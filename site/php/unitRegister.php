<?php
session_start();
include "connect.php";
error_reporting(0);

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$name = mysqli_real_escape_string($link, $_REQUEST['name']);
	$type = mysqli_real_escape_string($link, $_REQUEST['type']);
	$owner = mysqli_real_escape_string($link, $_REQUEST['owner']);
	$desc = mysqli_real_escape_string($link, $_REQUEST['desc']);
  $url = mysqli_real_escape_string($link, $_REQUEST['url']);

if(empty($name) || empty($url)){
  echo "<script>alert('Please fill every entry!');
        window.location.replace('../unit.php');
        </script>";
}
else{
  mysqli_autocommit($link, false);

  if($type == "Department"){
  	$type = "Department";
  }
  else if($type == "Lab"){
  	$type = "Lab";
  }

  $query = "INSERT INTO unit VALUES
              (
                NULL,
                '$name',
                '$type',
                '$owner',
                '$desc',
                '$url'
              )";

  $result = mysqli_query($link, $query);
  if ($result) {
  	#Redirect user to index.php
  	header("Location: ../index.php");
  	#commits the current transaction for the specified database connection
  	mysqli_commit($link);
  	exit();
  } else {
  	#Rolls back current transaction
  	mysqli_rollback($link);
  }
  mysqli_close($link);
}
}
?>
