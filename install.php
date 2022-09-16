<?php
include("Donnees.inc.php");

$db = 'Kokteyl';

// création de la requête sql
// on teste avant si elle existe ou non (par sécurité)
$sql = "CREATE DATABASE IF NOT EXISTS $db;
        ALTER DATABASE $db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
        USE $db;
        CREATE TABLE IF NOT EXISTS Utilisateur (
          nom VARCHAR(100) DEFAULT NULL,
          prenom VARCHAR(100) DEFAULT NULL,
          login VARCHAR(100) NOT NULL,
          mdp CHAR(40) NOT NULL,
          sexe VARCHAR(1) DEFAULT NULL,
          adresse VARCHAR(100) DEFAULT NULL,
          postal INT(5) DEFAULT NULL,
          ville VARCHAR(100) DEFAULT NULL,
          noTelephone CHAR(10) DEFAULT NULL,
          PRIMARY KEY (login)
        );

        CREATE TABLE IF NOT EXISTS Recettes (
          nom VARCHAR(100),
          ingredients VARCHAR(1000),
          preparation VARCHAR(1000),
          PRIMARY KEY (nom)
        );

        CREATE TABLE IF NOT EXISTS Ingredients (
          nomIngredient VARCHAR(100),
          PRIMARY KEY (nomIngredient)
        );

        CREATE TABLE IF NOT EXISTS Liaison (
          nomIngredient VARCHAR(100),
          nomRecette VARCHAR(100),
          PRIMARY KEY (nomIngredient, nomRecette),
          CONSTRAINT FK_LiaisonIngredient FOREIGN KEY (nomIngredient) REFERENCES Ingredients(nomIngredient),
          CONSTRAINT FK_LiaisonRecette FOREIGN KEY (nomRecette) REFERENCES Recettes(nom)
        );

        CREATE TABLE IF NOT EXISTS SuperCategorie (
          nom VARCHAR(100),
          nomSuper VARCHAR(100),
          PRIMARY KEY (nom, nomSuper),
          CONSTRAINT FK_SuperCategorieNomCategorie FOREIGN KEY (nom) REFERENCES Ingredients(nomIngredient),
          CONSTRAINT FK_SuperCategorieNomSuperCategorie FOREIGN KEY (nomSuper) REFERENCES Ingredients(nomIngredient)
        );

        CREATE TABLE IF NOT EXISTS Panier (
          utilisateur VARCHAR(100),
          nomRecette VARCHAR(100),
          PRIMARY KEY (utilisateur, nomRecette),
          CONSTRAINT FK_PanierUtilisateur FOREIGN KEY (utilisateur) REFERENCES Utilisateur(login) ON UPDATE CASCADE ON DELETE CASCADE,
          CONSTRAINT FK_PanierRecette FOREIGN KEY (nomRecette) REFERENCES Recettes(nom)
        )";

try {
    $bdd = new PDO('mysql:host=localhost;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

//On parcours les requêtes écrites précédemment et on les exécute dans la bdd
$tab = explode(';', $sql);
for ($i = 0; $i < sizeof($tab); $i++) {
    $bdd->exec($tab[$i]);
}

/*  On remplit la base de données avec les ressources de l'énoncé.
    Le remplissage des données se fait avec la requête 'INSERT IGNORE INTO' pour éviter que les tables
    se remplissent en plusieurs fois si elles sont déjà remplies.
    Le mot clé 'IGNORE' permet d'ignorer l'erreur renvoyée par la commande 'INSERT INTO' lorsque
    les valeurs à rentrer existent déjà, les valeurs sont donc juste ignorées si elles sont déjà dans la table.*/

//Remplissage de la table Recettes
$stmt = $bdd->prepare("INSERT IGNORE INTO Recettes (nom, ingredients, preparation) VALUES (:nom, :ingredients, :preparation)");
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':ingredients', $ingredients);
$stmt->bindParam(':preparation', $preparation);

//Si la liste 'Recettes' contient bien des valeurs on la parcourt et on exécute la requête préparée auparavant sur ces valeurs.
if (!empty($Recettes)) {
    foreach ($Recettes as $titre) {
        $nom = array_values($titre)[0];
        $ingredients = array_values($titre)[1];
        $preparation = array_values($titre)[2];
        $stmt->execute();
    }
}

//Remplissage de la table Ingredients
$stmt = $bdd->prepare("INSERT IGNORE INTO Ingredients (nomIngredient) VALUES (:nom)");
$stmt->bindParam(':nom', $nom);

//Si la liste 'Hierarchie' contient bien des valeurs on la parcourt et on exécute la requête préparée auparavant sur ces valeurs.
if (!empty($Hierarchie)) {
    foreach ($Hierarchie as $key => $aliment) {
        $nom = $key;
        $stmt->execute();
    }
}

//Remplissage de la table Liaison
$stmt = $bdd->prepare("INSERT IGNORE INTO Liaison (nomIngredient, nomRecette) VALUES (:nomIng, :nomRec)");
$stmt->bindParam(':nomIng', $nomIng);
$stmt->bindParam(':nomRec', $nomRec);

//Si la liste 'Recettes' contient bien des valeurs on la parcourt et on exécute la requête préparée auparavant sur ces valeurs.
if (!empty($Recettes)) {
    foreach ($Recettes as $titre) {
        $nomRec = array_values($titre)[0];
        foreach ($titre as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $ing) {
                    $nomIng = $ing;
                    $stmt->execute();
                }
            }
        }
    }
}

//Remplissage de la table SuperCategorie
$stmt = $bdd->prepare("INSERT IGNORE INTO SuperCategorie (nom, nomSuper) VALUES (:nom, :nomSuper)");
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':nomSuper', $nomSuper);

//Si la liste 'Hierarchie' contient bien des valeurs on la parcourt et on exécute la requête préparée auparavant sur ces valeurs.
if (!empty($Hierarchie)) {
    foreach ($Hierarchie as $aliment => $tab) {
        if (array_key_exists('super-categorie', $tab)) {
            foreach ($tab['super-categorie'] as $super) {
                $nom = $aliment;
                $nomSuper = $super;
                $stmt->execute();
            }
        }
    }
}
?>

Votre base de données 'Kokteyl' à bien été créée et remplie !