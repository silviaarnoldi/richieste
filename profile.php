<?php
session_start();
//echo($_SESSION['id']);
if(!isset($_SESSION['id'])){
    header("Location: login.php");
}
$id=$_SESSION['id'];
$nome=$_SESSION['nome'];
$cognome=$_SESSION['cognome'];
$ruolo = strtoupper($_SESSION['ruolo']);
if($ruolo == "TITOLARE"){
    header("Location: homeAmministratore.php");
}else{
    if($ruolo == "IT"){
        header("Location: homeIT.php");
    }else{
            header("Location: home.php");
        }
    
}
/*
<!DOCTYPE html>
<html lang="">
    <body>
        <h1>Benvenuto <?php echo $ruolo; ?></h1>
        <a href="logout.php">Logout</a>
    </body>
</html>
*/
?>
