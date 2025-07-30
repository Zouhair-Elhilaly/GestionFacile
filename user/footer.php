  </main>
    </div>

    <!-- Modal de Confirmation -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirmer la Commande</h3>
                <button class="modal-close" onclick="closeModal('confirmModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir confirmer cette commande ?</p>
                <div style="margin: 1rem 0; padding: 1rem; background: var(--gray-50); border-radius: 0.5rem;">
                    <strong>Total: <span id="modalTotal">€0.00</span></strong>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button class="btn btn-outline" onclick="closeModal('confirmModal')">Annuler</button>
                    <button class="btn btn-success" onclick="processOrder()">
                        <span class="loading" id="orderLoading" style="display: none;"></span>
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Succès -->
    <div class="modal" id="successModal">
        <div class="modal-content">
            <div class="success-animation">
                <div class="success-icon">✅</div>
                <h3 style="color: var(--success-color); margin-bottom: 1rem;">Commande Confirmée !</h3>
                <p style="margin-bottom: 2rem;">Votre commande a été enregistrée avec succès.</p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <button class="btn btn-primary" onclick="downloadInvoice()">📄 Télécharger Facture</button>
                    <button class="btn btn-outline" onclick="downloadEngagement()">📝 Télécharger Engagement</button>
                </div>
                <button class="btn btn-outline" onclick="closeModal('successModal')" style="margin-top: 1rem;">Fermer</button>
            </div>
        </div>
    </div>





    <!-- start designe toggle  -->
<script>
                // Toggle sidebar
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  if (window.innerWidth <= 768) {
    sidebar.classList.toggle("mobile-open");
  } else {
    sidebar.classList.toggle("collapsed");
  }
}


  window.addEventListener('DOMContentLoaded', () => {
    const savedMode = localStorage.getItem('mode') || 'light';
    document.body.setAttribute('data-theme', savedMode);
  

    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
      themeToggle.textContent = savedMode === 'dark' ? '☀️' : '🌙';
    }
 });


  function toggleTheme() {
    const body = document.body;
    const themeToggle = document.querySelector('.theme-toggle');

    const currentTheme = body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    localStorage.setItem('mode', newTheme);
    body.setAttribute('data-theme', newTheme);

    if (themeToggle) {
      themeToggle.textContent = newTheme === 'dark' ? '☀️' : '🌙';
    }
  }

document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    const currentPage = window.location.pathname.split('/').pop();
    
    navLinks.forEach(link => {
        // Activer le lien correspondant à la page actuelle
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }

        
        link.addEventListener('click', function() {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});


// ****************************** start voir plus
   let email_employe = document.querySelector("#email_employe").value;
  

   let voir_plus  = document.querySelector(".voir_plus");
  let card_pies_origin = document.querySelectorAll(".card_pies_origin");
    let category_card = document.querySelector(".category-card");

   voir_plus.addEventListener('click', () => {
  
    card_pies_origin.forEach((e) => {
    e.style.display = 'none';
   })

    fetch('http://localhost/projet_stage/user/api/produit_select.php?select=1').then(res => res.json()).then(data =>{
      
      voir_plus.style.display= 'none';

      data.data.forEach((e)=>{

        // ******************
          // Création de l'élément card principal
          const card = document.createElement('div');
          card.className = 'card_pies';
          
          // Partie image
          const categoryImage = document.createElement('div');
          categoryImage.className = 'category-image category-icon';
          
          const img = document.createElement('img');
          img.src = `../admin/image/image_produit/`+e.image;
          img.alt = 'Category Image';
          img.className = 'category-img';
          
          categoryImage.appendChild(img);
          
          // Partie texte
          const textDiv = document.createElement('div');
          textDiv.className = 'text';
          
          const title = document.createElement('h3');
          title.textContent = e.nom_produit;
          textDiv.appendChild(title);
          
          // Compteur de produits
          const countDiv = document.createElement('div');
          countDiv.className = 'products-count';
          
          const span = document.createElement('span');
          span.className = 'result_product';
          span.textContent = e.stock;
          
          countDiv.appendChild(span);
          countDiv.appendChild(document.createTextNode(' products'));
          
          // Bouton Ajouter
          const link = document.createElement('a');
          link.href = `insert/insert_product.php?id=${e.id_produit}&email=${email_employe}&page=produit`;
          link.className = 'btn btn-success';
          link.textContent = 'Ajouter';
          
          // Assemblage de la carte
          card.appendChild(categoryImage);
          card.appendChild(textDiv);
          card.appendChild(countDiv);
          card.appendChild(link);
          


       category_card.appendChild(card);
       

        // ****************
        console.log(e.id_produit)
         console.log(e.id_category)
         console.log(e.nom_produit)
         console.log(e.stock)
          console.log(e.image)
           console.log('*********')
      })
    }).catch(error =>{
      console.log(error)
    })
   })

</script>
<!-- end designe toggle  -->
 
</body>
</html>