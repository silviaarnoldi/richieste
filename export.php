<?php
session_start();

if (!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'TITOLARE') {
    header("Location: login.php");
    exit();
}

include "connessione.php";

if (!isset($_POST['from_date'], $_POST['to_date'])) {
    die("Intervallo date non valido.");
}

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

$tablename = "RICHIESTA";

// Intestazioni per esportazione Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=richieste_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Crea la tabella HTML esportabile
echo '<table border="1">';

// Intestazioni
$result = $connessione->query("SHOW COLUMNS FROM $tablename");
if ($result) {
    echo "<tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<th>" . htmlspecialchars($row['Field']) . "</th>";
    }
    echo "</tr>";
}

// Dati filtrati
$query = $connessione->prepare("SELECT ID,NOME,DESCRIZIONE,DATA_INVIA,URGENZA_ID,STATO,DATA_CHIUSURA FROM $tablename WHERE DATA_INVIA BETWEEN ? AND ?");
$query->bind_param("ss", $from_date, $to_date);
$query->execute();
$result = $query->get_result();

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
$connessione->close();
exit();
?>