<?php
if(!isset($_REQUEST['action'])){ 
	$_REQUEST['action'] = 'demandeConnexion'; 
}
$action = $_REQUEST['action'];
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
		$comptable = $pdo->getInfosComptable($login,$mdp); /*création d'un variable comptable*/
		$visiteur = $pdo->getInfosVisiteur($login,$mdp);
		
		if ( ! is_array($comptable) && ! is_array($visiteur)){ /* si les infos de visiteur ou comptable ne sont pas des tableaux*/
			ajouterErreur("Login ou mot de passe incorrect !");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else if (is_array($visiteur)){
			$id = $visiteur['id'];
			$nom =  $visiteur['nom'];
			$prenom = $visiteur['prenom'];
			connecter($id,$nom,$prenom);
			include("vues/v_sommaire.php");
		}
			else{
			$id = $comptable['id'];
			$nom =  $comptable['nom'];
			$prenom = $comptable['prenom'];
			connecter($id,$nom,$prenom);
			include("vues/v_sommaire.php");
			}
		
		break;
        }
        case'deconnexion':{
            deconnecter();
            include("vues/v_connexion.php");
		break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>