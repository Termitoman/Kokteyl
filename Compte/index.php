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
    <h2>
        <?php

        //Procédure permettant de factoriser le code.
        //La procédure permet de changer la valeur d'une donnée dans la base de données,
        //La donnée est rentrée par l'utilisateur lors de la modification des données de son compte.
        function changerDonnee($donnees, $str_donnee_form, $str_donnee_bdd) {
            //On ouvre la bdd et on ouvre la session car la fonction n'est pas appelée au chargement de la page mais quand l'utilisateur appuie sur le bouton enregistrer.
            include("../OuvertureBDD/ouvertureBDD.php");
            session_start();

            // On vérifie que la donnée entrée n'est ni vide, ni la même que celle contenue dans la base de données.
            if ($donnees[$str_donnee_bdd] != $_POST[$str_donnee_form] && !empty($_POST[$str_donnee_form])) {
                //On entre la nouvelle valeur dans la base de données.
                $requete = $bdd->prepare("UPDATE Utilisateur SET " . $str_donnee_bdd ." = :donnee WHERE login = :login");
                $requete->bindParam('login', $_SESSION['login']);
                $requete->bindParam('donnee', $_POST[$str_donnee_form]);
                $requete->execute();
                //On préviens l'utilisateur de la modification de la donnée.
                ?> <p>Modification enregistrée (<?=$str_donnee_form?>).</p><br/><?php
            } else {
                //On prévient l'utilisateur du fait que cette données n'ait pas été modifiée.
                ?> <p>Aucune modification n'a été apportée (<?=$str_donnee_form?>).</p><br/><?php
            }
        }

        $requete = $bdd->prepare("SELECT * FROM Utilisateur WHERE login = :loginSession");
        $loginSession = $_SESSION['login'];
        $requete->bindParam('loginSession', $loginSession);
        $requete->execute();

        if ($donnees = $requete->fetch()) {
            echo "Votre compte : ";
        }
        ?>
    </h2>
    <br><br>
    <div>
        <div>
            <form action="#" method="post">
                <?php
                // On vérifie ici que l'utilisateur a appuyé sur le bouton de validation des changements
                if (isset($_POST["validation"])) {
                    // On vérifie ici que si l'utilisateur rentre un mot de passe, alors tous les champs de mots de passe sont rempli.
                    if (isset($_POST['oldMdp']) && !empty($_POST["oldMdp"])) {
                        if (SHA1($_POST["oldMdp"]) == $donnees['mdp']) {
                            if (isset($_POST['newMdp']) && !empty($_POST["newMdp"]) && $_POST["newMdp"] != $_POST['oldMdp']) {
                                if (isset($_POST['confirmationMdp']) && !empty($_POST["confirmationMdp"]) && $_POST['newMdp'] == $_POST['confirmationMdp']) {
                                    $requete = $bdd->prepare("UPDATE Utilisateur SET mdp = SHA1(:mdpChange) WHERE login = :loginSession");
                                    $loginSession = $_SESSION['login'];
                                    $mdpChange = $_POST['confirmationMdp'];
                                    $requete->bindParam('loginSession', $loginSession);
                                    $requete->bindParam('mdpChange', $mdpChange);
                                    $requete->execute();
                                    echo "Nouveau mdp créé";
                                } else {
                                    ?> <em> Mot de passe de confirmation manquant ou différent du nouveau mot de
                                        passe </em> <?php
                                }
                            } else {
                                ?> <em> Nouveau mot de passe manquant ou identique à l'ancien </em> <?php
                            }
                        } else {
                            ?> <em> Mot de passe différent du mot de passe de l'utilisateur </em> <?php
                        }
                    } else {
                        ?><p>Aucune modification n'a été apportée (mot de passe).</p><br><?php
                    }
                    // On fait les différents tests afin de modifier les données de l'utilisateur

                    // On vérifie que le mail entré n'est ni vide, ni le même que celui contenu dans la base de données
                    if ($donnees['login'] != $_POST['email'] && !empty($_POST['email'])) {
                        $requete = $bdd->prepare("UPDATE Utilisateur SET login = :logina WHERE login = :login");
                        $nouveauLogin = $_POST['email'];
                        $requete->bindParam('login', $_SESSION['login']);
                        $requete->bindParam('logina', $nouveauLogin);

                        $requete->execute();
                        $_SESSION['login'] = $nouveauLogin;

                        ?> <p>Modification enregistrée (mail).</p><br/><?php
                    } else {
                        ?> <p>Aucune modification n'a été apportée (mail).</p><br/><?php
                    }


                    // Si l'utilisateur à changé l'adresse de son compte, on la modifie dans la base de données.
                    changerDonnee($donnees, 'adresse', 'adresse');

                    // Si l'utilisateur à changé le nom de son compte, on la modifie dans la base de données.
                    changerDonnee($donnees, 'nom', 'nom');

                    // Si l'utilisateur à changé le prénom de son compte, on la modifie dans la base de données.
                    changerDonnee($donnees, 'prenom', 'prenom');

                    // Si l'utilisateur à changé le code postal de son compte, on la modifie dans la base de données.
                    changerDonnee($donnees, 'postal', 'postal');

                    // Si l'utilisateur à changé le sexe de son compte, on la modifie dans la base de données.
                    changerDonnee($donnees, 'sexe', 'sexe');

                    // Si l'utilisateur à changé le téléphone de son compte, on la modifie dans la base de données.
                    changerDonnee($donnees, 'telephone', 'noTelephone');

                    // Si l'utilisateur à changé la ville de son compte, on la modifie dans la base de données.
                    changerDonnee($donnees, 'ville', 'ville');

                    ?>
                    <button class="w3-btn w3-black" name="retour" action="./">Retour</button>
                    <?php
                    // On vérifie que l'utilisateur veuille bien modifier les informations de son compte.
                } else if (isset($_POST["modification"])) {
                    // On remplit les champs du formulaire avec les informations contenues dans la base de données.
                    //Si il n'y à rien dans la base de données, on remplit le placeholder.
                    ?>
                    <input class="w3-input" name="email" type="email" pattern="[aA0-zZ9]+[.]?[aA0-zZ9]*@[aA-zZ]*[.]{1}[aA-zZ]+"
                           value=<?= $donnees['login'] ?>><br><br>
                    <?php if ($donnees['nom'] != "null") {
                        $nom = htmlspecialchars($donnees['nom'], ENT_QUOTES); ?>
                        <input class="w3-input" name="nom" value='<?= $nom ?>'><br><br>
                    <?php } else { ?>
                        <input class="w3-input" name="nom" placeholder="Nouveau nom"><br><br>
                    <?php }
                    if ($donnees['prenom'] != "null") {
                        $prenom = htmlspecialchars($donnees['prenom'], ENT_QUOTES); ?>
                        <input class="w3-input" name="prenom" value='<?= $prenom ?>'><br><br>
                    <?php } else { ?>
                        <input class="w3-input" name="prenom" placeholder="Nouveau prénom"><br><br>
                    <?php } ?>
                    <select name="sexe">
                        <option value="N" name="aucun">Non renseigné</option>
                        <option value="H" name="homme">Homme</option>
                        <option value="F" name="femme">Femme</option>
                    </select><br><br>
                    <?php
                    if ($donnees['adresse'] != "null") {
                        $adresse = htmlspecialchars($donnees['adresse'], ENT_QUOTES); ?>
                        <input class="w3-input" name="adresse" value='<?= $adresse ?>'><br><br>
                    <?php } else { ?>
                        <input class="w3-input" name="adresse" placeholder="Nouvelle adresse"><br><br>
                    <?php }
                    if ($donnees['postal'] != 0) { ?>
                        <input class="w3-input" name="postal" pattern="[0-9]{5}" value=<?= $donnees['postal'] ?>><br><br>
                    <?php } else { ?>
                        <input class="w3-input" name="postal" placeholder="Nouveau code postal" pattern="[0-9]{5}"><br><br>
                    <?php }
                    if ($donnees['ville'] != "null") {
                        $ville = htmlspecialchars($donnees['ville'], ENT_QUOTES); ?>
                        <input class="w3-input" name="ville" value='<?= $ville ?>'><br><br>
                    <?php } else { ?>
                        <input class="w3-input" name="ville" placeholder="Nouvelle ville"><br><br>
                    <?php }
                    if ($donnees['noTelephone'] != 0) { ?>
                        <input class="w3-input" name="telephone" type="tel" pattern="0[3, 6, 9, 7, 2, 1][0-9]{8}"
                               value=<?= $donnees['noTelephone'] ?>><br><br>
                    <?php } else { ?>
                        <input class="w3-input" name="telephone" type="tel" placeholder="0142928100" pattern="0[3, 6, 9, 7, 2][0-9]{8}">
                        <br><br>
                    <?php } ?>
                    <br>
                    <input class="w3-input" name="oldMdp" type="password" placeholder="Ancien mot de passe"><br><br>
                    <input class="w3-input" name="newMdp" type="password" placeholder="Nouveau mot de passe"><br><br>
                    <input class="w3-input" name="confirmationMdp" type="password" placeholder="Nouveau mot de passe">
                    <br><br>
                    <br>
                    <p><input class="w3-btn w3-black" name="validation" type="submit" value="Enregistrer"></p>
                <?php } else {
                    // On affiche les informations du compte de l'utilisateur
                    ?>
                    <p>Email : <?php echo $donnees['login']; ?> </p>
                    <p>Nom : <?php if ($donnees['nom'] != "null") echo $donnees['nom']; ?> </p>
                    <p>Prenom : <?php if ($donnees['prenom'] != "null") echo $donnees['prenom']; ?> </p>
                    <p>Sexe : <?php echo $donnees['sexe']; ?> </p>
                    <p>Adresse : <br><?php if ($donnees['adresse'] != "null") echo $donnees['adresse'];
                        if ($donnees['postal'] != 0) echo ' ' . $donnees['postal'];
                        if ($donnees['ville'] != "null") echo ' ' . $donnees['ville']; ?></p>
                    <p>N° de téléphone : <?php if ($donnees['noTelephone'] != 0) echo $donnees['noTelephone']; ?> </p>
                    <p><input class="w3-btn w3-black" name="modification" type="submit" value="Modifier informations"></p>
                    <?php
                }
                ?>
                <br>
            </form>
        </div>
        <div>
            <form class="w3-container" action="#" method="post">
                <?php if (isset($_POST["suppression"])) {
                    // On supprime l'utilisateur de la base de données si le bouton de suppression a été pressé
                    $requete = $bdd->prepare("DELETE FROM Utilisateur WHERE login = :login");
                    $login = $_SESSION['login'];
                    $requete->bindParam('login', $login);
                    $requete->execute();
                    $_SESSION = array();
                    session_destroy();
                    unset($_SESSION);
                    //On revient à l'accueil
                    ?>
                    <script>
                        document.location.replace("../");
                    </script>
                    <?php
                } ?>
                <br>
                <p><input class="w3-btn w3-black" name="suppression" type="submit" value="Supprimer le compte"></p>
            </form>

        </div>
    </div>
</div>

<?php
//On fait apparaître la structure du bas de la page
include_once("../StructurePage/piedDePage.php");
?>
