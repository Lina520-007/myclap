<?php 

include_once("libs/modele.php"); // listes
include_once("libs/maLibUtils.php");// tprint
include_once("libs/maLibForms.php");// mkTable, mkLiens, mkSelect ...
include_once("libs/maLibSecurisation.php");

echo "<h2> Connexion </h2>";
mkForm("controleur.php");
echo "nom complet : ";
mkInput("text", "name");
echo "<br/>  mot de passe : ";
mkInput("text", "password");
echo "<br/>";
mkInput("submit", "action", "Se connecter");

echo"<br/>Si vous n'avez pas encore créé de compte, cliquez ici :  ";
echo'<a href="index.php?view=signIn"> sign in </a>';




endForm()
?>