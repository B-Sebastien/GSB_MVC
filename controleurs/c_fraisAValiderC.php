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
            
            if (empty($lesMois)) { /* Permet d'afficher un message d'erreurs si aucun fiche n'est à valider */
                ajouterErreur("Pas de fiche frais a valider !");
                include("vues/v_erreurs.php");
            } else {
                $lesCles = array_keys($lesMois);
                $lstMois = $lesCles[0];
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
            include("vues/v_selectionnerVisiteur.php");

            $numAnnee = substr($leMois, 0, 4); /* Modifie le formatage de l'année */
            $numMois = substr($leMois, 4, 2); /* Modifie le formatage du mois */
            break;
        }

    /**
     * Affichage de la fiche de frais d'un visiteur
     */
    case 'voirFraisAValider': {
            $leMois = $_REQUEST['hdMois'];
            $lstVisiteur = $_REQUEST['lstVisiteurs'];
            
            $lesMois = $pdo->getLesMoisAValider();
            include("vues/v_listMoisAValider.php");
            
            $lesVisiteurs = $pdo->getLesVisiteursAValider($leMois);
            include("vues/v_selectionnerVisiteur.php");

            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($lstVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($lstVisiteur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($lstVisiteur, $leMois);

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

//    case 'supprimerFrais': {
//            $idFrais = $_REQUEST['idFrais'];
//            $pdo->supprimerFraisHorsForfait($idFrais);
//            break;
//        }

    case 'reporterFrais': {
            $idFrais = $_REQUEST['idFrais'];
            $pdo->reporterFraisHorsForfait($idFrais);
            break;
        }

    case 'refuserFrais': {
            $idFrais = $_REQUEST['idFrais'];
            $pdo->refuserFraisHorsForfait($idFrais);
            break;
        }

    case 'validerFraisHorsForfait': {
            $idFrais = $_REQUEST['idFrais'];
            $pdo->validerFraisHorsForfait($idFrais);
            break;
        }

    /**
     * Récupération de toutes les fiches de frais à l'état "VA"
     */
    case 'suiviPaiement': {
            $listeFichesFrais = $pdo->getFicheFraisSuivre();
            include("vues/v_suiviFiche.php");
            break;
        }

    case 'valideChoixFiche': {
            $listeFichesFrais = $pdo->getFicheFraisSuivre();

            $dateValide = substr($_REQUEST['lstVisiteur'], 0, 6);
            $visiteur = substr($_REQUEST['lstVisiteur'], 6, strlen($_REQUEST['lstVisiteur']));

            // On récupère toutes les infos de la fiche du visiteur pour le mois
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteur, $dateValide);
        
            /* retourne les nom des visiteurs */
            $listeVisiteur = $pdo->getNomPrenomIdVisiteur();
            $nomPrenomVisiteur = $pdo->getNomPrenomVisiteur($visiteur);
            /**/
            $nbJustificatifs = $pdo->getNbjustificatifs($visiteur, $dateValide);
            $lesFraisForfait = $pdo->getLesFraisForfait($visiteur, $dateValide);
            /**/
            $montantValide = $lesInfosFicheFrais['montantValide'];
            include("vues/v_suiviFiche.php");
            // Vérification si aucune fiche n'est retournée
            if (empty($lesInfosFicheFrais)) {
                ajouterErreur("Fiche inexistante");
                include("vues/v_erreurs.php");
                ;
            } else {
                include ("vues/v_suivreFrais.php");
            }
            break;
        }
    }
?> 