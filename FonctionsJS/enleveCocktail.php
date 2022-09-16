<?php
/*Suppression du cocktail envoyé dans les paramètres de la page dans la panier de l'utilisateur connecté, action effectuée sur la base de données*/
include("../OuvertureBDD/ouvertureBDD.php");

$tabRequete = explode("|", $_GET['p']);
$sql = "DELETE FROM Panier WHERE utilisateur = :utilisateur AND nomRecette = :recette";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(":utilisateur", $tabRequete[0]);
$stmt->bindParam(":recette", $tabRequete[1]);
$stmt->execute();
?>