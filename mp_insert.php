<?php

require_once "model/database.php";

// Récupère les données du formulaire
$label = $_POST['label'];

// Envoyer les données en BDD
$id = insertMoyenPaiement($label);

// Rediriger vers la liste
// On utilise header qui permet de modifier les données 'Header' visibles en inspection sur Network
header("Location: mp.php");
