<?php
session_start();
if(!isset($_SESSION['ruolo'])){
    header("Location: login.php");
    exit();
}
if($_SESSION['ruolo'] != "TITOLARE"){
    header("Location: login.php");
    exit();
}

include "connessione.php"; // Aggiunto per accesso al DB

$nome = $_SESSION['nome'];
$cognome = $_SESSION['cognome'];
$id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/stylehome.css">
    <a href="home.html"><img src="img/logo2.png" width="200" height="53"> </a>
    
</head>
<script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
<body>
    <center>
      
        <h1>Amministratore: <?php echo $nome . " " . $cognome; ?> 
        </h1> 
        <br>

        <h2>Lista Richieste</h2>
       <table border="1" cellpadding="5">
    <tr>
        <th>ID Richiesta</th>
        <th>Nome</th>
        <th>Data Invia</th>
        <th>Descrizione</th>
        <th>Tipo Urgenza</th>
        <th>Stato</th>
        <th>Data chiusuera</th>
    </tr>
    <?php
    $query = "SELECT *  FROM RICHIESTA ORDER BY DATA_INVIA DESC";
    $result = $connessione->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
             echo "<td>" . htmlspecialchars($row['NOME']) . "</td>";
            echo "<td>" . htmlspecialchars($row['DATA_INVIA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['DESCRIZIONE']) . "</td>";
            // Aggiungi una query per ottenere il nome del tipo di urgenza
            $urgenza_id = $row['URGENZA_ID'];
            $urgenza_query = "SELECT TIPOURGENZA FROM URGENZA WHERE ID = '$urgenza_id'";
            $urgenza_result = $connessione->query($urgenza_query);
            $urgenza_row = $urgenza_result->fetch_assoc();
            echo "<td>" . htmlspecialchars($urgenza_row['TIPOURGENZA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['STATO']) . "</td>";
            echo "<td>" . htmlspecialchars($row['DATA_CHIUSURA']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nessuna richiesta trovata.</td></tr>";
    }
    ?>
</table>

        <br>
        <a href="logout.php">Logout</a>
    </center>
    <button onclick="scrollToTop()" class="scroll-to-top">↑ Torna su</button>
</body>
</html>