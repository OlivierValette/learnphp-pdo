<?php

require_once "model/database.php";

// Paramètres récupérés dans l'url :
$id = (isset($_GET["id"]) ? $_GET["id"] : 1);         // Id sélectionné

// Extraction des données de l'utilisateur

$lignes = getAllCommandLinesByCommande($id);

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Détail commande</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Détail de la commande</h1>

<h2>
    <?php if(count($lignes) == 1) : ?>
        1 produit :
    <?php else : ?>
        <?= count($lignes); ?> produits :
    <?php endif; ?>
</h2>
<table>
    <thead>
    <tr>
        <th>Produit</th>
        <th>Qté</th>
        <th>Prix</th>
        <th>Montant</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($lignes as $ligne) : ?>
        <tr>
            <td><?= $ligne["produit_titre"]; ?></td>
            <td><?= $ligne["qte"]; ?></td>
            <td><?= $ligne["prix"]; ?></td>
            <td><?= $ligne["qte"]*$ligne["prix"]; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
