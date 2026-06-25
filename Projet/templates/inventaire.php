<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    
    redirigerParIndexVers("inventaire");

    $articles = listerArticlesDisponibles();
?>

<style>

    .produit-details {
        display: none;
    }

    .produit-details.open {
        display: block;
    }

</style>

<section class="inventorySection">

    <h2 class="sectionTitle">Matériels disponibles</h2>

    <div class="inventoryContainer" id="inventoryContainer">

        <?php foreach ($articles as $article): ?>

            <div class="produit-card">

                <div class="produit-header">

                    <img src="<?= $article['photo_url'] ?? 'ressources/myclap.png' ?>" alt="<?= htmlspecialchars($article['name']) ?>"/>

                    <h3><?= htmlspecialchars($article['name']) ?></h3>

                    <button type="button" class="toggleBtn">+</button>

                </div>

                <form action="controleur.php" method="POST">

                    <?php mkInput("hidden", $article["id"]) ?>

                    <div class="produit-details">

                        <p class="description">Description : <?= htmlspecialchars($article['description'] ?? 'Pas de description disponible') ?></p>

                        <span class="caution">Caution : <?= $article['bail'] ?>€</span>

                        <div>
                            Quantité : 
                            <select class="selection" name="quantite">
                                <?php 
                                    $qte = $article['stock'];
                                    for ($i=1 ; $i<$qte+1 ; $i++) {
                                        echo "<option value=\"$i\">\n";
                                        echo "$i";
                                        echo "</option>\n";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="datesPicker">
                            <label>
                                Début : <input type="date" name="dateDebut" min="2026-01-01" max="2026-12-31">
                            </label>
                            <label>
                                Fin : <input type="date" name="dateFin">
                            </label>
                        </div>

                        <input class="ajouterPanierBtn" type="submit" name="Ajouter au panier" value="Ajouter au panier"/>

                    </div>

                </form>

            </div>

        <?php endforeach; ?>

    </div>

</section>

<script>

    document.querySelectorAll('.toggleBtn').forEach(btn => {
        btn.addEventListener('click', function () {

            const card = this.closest('.produit-card');
            const details = card.querySelector('.produit-details');
            const isOpen = details.classList.contains('open');

            // fermer tous les autres
            document.querySelectorAll('.produit-details').forEach(d => {
                d.classList.remove('open');
            });

            document.querySelectorAll('.toggleBtn').forEach(b => {
                b.textContent = '+';
            });

            // ouvrir celui cliqué
            if (!isOpen) {
                details.classList.add('open');
                this.textContent = '−';
            }
        });
    });

    $("#")
    
</script>