<?php 
  session_start();
  require_once "../loginNewUser/config.php";
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Pokémon Center</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <?php
      function healPokemon(&$pokedex){

      }

      if(array_key_exists('heal',$_POST)){
        $wallet = json_decode($_SESSION['wallet']);
        $gold = $wallet->money;
        if($gold > 10){
          $gold -= 10;
          $wallet->money = $gold;
          $_SESSION['wallet'] = json_encode($wallet);

          $sql = "UPDATE userTable SET wallet = ? WHERE id = ?";
          if($stmt = mysqli_prepare($link,$sql)){
            mysqli_stmt_bind_param($stmt,"si",$param_wallet,$param_id);
            
            $param_wallet = $_SESSION["wallet"];
            $param_id = $_SESSION["id"];

            if(mysqli_stmt_execute($stmt)){

            }else{
              echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
          }
        }

        $pokedex = json_decode($_SESSION['pokedex']);
        foreach ($pokedex as $pokemon => $value) {
          $pokedex->$pokemon->hp = $pokedex->$pokemon->max_hp;
          $pokedex->$pokemon->exhausted = False;
        }
        $_SESSION['pokedex'] = json_encode($pokedex);
        $sql = "UPDATE userTable SET pokedex = ? WHERE id = ?";
        if($stmt = mysqli_prepare($link,$sql)){
          mysqli_stmt_bind_param($stmt,"si",$param_pokedex,$param_id);
          
          $param_pokedex = $_SESSION["pokedex"];
          $param_id = $_SESSION["id"];

          if(mysqli_stmt_execute($stmt)){

          }else{
            echo "Oops! Something went wrong. Please try again later.";
          }
          mysqli_stmt_close($stmt);
        }
      }
    ?>
    <img src="../sourceImg/header.png" alt="header" width="1024px" height="200px">
    <h2>Pokémon Center</h2>
    <p>This Pokémon Center is dedicated to the care of Pokémon! You can heal your exhausted or injured Pokémon here, free of charge.</p>
    <hr><br>
    <form id="clinic" method="POST">
      <input type="submit" name="heal" id="heal" value="HEAL POKEMON - 10G" /><br/>
    </form>
    
    <div class="center">
      <a href="worldMap.html"><button>Leave</button></a>
    </div>
  </body>
</html>
