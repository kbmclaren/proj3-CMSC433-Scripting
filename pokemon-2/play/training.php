<?php
  session_start();
  require_once "../loginNewUser/config.php";


  function getPokemonById($pokemonId){
    $file = fopen('../pokemon.csv','r');
    $cont = True;
    $output = "";
    while (($line = fgetcsv($file)) !== False && $cont){
      // Find matching id
      if($line[0] === (string)$pokemonId){
        $output = $line;
        $cont = False;
      }
    }
    fclose($file);
    return $output;
  }
  
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pokémon Training Center</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
  <?php
  
    function trainPokemon(&$exhausted, &$trainingCount, &$exhausted_err, &$training_msg){

      $experienceGain = 0;
      if($exhausted){
        $exhausted_err = "Your Pokémon is exhausted!<br>Take them to the hospital or use a healing potion before you attempt training.<br>";
      }
      else{
        if($trainingCount <= 2)
        {
          $experienceGain = 25;
          $trainingCount++;
        }
        elseif( ($trainingCount == 3) || ($trainingCount == 4 ))
        { 
          $experienceGain = 10;
          $trainingCount++;
        }
        else
        {
          $experienceGain = 5;
          $exhausted = true;
        }
        $training_msg = "You have trained your Pokémon!<br>It has earned $experienceGain experience.";
        return $experienceGain;
      }
    }

    //handle post of candy button
    if(isset($_POST['feed'])){
      //grab the wallet
      $wallet = json_decode($_SESSION['wallet']);
      //var_dump($wallet); 
      $candy = (int)$wallet->candy;

      //else the user has candy
      if($candy == 0 ){
        goto noCandy; 
      }
      
      //grab value of $_POST['feed'] = pokemon id, 
      // and unset $_POST so we can press the button again.
      $pkmid = $_POST['feed'];
      unset($_POST['feed']);
      
      //this grabs the correct pokemon from the pokedex
      $pokedex = json_decode($_SESSION['pokedex']);
      $pokemon = $pokedex->$pkmid;
      //echo $pokemon->id;

      //candy forces evolution if possible 
      //have to grab the next pokemon in the pokemon.csv and make sure it is related
      $tempId = $pokemon->id + 1;
      $nextPokemon = getPokemonById($tempId);
      //print_r($nextPokemon);

      //compare id and total
      if ((int)$nextPokemon[0] > $pokemon->id && (int)$nextPokemon[4] > $pokemon->total){
        //$nextPokemon is the next evolution, build pokemon and insert into database inplace of $pokemon
        //grab unique id to transfer to new evolution
        $unique_id = $pkmid; 
        //prepare new pokemon for insertion into pokedeck
        $goodKeyPokemonArray = [
          // Grabbed from table
          'id' => (int)$nextPokemon[0],
          'name' => $nextPokemon[1],
          'type1' => $nextPokemon[2],
          'type2' => $nextPokemon[3],
          'total' => (int)$nextPokemon[4],
          'max_hp' => (int)$nextPokemon[5],
          'attack' => (int)$nextPokemon[6],
          'defense' => (int)$nextPokemon[7],
          'special_attack' => (int)$nextPokemon[8],
          'special_defense' => (int)$nextPokemon[9],
          'speed' => (int)$nextPokemon[10],
          'generation' => (int)$nextPokemon[11],
          'legendary' => (bool)$nextPokemon[12],
          // Default Values
          'unique_id' => $unique_id,
          'hp' => (int)$nextPokemon[5],
          'experience' => 0,
          'trainCount' => 0,
          'exhausted' => false,
          'canEvolve' => true
        ];
        //old pokedex key points to newly evolved pokemon
        //store updated pokedex
        $pokedex->$pkmid = $goodKeyPokemonArray;
        $_SESSION["pokedex"] = json_encode($pokedex);

        //adjust $candy and store in wallet
        $candy -= 1;
        $wallet->candy = $candy;
        $_SESSION["wallet"] = json_encode($wallet);
        $sql = "UPDATE userTable SET wallet = ? WHERE id = ?";
        if($stmt = mysqli_prepare($link,$sql)){
          mysqli_stmt_bind_param($stmt,"si",$param_wallet,$param_id);
          
          $param_wallet = $_SESSION["wallet"];
          $param_id = $_SESSION["id"];
          if(mysqli_stmt_execute($stmt)){

          }else{
            $wallet_err = "Oops! Something went wrong updating wallet. Please try again later.";
          }
        }

        $sql = "UPDATE userTable SET pokedex = ? WHERE id = ?";
        if($stmt = mysqli_prepare($link,$sql)){
          mysqli_stmt_bind_param($stmt,"si",$param_pokedex,$param_id);
            
          $param_pokedex = $_SESSION['pokedex'];
          $param_id = $_SESSION["id"];

          if(mysqli_stmt_execute($stmt)){
            session_write_close(); 
            header("location: training.php");
          }
          else{
            $pokedex_err = "Oops! Something went wrong updating pokedex. Please try again later.";
          }
          mysqli_stmt_close($stmt);
        }

      } else {
        //unable to evolve further at this time 
        //no candy spent
        $evolution_err = "Unable to evolve pokemon #" . $pkmid;
      }

    
    }

    //handle post of "train" button
    if(isset($_POST['train'])){
      //grab pokemon id
      $pkmid = $_POST['train'];
      unset($_POST['train']);

      //this grabs the correct pokemon from the pokedex
      $pokedex = json_decode($_SESSION['pokedex']);
      //var_dump($pokedex);
      $pokemon = $pokedex->$pkmid;
      //var_dump($pokemon);
      //var_dump($pokemon->id);

      //check if trainable
      if($pokemon->exhausted == False){
        //pokemon train returns a new value based on $pokemon->trainCount 
        $gain = trainPokemon($pokemon->exhausted, $pokemon->trainCount,$exhausted_err, $training_msg, );
        
        //add gain to pokemon experience
        $pokemon->experience += $gain; 

        //training forces evolution if possible
        //check for eligibility to evolve
        $tempId = $pokemon->id + 1; 
        $nextPokemon = getPokemonById($tempId);
        //var_dump($nextPokemon); 
        if ((int)$nextPokemon[0] > $pokemon->id && (int)$nextPokemon[4] > $pokemon->total){
          //calc difference between pokemon evolutions
          //check if $pokemon->experience is enough to evolve
          $expDiff = (int)$nextPokemon[4] - $pokemon->total;
          //var_dump($expDiff); 
          if( $pokemon->experience > $expDiff) {
            //pokemon has enough experience to evolve - yay!
            $unique_id = $pkmid; 
            //prepare new pokemon for insertion into pokedeck
            $goodKeyPokemonArray = [
              // Grabbed from table
              'id' => (int)$nextPokemon[0],
              'name' => $nextPokemon[1],
              'type1' => $nextPokemon[2],
              'type2' => $nextPokemon[3],
              'total' => (int)$nextPokemon[4],
              'max_hp' => (int)$nextPokemon[5],
              'attack' => (int)$nextPokemon[6],
              'defense' => (int)$nextPokemon[7],
              'special_attack' => (int)$nextPokemon[8],
              'special_defense' => (int)$nextPokemon[9],
              'speed' => (int)$nextPokemon[10],
              'generation' => (int)$nextPokemon[11],
              'legendary' => (bool)$nextPokemon[12],
              // Default Values
              'unique_id' => $unique_id,
              'hp' => (int)$nextPokemon[5],
              'experience' => 0,
              'trainCount' => 0,
              'exhausted' => false,
              'canEvolve' => true
            ];
            //old pokedex key points to newly evolved pokemon
            //store updated pokedex
            $pokedex->$pkmid = $goodKeyPokemonArray;
            $_SESSION['pokedex'] = json_encode($pokedex);
            /* echo "pokemon ". $pkmid . " evolved.<br>"; */
            //var_dump($_SESSION['pokedex']); 

            $sql = "UPDATE userTable SET pokedex = ? WHERE id = ?";
            if($stmt = mysqli_prepare($link,$sql)){
              mysqli_stmt_bind_param($stmt,"si",$param_pokedex,$param_id);
                
              $param_pokedex = $_SESSION['pokedex'];
              $param_id = $_SESSION["id"];

              if(mysqli_stmt_execute($stmt)){
                session_write_close(); 
                header("location: training.php");
              }
              else{
                $pokedex_err = "Oops! Something went wrong updating pokedex. Please try again later.";
              }
              mysqli_stmt_close($stmt);
            }
          }
        }else{
          $evolution_err = "Unable to evolve pokemon #" . $pkmid ;
        }
        //re-assign (unecessary?) newly trained pokemon.
        //store adjusted pokedex 
        $pokedex->$pkmid = $pokemon;
        $_SESSION['pokedex'] = json_encode($pokedex);
      } else {
        $exhausted_err = "Your pokemon ". $pkmid ." is exhausted. <br>Go to the hospital or use a healing potion before further training.";
      }
      
    }
    goto end;
    noCandy: 
    $candy_err = "No candy in your wallet"; 
    end: 
    //do nothing 
  ?>
    <img src="../sourceImg/header.png" alt="header" width="1024px" height="200px">
    <h2>Pokémon Training Center</h2>
    <p>Here in the Pokémon Training Center, you can help your current team level up and become stronger. They even have a chance to evolve!</br>
      Your wallet has the following:<br> <?php $wallet = json_decode($_SESSION['wallet']); ?>
      <?php echo "$wallet->heal_potion " ?>healing potions,<br>
      <?php echo "$wallet->candy " ?>candy.<br>
    </p>
    <?php
    if(!empty($exhausted_err)){
      $trainingCenterMsg = $exhausted_err;
    }elseif(!empty($training_msg)) {
      $trainingCenterMsg = $training_msg;
    }elseif(!empty($evolution_err)) {
      $trainingCenterMsg = $evolution_err;
    }elseif(!empty($potion_err)) {
      $trainingCenterMsg = $potion_err;
    }elseif(!empty($candy_err)){
      $trainingCenterMsg = $candy_err;
    }elseif(!empty($wallet_err)) {
      $trainingCenterMsg = $wallet_err;
    }elseif(!empty($pokedex_err)) {
      $trainingCenterMsg = $pokedex_err;
    };
    
    if(!empty($trainingCenterMsg)){
      echo('<p id="noticeMessage"><b>Notice: ' . $trainingCenterMsg . "<b></p>\n");
      unset($trainingCenterMsg, $wallet_err, $training_msg, $exhausted_err, $potion_err, $candy_err);
    }
    ?>
    <hr><br>

    <table class="pokeTable">
        <thead>
            <tr>
            <th scope="col">UniqueID</th>
            <th scope="col">Name</th>
            <th scope="col">Type 1</th>
            <th scope="col">Type 2</th>
            <th scope="col">Total</th>
            <th scope="col">HP</th>
            <th scope="col">Attack</th>
            <th scope="col">Defense</th>
            <th scope="col">Special Attack</th>
            <th scope="col">Special Defense</th>
            <th scope="col">Speed</th>
            <th scope="col">Legendary?</th>
            <th scope="col">Exhausted?</th>
            <th scope="col">Experience</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $pokedex = json_decode($_SESSION['pokedex']); 
            foreach( $pokedex as $pokemon) { ?>
            <tr>
                <td><?php echo $pokemon->unique_id ?>
                <td><?php echo $pokemon->name ?></td>
                <td><?php echo $pokemon->type1 ?></td>
                <td><?php echo $pokemon->type2 ?></td>
                <td><?php echo $pokemon->total ?></td>
                <td><?php echo $pokemon->hp ?></td>
                <td><?php echo $pokemon->attack ?></td>
                <td><?php echo $pokemon->defense ?></td>
                <td><?php echo $pokemon->special_attack ?></td>
                <td><?php echo $pokemon->special_defense ?></td>
                <td><?php echo $pokemon->speed ?></td>
                <td><?php if($pokemon->legendary == "False"){echo "False";}else{echo "True";} ?></td>
                <td><?php if($pokemon->exhausted){echo "True";}else{echo "False";} ?></td>
                <td><?php echo $pokemon->experience ?></td>
                <td>
                    <form method="post">
                      <?php /* $train_id = "train_" . $pokemon->unique_id; $feed_id = "feed_" . $pokemon->unique_id; */ ?>
                      <button type="submit" name="train" id="train" value="<?php echo $pokemon->unique_id ?>">Train</button>
                      <button type="submit" name="feed" id="feed" value="<?php echo $pokemon->unique_id ?>">Feed Candy</button>
                    </form>
                </td>
            </tr>

            <?php } ?>
        </tbody>
    </table>

    <div class="center">
      <!-- <form method="post">
        <button type="submit" name="train_all" id="train_all" value="all">Train All</button>
      </form> -->
      <a href="worldMap.html"><button>Leave</button></a>
    </div>
  </body>
</html>
