<?php

session_start();
require_once "../loginNewUser/config.php";
$sql = "UPDATE userTable SET wallet = ?, pokedex = ? WHERE id = ?";
if($stmt = mysqli_prepare($link,$sql)){
    mysqli_stmt_bind_param($stmt,"ssi",$param_wallet,$param_pokedex,$param_id);
        
    $param_wallet = $_SESSION["wallet"];
    $param_pokedex = $_SESSION["pokedex"];
    $param_id = $_SESSION["id"];

    if(mysqli_stmt_execute($stmt)){}
    else{echo "Oops! Something went wrong. Please try again later.";}
    mysqli_stmt_close($stmt);
}

$_SESSION = array();
session_destroy();
header("location: login.php");
exit;

?>