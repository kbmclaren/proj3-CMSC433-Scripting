<?php
require_once "../loginNewUser/config.php";
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	header("location: ../loginNewUser/login.php");
	exit;
}
?>

<!DOCTYPE html>
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
			<div class="container" id="welcomeContainer" style="display: block;">\
				<?php echo("<h1>Welcome,<br>". $_SESSION["username"] . "!</h1>");?>
				<button type="reset" id="play" onclick="location.href='./worldMap.html';">Play</button>
				<button type="reset" id="resetPassword" onclick="location.href='../loginNewUser/reset_password.php';">Reset Password</button>
				<button type="reset" id="logout" onclick="location.href='../loginNewUser/logout.php';">Logout</button>
			</div>
		</form>
	</div>
</body>
</html>