<?php
	session_start();
	//print_r($_SESSION); 
	//var_dump($_SESSION);
	//var_dump($_SESSION['wallet']);

	
	$json = array(
		'name' => $_SESSION['username'],
		'wallet' => json_decode($_SESSION['wallet']),
		'pokedex' => json_decode($_SESSION['pokedex'])
	);
	$jsonString = json_encode( $json ); 
	//echo $jsonString;

?>

<!DOCTYPE html>  
<html><head><br><meta charset=utf-8 />  
<title>Beach Encounter</title>  

</head><body>
	<script>
		var jsonData = JSON.parse('<?php echo $jsonString; ?>');
	</script>
<style>
	canvas {
		width: 1800px;
		height: 900px;
		background-image: url('beachBG.jpg');
		background-repeat: no-repeat;
	}
	body{
	max-width: 1800px;
	margin: auto;
	background-image: url('background_border.png');
	}
	.menu .prompt {
		padding: 30px 0;
		background: #fff;
		width: 500px;
		height: 70px;
		border: 3px solid #000;
		border-radius: 4px;
		color: #000;
		text-align: center;
		font-size: 34px;
		float: left;
		
	}
	.menu .actions button {
		position: relative;
		float: left;
		background: #8B0000;
		border: 3px solid #CD5C5C;
		border-radius: 4px;
		color: #fff;
		font-family: monospace;
		font-size: 20px;
		line-height: 14px;
		margin: 4px;
		padding: 16px;
		width: calc(33% - 6px);
		height: calc(50% - 8px);
	}

	.menu .actions button:hover {
		background: #B22222;
		border-color: #CD5C5C;
	}

	.menu .actions button:active {
		background: #FA8072;
		border-color: #CD5C5C;
	}

	.menu .actions {
		float: left;
		width: 50%;
	}
</style>
<div class="menu">
  <div class="prompt" id="prompt">
  </div>
  <div class="actions">
    <button id="button1"></button>
    <button id="button3"></button>
	<button id="button5"></button>
  </div>
   <div class="actions">
	<button id="button2"></button>
    <button id="button4"></button>
	<button id="button6"></button>
  </div>
</div>
<script src="beachBattle.js" async></script>
	<canvas id="myCanvas" width="1800" height="900">
	</canvas>
</body>
</html>
