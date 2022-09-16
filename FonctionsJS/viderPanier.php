<?php
/*On vide le panier de l'utilisateur dans la base de données si il est connecté, sinon on vide les cookies*/
session_start();
include_once("../OuvertureBDD/ouvertureBDD.php");
if (!isset($_SESSION['login'])) { //Si on est pas connecté
    foreach ($_SESSION['panier'] as $cocktail => $valeur) { //On parcourt les cookies de panier et on se retrouve avec chaque cocktail et sa valeur (true si dans le panier, false si supprimé).
        //On vide la panier
        $_SESSION['panier'][$cocktail] = false;
    }
} else { //Si on est connecté
    $panier = $bdd->prepare("DELETE from Panier WHERE utilisateur = :utilisateur");
    $panier->bindParam(":utilisateur", $_SESSION['login']);
    $panier->execute();
}