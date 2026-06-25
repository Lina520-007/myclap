<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    
    redirigerParIndexVers("inventaire");
    $articles = listerArticlesDisponibles();
?>
 <!-------------Filtres------------->
<div class="filtersSection">
    <span class="filtersTitle">Filtres :</span>
    
    <div class="dropdown elmtsFilter">

        <button class="dropdownBtn"> ▼ Catégories </button>
        <div class="dropdownElmts">
            <?php foreach (listerCategory() as $category): ?>
                <label><input type="checkbox" name="categorie" value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></label>
            <?php endforeach; ?>
        </div>
    </div>
    
    <label class="favCheck elmtsFilter">
        <?php if (isset($_SESSION["idUser"])): ?>
            <input type="checkbox" name="favoris" id="favFilter">Favoris
        <?php endif; ?>
    </label>
    
    <div class="dateSelection elmtsFilter">
        <label for="filtreDate">Disponible à partir de :</label>
        <input type="date" id="filtreDate" name="dateFiltre">
    </div>
 </div>
 <!------------- SECTION PRINCIPALE: affichage de tous les éléments ------------->
<section class="inventorySection">
    <h2 class="sectionTitle">Matériels disponibles</h2>

    <div class="inventoryContainer" id="inventoryContainer">
        <?php foreach ($articles as $article): ?>
            <div class="cardProduct">
                <div class="headerProduct">
                    <img src="<?= $article['photo_url'] ?? 'ressources/myclap.png' ?>" 
                         alt="<?= htmlspecialchars($article['name']) ?>">
                    <h3><?= htmlspecialchars($article['name']) ?></h3>
                    <button class="toggleBtn">+</button>
                </div>

                <div class="detailsProduct" style="display:none;">
                    <p class="description">Description : <?= htmlspecialchars($article['description'] ?? 'Pas de description disponible') ?></p>
                    <span class="bail">bail : <?= $article['bail'] ?>€</span>

                    <div class="datesPicker">
                        <label>Début : <input type="date" name="startDate"></label>
                        <label>Fin : <input type="date" name="endDate"></label>
                    </div>

                    <button class="addCartBtn" data-id="<?= $article['id'] ?>">
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
        const details = this.closest('.cardProduct').querySelector('.detailsProduct');
        const isOpen = details.style.display === 'block';
        details.style.display = isOpen ? 'none' : 'block';
        this.textContent = isOpen ? '+' : '−';
    });
});
const dropdown = document.querySelector('.dropdown');
const dropdownBtn = document.querySelector('.dropdownBtn');

dropdownBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    dropdown.classList.toggle('open');
});

document.addEventListener('click', function() {
    dropdown.classList.remove('open');
});

document.querySelector('.dropdownElmts').addEventListener('click', function(e) {
    e.stopPropagation();
});
</script>