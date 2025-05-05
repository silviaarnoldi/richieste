<?php
// interventoController.php
include "connessione.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_richiesta"]) && isset($_POST["nuovo_stato"])) {
    $id_richiesta = intval($_POST["id_richiesta"]);
    $nuovo_stato = $_POST["nuovo_stato"];

    if ($nuovo_stato === 'Risolta') {
        $data_chiusura = date("Y-m-d H:i:s");
        $query = "UPDATE RICHIESTA SET STATO = ?, DATA_CHIUSURA = ? WHERE ID = ?";
        $stmt = $connessione->prepare($query);
        $stmt->bind_param("ssi", $nuovo_stato, $data_chiusura, $id_richiesta);
    } else {
        $query = "UPDATE RICHIESTA SET STATO = ? WHERE ID = ?";
        $stmt = $connessione->prepare($query);
        $stmt->bind_param("si", $nuovo_stato, $id_richiesta);
    }

    if ($stmt->execute()) {
        header("Location: homeIT.php");
        exit();
    } else {
        echo "Errore nell'aggiornamento della richiesta.";
    }
}
?>