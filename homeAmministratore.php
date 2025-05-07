<?php
session_start();
if (!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] !== "TITOLARE") {
    header("Location: login.php?error=Accesso negato");
    exit();
}
include "connessione.php";

$nome = $_SESSION['nome'];
$cognome = $_SESSION['cognome'];
$id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Richieste</title>
    <link rel="stylesheet" href="CSS/stylehome.css">
    <style>
        table {
            border-collapse: collapse;
            width: 95%;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            cursor: pointer;
        }
        input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            padding: 5px;
        }
    </style>
</head>
<script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function filtraTabella() {
    const input = document.querySelectorAll("thead input");
    const righe = document.querySelectorAll("#tabellaRichieste tbody tr");

    righe.forEach(riga => {
        let mostra = true;
        input.forEach((campo, indice) => {
            const cella = riga.cells[indice].textContent.toLowerCase();
            const filtro = campo.value.toLowerCase();
            if (!cella.includes(filtro)) mostra = false;
        });
        riga.style.display = mostra ? "" : "none";
    });
}

function ordina(colIndex) {
    const table = document.getElementById("tabellaRichieste");
    const rows = Array.from(table.tBodies[0].rows);
    const asc = table.getAttribute("data-sort-dir") !== "asc";
    rows.sort((a, b) => {
        const aText = a.cells[colIndex].textContent.trim().toLowerCase();
        const bText = b.cells[colIndex].textContent.trim().toLowerCase();
        return asc ? aText.localeCompare(bText) : bText.localeCompare(aText);
    });
    rows.forEach(row => table.tBodies[0].appendChild(row));
    table.setAttribute("data-sort-dir", asc ? "asc" : "desc");
}
</script>
<body>
    <a href="home.php"><img src="img/logo2.png" width="200" height="53"></a>
    <center>
        <h1>Amministratore: <?php echo htmlspecialchars($nome) . " " . htmlspecialchars($cognome); ?></h1>
        <h2>Lista Richieste </h2> 
        <!-- Bottone per aprire il popup -->
<button onclick="document.getElementById('exportModal').style.display='block'">Esporta dati</button>

<!-- Modale per esportazione -->
<div id="exportModal" style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fff; margin:10% auto; padding:20px; border:1px solid #888; width:300px; border-radius:10px;">
        <span onclick="document.getElementById('exportModal').style.display='none'" style="float:right; cursor:pointer; font-size:20px;">&times;</span>
        <h3>Esporta richieste per data</h3>
        <form method="post" action="export.php">
            <label for="from_date">Da:</label><br>
            <input type="date" name="from_date" required><br><br>
            <label for="to_date">A:</label><br>
            <input type="date" name="to_date" required><br><br>
            <button type="submit">Esporta</button>
        </form>
    </div>
</div>
<br>
<br>

        <table id="tabellaRichieste" data-sort-dir="asc" border="1">
            <thead>
                <tr>
                    <th onclick="ordina(0)">ID Richiesta</th>
                    <th onclick="ordina(1)">Nome</th>
                    <th onclick="ordina(2)">Data Invio</th>
                    <th onclick="ordina(3)">Descrizione</th>
                    <th onclick="ordina(4)">Tipo Urgenza</th>
                    <th onclick="ordina(5)">Stato</th>
                    <th onclick="ordina(6)">Data Chiusura</th>
                </tr>
                <tr>
                    <th><input type="text" onkeyup="filtraTabella()"></th>
                    <th><input type="text" onkeyup="filtraTabella()"></th>
                    <th><input type="text" onkeyup="filtraTabella()"></th>
                    <th><input type="text" onkeyup="filtraTabella()"></th>
                    <th><input type="text" onkeyup="filtraTabella()"></th>
                    <th><input type="text" onkeyup="filtraTabella()"></th>
                    <th><input type="text" onkeyup="filtraTabella()"></th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM RICHIESTA ORDER BY DATA_INVIA DESC";
            $result = $connessione->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Recupera il nome del tipo di urgenza
                    $urgenza_id = $row['URGENZA_ID'];
                    $urgenza_query = "SELECT TIPOURGENZA FROM URGENZA WHERE ID = '$urgenza_id'";
                    $urgenza_result = $connessione->query($urgenza_query);
                    $urgenza_row = $urgenza_result->fetch_assoc();

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NOME']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['DATA_INVIA']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['DESCRIZIONE']) . "</td>";
                    echo "<td>" . htmlspecialchars($urgenza_row['TIPOURGENZA']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['STATO']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['DATA_CHIUSURA']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nessuna richiesta trovata.</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <br>
        <a href="logout.php">Logout</a>
    </center>
    <button onclick="scrollToTop()" class="scroll-to-top">↑ Torna su</button>
</body>
</html>
