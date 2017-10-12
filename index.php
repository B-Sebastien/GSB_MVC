<?php
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");
include("vues/v_entete.php") ;
session_start();
$pdo = PdoGsb::getPdoGsb(); /* appel le constructeur */
$estConnecte = estConnecte(); /* Renvoie vrai si un visiteur est connecté */
if (!isset($_REQUEST['uc'])/* uc viens de vues/v_connecion */ || !$estConnecte) {
     $_REQUEST['uc'] = 'connexion';
}

if (isset($_REQUEST['action'])) {
   $action = $_REQUEST['action'];
} else {
    $action = " ";
}

$uc = $_REQUEST['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
	case 'gererFrais' :{
		include("controleurs/c_gererFrais.php");break;
	}
	case 'etatFrais' :{
		include("controleurs/c_etatFrais.php");break; 
	}
        case 'fraisAValider' :{
		include("controleurs/c_fraisAValiderC.php");break; 
	}
}
include("vues/v_pied.php") ;
?>

