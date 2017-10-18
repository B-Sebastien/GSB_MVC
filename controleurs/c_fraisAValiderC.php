<?php

/* * ******************************************** COMPTABLE ********************************************* */
include("vues/v_sommaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];

switch ($action) {
    /**
     * Affichage des mois qui sont à valider
     */
    case 'selectionnerMoisAValider': {
            $lesMois = $pdo->getLesMoisAValider();
            /**
             * Afin de sélectionner (par défaut) le dernier mois dans la zone de liste
             * Array : Nettoi le tableau et supprime les lignes qui ont des valeurs null, vides ou false
             * Test : Demande toutes les clés et prend la première
             */
            //array_filter($lesMois);
            //var_dump($lesMois);
            if (empty($lesMois)) { /* Permet d'afficher un message d'erreurs si aucun fiche n'est à valider */
                ajouterErreur("Pas de fiche frais a valider !");
                include("vues/v_erreurs.php");
            } else {
                $lesCles = array_keys($lesMois);
                $moisASelectionner = $lesCles[0];
                include("vues/v_listMoisAValider.php");
            }
            break;
        }

    /**
     * Affichage des visiteurs en rapport avec le mois sélectionner
     */    
    case 'selectionnerVisiteurAValider': {
            $leMois = $_REQUEST['lstMois'];
            $lesMois = $pdo->getLesMoisAValider();
            include("vues/v_listMoisAValider.php");

            $lesVisiteurs = $pdo->getLesVisiteursAValider($leMois);
            include("vues/v_afficheVisiteur.php");
            
            $numAnnee = substr($leMois, 0, 4); /* Modifie le formatage de l'année */
            $numMois = substr($leMois, 4, 2); /* Modifie le formatage du mois */
            break;
        }
        
    /**
     * Affichage de la fiche de frais d'un visiteur
     */
    case 'voirFraisAValider': {
            $leMois = $_REQUEST['lstMois'];
            $lesMois = $pdo->getLesMoisAValider();
            include("vues/v_listMoisAValider.php");
            
            $lesVisiteurs = $pdo->getLesVisiteursAValider($leMois);
            include("vues/v_afficheVisiteur.php");
            
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
            
            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);
            
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);
    
            include("vues/v_visiteurFrais.php");
            break;
        }
}
?> 