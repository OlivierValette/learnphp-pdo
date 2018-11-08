<?php

// Récupération des paramètres
require_once __DIR__ . "/../config/parameters.php";

// Connexion à la BDD
try {
    $connexion = new PDO(
        'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8', lc_time_names = 'fr_FR'"
        ]
    );
} catch (PDOException $exception) {
    echo 'Erreur de connexion à la base de données';
}


// Fonctions assurant les requêtes SQL


/** Count * sur une table donnée en paramètre
 * @param string $table Nom de la table
 * @return int
 */
function getCountEntities(string $table) : int {
    global $connexion;

    $query = "SELECT COUNT(*) AS nb_rows FROM $table ";

    $stmt = $connexion->prepare($query);            // Prépare la requête
    $stmt->execute();                               // Exécute la requête
    return intval($stmt->fetch()["nb_rows"]);       // fetch retourne la première ligne uniquement (tableau simple)
}

/** Récupère les utilisateurs avec pagination
 * @param int $page Numéro de page
 * @param int $page_rows Nombre de lignes à récupérer sur la page
 * @return array (size $page_rows) of array (size=20)
          'id' => string '1' (length=1)
          0 => string '1' (length=1)
          'nom' => string 'Brandon' (length=7)
          1 => string 'Brandon' (length=7)
          'prenom' => string 'Morgan' (length=6)
          2 => string 'Morgan' (length=6)
          'login' => string 'bmorgan0' (length=8)
          3 => string 'bmorgan0' (length=8)
          'mot_de_passe' => string 'bmorgan0' (length=8)
          4 => string 'bmorgan0' (length=8)
          'email' => string 'bmorgan0@pen.io' (length=15)
          5 => string 'bmorgan0@pen.io' (length=15)
          'date_naissance' => null
          6 => null
          'adresse' => string '4 South Center' (length=14)
          7 => string '4 South Center' (length=14)
          'ville_id' => null
          8 => null
          'civilite_id' => string '2' (length=1)
          9 => string '2' (length=1)
 */
function getAllUtilisateurs(int $page, int $page_rows = 10) {
    global $connexion;

    $offset = ($page - 1) * $page_rows;

    $query = "
      SELECT *, DATE_FORMAT(utilisateur.date_naissance, '%e %M %Y') AS date_naissance_format
      FROM utilisateur
      ORDER BY utilisateur.nom, utilisateur.prenom
      LIMIT $page_rows
      OFFSET $offset
    ";

    $stmt = $connexion->prepare($query);    // Prépare la requête
    $stmt->execute();                       // Exécute la requête
    return $stmt->fetchAll();               // Retourne toutes les lignes de la requête (tableau de tableaux associatifs)

}

/** Récupère un utilisateur à partir de l'id en paramètre
 * @param int $id id de l'utilisateur
 * @return array
 */
function getUtilisateur(int $id) : array {
    global $connexion;

    $query = "
      SELECT utilisateur.*,
        v.cp AS ville_cp, 
        v.nom AS ville_nom, 
        DATE_FORMAT(utilisateur.date_naissance, '%e %M %Y') AS date_naissance_format
      FROM utilisateur
      LEFT JOIN ville v on utilisateur.ville_id = v.id
      WHERE utilisateur.id = :id
    ";

    $stmt = $connexion->prepare($query);
    // Protection SQL injection avec passage de paramètres via "bindParam"
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch();
}


/** Récupère les commandes d'un utilisateur donné
 * @param int $id id de l'utilisateur
 * @return array
 */
function getAllCommandesByUtilisateur(int $id) : array {
    global $connexion;

    $query = "
      SELECT
        commande.id,
        commande.reference,
        commande.date_creation AS date_cde,
        mp.label AS mp_lib,
        DATE_FORMAT(commande.date_creation, '%d/%m/%Y') AS date_cde_format,
		SUM(prix*qte) AS montant
      FROM commande
      INNER JOIN produit_has_commande ON produit_has_commande.commande_id = commande.id
      INNER JOIN moyen_paiement mp on commande.moyen_paiement_id = mp.id
      WHERE commande.utilisateur_id = :id
      GROUP BY commande.id;
    ";

    $stmt = $connexion->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetchAll();
}

/** Récupère les lignes de commande d'une commande donnée
 * @param int $id id de la commande
 * @return array
 */
function getAllCommandLinesByCommande(int $id) : array {
    global $connexion;

    $query = "
      SELECT
        produit_has_commande.produit_id,
        p.titre as produit_titre,
        produit_has_commande.qte,
        produit_has_commande.prix
      FROM produit_has_commande 
      INNER JOIN produit p on produit_has_commande.produit_id = p.id
      WHERE produit_has_commande.commande_id = :id
    ";

    $stmt = $connexion->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetchAll();
}

/** Récupère les lignes d'une table donnée en paramètre
 * @param string $table Nom de la table
 * @return array
 */
function getAllEntities(string $table) : array {
    global $connexion;

    $query = "SELECT * FROM $table";

    $stmt = $connexion->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}


// Insert dans la table moyen_paiement

function insertMoyenPaiement(string $label) : int {
    global $connexion;

    $query = "INSERT INTO moyen_paiement (label) VALUES (:label)";

    $stmt = $connexion->prepare($query);
    $stmt->bindParam(":label", $label);
    $stmt->execute();
    return $connexion->lastInsertId();
}