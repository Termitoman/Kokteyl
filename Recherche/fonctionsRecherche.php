<script>
    var ingredientsAjoutes = new Array();
    var ingredientsEnleves = new Array();

    //Fonction qui ajoute à la liste déroulante les ingrédients correspondant à la recherche de l'utilisateur
    function suggestion(str) {
        const listeSuggestions = document.getElementById("suggestion");
        if (str == "") {
            var options = select.getElementsByTagName('option');
            for (var i = 0; i < options.length;) {
                listeSuggestions.removeChild(options[i]);
            }
            return;
        } else {
            if (window.XMLHttpRequest) {
                // requête pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // requête pour les navigateurs IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                var listeOptions = '';
                var listeRecue = this.responseText.split("\n");
                for (var i = 0; i < listeRecue.length; ++i) {
                    listeOptions += "<option value=\"" + listeRecue[i] + "\" />\n"; // Stockage des options dans une variable
                }
                listeSuggestions.innerHTML = listeOptions;
            };
            xmlhttp.open("GET", "getIngredients.php?ing=" + str, true);
            xmlhttp.send();

        }
    }

    //Fonction qui supprime un ingredient des ingrédients demandés ainsi que son bouton
    function supprIngredientDemande(str) {
        ingredientsAjoutes.splice(ingredientsAjoutes.indexOf(str), 1);
        afficheRecette("");
        var bouton = document.getElementById(str);
        bouton.remove();
    }

    //Fonction qui supprime un ingredient des ingrédients enlevés ainsi que son bouton
    function supprIngredientEnleve(str) {
        ingredientsEnleves.splice(ingredientsEnleves.indexOf(str), 1);
        afficheRecette("");
        var bouton = document.getElementById(str);
        bouton.remove();
    }

    //Focntion qui ajoute un ingrédient aux ingrédients demandés ainsi que son bouton
    function ajoutIngredientDemande(str) {
        if (ingredientsAjoutes.indexOf(str) == -1) {
            /*Ajout de l'ingrédient aux ingrédients non demandés*/
            ingredientsAjoutes.push(str);

            /*Création du bouton*/
            var formulaire = document.getElementById('boutonsAjouts');
            var bouton = document.createElement("BUTTON");
            bouton.setAttribute("id", str);
            bouton.setAttribute("class", "w3-button w3-black");

            bouton.setAttribute("onclick", "supprIngredientDemande(this.id)");
            bouton.innerHTML = "<span>" + str + "</span>";
            formulaire.insertBefore(bouton, null);
        }
    }

    //Focntion qui ajoute un ingrédient aux ingrédients enlevés ainsi que son bouton
    function ajoutIngredientEnleve(str) {
        /*Ajout de l'ingrédient aux ingrédients non demandés*/
        if (ingredientsEnleves.indexOf(str) == -1) {
            ingredientsEnleves.push(str);

            /*Création du bouton*/
            var formulaire = document.getElementById('boutonsSuppressions');
            var bouton = document.createElement("BUTTON");

            bouton.setAttribute("id", str);
            bouton.setAttribute("class", "w3-button w3-black");
            bouton.setAttribute("onclick", "supprIngredientEnleve(this.id)");
            bouton.innerHTML = "<span>" + str + "</span>";

            formulaire.insertBefore(bouton, null);
        }
    }




    //Affiche les cocktails selon les ingrédients ajoutés ou enlevés
    function afficheRecette(condition) {
        if (condition == 'ajout') {
            ajoutIngredientDemande(document.getElementById('ingVoulu').value);
        } else if (condition == 'supp') {
            ajoutIngredientEnleve(document.getElementById('ingNonVoulu').value);
        }

        var strRequete = "";
        var div = document.getElementById('listeCocktails');

        /*On supprime tous les cocktails déjà affichés*/
        div.innerHTML = "";

        var compteur = 0;
        var strRequeteAjout = "";
        var strRequeteSupp = "";

        /*On crée la requête avec tous les ingrédients ajoutés*/
        compteur = 0;
        ingredientsAjoutes.forEach(element => {
            strRequeteAjout += "nomRecette IN (SELECT nomRecette FROM Liaison WHERE nomIngredient = \"" + element + "\")";
            if (compteur < ingredientsAjoutes.length - 1) {
                strRequeteAjout += " AND ";
            }
            compteur += 1;
        });

        /*On crée la requête avec tous les ingrédients enlevés*/
        compteur = 0;
        ingredientsEnleves.forEach(element => {
            strRequeteSupp += "nomRecette NOT IN (SELECT nomRecette FROM Liaison WHERE nomIngredient = \"" + element + "\")";
            if (compteur < ingredientsEnleves.length - 1) {
                strRequeteSupp += " AND ";
            }
            compteur += 1;
        });

        if (window.XMLHttpRequest) {
            // requête pour les navigateurs IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // requête pour les navigateurs IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var div2 = document.createElement("div");

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var listeCocktails = this.responseText.split("-_-");
                div2.innerHTML = htmlAffichageCocktails(listeCocktails);
                div.insertBefore(div2, null);
            }
        };

        if (strRequeteAjout != "") {
            strRequete += "(" + strRequeteAjout + ")";
            if (strRequeteSupp != "") {
                strRequete += " AND (" + strRequeteSupp + ")";
            }
        } else {
            if (strRequeteSupp != "") {
                strRequete += strRequeteSupp;
            }
        }
        xmlhttp.open("GET", "getCocktails.php?ing=" + strRequete, false);
        xmlhttp.send();
    }

    //Retourne le code HTML de l'affichage des cocktails (titre cliquable)
    function htmlAffichageCocktails(liste) {
        var strP = "<br><br>";
        var nomCocktail;
        for (var j = 0; j < liste.length - 1; j++) {
            nomCocktail = liste[j].replace("'", "-_-");
            strP += "<a href='../VisualisationRecette/index.php?cocktail=" + nomCocktail + "'><h3>" + liste[j] + "</h3></a><br>";
        }
        return strP;
    }
</script>