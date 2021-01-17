<?php
    session_start();
    include "connect.php";
    error_reporting(0);

	  $username = mysqli_real_escape_string($link, $_REQUEST['username']);
    $pwd = mysqli_real_escape_string($link, $_REQUEST['pwd']);

    $user = array();

    $stmt = $link->prepare("SELECT username, password, role FROM user WHERE username = ?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
      $user = $row;
    }
    mysqli_commit($link); $stmt->close();

    if(empty($user)){
      echo "<script>alert('User does not exist!');
            window.location.replace('../login.html');
            </script>";
    }

    if(password_verify($pwd,$user['password'])){
      $_SESSION['username'] = $user['username'];
  		$_SESSION['role'] = $user['role'];
      header("Location: ../index.php");
      exit();
    }
?>
