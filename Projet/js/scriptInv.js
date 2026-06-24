$('#searchToggle').click(function(event) {
  if ($(window).width() < 768) {
    event.stopPropagation(); 
    $('#searchContainer').toggleClass('active');
    if ($('#searchContainer').hasClass('active')) $('.searchInput').focus();
  }
});

$(document).click(function(event) {
  if ($(window).width() < 768 && !$('#searchContainer').has(event.target).length 
      && !$('#searchContainer').is(event.target)){
    $('#searchContainer').removeClass('active');
  }  
});

//on vérifie que le conteneur d'inventaire existe sur la page actuelle
const inventoryContainer = document.getElementById('inventoryContainer');

document.querySelector('.searchInput').addEventListener('keyup', (event) => {
    // Si on n'est pas sur la page inventaire, on ne lance pas la requête AJAX
    if (!inventoryContainer) return; 

    const searchString = event.target.value.trim();

    $.ajax({
        url: 'controleur.php', 
        type: 'POST',
        data: { action: 'Recherche AJAX', search: searchString }, 
        dataType: 'json', 
        success: function(products) {
            displayCharacters(products); 
        },
        error: function(xhr, status, error) {
            console.error("Erreur lors de la recherche AJAX: ", error);
        }
    });
});

function displayCharacters(products) {
    inventoryContainer.innerHTML = ''; 
    
    if (products.length === 0) {
        inventoryContainer.innerHTML = '<p class="no-result">Aucun matériel trouvé.</p>';
        return;
    }

    products.forEach(product => {
        //Juste au cas y a pas d'images je mets une le logo
        const photo = product.photo_url ? product.photo_url : "ressources/myclap.png";

        const productCard = `
            <div class="cardProduct">
                <img src="${photo}" alt="${product.nom}" width=30%>
                <div class="produit-info">
                    <h3>${product.nom}</h3>
                    <p class="description">${product.description}</p>
                    <span class="caution">Caution : ${product.caution}€</span>
                </div>
            </div>
        `;
        inventoryContainer.innerHTML += productCard;
    });
}