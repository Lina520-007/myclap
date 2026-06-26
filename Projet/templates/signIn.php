<h2> Création de compte </h2>

<?php

include_once("libs/modele.php"); // listes
include_once("libs/maLibUtils.php");// tprint
include_once("libs/maLibForms.php");// mkTable, mkLiens, mkSelect ...

?>

<form action="controleur.php" method="get">
    Nom complet :
    <input type="text" name="name" value="" required/>
    <br/> email tel ou facebook :
    <input type="text" name="contact" value="" required/>
    <br/>  mot de passe :
    <input type="password" name="password" value="" required />
    <br/>
    <input type="submit" name="action" value="Créer mon compte" />
</form>














