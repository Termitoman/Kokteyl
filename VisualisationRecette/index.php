<?php
//On ouvre la bdd.
include("../OuvertureBDD/ouvertureBDD.php");
//On crée une session ou récupère celle en cours (gestion des cookies de session).
session_start();
//On fait apparaître la structure du haut de la page
include_once("../StructurePage/entete.php");
include_once("../StructurePage/menu.php");
include_once("../StructurePage/formatageString.php");
include_once("../FonctionsJS/fonctionsPanier.php");

//On ne parcourt pas les élements vides de l'Array
if (isset($_GET['cocktail'])) {
    $nomCocktail = str_replace("-_-", "'", $_GET['cocktail']);
    $nomBis = str_replace("-_-", "'", $_GET['cocktail']);
    $nomImage = formatageString($nomCocktail);
    $nomFinal = "../Photos/" . $nomImage . ".jpg";
?>
    <div class="w3-main w3-content w3-padding w3-centerr" style="max-width:1200px;margin-top:100px">
        <div class="w3-hover-opacity w3-center">

            <?php
            //Si le cocktail à une image, on l'affiche sinon on affiche une image par défaut
            if (file_exists($nomFinal)) {
                echo "<img style=\"width:100%;max-width:400px\" src=\"" . $nomFinal . "\" alt=\"" . $nomCocktail . "\" class = \"images\" onclick=\"";
            } else {
                echo "<img style=\"width:100%;max-width:400px\" src=\"../Ressources/defaultboisson.png\" alt=\"Image à clicker pour ajouter l'article " . $nomCocktail . " au panier\" class = \"images\" onclick=\"";
            }

            //Si le cocktail contient le caractère spécial ' non échappé, on l'échappe
            //$nomCocktail = str_replace("'", "\'", $cocktails[$i]['nom']);

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
            ?>
            ">
        </div>
        <h3 class="w3-center"> <?= $nomBis ?></h3>
        <?php
        $infos = $bdd->prepare("SELECT ingredients, preparation FROM Recettes WHERE nom = :nom");
        $infos->bindParam(":nom", $nomBis);
        $infos->execute();
        $infoCocktail = $infos->fetch()
        ?>
        <p class="w3-center">Ingrédients : <?= str_replace("|", ", ", $infoCocktail['ingredients']) ?></p>
        <!--On remplace les | dans la description par des , pour rendre la description plus lisible -->
        <p class="w3-center">Préparation : <?= $infoCocktail['preparation'] ?></p>
        <div class="w3-center">
            <button class='w3-center glow-on-hover' onclick="history.go(-1)">Retour</button>
        </div>
    </div>
<?php
}

//On fait apparaître la structure du bas de la page
include_once("../StructurePage/piedDePage.php");
?>