<?php
    global $_SESSION;

    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    include_once("libs/maLibForms.php");
    
    redirigerParIndexVers("panier");
?>

<h1> Mon Panier </h1>

<?php
    /*
    // Si l'utilisateur n'est pas connecté, on le renvoie vers la page de connection
    if (!isset($_SESSION["idUser"])) {
        $url = dirname($_SERVER["PHP_SELF"]) . "/index.php?view=login";
        header("Location:$url");
        die();
    }
    */
    
    // Récupération de l'indentifiant utilisateur
    
    $idUser = 2;
    //$idUser = $_SESSION["idUser"];

    // On affiche le panier
    $cart = listerPanier($idUser);
    if ($cart != null) $cartId = $cart[0]["id"];
?>


<div id="panier"  class="table-wrapper">

    <table class="table-panier">

        <thead>
            <tr>
                <th>Nom</th>
                <th>Quantité</th>
                <th>Date de début</th>
                <th>Date de fin</th>
            </tr>
        </thead>

        <tbody id="body-panier">
            <?php mkTableBody($cart); ?>
        </tbody>

    </table>

</div>

</br>

<form action="controleur.php" method="POST">

    <?php 
        if ($cart != null) mkInput("hidden", "cartId", $cartId);
    ?>

    <input type="submit" name="action" value="Reserver"/>

</form>