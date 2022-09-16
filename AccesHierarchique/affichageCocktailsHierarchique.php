<?php

//Fichier créé dans le but d'alléger le code

//On ne parcourt pas les élements vides de l'Array
if (!empty($cocktails[$i])) {
    $nomImage = formatageString($cocktails[$i]['nom']);
    $nomFinal = $cheminImage . $nomImage . ".jpg";
?>
    <div class="w3-quarter">
        <div class="img w3-hover-opacity">
            <?php
            $nomCocktail = $cocktails[$i]['nom'];

            //Si le cocktail à une image, on l'affiche sinon on affiche une image par défaut
            if (file_exists($nomFinal)) {
                echo "<img src=\"" . $nomFinal . "\" alt=\"" . $nomCocktail . "\" class = \"images\" onclick=\"";
            } else {
                echo "<img src=\"../Ressources/defaultboisson.png\" alt=\"Image à clicker pour ajouter l'article " . $nomCocktail . " au panier\" class = \"images\" onclick=\"";
            }

            //On vérifie si la recette est dans le panier de l'utilisateur, si oui un click sur l'image l'enlève, si non, un click sur l'image l'ajoute.
            //Si l'utilisateur est connecté
            if (isset($_SESSION['login'])) {

                $estDansPanier = false;
                //On parcours le panier de l'utilisateur pour savoir si le cocktail est dedans
                $panier = $bdd->prepare("SELECT nomRecette FROM Panier WHERE utilisateur = :utilisateur");
                $panier->bindParam(":utilisateur", $_SESSION['login']);
                $panier->execute();
                while ($cocktail = $panier->fetch()) {
                    if ($cocktail['nomRecette'] == $nomCocktail)
                        $estDansPanier = true;
                }

                $nomCocktail = str_replace("'", "\'", $nomCocktail);
                //Si le cocktail est dans le panier on propose de le supprimer et inversement sinon
                if ($estDansPanier) {
                    echo "enleveCocktail('" . $_SESSION['login'] . "','" . $nomCocktail . "')";
                } else {
                    echo "ajoutCocktail('" . $_SESSION['login'] . "','" . $nomCocktail . "')";
                }
            } else { //Si l'utilisateur n'est pas connecté
                //Si l'utilisateur à déjà ajouté ou supprimé le cocktail au panier
                if (isset($_SESSION['panier'][$nomCocktail])) {
                    //Si il est actuellement ajouté au panier on le supprime lors d'un click sur l'image
                    if ($_SESSION['panier'][$nomCocktail]) {
                        $nomCocktail = str_replace("'", "\'", $nomCocktail);
                        echo "enleveCookie('" . $nomCocktail . "')";
                    } else { //Si il n'est pas dans le panier on l'ajoute lors d'un click sur l'image
                        $nomCocktail = str_replace("'", "\'", $nomCocktail);
                        echo "ajoutCookie('" . $nomCocktail . "')";
                    }
                } else { //Sinon, on ajoute le cocktail au panier lors d'un click sur l'image
                    $nomCocktail = str_replace("'", "\'", $nomCocktail);
                    echo "ajoutCookie('" . $nomCocktail . "')";
                }
            }
            $nomBis = str_replace("'", "-_-", $cocktails[$i]['nom']);
            ?>
            ">
        </div>
        <a href='../VisualisationRecette/index.php?cocktail=<?= $nomBis ?>'>
            <h3> <?= $cocktails[$i]['nom'] ?></h3>
        </a>
    </div>
<?php }
