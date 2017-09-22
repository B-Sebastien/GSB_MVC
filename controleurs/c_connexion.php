<?php
if (!isset($_REQUEST['action'])) { /* 'action' vient de v_connexion et est égale à 'valideconnexion' | On vérifie simplement si la variable action est vide ou pas */
    $_REQUEST['action'] = 'demandeConnexion'; /* Si action n'existe pas */
}
$action = $_REQUEST['action']; /* Transforme la variable action afin de la tester */

switch ($action) {
    case 'demandeConnexion': {
            include("vues/v_connexion.php");
            break;
        }
    case 'valideConnexion': {

            $login = $_REQUEST['login'];
            $mdp = $_REQUEST['mdp'];
            //Si ne fonctionne pas avec sha1 : $mdp = md5($mdp);
            $comptable = $pdo->getInfosComptable($login, $mdp); /* Création d'une variable comptable */
            $visiteur = $pdo->getInfosVisiteur($login, $mdp);

            if (!is_array($comptable) && !is_array($visiteur)) { /* Vérifie si les infos d'un visiteur ou d'un comptable ne sont pas des tableaux */
                ajouterErreur("Login ou mot de passe incorrect !");
                include("vues/v_erreurs.php");
                include("vues/v_connexion.php");
            } 
            else if (is_array($visiteur)) {
                $type = "visiteur"; //Ajout d'un type pour différencier un visiteur d'un comptable
                $id = $visiteur['id'];
                $nom = $visiteur['nom'];
                $prenom = $visiteur['prenom'];
                connecter($id, $nom, $prenom, $type);
                include("vues/v_sommaire.php");
            } 
            else {
                $type = "comptable"; //Ajout d'un type pour différencier un visiteur d'un comptable
                $id = $comptable['id'];
                $nom = $comptable['nom'];
                $prenom = $comptable['prenom'];
                connecter($id, $nom, $prenom, $type);
                include("vues/v_sommaire.php");
            }

            break;
        }
        
    case'deconnexion': {
            deconnecter();
            include("vues/v_connexion.php");
            break;
        }
        
    default : {
            include("vues/v_connexion.php");
            break;
        }
}
?>
