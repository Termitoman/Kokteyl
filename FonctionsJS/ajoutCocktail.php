<?php
/*Ajout du cocktail envoyé dans les paramètres de la page dans la panier de l'utilisateur connecté, action effectuée sur la base de données*/
include("../OuvertureBDD/ouvertureBDD.php");

$tabRequete = explode("|", $_GET['p']);
$sql = "INSERT INTO Panier(utilisateur, nomRecette) VALUES (:utilisateur, :recette)";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(":utilisateur", $tabRequete[0]);
$stmt->bindParam(":recette", $tabRequete[1]);
$stmt->execute();
?>