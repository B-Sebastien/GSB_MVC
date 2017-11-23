<?php

/**
 * Page PDO
 * Classe d'accès aux données. 

 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */
class PdoGsb {

    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsb';
    private static $user = 'root';
    private static $mdp = '';
    private static $monPdo;
    private static $monPdoGsb = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct() {
        PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
        PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
    }

    public function _destruct() {
        PdoGsb::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe

     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();

     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb() {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }

    /**
     * Retourne les informations d'un visiteur

     * @param $login 
     * @param $mdp
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
     */
    public function getInfosVisiteur($login, $mdp) {
        $req = "SELECT visiteur.id AS id, visiteur.nom AS nom, visiteur.prenom AS prenom 
                FROM visiteur 
		WHERE visiteur.login='$login' AND visiteur.mdp='$mdp'";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
     * concernées par les deux arguments

     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois) {
        $req = "SELECT *
                FROM lignefraishorsforfait
                WHERE lignefraishorsforfait.idvisiteur ='$idVisiteur' AND lignefraishorsforfait.mois = '$mois' ";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return le nombre entier de justificatifs 
     */
    public function getNbjustificatifs($idVisiteur, $mois) {
        $req = "SELECT fichefrais.nbjustificatifs AS nb from  fichefrais
                WHERE fichefrais.idvisiteur ='$idVisiteur' AND fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
     * concernées par les deux arguments

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
     */
    public function getLesFraisForfait($idVisiteur, $mois) {
        $req = "SELECT fraisforfait.id AS idfrais, fraisforfait.libelle AS libelle, lignefraisforfait.quantite AS quantite, fraisforfait.montant AS montant
                FROM lignefraisforfait INNER JOIN fraisforfait 
		ON fraisforfait.id = lignefraisforfait.idfraisforfait
		WHERE lignefraisforfait.idvisiteur ='$idVisiteur' AND lignefraisforfait.mois='$mois' 
		ORDER BY lignefraisforfait.idfraisforfait";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * Retourne tous les id de la table FraisForfait

     * @return un tableau associatif 
     */
    public function getLesIdFrais() {
        $req = "SELECT fraisforfait.id AS idfrais
                FROM fraisforfait 
                ORDER BY fraisforfait.id";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * Met à jour la table ligneFraisForfait

     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
     * @return un tableau associatif 
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais) {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $req = "UPDATE lignefraisforfait
                    SET lignefraisforfait.quantite = $qte
                    WHERE lignefraisforfait.idvisiteur = '$idVisiteur' AND lignefraisforfait.mois = '$mois' AND lignefraisforfait.idfraisforfait = '$unIdFrais'";
            PdoGsb::$monPdo->exec($req);
        }
    }

    /**
     * met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs) {
        $req = "UPDATE fichefrais SET nbjustificatifs = $nbJustificatifs
		WHERE fichefrais.idvisiteur = '$idVisiteur' AND fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return vrai ou faux 
     */
    public function estPremierFraisMois($idVisiteur, $mois) {
        $ok = false;
        $req = "SELECT count(*) AS nblignesfrais
                FROM fichefrais 
		WHERE fichefrais.mois = '$mois' AND fichefrais.idvisiteur = '$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        if ($laLigne['nblignesfrais'] == 0) {
            $ok = true;
        }
        return $ok;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur

     * @param $idVisiteur 
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur) {
        $req = "SELECT max(mois) AS dernierMois
                FROM fichefrais 
                WHERE fichefrais.idvisiteur = '$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés

     * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
     * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois) {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $req = "INSERT INTO fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		VALUES('$idVisiteur','$mois',0,0,now(),'CR')";
        PdoGsb::$monPdo->exec($req);
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $uneLigneIdFrais) {
            $unIdFrais = $uneLigneIdFrais['idfrais'];
            $req = "INSERT INTO lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			VALUES('$idVisiteur','$mois','$unIdFrais',0)";
            PdoGsb::$monPdo->exec($req);
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @param $libelle : le libelle du frais
     * @param $date : la date du frais au format français jj//mm/aaaa
     * @param $montant : le montant
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant) {
        $dateFr = dateFrancaisVersAnglais($date);
        $req = "INSERT INTO lignefraishorsforfait 
		VALUES('$idVisiteur','$mois','$libelle','$dateFr','$montant')";
        PdoGsb::$monPdo->exec($req);
    }
    
    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais

     * @param $idVisiteur 
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
     */
    public function getLesMoisDisponibles($idVisiteur) {
        $req = "SELECT fichefrais.mois AS mois
                FROM  fichefrais 
                WHERE fichefrais.idvisiteur ='$idVisiteur' 
		ORDER BY fichefrais.mois desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois = array();
        $laLigne = $res->fetch();
        while ($laLigne != null) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois["$mois"] = array(
                "mois" => "$mois",
                "numAnnee" => "$numAnnee",
                "numMois" => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois) {
        $req = "SELECT ficheFrais.idEtat AS idEtat, ficheFrais.dateModif AS dateModif, ficheFrais.nbJustificatifs AS nbJustificatifs, ficheFrais.montantValide AS montantValide, etat.libelle AS libEtat 
                FROM fichefrais INNER JOIN Etat ON ficheFrais.idEtat = Etat.id 
		WHERE fichefrais.idvisiteur ='$idVisiteur' AND fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais

     * Modifie le champ idEtat et met la date de modif à aujourd'hui
     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat) {
        $req = "UPDATE ficheFrais SET idEtat = '$etat', dateModif = now() 
		WHERE fichefrais.idvisiteur ='$idVisiteur' AND fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * @return un tableau avec les fiche frais a valider
     */
    public function getLesFicheFraisAValider($mois) {
        $req = "SELECT `idVisiteur`, visiteur.nom, visiteur.prenom 
                FROM `fichefrais` INNER JOIN visiteur 
                WHERE `idEtat`= 'CR' AND `mois`= '$mois' AND fichefrais.`idVisiteur` = visiteur.`id`";
        $res = PdoGsb::$monPdo->query($req);
        $ligne = $res->fetchAll();
        return $ligne;
    }

    /*     * *********************************** COMPTABLE ************************************ */

    /**
     * Retourne les informations d'un comptable

     * @param $login 
     * @param $mdp
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
     */
    public function getInfosComptable($login, $mdp) {
        $req = "SELECT comptable.id AS id, comptable.nom AS nom, comptable.prenom AS prenom 
                FROM comptable 
		WHERE comptable.login='$login' AND comptable.mdp='$mdp'";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Fonction : Voir les mois à valider d'une fiche de frais
     * @return type
     */
    public function getLesMoisAValider() {
        $req = "SELECT fichefrais.mois AS mois 
                FROM fichefrais 
                WHERE fichefrais.idEtat='CL'  
		ORDER BY fichefrais.mois desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois = array();
        $laLigne = $res->fetch();
        while ($laLigne != null) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois["$mois"] = array(
                "mois" => "$mois",
                "numAnnee" => "$numAnnee",
                "numMois" => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }

    /**
     * Récupère les informations d'un visiteur des fiches à l'état CL par rapport au mois
     * Fonction : Voir les mois à valider d'une fiche de frais
     * @return type
     */
    public function getLesVisiteursAValider($mois) {
        $req = "SELECT idVisiteur, visiteur.nom, visiteur.prenom
                FROM fichefrais INNER JOIN visiteur ON fichefrais.idVisiteur = visiteur.id 
                WHERE idEtat= 'CL' AND fichefrais.mois = '$mois' ";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll();

        return $laLigne;
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument

     * @param $idFrais 
     */
    public function supprimerFraisHorsForfait($idFrais) {
        $req = "DELETE FROM lignefraishorsforfait
                WHERE lignefraishorsforfait.id =$idFrais ";
        PdoGsb::$monPdo->exec($req);
    }

    /* Voir les frais hors forfait qui sont refusés */

    public function voirFraisRefuse($idFrais) {
        $req = "SELECT situation
                FROM lignefraishorsforfait 
                WHERE lignefraishorsforfait.id =$idFrais";
        $res = PdoGsb::$monPdo->query($req);
        $resu = $res->fetchAll();
        return $resu;
    }

    /* Refuse le frais hors forfait dont l'id est passé en argument */

    public function refuserFraisHorsForfait($idFrais) {
        $req = "UPDATE lignefraishorsforfait
                SET situation = 'REF' 
                WHERE lignefraishorsforfait.id =$idFrais ";
        PdoGsb::$monPdo->exec($req);
    }

    /* Valider le frais hors forfait dont l'id est passé en argument */

    public function validerFraisHorsForfait($idFrais) {
        $req = "UPDATE lignefraishorsforfait
                SET situation = 'VAL' 
                WHERE lignefraishorsforfait.id =$idFrais ";
        PdoGsb::$monPdo->exec($req);
    }

    /* Reporte le frais hors forfait dont l'id est passé en argument */

    public function reporterFraisHorsForfait($idFrais) {
        $req = "SELECT mois
                FROM lignefraishorsforfait 
                WHERE id =$idFrais";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        $annee = $laLigne[0];
        $mois = substr("$annee", 4);
        $annee = substr("$annee", 0, -2);

        if ($mois < 12) {
            $mois = $mois + 1;
        } else {
            $mois = 01;
            $annee = $annee + 1;
        }
        $date = $annee . $mois;
        echo $date;

    }

    /**
     * Retourne la liste des fiches de frais qui sont à valider
     * @return type
     */
    public function getFicheFraisSuivre() {
        $req = PdoGsb::$monPdo->prepare("SELECT fichefrais.idVisiteur AS id, fichefrais.mois AS mois, fichefrais.montantValide AS montant, fichefrais.idEtat, visiteur.nom AS nom, visiteur.prenom AS prenom
                                            FROM fichefrais JOIN visiteur ON fichefrais.idVisiteur = visiteur.id
                                            WHERE idEtat = 'VA' 
                                            ORDER BY mois DESC");
        $req->execute();
        $fiche = $req->fetchAll();
        return $fiche;
    }

    /**
     * Retourne le nom le prenom du visiteur en fonction de sont identifiant
     * @param type $pid
     * @return type
     */
    public function getNomPrenomVisiteur($pid) {
        $req = "select visiteur.nom as nom, visiteur.prenom as prenom from visiteur where id ='$pid'";
        $res = PdoGsb::$monPdo->query($req);
        $res = $res->fetch();
        return $res;
    }
    
     /**
     * Retourne tout les nom,prenom et l'id dans la table visiteur
     * @return type
     */
    public function getNomPrenomIdVisiteur() {
        $req = "select visiteur.nom as nom, visiteur.prenom as prenom, visiteur.id as id from visiteur";
        $res = PdoGsb::$monPdo->query($req);
        //$nom = $rs->fetch();
        return $res;
    }
}
?>
