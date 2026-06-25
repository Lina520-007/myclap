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
            <?php 
                foreach ($userInfo as $item) {
                    mkForm("controleur.php","POST");
                    echo "<tr>\n";
                    foreach ($item as $champ => $val) {
                        if ($champ != "id" && $champ != "role"&& $champ != "score") echo "\t<td><input type='text' name='$champ' value='$val'/>\n</td>\n";
                    }
                    
                    }
            ?>
        </tbody>
    </table>
        <?php 
        echo "<input type='hidden' name='id' value='$idUser'>\n</tr>\n";
        mkInput("submit", "action", "Sauvegarder les modifications", "button-primary");
                    endForm();  
        ?>

   
    

</div>
