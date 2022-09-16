<?php
//Fonction qui remplace les accents, les espaces et les apostrophes d'un string utilse pour charger les images à partir du nom des cocktails.
function formatageString($str) : string
{
    $strSansAcc = $str; //Lettre sans accent
    $strSansAcc = preg_replace('#Ç#', 'C', $strSansAcc);
    $strSansAcc = preg_replace('#ç#', 'c', $strSansAcc);
    $strSansAcc = preg_replace('#è|é|ê|ë#', 'e', $strSansAcc);
    $strSansAcc = preg_replace('#È|É|Ê|Ë#', 'E', $strSansAcc);
    $strSansAcc = preg_replace('#à|á|â|ã|ä|å#', 'a', $strSansAcc);
    $strSansAcc = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $strSansAcc);
    $strSansAcc = preg_replace('#ì|í|î|ï#', 'i', $strSansAcc);
    $strSansAcc = preg_replace('#Ì|Í|Î|Ï#', 'I', $strSansAcc);
    $strSansAcc = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $strSansAcc);
    $strSansAcc = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $strSansAcc);
    $strSansAcc = preg_replace('#ù|ú|û|ü#', 'u', $strSansAcc);
    $strSansAcc = preg_replace('#Ù|Ú|Û|Ü#', 'U', $strSansAcc);
    $strSansAcc = preg_replace('#ý|ÿ#', 'y', $strSansAcc);
    $strSansAcc = preg_replace('#Ý#', 'Y', $strSansAcc);
    $strSansAcc = preg_replace('#ñ#', 'n', $strSansAcc);

    $sansEspaces = str_replace(' ', '_', $strSansAcc);
    $sansAppostrophe = str_replace("'", '', $sansEspaces);

    //On veut le string au format : première lettre est une majuscule, tout le reste est en minuscule (pour un affichage PROPRE)
    $lettreEnMaj = substr($sansAppostrophe, 0, 1); //On récup la première lettre
    $suiteDeMot = strtolower(substr($sansAppostrophe, 1));

    return ($lettreEnMaj . $suiteDeMot);
}