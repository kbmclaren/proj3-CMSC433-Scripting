<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}

require_once "config.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

	if(empty(trim($_POST["npassword"]))){
		$new_password_err = "Please enter a new password.";
	}
	elseif(!preg_match('/^[a-zA-z0-9_]+$/',trim($_POST["npassword"]))){
		$new_password_err = "Username can only contain letters, numbers, and underscores.";
	}
	else{
		$new_password = trim($_POST["npassword"]);
	}

	if(empty(trim($_POST["cpassword"]))){
		$confirm_password_err = "Please confirm the password.";
	} else{
		$confirm_password = trim($_POST["cpassword"]);
		if(empty($new_password_err) && ($new_password != $confirm_password)){
			$confirm_password_err = "Passwords did not match.";
		}
	}

	if(empty($new_password_err) && empty($confirm_password_err)){
		$sql = "UPDATE userTable SET password = ? WHERE id = ?";

		if($stmt = mysqli_prepare($link,$sql)){
			mysqli_stmt_bind_param($stmt,"si",$param_password,$param_id);

			$param_password = password_hash($new_password, PASSWORD_DEFAULT);
			$param_id = $_SESSION["id"];

			if(mysqli_stmt_execute($stmt)){
				session_destroy();
				header("location: login.php");
				exit();
			}else{
				echo "Oops! Something went wrong. Please try again later.";
			}

			mysqli_stmt_close($stmt);
		}
	}
	mysqli_close($link);
}

?>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../proj3.css">
	<link href="//db.onlinewebfonts.com/c/f4d1593471d222ddebd973210265762a?family=Pokemon" rel="stylesheet" type="text/css"/>
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script type="text/javascript">
		
	</script>
</head>
<body>
	<div class="background" id="mainBackground">
		<img src="../sourceImg/pokemon_logo.png" id="loginLogo">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="container" id="resetPasswordContainer" style="display: block;">
				<div id="npassword">
					<label for="npassword">New Password:</label><br>
					<input type="password" placeholder="  Enter New Password" name="npassword"><br>
				</div>
				<div id="cpassword">
					<label for="cpassword">Confirm Password:</label><br>
					<input type="password" placeholder="  Confirm Password" name="cpassword"><br>
				</div>
				<button type="submit" id="resetPassword">Reset Password</button>
				<button type="reset" id="cancel" onclick="location.href='../play/welcome.php';">Cancel</button>
				<?php
					$error="";
					if(!empty($new_password_err)){
						$error = $new_password_err;
					}elseif(!empty($confirm_password_err)){
						$error = $confirm_password_err;
					}
					if(!empty($error)){
						echo('<p id="errorMessage"><b>Error: ' . $error . "</b></p>\n");
						unset($error,$new_password_err,$confirm_password_err);
					}
				?>
			</div>
		</form>
	</div>
</body>
</html>