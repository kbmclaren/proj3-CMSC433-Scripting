<?php
session_start();

// Check if user is already logged in, if so then redirect to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("location: ../play/welcome.php");
	exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

	if(empty(trim($_POST["lusername"]))){
		$username_err = "Please enter username.";
	} else {
		$username = trim($_POST["lusername"]);
	}
	if(empty(trim($_POST["lpassword"]))){
		$password_err = "Please enter your password.";
	} else {
		$password = trim($_POST["lpassword"]);
	}

	if(empty($username_err) && empty($password_err)){
		$sql = "SELECT id, username, password, wallet, pokedex FROM userTable WHERE username = ?";

		if($stmt = mysqli_prepare($link,$sql)){

			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = $username;

			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) == 1){
					mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $wallet, $pokedex);
					if(mysqli_stmt_fetch($stmt)){
						echo('<p id="errorMessage"><b>@: ' . mysqli_stmt_fetch($stmt) . "<b></p>\n");
						if(password_verify($password, $hashed_password)){
							//session_start();

							$_SESSION["loggedin"] = true;
							$_SESSION["id"] = $id;
							$_SESSION["username"] = $username;
							$_SESSION["wallet"] = $wallet;
							$_SESSION["pokedex"] = $pokedex;
							header("location: ../play/welcome.php");
						} else{
							$login_err = "Invalid username or password.";
						}
					}
				}else{
					$login_err = "Invalid username or password.";
				}
			} else{
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
			<div class="container" id="loginContainer" style="display: block;">
				<div id="username">
					<label for="lusername">Username:</label><br>
					<input type="text" placeholder="  Enter Username" name="lusername"><br>
				</div>
				<div id="password">
					<label for="lpassword">Password:</label><br>
					<input type="password" placeholder="  Enter Password" name="lpassword"><br>
				</div>
				<button type="submit" id="login">Login</button>
				<a href="./register.php">Dont have an account? Register.</a>
				<!-- <button type="reset" id="toregister" onclick="document.location.href = 'register.php';">Register</button> 
				-->
				<?php
					if(!empty($username_err)){
						$error = $username_err;
					}elseif(!empty($password_err)) {
						$error = $password_err;
					}elseif(!empty($login_err)){
						$error = $login_err;
					}
					if(!empty($error)){
						echo('<p id="errorMessage"><b>Error: ' . $error . "<b></p>\n");
						unset($error,$username_err,$password_err,$login_err);
					}
				?>
			</div>
		</form>
	</div>
</body>
</html>