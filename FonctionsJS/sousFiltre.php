<?php
/*Lorsque l'on clique sur un filtre pour descendre dans la recherche*/
session_start();
$cat = $_GET['p'];
$_SESSION['supCategorie'][$cat] = 1;
$_SESSION['categorieCourante'] = $cat;