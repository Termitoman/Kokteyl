<!-- Fichier servant à factoriser le code que l'on réutilise dans tous les pages, il suffit de l'include pour éviter la duplication de code. -->
<!-- Ce code est présent dans toutes les pages nécessitant une pagination. -->
<!-- Fichier correspondant à la pagination-->

<div class="w3-center w3-padding-32">
    <div class="w3-bar">
        <!-- Lorsque l'on clique sur la flèche précedante, cela ramène l'utilisateur sur la page précedente, si celle-ci n'existe pas, le bouton n'est pas cliquable-->
        <a href="./?page=<?= $currentPage - 1 ?>" class="w3-bar-item w3-button w3-hover-black <?= ($currentPage == 1) ? "disable" : "" ?>">«</a>

        <!-- On créer des boutons qui permettent de naviguer entre les pages, si on est sur la page correspondante à un bouton, celui-ci est mis en valeur-->
        <?php for ($i = 1; $i <= $nbPages; ++$i) {
        ?>
            <a href="./?page=<?= $i ?>" class="<?= ($currentPage == $i) ? "w3-bar-item w3-black w3-button" : "w3-bar-item w3-button w3-hover-black" ?>"><?= $i ?></a>
        <?php } ?>

        <!-- Lorsque l'on clique sur la flèche suivante, cela ramène l'utilisateur sur la page suivante, si celle-ci n'existe pas, le bouton n'est pas cliquable-->
        <a href=" ./?page=<?= $currentPage + 1 ?>" class="w3-bar-item w3-button w3-hover-black <?= ($currentPage == $nbPages) ? "disable" : "" ?>">»</a>
    </div>
</div>