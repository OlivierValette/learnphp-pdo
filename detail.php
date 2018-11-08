<?php

require_once "model/database.php";

// Paramètres récupérés dans l'url :
$id = (isset($_GET["id"]) ? $_GET["id"] : 1);         // Id de la commande

// Extraction des données de l'utilisateur
$utilisateur = getUtilisateur($id);
$commandes = getAllCommandesByUtilisateur($id);

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Détail utilisateur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1><?= $utilisateur["nom"]; ?> <?= $utilisateur["prenom"]; ?></h1>
    <p> <?= $utilisateur["adresse"]; ?><br>
        <?= $utilisateur["ville_cp"]; ?> <?= $utilisateur["ville_nom"]; ?> </p>
    <p><a href="mailto:<?= $utilisateur["email"]; ?>"><?= $utilisateur["email"]; ?></a></p>

    <h2>
        <?php if(count($commandes) == 0) : ?>
            Pas de commande.
        <?php elseif(count($commandes) == 1) : ?>
            1 commande :
        <?php else : ?>
            <?= count($commandes); ?> commandes :
        <?php endif; ?>
    </h2>
    <?php if(count($commandes) > 0) : ?>
        <table>
            <thead>
            <tr>
                <th>Référence</th>
                <th>Date de commande</th>
                <th>Moyen de paiement</th>
                <th>Montant</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($commandes as $commande) : ?>
                <tr>
                    <td><?= $commande["reference"]; ?></td>
                    <td><?= $commande["date_cde_format"]; ?></td>
                    <td><?= $commande["mp_lib"]; ?></td>
                    <td><?= $commande["montant"]; ?></td>
                    <td>
                        <a href="detailcde.php?id=<?= $commande["id"]; ?>">Détail</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
