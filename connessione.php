<?php
$connessione= new mysqli('localhost','root','','my_registrarichiesteit'); 
if($connessione->connect_error){
    die("Connessione fallita: " . $connessione->connect_error);
    exit();
}
?>

