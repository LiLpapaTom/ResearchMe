<?php
session_start();
include "connect.php";
error_reporting(0);

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$username = mysqli_real_escape_string($link, $_REQUEST['username']);
	$pwd = mysqli_real_escape_string($link, $_REQUEST['pwd']);
	$cpwd = mysqli_real_escape_string($link, $_REQUEST['confirmPwd']);
	$role = mysqli_real_escape_string($link, $_REQUEST['role']);

if(empty($username) || empty($pwd) || empty($cpwd)){
	echo "<script>alert('Please fill every entry!');
				window.location.replace('../register.html');
				</script>";
}
else{
	if( $pwd != $cpwd )
		echo "<script>alert('Passwords do not match!');
					window.location.replace('../register.html');
					</script>";
	else{
		mysqli_autocommit($link, false);

		$stmt = $link->prepare("SELECT username FROM user WHERE username = ?");
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$user = $stmt->fetch();
		$stmt->close();
		#if user already exists in DB
		if($user == 1 || $user > 1){
			echo "<script>alert('User already exists!');
						window.location.replace('../register.html');
						</script>";
		}else{
			$pass = password_hash($pwd, PASSWORD_BCRYPT);
			#echo $user;
			$stmt = $link->prepare("INSERT INTO user VALUES (NULL,?,?,?)");
			$stmt->bind_param("sss",$username,$pass,$role);
			$stmt->execute();
			$stmt->close();

			$_SESSION['username'] = $username;
			$_SESSION['role'] = $role;

			mysqli_commit($link);
			mysqli_close($link);

			header("Location: ../index.php");
			exit();
		}
	}
}
}
?>
