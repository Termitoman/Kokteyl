<?php
//On ouvre la bdd.
include("../OuvertureBDD/ouvertureBDD.php");
//On crée une session ou récupère celle en cours (gestion des cookies de session).
session_start();
//On fait apparaître la structure du haut de la page
include_once("../StructurePage/entete.php");
include_once("../StructurePage/menu.php");
?>

<div class="w3-main w3-content w3-padding w3-center" style="max-width:1200px;margin-top:100px">
    <div>
        <h2>Connexion au compte<br /></h2>
    </div>
    <div>
        <div>
            <p>Les champs comportant le symbole <em>*</em> sont <strong>obligatoires.</strong></p>
        </div>
        <div>
            <form class="w3-container" method="post" action="index.php">
                <fieldset>
                    <legend>Informations du compte</legend>
                    <label for="mail">Mail <em>*</em></label>
                    <input class="w3-input" name="mail" type="mail" placeholder="Mail" required="" pattern="[aA0-zZ9]+[.]?[aA0-zZ9]*@[aA-zZ]*[.]{1}[aA-zZ]+"><br>
                    <label for="mdp">Mot de passe <em>*</em></label>
                    <input class="w3-input" type="password" name="mdp" placeholder="Mot de passe" required=""><br>
                    <?php

                    if (isset($_POST["submit"])) {
                        $mdpVerification = '';
                        $verifMail = $bdd->prepare("SELECT mdp FROM Utilisateur where login = :mailVerification");
                        $verifMdp = $bdd->prepare("SELECT login FROM Utilisateur where login = :mailVerification AND mdp = SHA1(:mdpVerification)");
                        $reqMdp = $bdd->prepare("SELECT mdp FROM Utilisateur where login = :mailVerification");
                        $shamdp = $bdd->prepare("SELECT SHA1(:mdp) AS mdp FROM Utilisateur");

                        /*On test si le mail existe dans la base de données*/
                        $mailVerification = $_POST['mail'];
                        $verifMail->bindParam(':mailVerification', $mailVerification);
                        $verifMail->execute();

                        if ($donnees = $verifMail->fetch()) {
                            /*Le mail est dans la base de données*/

                            /*On teste si le mot de passe associé  ce mail est celui qui est entré*/
                            $verifMdp->bindParam(':mailVerification', $mailVerification);
                            $verifMdp->bindParam(':mdpVerification', $_POST["mdp"]);
                            $verifMdp->execute();

                            if ($donnees = $verifMdp->fetch()) {
                                /*Le mot de passe correspond*/
                                $_SESSION['login'] = $mailVerification;
                                include_once "../StructurePage/recupDonneesPanier.php";

                                //On revient à l'accueil
                                ?>
                                <script>
                                    document.location.replace("../");
                                </script>
                                <?php
                            } else {
                                /*Le mot de passe ne correspond pas au mail*/
                                echo "<p>Le mode de passe est incorrecte !</p>";
                            }
                        } else {
                            /*Le mail n'est pas dans la base de données*/
                            echo "<p>Mail inconnu !</p>";
                        }
                    }
                    ?>

                </fieldset>
                <p><input class="w3-btn w3-black" name="submit" type="submit" value="Connexion"></p>
            </form>
        </div>
        <div>
            <br><br><br>
            <p><a href="../Inscription/"> Cliquez-ici </a> pour vous inscrire.</p>
        </div>
    </div>
</div>

<?php
//On fait apparaître la structure du bas de la page
include_once("../StructurePage/piedDePage.php");
?>