<?php

require_once "model/database.php";

// Extraction des données de l'utilisateur
$mp = getAllEntities("moyen_paiement");

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Moyens de paiement</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Moyens de paiement</h1>

<?php if(count($mp) > 0) : ?>
    <table>
        <thead>
        <tr>
            <th>Code</th>
            <th>Moyen de paiement</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($mp as $item) : ?>
            <tr>
                <td><?= $item["id"]; ?></td>
                <td><?= $item["label"]; ?></td>
                <td>
                    <!-- Ne pas passer un ordre de suppression en get pour des raisons de sécurité -->
                    <form action="mp_delete.php" method="post">
                        <input type="hidden" name="id" value="<?= $item["id"]; ?>">
                        <input type="submit" value="Supprimer">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Ajout d'un moyen de paiement -->
<p>
    <form action="mp_insert.php" method="post">
        <label for="mp_add">Ajout d'un moyen de paiement : </label>
        <input type="text" id="mp_add" name="label">
        <input type="submit">
    </form>
</p>

</body>
</html>

