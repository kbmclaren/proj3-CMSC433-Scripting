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
      $wallet = json_decode($_SESSION['wallet']);

      //Replace with wallet values.
      $gold = $wallet->money;
      $gold += rand(5, 25); 
 

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

    ?>

    <img src="../sourceImg/header.png" alt="header" width="1024px" height="200px">
    <h2>Battle Complete</h2>
    <p>You have completed a battle!<br> 
      Your wallet has the following: <?php /* $wallet = json_decode($_SESSION['wallet']); */ echo "$wallet->money "?> gold<br>
    </p>
    <hr><br>
    <div class="center">
      <a href="worldMap.html"><button>Leave</button></a>
    </div>
  </body>
</html>