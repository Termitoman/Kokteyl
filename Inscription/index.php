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
        <h2>Création du compte<br /></h2>
    </div>
    <div>
        <div>
            <h2>Inscription<br /></h2>
            <p>Les champs comportant le symbole <em>*</em> sont <strong>obligatoires.</strong></p>
        </div>
        <form class="w3-container" method="post" action="#">
            <fieldset>
                <legend>Informations du compte</legend>
                <?php
                if (isset($_POST["submit"])) {

                    //Tous les champs non obligatoires peuvent être null
                    $nom = "null";
                    $prenom = "null";
                    $adresse = "null";
                    $cp = 0;
                    $ville = "null";
                    $telephone = 0;

                    $results = $bdd->prepare('SELECT * FROM Utilisateur where login = :mailVerification');
                    $mailVerification = $_POST['mail'];
                    $results->bindParam(':mailVerification', $mailVerification);
                    $results->execute();

                    //On vérifie que le mail entré n'est pas celui d'un autre utilisateur déjà inscrit
                    if ($donnees = $results->fetch()) {
                ?>
                        <em> L'adresse mail : <?= $donnees['login']; ?> est déjà utilisée</em>
                        <br>
                        <?php
                    } //Le mail n'est pas utilisé par quelqu'un d'autre
                    else {
                        //On vérifie que le mot de passe fait moins de 20 caractères
                        if (strlen($_POST["mdp"]) <= 20) {
                            $mail = $_POST["mail"];
                            $mdp = $_POST["mdp"];
                            $sexe = $_POST["sexe"];

                            //Si les champs obligatoires ne sont pas vides, on enregistre leur valeur
                            if (!empty($_POST["nom"])) {
                                $nom = $_POST["nom"];
                            }
                            if (!empty($_POST["prenom"])) {
                                $prenom = $_POST["prenom"];
                            }
                            if (!empty($_POST["adresse"])) {
                                $adresse = $_POST["adresse"];
                            }
                            if (!empty($_POST["cp"])) {
                                $cp = $_POST["cp"];
                            }
                            if (!empty($_POST["ville"])) {
                                $ville = $_POST["ville"];
                            }
                            if (!empty($_POST["telephone"])) {
                                $telephone = $_POST["telephone"];
                            }

                            //On insère tous les champs dans la base de données
                            $stmt = $bdd->prepare("INSERT INTO Utilisateur (nom, prenom, login, mdp, sexe, adresse, postal, ville, noTelephone) VALUES (:nom, :prenom, :login, SHA1(:mdp), :sexe, :adresse, :cp, :ville, :noTelephone)");
                            $stmt->bindParam(':nom', $nom);
                            $stmt->bindParam(':prenom', $prenom);
                            $stmt->bindParam(':login', $mail);
                            $stmt->bindParam(':mdp', $mdp);
                            $stmt->bindParam(':sexe', $sexe);
                            $stmt->bindParam(':adresse', $adresse);
                            $stmt->bindParam(':cp', $cp);
                            $stmt->bindParam(':ville', $ville);
                            $stmt->bindParam(':noTelephone', $telephone);
                            $stmt->execute();

                            $_SESSION['login'] = $mail;
                            include_once "../StructurePage/recupDonneesPanier.php";

                            if (isset($_SESSION['login'])) {
                                //On revient à l'accueil
                                ?>
                                <script>
                                    document.location.replace("../");
                                </script>
                                <?php
                            }
                        } //Le mot de passe fait plus de 20 caractères
                        else {
                        ?> <em>Le mot de passe doit contenir moins de 20 caractères<br><br></em><?php
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                                ?>

                <label for="mail">Mail <em>*</em></label>
                <!--Le mail doit correspondre à l'expression régulière donnée-->
                <input class="w3-input" name="mail" type="mail" placeholder="Mail" required="" pattern="[aA0-zZ9]+[.]?[aA0-zZ9]*@[aA-zZ]*[.]{1}[aA-zZ]+"><br>
                <label for="mdp">Mot de passe <em>*</em></label>
                <input class="w3-input" name="mdp" type="password" placeholder="Mot de passe" required=""><br>
            </fieldset>
            <fieldset>
                <legend>Informations personnelles</legend>
                <label for="nom">Nom</label>
                <input class="w3-input" name="nom" placeholder="Nom"><br>
                <label for="prenom">Prénom</label>
                <input class="w3-input" name="prenom" placeholder="Prénom"><br>
                <label for="sexe">Sexe</label>
                <select name="sexe">
                    <option value="N" name="aucun">Non renseigné</option>
                    <option value="H" name="homme">Homme</option>
                    <option value="F" name="femme">Femme</option>
                </select><br>
                <label for="naissance">Date de naissance</label>
                <input class="w3-input" name="naissance" type="date"><br>
                <label for="adresse">Adresse</label>
                <input class="w3-input" name="adresse" placeholder="Adresse"><br>
                <label for="cp">Code postal</label>
                <!--Le code postal doit correspondre à l'expression régulière donnée-->
                <input class="w3-input" name="cp" pattern="[0-9]{5}" placeholder="54000"><br>
                <label for="ville">Ville</label>
                <input class="w3-input" name="ville"placeholder="Nancy"><br>
                <label for="telephone">Téléphone</label>
                <!--Le numéro de téléphone doit correspondre à l'expression régulière donnée-->
                <input class="w3-input" name="telephone" type="tel" placeholder="0142928100" pattern="0[3, 6, 9, 7, 2][0-9]{8}"><br>
            </fieldset>
            <p><input class="w3-btn w3-black" name="submit" type="submit" value="Créer le compte"></p>
        </form>
    </div>
</div>

<?php
//On fait apparaître la structure du bas de la page
include_once("../StructurePage/piedDePage.php");
?>