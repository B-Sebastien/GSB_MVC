<?php
/********************************************** COMPTABLE **********************************************/
include("vues/v_sommaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];

switch ($action) {
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
    case 'fraisAValider': {
            $leMois = $_REQUEST['lstMois'];
            $lesMois = $pdo->getLesMoisAValider(); //Appel fonction
            $moisASelectionner = $leMois;

            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($valeur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($valeur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($valeur, $leMois);

            $numAnnee = substr($leMois, 0, 4); /* Modifie le formatage de l'année */
            $numMois = substr($leMois, 4, 2); /* Modifie le formatage du mois */

            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);

            include("vues/v_listMoisAValider.php");
            break;
        }
        
    case 'selectionnerVisiteurAValider': {
            $leMois = $_REQUEST['lstMois'];
            $lesMois = $pdo->getLesMoisAValider(); //Appel fonction  
           
            
           // $_SESSION['idVisiteur']=$idVisiteur;
            
            $lesVisiteurs = $pdo->getLesVisiteursAValider($leMois);
            
            include("vues/v_listMoisAValider.php");
            include("vues/v_afficheVisiteur.php");
            break;  
        }
}
?> 