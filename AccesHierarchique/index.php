<?php
//On ouvre la bdd.
include("../OuvertureBDD/ouvertureBDD.php");
//On crée une session ou récupère celle en cours (gestion des cookies de session).
session_start();
//On fait apparaître la structure du haut de la page
include_once("../StructurePage/entete.php");
include_once("../StructurePage/menu.php");
include_once("../FonctionsJS/fonctionsPanier.php");
include_once("../StructurePage/formatageString.php");
?>

<div class="w3-main w3-content w3-padding w3-center w3-animate-opacity" style="max-width:1200px;margin-top:100px">
    <div>
        <div>
            <h2>Notre proposition de cocktails</h2>
            <span>Cliquez sur un filtre pour afficher les boissons utilisant cet ingrédient, cliquez à nouveau desssus pour l'enlever.</span>
        </div>

        <h2>Voici nos filtres</h2><br>
        <div>
            <?php
            //On affiche toutes les catégories possibles de sélectionner

            //Si c'est le premier chargement la catégorie courante est Aliment
            if (!isset($_SESSION['categorieCourante'])) {
                $_SESSION['categorieCourante'] = 'Aliment';
                $_SESSION['supCategorie']['Aliment'] = 1;
                $superCat = $bdd->query("SELECT DISTINCT nom FROM SuperCategorie WHERE nomSuper = 'Aliment'");
                while ($donnees = $superCat->fetch()) {
                    $nomCat = $donnees['nom'];
                    $nomCat = str_replace("'", "\'", $nomCat);
                    echo "<button onclick=\"sousFiltre('" . $nomCat . "')\">" . $donnees['nom'] . "</button>";
                }
            ?>
        </div><br>
    <?php
            }
            //Sinon on prend la catégorie courante enregistrée
            else {
                if (isset($_SESSION['supCategorie'])) {
                    foreach ($_SESSION['supCategorie'] as $cat => $val) {
                        if ($cat != 'Aliment' && $val == 1) {
                            $cat = str_replace("'", "\'", $cat);
                            $nomAffichage = str_replace("\'", "'", $cat);
                            echo "<button onclick=\"retourArriereFiltre('$cat')\">$nomAffichage</button><br><br>";
                        }
                    }
                }
                $superCat = $bdd->prepare("SELECT DISTINCT nom FROM SuperCategorie WHERE nomSuper = :super");
                $superCat->bindParam(":super", $_SESSION['categorieCourante']);
                $superCat->execute();
                while ($donnees = $superCat->fetch()) {
                    $nomCat = $donnees['nom'];
                    $nomCat = str_replace("'", "\'", $nomCat);
                    echo "<button onclick=\"sousFiltre('" . $nomCat . "')\">" . $donnees['nom'] . "</button>";
                }
    ?>
    </div>
    <br>
    <?php
            }

            // On détermine sur quelle page on se trouve
            if (isset($_GET['page']) && !empty($_GET['page'])) {
                $currentPage = (int)strip_tags($_GET['page']);
            } else {
                $currentPage = 1;
            }

            //On donne le chemin des images nécessaire à l'affichage des images des cocktails
            $cheminImage = "../Photos/";
            $nomCat = str_replace("'", "\'", $_SESSION['categorieCourante']);
            $queryNbCocktails = $bdd->query("SELECT COUNT(DISTINCT nom) AS nbCocktails FROM Recettes JOIN Liaison ON Liaison.nomRecette = Recettes.nom WHERE nomIngredient = '" . $nomCat . "' OR  nomIngredient IN (" . sousCategorie($_SESSION['categorieCourante']) . ")");
            if ($resultQuery = $queryNbCocktails->fetch()) {
                //On récupère le nombre de cocktails et fait nos calculs pour les afficher correctement avec la pagination

                //On récupère le nombre de cocktails
                $nbCocktails = (int)$resultQuery['nbCocktails'];

                // On détermine le nombre de cocktails par page
                $parPage = 8;

                // On calcule le nombre de pages total
                $nbPages = ceil($nbCocktails / $parPage);

                // Calcul du 1er cocktail de la page
                $premier = ($currentPage * $parPage) - $parPage;

                //On récupère les cocktails à afficher pour cette page
                $queryCocktails = $bdd->query("SELECT DISTINCT nom FROM Recettes JOIN Liaison ON Liaison.nomRecette = Recettes.nom WHERE nomIngredient = '" . $nomCat . "' OR  nomIngredient IN (" . sousCategorie($_SESSION['categorieCourante']) . ")");
                if ($resultQuery2 = $queryCocktails->fetchAll()) {
                    //On récupère tous les cocktails à afficher sous un format utilisable en sql
                    $listeStrCocktails = "";
                    for ($i = 0; $i < sizeof($resultQuery2); $i++) {
                        $temp = str_replace("'", "\'", $resultQuery2[$i]['nom']);
                        $listeStrCocktails .= "'" . $temp . "',";
                    }
                    $listeStrCocktails = rtrim($listeStrCocktails, ","); //On enlève la dernière virgule
                    $queryCocktails2 = $bdd->prepare("SELECT * FROM Recettes WHERE nom IN (" . $listeStrCocktails . ") ORDER BY nom LIMIT :premier, :parpage;");
                    $queryCocktails2->bindValue(':premier', $premier, PDO::PARAM_INT);
                    $queryCocktails2->bindValue(':parpage', $parPage, PDO::PARAM_INT);

                    // On exécute la requète
                    $queryCocktails2->execute();
                    $cocktails = $queryCocktails2->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <!-- !PAGE CONTENT! -->
        <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">

            <!-- First Photo Grid-->
            <div class="w3-row-padding w3-padding-16 w3-center">

                <?php
                    for ($i = 0; $i < $parPage / 2; ++$i) {
                        //On affiche les cocktails 1 par 1
                        include("affichageCocktailsHierarchique.php");
                    }
                ?>
            </div>

            <!-- Second Photo Grid-->
            <div class="w3-row-padding w3-padding-16 w3-center">
                <?php

                    for ($i = $parPage / 2; $i < $parPage; ++$i) {
                        //On affiche les cocktails 1 par 1
                        include("affichageCocktailsHierarchique.php");
                    }
                ?>
            </div>

            <!-- Pagination -->
    <?php
                    //On affiche et mets en place la pagination
                    include_once("../StructurePage/pagination.php");
                }
            } ?>
        </div>
</div>

<?php
//On fait apparaître la structure du bas de la page
include_once("../StructurePage/piedDePage.php");
?>