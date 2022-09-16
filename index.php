<?php
//On ouvre la bdd.
include_once("OuvertureBDD/ouvertureBDD.php");
include_once("StructurePage/nbCocktailsPanier.php");
//On crée une session ou récupère celle en cours (gestion des cookies de session).
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Kokteyl</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="font.css">
    <link rel="icon" href="favicon.ico" />
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Karma", sans-serif
        }

        .w3-bar-block .w3-bar-item {
            padding: 20px
        }
    </style>
</head>

<body>

    <!-- Sidebar (hidden by default) -->
    <nav class="w3-sidebar w3-bar-block w3-card w3-top w3-xlarge w3-animate-left" style="display:none;z-index:2;width:40%;min-width:300px" id="mySidebar">
        <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button">Fermer</a>
        <a onclick="location.href='AccesHierarchique/'" class="w3-bar-item w3-button">Accéder aux cocktails</a>
        <a onclick="location.href='Recherche/'" class="w3-bar-item w3-button">Recherche avancée</a>
        <?php if (isset($_SESSION['login'])) { ?>
            <a onclick="location.href='Compte/'" class="w3-bar-item w3-button">Mon compte</a>
            <a onclick="location.href='Deconnexion/'" class="w3-bar-item w3-button">Déconnexion</a>
        <?php } else { ?>
            <a onclick="location.href='Inscription/'" class="w3-bar-item w3-button">Inscription</a>
            <a onclick="location.href='Connexion/'" class="w3-bar-item w3-button">Connexion</a>
        <?php } ?>
        <a href="#about" onclick="w3_close()" class="w3-bar-item w3-button">À propos de nous</a>
    </nav>

    <!-- Top menu -->
    <div class="w3-top">
        <div class="w3-white w3-xlarge" style="max-width:1200px;margin:auto">
            <div class="w3-button w3-padding-16 w3-left" onclick="w3_open()">☰</div>
            <div class="w3-right w3-padding-16 w3-shake"><a id="caddie" href="Panier/"><img src="Ressources/caddie<?= nbCocktailsPanier() ?>.png" /></a></div>
            <div class="w3-center w3-padding-16"><a id="logo" href="#"><img src="Ressources/logoKok.png" /></a></div>
        </div>
    </div>

    <!-- On prépare la pagination utile à l'affichage des cocktails. -->
    <?php

    include_once("StructurePage/formatageString.php");

    // On détermine sur quelle page on se trouve
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = (int)strip_tags($_GET['page']);
    } else {
        $currentPage = 1;
    }

    //On affiche seulement les cocktails avec des photos
    $cocktailsAvecPhotos = "'Black velvet','Bloody Mary','Bora bora','Builder','Caïpirinha','Coconut kiss','Cuba libre','Frosty lime','Le vandetta','Margarita','Mojito','Piña colada','Raifortissimo','Sangria sans alcool','Screwdriver','Shoot up','Tequila sunrise','Ti\'punch'";
    $query = "SELECT COUNT(*) AS nbCocktails FROM Recettes WHERE nom IN (" . $cocktailsAvecPhotos . ");"; //On créer la requête qui compte le nombre de cokctails.
    $query2 = $bdd->prepare($query); //On prépare son exécution dans la base de données.

    // On exécute la requête
    $query2->execute();

    // On récupère le nombre de cocktails
    $resultQuery = $query2->fetch();

    $nbCocktails = (int)$resultQuery['nbCocktails'];
    // On détermine le nombre de cocktails par page
    $parPage = 8;

    // On calcule le nombre de pages total
    $nbPages = ceil($nbCocktails / $parPage);

    // Calcul du 1er cocktail de la page
    $premier = ($currentPage * $parPage) - $parPage;

    //Requête qui récupère les 8 cocktails par page.
    $query = "SELECT * FROM Recettes WHERE nom IN (" . $cocktailsAvecPhotos . ") ORDER BY nom LIMIT :premier, :parpage;";

    // On prépare la requête
    $query2 = $bdd->prepare($query);

    $query2->bindValue(':premier', $premier, PDO::PARAM_INT);
    $query2->bindValue(':parpage', $parPage, PDO::PARAM_INT);

    // On exécute
    $query2->execute();

    // On récupère les données dans un tableau
    $cocktails = $query2->fetchAll(PDO::FETCH_ASSOC);

    $cheminImage = "Photos/";

    include_once("afficherCocktailsAcceuil.php");
    ?>

    <!-- !PAGE CONTENT! -->
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">

        <!-- First Photo Grid-->
        <div class="w3-row-padding w3-padding-16 w3-center" id="drink">

            <?php
            for ($i = 0; $i < $parPage / 2; ++$i) {
                //On éxecute la fonction permettant d'afficher les images des cocktails de la première ligne
                list($cocktails, $nomImage, $nomFinal) = afficherCocktails($cocktails, $i, $cheminImage);
            }
            ?>
        </div>

        <!-- Second Photo Grid-->
        <div class="w3-row-padding w3-padding-16 w3-center">
            <?php
            for ($i = $parPage / 2; $i < $parPage; ++$i) {
                //On éxecute la fonction permettant d'afficher les images des cocktails de la première ligne
                list($cocktails, $nomImage, $nomFinal) = afficherCocktails($cocktails, $i, $cheminImage);
            }
            ?>
        </div>

        <!-- Pagination -->
        <?php
        //On affiche et mets en place la pagination
        include_once("StructurePage/pagination.php");
        ?>

        <!-- Slideshow -->
        <div class="w3-container w3-padding-32 w3-center">
            <h3>La sélection du jour</h3><br>

            <div class="w3-content w3-section" style="max-width:500px">
                <a class="myTitles w3-animate-left" href="VisualisationRecette/index.php?cocktail=Builder" style="width:100%">
                    <h3>Bob the builder</h3>
                </a>
                <a class="myTitles w3-animate-left" href="VisualisationRecette/index.php?cocktail=Coconut kiss" style="width:100%">
                    <h3>"Le goût des tropiques dans un verre" <br>Gilbert Montagné</h3>
                </a>
                <a class="myTitles w3-animate-left" href="VisualisationRecette/index.php?cocktail=Margarita" style="width:100%">
                    <h3>Tequila ou Tequipala</h3>
                </a>
                <a class="myTitles w3-animate-left" href="VisualisationRecette/index.php?cocktail=Cuba libre" style="width:100%">
                    <h3>Rhum-Coca</h3>
                </a>
            </div>

            <div class="w3-content w3-section" style="max-width:500px">
                <img class="mySlides w3-animate-left" src="Photos/Builder.jpg" style="width:100%">
                <img class="mySlides w3-animate-left" src="Photos/Coconut_kiss.jpg" style="width:100%">
                <img class="mySlides w3-animate-left" src="Photos/Margarita.jpg" style="width:100%">
                <img class="mySlides w3-animate-left" src="Photos/Cuba_libre.jpg" style="width:100%">
            </div>

            <script>
                var myIndex = 0;
                carousel();

                function carousel() {
                    var i;
                    var x = document.getElementsByClassName("mySlides");
                    for (i = 0; i < x.length; i++) {
                        x[i].style.display = "none";
                    }
                    myIndex++;
                    if (myIndex > x.length) {
                        myIndex = 1
                    }
                    x[myIndex - 1].style.display = "block";
                    setTimeout(carousel, 4000);
                }

                var myIndex2 = 0;
                carousel2();

                function carousel2() {
                    var i;
                    var x = document.getElementsByClassName("myTitles");
                    for (i = 0; i < x.length; i++) {
                        x[i].style.display = "none";
                    }
                    myIndex2++;
                    if (myIndex2 > x.length) {
                        myIndex2 = 1
                    }
                    x[myIndex2 - 1].style.display = "block";
                    setTimeout(carousel2, 4000);
                }
            </script>
        </div>

        <!-- Footer -->
        <hr id="about">
        <footer class="w3-row-padding w3-padding-32">
            <div class="w3-third">
                <h3>DESCRIPTION</h3>
                <p>Kokteyl est un site web de gestions de recettes de cocktails.</p>
                <p>2021-2022</p>
                <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
            </div>

            <div class="w3-third">
                <h3>DÉVELOPPEMENT</h3>
                <ul class="w3-ul w3-hoverable">
                    <li class="w3-padding-16">
                        <img src="Ressources/Roselmo.png" class="w3-left w3-margin-right" style="width:50px">
                        <span class="w3-large">Hugo Iopeti</span><br>
                        <span>Développeur</span>
                    </li>
                    <li class="w3-padding-16">
                        <img src="Ressources/Nigelmo.png" class="w3-left w3-margin-right" style="width:50px">
                        <span class="w3-large">Ludovic Yvoz</span><br>
                        <span>Développeur</span>
                    </li>
                </ul>
            </div>

            <div class="w3-third w3-serif">
                <h3>AVIS</h3>
                <p>
                    <span class="w3-tag w3-black w3-margin-bottom">"Meilleur site de la décennie"</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">CEO de Google</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Californie</span><br>
                    <span class="w3-tag w3-black w3-margin-bottom">"Un choix de cocktails varié"</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Gérard Depardieu</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Saransk</span><br>
                    <span class="w3-tag w3-black w3-margin-bottom">"Je peux sentir les couleurs"</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Dewey</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">TV cathodique 14"</span><br>
                    <span class="w3-tag w3-black w3-margin-bottom">"Mais c'était sur en fait"</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Sardoche</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Guérande</span><br>
                    <span class="w3-tag w3-black w3-margin-bottom">"20/20, juste exceptionnel"</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">AlloCiné</span><br>
                </p>
            </div>
        </footer>

        <!-- End page content -->

        <script>
            // Script to open and close sidebar
            function w3_open() {
                document.getElementById("mySidebar").style.display = "block";
            }

            function w3_close() {
                document.getElementById("mySidebar").style.display = "none";
            }
        </script>

    </div>

</body>

</html>