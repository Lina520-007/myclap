<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    
    redirigerParIndexVers("inventaire");
    $articles = listerArticlesDisponibles();
?>

<section class="inventorySection">
    <h2 class="sectionTitle">Matériels disponibles</h2>

    <div class="inventoryContainer" id="inventoryContainer">
        <?php foreach ($articles as $article): ?>
            <div class="produit-card">
                <div class="produit-header">
                    <img src="<?= $article['photo_url'] ?? 'ressources/myclap.png' ?>" 
                         alt="<?= htmlspecialchars($article['nom']) ?>">
                    <h3><?= htmlspecialchars($article['nom']) ?></h3>
                    <button class="toggleBtn">+</button>
                </div>

                <div class="produit-details" style="display:none;">
                    <p class="description">Description : <?= htmlspecialchars($article['description'] ?? 'Pas de description disponible') ?></p>
                    <span class="caution">Caution : <?= $article['caution'] ?>€</span>

                    <div class="datesPicker">
                        <label>Début : <input type="date" name="dateDebut"></label>
                        <label>Fin : <input type="date" name="dateFin"></label>
                    </div>

                    <button class="ajouterPanierBtn" data-id="<?= $article['id'] ?>">
                        Ajouter au panier
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
document.querySelectorAll('.toggleBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const details = this.closest('.produit-card').querySelector('.produit-details');
        const isOpen = details.style.display === 'block';
        details.style.display = isOpen ? 'none' : 'block';
        this.textContent = isOpen ? '+' : '−';
    });
});
</script>