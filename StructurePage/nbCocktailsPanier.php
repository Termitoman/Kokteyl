<?php
function nbCocktailsPanier(): string
{
    //On veut ouvrir la bdd, si le fichier qui include ce fichier se trouve à la racine on include la bdd depuis la racine sinon on l'include depuis un package
    if (file_exists("../OuvertureBDD/ouvertureBDD.php")) {
        include("../OuvertureBDD/ouvertureBDD.php");
    } else if (file_exists("OuvertureBDD/ouvertureBDD.php")) {
        include("OuvertureBDD/ouvertureBDD.php");
    }

    $res = "";
    if (isset($_SESSION['login'])) { //Si on est connecté
        $panier = $bdd->prepare("SELECT nomRecette FROM Panier WHERE utilisateur = :utilisateur");
        $panier->bindParam(":utilisateur", $_SESSION['login']);
        $panier->execute();

        $cocktails = $panier->fetchAll();
        $nbCocktails = sizeof($cocktails);
        if ($nbCocktails <= 3 & $nbCocktails > 0) {
            $res = "" . $nbCocktails;
        } else if ($nbCocktails > 3) {
            $res = "+";
        }
    } else { //Si on est pas connecté
        if (isset($_SESSION['panier'])) { //Si il existe un cookie relatif au panier
            $nbCocktails = 0;
            //On parcourt les recettes enregistrées dans le panier dans les cookies
            foreach ($_SESSION['panier'] as $cocktail => $valeur) { //On parcourt les cookies de panier et on se retrouve avec chaque cocktail et sa valeur (true si dans le panier, false si supprimé).
                //Si le cocktail n'a pas été enlevé du panier par l'utilisateur on incrémente le nombre de cocktails dans le panier
                if ($valeur) {
                    $nbCocktails++;
                }
            }

            if ($nbCocktails <= 3 & $nbCocktails > 0) {
                $res = "" . $nbCocktails;
            } else if ($nbCocktails > 3) {
                $res = "+";
            }
        }
    }
    return $res;
}
