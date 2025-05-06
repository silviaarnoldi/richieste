<?php
include 'connessione.php';

if (isset($_POST['id_richiesta'])) {
    $id = intval($_POST['id_richiesta']);

    $sql = "SELECT SCREENSHOT_PATH FROM RICHIESTA WHERE ID = ?";
    $stmt = $connessione->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($path);

    if ($stmt->fetch() && $path != null) {
        echo "<img src='$path' alt='Screenshot' style='max-width:100%;'>";
    } else {
        echo "Nessuno screenshot disponibile per questa richiesta.";
    }

    $stmt->close();
} else {
    echo "ID non ricevuto.";
}

$connessione->close();
?>