<?php
session_start();
include "connect.php";
error_reporting(0);

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$name = mysqli_real_escape_string($link, $_REQUEST['name']);
	$surname = mysqli_real_escape_string($link, $_REQUEST['surname']);
	$orcid = mysqli_real_escape_string($link, $_REQUEST['orcid']);
	$url = mysqli_real_escape_string($link, $_REQUEST['url']);
  $attribute = mysqli_real_escape_string($link, $_REQUEST['role']);


if(empty($name) || empty($surname)){
	echo "<script>alert('The required fields are Name and Surname.');
				window.location.replace('../author.php');
				</script>";
}
else{
  mysqli_autocommit($link, false);

  if($attribute == "Lecturer"){
  	$attribute = "Professor";
  }
  else if($attribute == "Researcher"){
  	$attribute = "Researcher";
  }
  else if($attribute == "Technical Personnel"){
  	$attribute = "Technical Personnel";
  }
  else if($attribute == "Research Personnel"){
    $attribute = "Research Personnel";
		echo $attribute;
  }
  else if($attribute == "None"){
    $attribute = "-";
  }

	$username = $_SESSION['username'];

	$stmt = $link->prepare("SELECT userID FROM user WHERE username = ?");
	$stmt->bind_param("s",$username);
	$stmt->execute();
	$stmt->bind_result($userID);
	$stmt -> fetch();
  $stmt->close();

	if(empty($orcid)){
		$orcid = '-';
	}
	if(empty($url)){
		$orcid = '-';
	}

	echo $name,$surname,$orcid,$url,$attribute,$userID;
	$stmt = $link->prepare("INSERT INTO author VALUES (NULL,?,?,?,?,?,?)");
	$stmt->bind_param("sssssi",$name,$surname,$orcid,$url,$attribute,$userID);
	$stmt->execute();
	mysqli_commit($link); $stmt->close();

	echo "<script>alert('Author was successfully added.');
				window.location.replace('../index.php');
				</script>";
	}
}
?>
