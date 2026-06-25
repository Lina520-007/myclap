<?php

    include_once "libs/maLibUtils.php";
    include_once "libs/maLibSQL.pdo.php";
    include_once "libs/modele.php"; 

    $qs = "?view=inventaire";
    
 if ($action = valider("action")) {

    ob_start();

    switch($action) {
        case"Recherche AJAX": 
        $search = valider("search");
        $resultats = rechercherMateriel($search);
        header('Content-Type: application/json');
        echo json_encode($resultats);
        die();
        
        case "Reserver":
            echo "blablabla";
            $qs = "?view=mes_emprunts";
        break;

        case 'updateUser':
            $idUser = valider("id");
            $nom = valider("nom");
            $contact = valider("contact");
            $numAppart = valider("numAppart");
            $score = valider("score");

            updateUser($idUser, $nom, $contact, $numAppart, $score);
            $qs = "?view=compte";:
			break;

    }
}
    // Redirection après traitement de la requete
    $urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";

	header("Location:" . $urlBase . $qs);

	ob_end_flush();

?>
