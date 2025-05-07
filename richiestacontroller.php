<?php
include "connessione.php";
session_start();

$target_dir = "uploads/";

// Crea la cartella se non esiste
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($connessione, $_POST['nome']);
    $descrizione = mysqli_real_escape_string($connessione, $_POST['descrizione']);
    $urgenza_id = intval($_POST['urgenza_id']);
    
    // Aggiungi la data e ora corrente nel formato DATETIME
    $data_invia_completa = date('Y-m-d H:i:s');  // data corrente con ora inclusa

    $screenshot_path = null;

    // Se è stato caricato uno screenshot
    if (isset($_FILES["screenshot"]) && $_FILES["screenshot"]["error"] === 0) {
        $filename = time() . "_" . basename($_FILES["screenshot"]["name"]);
        $target_file = $target_dir . $filename;
        $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Accetta solo immagini
        if (in_array($filetype, ['jpg', 'jpeg', 'png'])) {
            if (move_uploaded_file($_FILES["screenshot"]["tmp_name"], $target_file)) {
                $screenshot_path = mysqli_real_escape_string($connessione, $target_file);
            } else {
                echo "Errore nel salvataggio del file.";
                exit;
            }
        } else {
            echo "Tipo di file non valido. Solo JPG, JPEG e PNG sono permessi.";
            exit;
        }
    }

    // Costruzione della query
    $query = "INSERT INTO RICHIESTA (NOME, DESCRIZIONE, DATA_INVIA, URGENZA_ID, SCREENSHOT_PATH)
              VALUES (
                  '$nome',
                  '$descrizione',
                  '$data_invia_completa',
                  $urgenza_id,";

    if ($screenshot_path !== null) {
        $query .= "'$screenshot_path'";
    } else {
        $query .= "NULL";
    }

    $query .= ")";

    if (mysqli_query($connessione, $query)) {
        header("Location: home.php");
        exit();
    } else {
        echo "Errore nell'inserimento della richiesta: " . mysqli_error($connessione);
    }
} else {
    echo "Metodo non valido.";
}
?>
