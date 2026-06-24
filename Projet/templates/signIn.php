<h2> Création de compte </h2>

<?php

include_once("libs/modele.php"); // listes
include_once("libs/maLibUtils.php");// tprint
include_once("libs/maLibForms.php");// mkTable, mkLiens, mkSelect ...



mkForm("controleur.php");
echo "Nom complet : ";
mkInput("text", "Nom");
echo "<br/> email tel ou facebook : ";
mkInput("text", "contact");
echo "<br/>  mot de passe : ";
mkInput("text", "passe");
echo "<br/>";
mkInput("submit", "action", "Créer mon compte");
endForm();










?>