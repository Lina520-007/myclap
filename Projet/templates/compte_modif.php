<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    include_once("libs/maLibForms.php");

    redirigerParIndexVers("compte_modif");
?>

<h1>Mon compte</h1>

<div class="accountWrapper">
    <?php
        $idUser = 1;
        $userInfo = userInfo($idUser);
        $user = $userInfo[0]; 
        mkForm("controleur.php", "POST");
    ?>
        
        <div class="formGroup">
            <label for="userName">Nom</label>
            <input type="text" id="userName" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
        </div>

        <div class="formGroup">
            <label for="userContact">Contact</label>
            <input type="text" id="userContact" name="contact" value="<?= htmlspecialchars($user['contact'] ?? '') ?>">
        </div>

        <div class="formGroup">
            <label for="flatNumber">Numéro d'appartement</label>
            <input type="text" id="flatNumber" name="flat_num" value="<?= htmlspecialchars($user['flat_num'] ?? '') ?>">
        </div>

        <div class="formGroup">
            <label>Score de confiance</label>
            <div class="scoreDisplay"><?= htmlspecialchars($user['score'] ?? '0') ?></div>
        </div>

        <?php mkInput("hidden", "id", $idUser); ?>

        <div class="formActions">
            <?php mkInput("submit", "action", "Sauvegarder les modifications", "primaryButton"); ?>
        </div>

    <?php 
            endForm(); 
    ?>
</div>