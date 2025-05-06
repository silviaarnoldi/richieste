<?php
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
    <title>Home IT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/stylehome.css">
    <style>
        /* Stile per il modale */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            width: 80%;
            max-width: 800px;
            border-radius: 10px;
            text-align: center;
            position: relative;
        }
        .close {
            position: absolute;
            top: 10px; right: 20px;
            font-size: 28px;
            color: #aaa;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
    </style>
    <script>
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function mostraScreenshot(idRichiesta) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "screenshot.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById("screenshotContainer").innerHTML = xhr.responseText;
                document.getElementById("screenshotModal").style.display = "block";
            } else {
                document.getElementById("screenshotContainer").innerHTML = "Errore nel caricamento dello screenshot.";
            }
        };

        xhr.send("id_richiesta=" + encodeURIComponent(idRichiesta));
    }

    function chiudiModale() {
        document.getElementById("screenshotModal").style.display = "none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById("screenshotModal");
        if (event.target === modal) {
            chiudiModale();
        }
    }
    </script>
</head>
<body>

<center>
    <a href="home.php"><img src="img/logo2.png" width="200" height="53"></a>
    <h1>IT: <?php echo htmlspecialchars($nome . " " . $cognome); ?></h1>

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
        $query = "SELECT * FROM RICHIESTA WHERE STATO != 'Risolta' ORDER BY DATA_INVIA DESC";
        $result = $connessione->query($query);

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['ID'] . "</td>";
            echo "<td>" . htmlspecialchars($row['NOME']) . "</td>";
            echo "<td>" . $row['DATA_INVIA'] . "</td>";
            echo "<td>" . htmlspecialchars($row['DESCRIZIONE']) . "</td>";

            $urgenza_id = $row['URGENZA_ID'];
            $urgenza_query = "SELECT TIPOURGENZA FROM URGENZA WHERE ID = '$urgenza_id'";
            $urgenza_result = $connessione->query($urgenza_query);
            $urgenza_row = $urgenza_result->fetch_assoc();
            echo "<td>" . htmlspecialchars($urgenza_row['TIPOURGENZA']) . "</td>";

            echo "<td>" . htmlspecialchars($row['STATO']) . "</td>";

            if ($row['SCREENSHOT_PATH'] != null) {
                echo "<td><button onclick='mostraScreenshot(" . $row['ID'] . ")'>VISUALIZZA</button></td>";
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

<!-- MODALE -->
<div id="screenshotModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="chiudiModale()">&times;</span>
        <div id="screenshotContainer"></div>
    </div>
</div>

<button onclick="scrollToTop()" class="scroll-to-top">↑ Torna su</button>

</body>
</html>
