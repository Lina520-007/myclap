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
echo "<h1>Inventaire du matériel</h1>";




$materiel = listerMateriel();


if (!empty($materiel)) {
    foreach ($materiel as &$objet) {
       
        if (!empty($objet['Photo'])) {
            $objet['Photo'] = "<img src='" . htmlspecialchars($objet['Photo']) . "' alt='Photo de " . htmlspecialchars($objet['Nom']) . "' style='max-width: 100px; height: auto; border-radius: 5px;' />";
        } else {
            $objet['Photo'] = "Pas de photo";
        }
        $objet['Caution'] = $objet['Caution'] . " €";
    }


    mkTable($materiel, array('Nom', 'Quantite', 'Description', 'Caution','Photo'));
} else {
    echo "Aucun matériel n'est actuellement disponible dans l'inventaire";
}








echo"<h1> Ajouter un objet à l'inventaire </h1>";
mkForm("controleur.php");


echo"Nom de l'objet : ";
mkInput("text","name");
echo"</br> Description : ";
mkInput("text","description");
echo"</br> Montant de la caution en euros : ";
mkInput("text","bail");
echo"</br> Catégorie d'objet : ";
$categories = listerCategories();
mkSelect("categoryId", $categories, "id", "name");
echo"</br> Quantité : ";
mkInput("text","stock");
echo"</br> Url de la photo : ";
mkInput("text","photoUrl");
echo"</br>";
mkInput("submit", "action", "Ajouter l objet");




endForm();


