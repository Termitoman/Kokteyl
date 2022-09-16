<?php
//Fichier qui recherche les cocktails dans la base de données recherchés par l'utilisateur
include("../OuvertureBDD/ouvertureBDD.php");

$sql = "SELECT DISTINCT nomRecette FROM Liaison WHERE ";

$listeIng = $_GET['ing'];
$sql .= $listeIng;

$recette = $bdd->prepare($sql);
$recette->execute();

while ($donnees = $recette->fetch()) {
    echo $donnees['nomRecette']."\n";
    echo "-_-";
}
$recette->closeCursor(); // Termine le traitement de la requête
