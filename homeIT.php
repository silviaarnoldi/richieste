<?php
// homeIT.php
include "connessione.php";
session_start();

if(!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] != "IT"){
    header("Location: login.php");
    exit();
}

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
    <a href="home.php"><img src="img/logo2.png" width="200" height="53"> </a>
    <title>Home IT</title>
</head>
<script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
<body>
<center>
    <h1>IT: <?php echo $nome . " " . $cognome; ?> </h1>

    <h1>Richieste:</h1>
    <table>
        <tr>
            <th>ID Richiesta</th>
            <th>Nome</th>
            <th>Data Invia</th>
            <th>Descrizione</th>
            <th>Tipo Urgenza</th>
            <th>Stato</th>
            <th>Screenshot</th>
            <th>Azioni</th>
        </tr>
        <?php
        // Mostra solo richieste con stato diverso da "Risolta"
        $query = "SELECT * FROM RICHIESTA WHERE STATO != 'Risolta' ORDER BY DATA_INVIA DESC";
        $result = $connessione->query($query);
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['ID'] . "</td>";
            echo "<td>" . $row['NOME'] . "</td>";
             echo "<td>" . $row['DATA_INVIA'] . "</td>";
            echo "<td>" . $row['DESCRIZIONE'] . "</td>";
             $urgenza_id = $row['URGENZA_ID'];
            $urgenza_query = "SELECT TIPOURGENZA FROM URGENZA WHERE ID = '$urgenza_id'";
            $urgenza_result = $connessione->query($urgenza_query);
            $urgenza_row = $urgenza_result->fetch_assoc();
            echo "<td>" . htmlspecialchars($urgenza_row['TIPOURGENZA']) . "</td>";
           
            echo "<td>" . $row['STATO'] . "</td>";
            if ($row['SCREENSHOT_PATH'] != null) {
                echo "<td> <form action='screenshot.php' method='post' style='display:inline; margin-right:5px;'>
                    <input type='hidden' name='id_richiesta' value='" . $row['ID'] . "'>
                    <button type='submit' >VISUALIZZA</button>
                </form></td>";
            } else {
                echo "<td>Nessuno</td>";
            }
            echo "<td>
                <form action='interventoController.php' method='post' style='display:inline; margin-right:5px;'>
                    <input type='hidden' name='id_richiesta' value='" . $row['ID'] . "'>
                    <input type='hidden' name='nuovo_stato' value='In corso'>
                    <button type='submit' style='background-color:orange;'>Segna In Corso</button>
                </form><br>
                <form action='interventoController.php' method='post' style='display:inline;'>
                    <input type='hidden' name='id_richiesta' value='" . $row['ID'] . "'>
                    <input type='hidden' name='nuovo_stato' value='Risolta'>
                    <button type='submit' style='background-color:green;'>Segna Risolta</button>
                </form>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>
    <br>
    <a href="logout.php">Logout</a>
</center>
<button onclick="scrollToTop()" class="scroll-to-top">↑ Torna su</button>
</body>
</html>