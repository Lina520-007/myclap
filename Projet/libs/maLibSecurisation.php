<?php

include_once "maLibUtils.php";
include_once "modele.php";

/**
 * @param string $login
 * @param string $password
 * @return false ou true 
 */
function verifUser($login,$password)
{	
	if ($idUser = verifUserBdd($login,$password)) {
		$_SESSION["pseudo"] = $login;
		$_SESSION["idUser"] = $idUser; 
		$_SESSION["connecte"] = true; 
		$_SESSION["heureConnexion"] = date("H:i:s"); 
		$_SESSION["isAdmin"]  =  isAdmin($idUser); 
		return true; 
	}
	
	return false; 
}

function securiser($urlBad,$urlGood=false)
{

}

?>
