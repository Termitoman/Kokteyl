<!-- Le fichier contenant toutes les fonctions JS et PHP nécessaires à la bonne gestion du panier-->
<script>
    var cheminDossier = "";
    <?php
    if (file_exists("../OuvertureBDD/ouvertureBDD.php")) {
    ?>
        cheminDossier = "../FonctionsJS/"
    <?php
    } else if (file_exists("OuvertureBDD/ouvertureBDD.php")) {
    ?>
        cheminDossier = "FonctionsJS/"
    <?php
    }
    ?>
    //Fonction permettant d'ajouter une recette au panier quand l'utilisateur est connecté
    function ajoutCocktail(user, recette) {
        if (window.XMLHttpRequest) {
            // code pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code pour les navigateurs IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var str = user + "|" + recette;
        xmlhttp.onreadystatechange = function() {
            if (!this.readyState == 4) { //Si l'appel n'est pas terminé
                xmlhttp.send();
            } else {
                document.location.reload();
            }
        };
        xmlhttp.open("GET", cheminDossier + "ajoutCocktail.php?p=" + str, true);
        xmlhttp.send();
    }

    //Fonction permettant de supprimer une recette du panier quand l'utilisateur est connecté
    function enleveCocktail(user, recette) {
        if (window.XMLHttpRequest) {
            // code pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code pour les navigateurs IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var str = user + "|" + recette;
        xmlhttp.onreadystatechange = function() {
            if (!this.readyState == 4) { //Si l'appel n'est pas terminé
                xmlhttp.send();
            } else {
                document.location.reload();
            }
        };
        xmlhttp.open("GET", cheminDossier + "enleveCocktail.php?p=" + str, false);
        xmlhttp.send();
    }

    //Fonction permettant d'ajouter une recette au panier quand l'utilisateur n'est pas connecté
    function ajoutCookie(recette) {
        if (window.XMLHttpRequest) {
            // code pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code pour les navigateurs IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (!this.readyState == 4) { //Si l'appel n'est pas terminé
                xmlhttp.send();
            } else {
                document.location.reload();
            }
        };
        xmlhttp.open("GET", cheminDossier + "ajoutCookie.php?p=" + recette, true);
        xmlhttp.send();
    }


    //Fonction permettant de supprimer une recette du panier quand l'utilisateur n'est pas connecté
    function enleveCookie(recette) {
        if (window.XMLHttpRequest) {
            // code pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code pour les navigateurs IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (!this.readyState == 4) { //Si l'appel n'est pas terminé
                xmlhttp.send();
            } else {
                document.location.reload(); //Refresh les élements de la page sans bouger l'utilisateur.
            }
        };
        xmlhttp.open("GET", cheminDossier + "enleveCookie.php?p=" + recette, true);
        xmlhttp.send();
    }

    //Fonction qui change la catégorie courante par celle donnée en paramètre et enregistre le chemin parcourue jusque celle ci dans $_SESSION
    function sousFiltre(superCat) {

        if (window.XMLHttpRequest) {
            // code pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code pour les navigateurs IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (!this.readyState == 4) {
                xmlhttp.send();
            } else {
                document.location.href = "./"; //Refresh + remet au début d'une page.
            }
        };
        xmlhttp.open("GET", cheminDossier + "sousFiltre.php?p=" + superCat, true);
        xmlhttp.send();
    }

    function retourArriereFiltre(categorie) {

        if (window.XMLHttpRequest) {
            // code pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code pour les navigateurs IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (!this.readyState == 4) {
                xmlhttp.send();
            } else {
                document.location.href = "./";
            }
        };
        xmlhttp.open("GET", cheminDossier + "retourArriereFiltre.php?p=" + categorie, true);
        xmlhttp.send();
    }

    function viderPanier() {

        if (window.XMLHttpRequest) {
            // code pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code pour les navigateurs IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (!this.readyState == 4) {
                xmlhttp.send();
            } else {
                document.location.href = "./";
            }
        };
        xmlhttp.open("GET", cheminDossier + "viderPanier.php", true);
        xmlhttp.send();
    }
</script>

<?php
function sousCategorie($categorie): string
{
    if (!str_contains($categorie, "\'")) {
        $nomBis = str_replace("'", "\'", $categorie);
    }
    $sql = "SELECT nom FROM SuperCategorie WHERE nomSuper = '" . $nomBis . "'";
    include("../OuvertureBDD/ouvertureBDD.php");
    $req = $bdd->query($sql);
    while ($donnees = $req->fetch()) {
        if (!str_contains($donnees['nom'], "\'")) {
            $nomCat = str_replace("'", "\'", $donnees['nom']);
        }
        $sql .= " OR nom IN (" . sousCategorie($nomCat) . ")";
    }
    return $sql;
}
