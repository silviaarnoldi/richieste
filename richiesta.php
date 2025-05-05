<?php
include "connessione.php";
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invia Richiesta</title>
    <link rel="stylesheet" href="CSS/stylehome.css">
   <a href="home.php"><img src="img/logo2.png" width="200" height="53"> </a>
</head>
<body>
<center>
    <h1>INVIA RICHIESTA DI INTERVENTO</h1>
    <form action="richiestacontroller.php" method="POST" enctype="multipart/form-data">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" required><br>
<br>
    <label for="descrizione">Descrizione:</label>
    <textarea name="descrizione" required></textarea><br>
<br>
    <label for="urgenza_id">Tipo Urgenza:</label>
        <select name="urgenza_id" required>
            <?php
            $query = "SELECT * FROM URGENZA";
            $result = mysqli_query($connessione, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['ID'] . "'>" . $row['TIPOURGENZA'] . "</option>";
            }
            ?>
        </select><br><br>
<br>
    <label for="data_invia">Data invio:</label>
    <input type="date" name="data_invia" required><br>
<br>
    <!-- Pulsante per aggiungere screenshot -->
    <button type="button" onclick="mostraCampoScreenshot()">Aggiungi Screenshot (opzionale)</button><br><br>
<br>
    <!-- Campo per il file, nascosto inizialmente -->
    <div id="campoScreenshot" style="display:none;">
        <label for="screenshot">Carica Screenshot:</label>
        <input type="file" name="screenshot" accept=".jpg,.jpeg,.png">
    </div><br>

    <input type="submit" value="Invia richiesta">
</form>

<script>
function mostraCampoScreenshot() {
    document.getElementById("campoScreenshot").style.display = "block";
}
</script>
</center>
</body>
</html>
