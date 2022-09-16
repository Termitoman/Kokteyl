<?php
/*Ajout du cocktail envoyé dans les paramètres de la page dans la panier de l'utilisateur connecté action effectuée sur les cookies*/
session_start();
$recette = $_GET['p'];
$_SESSION['panier'][$recette] = true;