<?php

function getPokemonById($pokemonId){
	$file = fopen('../pokemon.csv','r');
	$cont = True;
	$output = "";
	while (($line = fgetcsv($file)) !== False && $cont){
		// Find matching id
		if($line[0] === $pokemonId){
			$output = $line;
			$cont = False;
		}
	}
	fclose($file);
	return $output;
}

require_once "config.php";

$username = $password = $confirm_password= "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$playerId;
	if(empty(trim($_POST["rusername"]))){
		$username_err = "Please enter a username.";
	}
	elseif(!preg_match('/^[a-zA-z0-9_]+$/',trim($_POST["rusername"]))){
		$username_err = "Username can only contain letters, numbers, and underscores.";
	}
	else{
		$sql = "SELECT id FROM userTable WHERE username = ?";

		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = trim($_POST["rusername"]);
			
			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) >= 1){
					$username_err = "This username is already taken.";
				} else {

					$username = trim($_POST["rusername"]);
				}
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}

			mysqli_stmt_close($stmt);
		}
	}

	if(empty(trim($_POST["rpassword"]))){
		$password_err = "Please enter a password.";
	} elseif(strlen(trim($_POST["rpassword"])) < 6){
		$password_err = "Password must have at least 6 characters.";
	} else {
		$password = trim($_POST["rpassword"]);
	}

	if(empty(trim($_POST["cpassword"]))){
		$confirm_password_err = "Please confirm password.";
	} else {
		$confirm_password = trim($_POST["cpassword"]);
		if(empty($password_err) && ($password != $confirm_password)){
			$confirm_password_err = "Passwords did not match.";
		}
	}

	if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

		$sql = "INSERT INTO userTable (username, password, wallet, pokedex) VALUES (?, ?, ?, ?)";

		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_wallet, $param_pokedex);

			$startPokemon = getPokemonById($_POST['starter']);
			$unique_id = (int)date_timestamp_get(date_create());
			$goodKeyPokemonArray = [
				// Grabbed from table
				'id' => (int)$startPokemon[0],
				'name' => $startPokemon[1],
				'type1' => $startPokemon[2],
				'type2' => $startPokemon[3],
				'total' => (int)$startPokemon[4],
				'max_hp' => (int)$startPokemon[5],
				'attack' => (int)$startPokemon[6],
				'defense' => (int)$startPokemon[7],
				'special_attack' => (int)$startPokemon[8],
				'special_defense' => (int)$startPokemon[9],
				'speed' => (int)$startPokemon[10],
				'generation' => (int)$startPokemon[11],
				'legendary' => (bool)$startPokemon[12],
				// Default Values
				'unique_id' => $unique_id,
				'hp' => (int)$startPokemon[5],
				'experience' => 0,
				'trainCount' => 0,
				'exhausted' => false,
				'canEvolve' => true
			] ;
			$pokedexObj = array(
				$unique_id => $goodKeyPokemonArray
			);
			$walletObj = array(
				'money' => 100,
				'pokeballs' => 3,
				'candy' => 2,
				'heal_potion' => 1
			);
			$walletJSON = json_encode($walletObj);
			$pokedexJSON = json_encode($pokedexObj);

			//https://www.kindacode.com/article/xampp-on-mac-permission-denied/
			$targetFileName = "../" . $username . ".json";
			$breaker = ",";
			$handle = fopen($targetFileName, "w");
			fwrite($handle, $walletJSON);
			fwrite($handle, $breaker);
			fwrite($handle, $pokedexJSON);



			$param_username = $username;
			$param_password = password_hash($password, PASSWORD_DEFAULT);
			$param_wallet = $walletJSON ;
			$param_pokedex = $pokedexJSON;

			if($result = mysqli_stmt_execute($stmt)){

				header("location: login.php");
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
	<script src="../proj3.js">
		var player;
	</script>
</head>
<body>
	<div class="background" id="mainBackground">
		<img src="../sourceImg/pokemon_logo.png" id="loginLogo">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="container" id="registerContainer" style="display: block;">
				<div id="username">
					<label for="rusername">Username:</label><br>
					<input type="text" placeholder="  Enter Username" name="rusername" ><br>
				</div>
				<div id="password">
					<label for="rpassword">Password:</label><br>
					<input type="password" placeholder="  Enter Password" name="rpassword"><br>
				</div>
				<div id="cpassword">
					<label for="cpassword">Confirm Password:</label><br>
					<input type="password" placeholder="  Confirm Password" name="cpassword"><br>
				</div>
				<div id="dstarter">
					<label for="starter"><b>Select Starter Pokemon</b></label>
					<select name="starter" id="starter">
						<option value=1>Bulbasaur</option>
						<option value=4>Charmander</option>
						<option value=7>Squirtle</option>
					</select>
				</div>
				<button type="submit" id="register">Register</button>
				<a href="./login.php">Already have an account? Login.</a>
				<?php
					$error="";
					if(!empty($username_err)){
						$error = $username_err;
					}elseif(!empty($password_err)) {
						$error = $password_err;
					}elseif(!empty($confirm_password_err)){
						$error = $confirm_password_err;
					}
					if(!empty($error)){
						echo('<p id="errorMessage"><b>Error: ' . $error . "</b></p>\n");
						unset($error,$username_err,$password_err,$confirm_password_err);
					}
				?>
			</div>
		</form>
	</div>
</body>
</html>