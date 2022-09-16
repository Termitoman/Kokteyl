<?php
// Démarrage ou restauration de la session.
session_start();
// On vide intégralement le tableau de session.
$_SESSION = array();
// Destruction de la session.
session_destroy();
// Destruction du tableau de session.
unset($_SESSION);
//On fait apparaître la structure du haut de la page.
include_once("../StructurePage/entete.php");
include_once("../StructurePage/menu.php");
?>

<div class="w3-main w3-content w3-padding w3-center" style="max-width:1200px;margin-top:100px">
    <div>
        <div>
            <h2>Déconnexion</h2>
            <p>Vous êtes bien déconnecté. <a href="../">Cliquez-ici </a>pour revenir à la page d'accueil.</p>
        </div>
    </div>
</div>

<?php
//On fait apparaître la structure du bas de la page.
include_once("../StructurePage/piedDePage.php");
?>