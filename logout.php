<?php
session_start();
$_SESSION['id']=null;
$_SESSION['nome']=null;

header("Location: login.php");
?>