<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    include_once("libs/maLibForms.php");
    
    redirigerParIndexVers("mes_emprunts");
?>

<style>
    /* Tableau du panier */
    .table-panier {
        width: 100%;
        border-collapse: collapse;
        background-color: #1a1a1a;
        color: #ffffff;
        font-family: Arial, sans-serif;
    }

    /* En-tête */
    .table-panier thead {
        background-color: #222222;
    }

    .table-panier th {
        text-align: left;
        padding: 14px 16px;
        font-weight: bold;
        border-bottom: 2px solid #E50914;
    }

    /* Cellules */
    .table-panier td {
        padding: 14px 16px;
        border-bottom: 1px solid #2a2a2a;
        color: #d0d0d0;
    }

    /* Lignes alternées */
    .table-panier tbody tr:nth-child(even) {
        background-color: #161616;
    }

    /* Survol */
    .table-panier tbody tr:hover {
        background-color: #222222;
    }

    /* Dernière ligne */
    .table-panier tbody tr:last-child td {
        border-bottom: none;
    }
</style>

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
    $idUser = 1;

    $emprunts = listerEmprunts($idUser);

    mkTable($emprunts);
?>

<div id="panier">

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
