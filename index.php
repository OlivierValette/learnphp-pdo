<?php

require_once "model/database.php";


/*  Paramètres de pagination
    ------------------------
*/

// Paramètres fixes
$page_ranges = [10, 25, 50, 100];

// Paramètres récupérés dans l'url :
$current_page = (isset($_GET["page"]) ? $_GET["page"] : 1);         // Page en cours
$page_rows = (isset($_GET["rows"]) ? $_GET["rows"] : 10);           // Nombre de lignes par page



/*  Appel des requêtes sur la base de données
    -----------------------------------------
*/

// Extraction des utilisateurs pour la page donnée
$utilisateurs = getAllUtilisateurs($current_page, $page_rows);

// Nombre de pages du tableau pour le nombre de lignes par page donné
$maxpage = ceil(getCountEntities('utilisateur') / $page_rows);

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<table>
    <thead>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Date de naissance</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach($utilisateurs as $utilisateur) : ?>
        <tr>
            <td><?= $utilisateur["nom"]; ?></td>
            <td><?= $utilisateur["prenom"]; ?></td>
            <td><?= $utilisateur["email"]; ?></td>
            <td><?= $utilisateur["date_naissance_format"]; ?></td>
            <td>
                <a href="detail.php?id=<?= $utilisateur["id"]; ?>">Voir plus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>
    <!-- Gestion de la pagination -->
    <form action="index.php" method="get">
    <label for="page_selector">Pagination : </label>
        <select name="rows" id="page_selector">
            <?php foreach($page_ranges as $page_range) : ?>
            <option value="<?= $page_range; ?>"
                <?php if ($page_rows == $page_range) : ?>
                    selected
                <?php endif; ?>
            ><?= $page_range; ?></option>
            <?php endforeach; ?>
        </select>
    <input type="submit">
    </form>
    <!-- Affichage de la barre de navigation -->
    <a href="index.php?page=1&rows=<?= $page_rows; ?>">Début</a>
    &nbsp;
    <?php if($current_page > 1) : ?>
        <a href="index.php?page=<?= $current_page - 1; ?>&rows=<?= $page_rows; ?>">Précédent</a>
    <?php endif; ?>
    &nbsp;
    Page : <?= $current_page; ?> / <?= $maxpage; ?>
    &nbsp;
    <?php if($current_page < $maxpage) : ?>
    <a href="index.php?page=<?= $current_page + 1; ?>&rows=<?= $page_rows; ?>">Suivant</a>
    <?php endif; ?>
    &nbsp;
    <a href="index.php?page=<?= $maxpage; ?>&rows=<?= $page_rows; ?>">Fin</a>
</p>

<footer>
    <a href="mp.php">Moyens de paiement</a>
</footer>

</body>
</html>
