<?php 
include_once("libs/modele.php"); // listes
include_once("libs/maLibUtils.php");// tprint
include_once("libs/maLibForms.php");// mkTable, mkLiens, mkSelect ...

if ($_SESSION["isAdmin"] ==false) { 
    $qs="?view=accueil";
     $urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	// On redirige vers la page index avec les bons arguments

	header("Location:" . $urlBase . $qs);
	//qs doit contenir le symbole '?'

	// On écrit seulement après cette entête
	ob_end_flush();
	

}

?>


<scrypt>
<h1> Utilisateurs ayants accès </h1>

<?php


$utilisateurs=listerUtilisateursEtEmprunts();


mkTable($utilisateurs, array("name", "contact", "flat_num","emprunts_termines","emprunts_en_cours","score", "role"));

$utilisateurs=listerUtilisateurs("En attente","eleve");
mkForm("controleur.php");
echo"</br>";
mkSelect("idUser", $utilisateurs, "id", "name");
mkInput("submit", "action", "Rendre administrateur");
mkInput("submit", "action", "Retirer rôle administrateur");


endForm();


