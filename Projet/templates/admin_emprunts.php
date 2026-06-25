

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
	ob_end_flush();}
?>
<form action="index.php" method="get">
<input type="hidden" name="view" value="admin_emprunts" />
<label> Choisissez un filtre :</label>

<select id='filtre' name="filtre">
  <option value=''>--Veuillez choisir un filtre--</option>
  <option value='statut'>statut</option>
  <option value='utilisateurs'>utilisateur</option>

  
</select>

<input type="submit" name = "action" value ="afficher les emprunts" id="afficher_emprunts"/>
</form>

<?php
echo "</br>";
$statutsPossibles = ['PENDING',  'VALIDATED', 'RETRIEVED', 'RETURNED'];
$statutsPossiblesTexte = ['en attente de validation', 'validés', 'en cours', 'retournés'];
$tabStatuts = [];

for ($i = 0; $i <= 3; $i++) {
    $tabStatuts[] = [
        'code_statut' => $statutsPossibles[$i],      
        'texte_statut' => $statutsPossiblesTexte[$i] 
      ];}
$filtre = $_GET['filtre'] ?? '';
if ($filtre == "statut"){


    for ($i=0;$i<=3;$i++){
        echo"<h2>Emprunts " .$statutsPossiblesTexte[$i]. "</h2>";
    $affichage = listerEmpruntsStatut($statutsPossibles[$i]);
    if (!empty($affichage)) {
          mkTable($affichage, array("nom_utilisateur","start_date","end_date","return_date","produits","id"));
      
      echo "<div>";

    mkForm("controleur.php");
      echo "Selectionner l'emprunt à modifier :  ";

    mkSelect("idEmprunt",$affichage,"id","id");
    echo "</br>";
    echo "Selectionner le nouveau statut :  ";
    mkSelect("statutSelectionne", $tabStatuts, "code_statut", "texte_statut");
    echo "</br>";
    mkInput("hidden","filtre",$filtre);
    mkInput("Submit", "action", "Modifier le statut");
    endForm();
        echo "</div>";}

        else {
          echo "<p>Aucun emprunt trouvé avec ce statut.</p>";
      }

    

    
      
  }}

if ($filtre == "utilisateurs"){

$affichage = listerEmpruntsUtilisateurs();
mkTable($affichage, array("nom_utilisateur","emprunt_id","start_date","end_date","return_date","produits","status"));

mkForm("controleur.php");
echo "Selectionner un emprunt : ";
mkSelect("idEmprunt",$affichage,"emprunt_id","emprunt_id");
echo "</br>";
echo "Selectionner le nouveau statut :  ";
mkSelect("statutSelectionne", $tabStatuts, "code_statut", "texte_statut");
echo "</br>";
mkInput("hidden","filtre",$filtre);
mkInput("Submit","action", "Modifier le statut");

endForm();

}







