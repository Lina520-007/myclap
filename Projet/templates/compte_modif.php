<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    include_once("libs/maLibForms.php");
    
    redirigerParIndexVers("mes_emprunts");
?>
<h1> Mon compte</h1>


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
    $idUser =1;


    $userInfo = userInfo($idUser);
?>

<div id="panier"  class="table-wrapper">

    <table class="table-panier">

        <thead>
            <tr>
                <th>Nom</th>
                <th>Contact</th>
                <th>Numéro d'appartement</th>
                <th>Score</th>
            </tr>
        </thead>

        <tbody id="body-panier">
            <?php mkTableBody($userInfo); ?>
        </tbody>

    </table>
    

</div>

<div style="display: flex; justify-content: flex-end; margin-top: 30px;margin-right: 20px;">
    <a href="index.php?view=compte" class="actionBtn">
        <span class="ui-icon ui-icon-circle-check"></span>
    </a>
</div>
