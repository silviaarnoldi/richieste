<?php
session_start();

// Sicurezza: solo utenti autenticati (opzionale)
if (!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'TITOLARE') {
    header("Location: login.php");
    exit();
}

include "connessione.php";

$tablename = "RICHIESTA"; // Corretto nome tabella

// Imposta intestazioni per esportazione Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=richieste_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Crea la tabella HTML da esportare
echo '<table border="1">';

// Intestazioni colonna
$result = $connessione->query("SHOW COLUMNS FROM $tablename");
if ($result) {
    echo "<tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<th>" . htmlspecialchars($row['Field']) . "</th>";
    }
    echo "</tr>";
}

// Dati riga
$result = $connessione->query("SELECT * FROM $tablename");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
}

echo "</table>";

// Chiude connessione e termina
$connessione->close();
exit();
?>