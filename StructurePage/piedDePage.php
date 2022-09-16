<!-- Fichier servant à factoriser le code que l'on réutilise dans tous les pages, il suffit de l'include pour éviter la duplication de code. -->
<!-- Ce code est présent dans toutes les pages à l'exception de l'acceuil car les liens utilisés ne sont pas les mêmes. -->
<!-- Fichier correspondant au pied de page du site -->

<!-- Slideshow -->
<div class="w3-container w3-padding-32 w3-center">
    <h3>La sélection du jour</h3><br>

    <div class="w3-content w3-section" style="max-width:500px">
        <a class="myTitles w3-animate-left" href="../VisualisationRecette/index.php?cocktail=Builder" style="width:100%">
            <h3>Bob the builder</h3>
        </a>
        <a class="myTitles w3-animate-left" href="../VisualisationRecette/index.php?cocktail=Coconut kiss" style="width:100%">
            <h3>"Le goût des tropiques dans un verre" <br>Gilbert Montagné</h3>
        </a>
        <a class="myTitles w3-animate-left" href="../VisualisationRecette/index.php?cocktail=Margarita" style="width:100%">
            <h3>Tequila ou Tequipala</h3>
        </a>
        <a class="myTitles w3-animate-left" href="../VisualisationRecette/index.php?cocktail=Cuba libre" style="width:100%">
            <h3>Rhum-Coca</h3>
        </a>
    </div>

    <div class="w3-content w3-section" style="max-width:500px">
        <img class="mySlides w3-animate-left" src="../Photos/Builder.jpg" style="width:100%">
        <img class="mySlides w3-animate-left" src="../Photos/Coconut_kiss.jpg" style="width:100%">
        <img class="mySlides w3-animate-left" src="../Photos/Margarita.jpg" style="width:100%">
        <img class="mySlides w3-animate-left" src="../Photos/Cuba_libre.jpg" style="width:100%">
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
                <img src="../Ressources/Roselmo.png" class="w3-left w3-margin-right" style="width:50px">
                <span class="w3-large">Hugo Iopeti</span><br>
                <span>Développeur</span>
            </li>
            <li class="w3-padding-16">
                <img src="../Ressources/Nigelmo.png" class="w3-left w3-margin-right" style="width:50px">
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
</div>
</body>

</html>