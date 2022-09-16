<?php
include_once("../OuvertureBDD/ouvertureBDD.php");
session_start();

//On ajoute les cocktails déjà sélectionnés hors connexion
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $cocktailCookie => $elem) {
        $panier = $bdd->prepare("INSERT IGNORE INTO Panier (utilisateur, nomRecette) VALUES (:utilisateur, :recette)");
        $panier->bindParam(":utilisateur", $_SESSION['login']);
        $panier->bindParam(":recette", $cocktailCookie);
        $panier->execute();
    }
}
