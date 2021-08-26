<?php
session_start();

require_once "../loginNewUser/config.php";
$pokeball_err = $potion_err = $candy_err = "";
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Poké Mart</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <?php
      
      function buyPokeball(&$gold, &$pokeballCount, &$pokeball_err)
      {
        if($gold >= 15)
        {
          $gold = $gold - 15;
          $pokeballCount += 1;
          /* echo "You have purchased one Pokeball!";
          echo "You now have $gold gold."; */
        }
        else
        {
          /* echo "You do not have enough gold to buy this item."; */
          $pokeball_err = "You do not have enough gold to buy this pokeball.";

        }
      }
      
      function buyPotion(&$gold, &$potionCount, &$potion_err)
      {
        if($gold >= 50)
        {
          $gold = $gold - 50;
          $potionCount += 1;
          /* echo "You have purchased one Potion!";
          echo "You now have $gold gold."; */
        }
        else
        {
          /* echo "You do not have enough gold to buy this item."; */
          $potion_err = "You do not have enough gold to buy a healing potion.";

        }
      }
      
      function buyCandy(&$gold, &$candyCount, &$candy_err)
      {
        if($gold >= 75)
        {
          $gold = $gold - 75;
          $candyCount += 1;
          /* echo "You have purchased one piece of candy!";
          echo "You now have $gold gold."; */
        }
        else
        {
          /* echo "You do not have enough gold to buy this item."; */
          $candy_err = "You do not have enough gold to buy candy.";

        }
      }

      $wallet = json_decode($_SESSION['wallet']);

      //Replace with wallet values.
      $gold = $wallet->money; 
      $pokeballCount = $wallet->pokeballs;
      $potionCount = $wallet->heal_potion;
      $candyCount = $wallet->candy;

      if(array_key_exists('buyBall',$_POST)){
        buyPokeball($gold, $pokeballCount, $pokeball_err);
      }
      if(array_key_exists('buyPotion',$_POST)){
        buyPotion($gold, $potionCount, $potion_err);
      }
      if(array_key_exists('buyCandy',$_POST)){
        buyCandy($gold, $candyCount, $candy_err);
      }

      $wallet->money = $gold; 
      $wallet->pokeballs = $pokeballCount;
      $wallet->heal_potion = $potionCount;
      $wallet->candy = $candyCount;

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

    ?>

    <img src="../sourceImg/header.png" alt="header" width="1024px" height="200px">
    <h2>Poké Mart</h2>
    <p>What would you like to buy?<br> 
      Your wallet has the following: <?php /* $wallet = json_decode($_SESSION['wallet']); */ echo "$wallet->money "?> gold,<br>
      <?php echo "$wallet->pokeballs " ?>pokeballs,<br>
      <?php echo "$wallet->heal_potion " ?>healing potions,<br>
      <?php echo "$wallet->candy " ?>candy.<br>
    </p>
    <?php
    if(!empty($pokeball_err)){
      $error = $pokeball_err;
    }elseif(!empty($potion_err)) {
      $error = $potion_err;
    }elseif(!empty($candy_err)){
      $error = $candy_err;
    }
    if(!empty($error)){
      echo('<p id="errorMessage"><b>Error: ' . $error . "<b></p>\n");
      unset($error,$pokeball_err,$potion_err,$candy_err);
    }
    ?>
    <hr><br>

    <div class="row">
      <div class="column">
        <h3 class="item">Pokéball</h3>
        <p>Used to capture weakened Wild Pokémon</p>
        <img src="../sourceImg/pokeball.png" alt="pokeball" width="250px" height="200px">
        <form method="post">
          <input type="submit" name="buyBall" id="buyBall" value="BUY - 15G" /><br/>
        </form>
      </div>
      <div class="column">
        <h3 class="item">Healing Potion</h3>
        <p>Heals your Pokémon in battle</p><br>
        <img src="../sourceImg/Potion.png" alt="potion" width="180px" height="200px">
        <form method="post">
          <input type="submit" name="buyPotion" id="buyPotion" value="BUY - 50G" /><br/>
        </form>
      </div>
      <div class="column">
        <h3 class="item">Candy</h3>
        <p>Feed to your Pokémon to evolve it</p><br>
        <img src="../sourceImg/candy.png" alt="candy">
        <form method="post">
          <input type="submit" name="buyCandy" id="buyCandy" value="BUY - 75G" />
        </form>
      </div>
    </div>

    <br>
    <div class="center">
      <a href="worldMap.html"><button>Leave</button></a>
    </div>
  </body>
</html>
