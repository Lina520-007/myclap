<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    include_once("libs/maLibForms.php");
    
    redirigerParIndexVers("mes_emprunts");
?>
<h1> Mes emprunts</h1>

<?php
    /*
    // Si l'utilisateur n'est pas connecté, on le renvoie vers la page de connection
    if (!isset($_SESSION["idUser"])) {
        $url = dirname($_SERVER["PHP_SELF"]) . "/index.php?view=login";
        header("Location:$url");
        die();
    }
    */

    //$idUser = $_SESSION["idUser"];
    $idUser = 2;

    $emprunts = listerEmprunts($idUser);
?>

<div id="panier"  class="table-wrapper">

    <table class="table-panier">

        <thead>
            <tr>
                <th>Nom</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Statut</th>
            </tr>
        </thead>

        <tbody id="body-panier">
            <?php mkTableBody($emprunts); ?>
        </tbody>

    </table>

</div>
