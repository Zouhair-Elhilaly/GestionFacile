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



<?php 
if (!defined('SECURE_ACCESS')) {
    // Si on tente d’accéder directement au fichier sans passer par include
    header('location:../error.php');
    exit();
}
?>


    <!-- start designe toggle  -->
<script>

    // Logout function
        function logout(v,d) {
           // start affiche delete
                Swal.fire({
                    title: 'Confirmez',
                    text: "Êtes-vous sûr de vouloir vous déconnecter ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, déconnecter  !',
                    cancelButtonText: 'Annuler',
                    backdrop: 'rgba(0,0,0,0.7)',
                    customClass: {
                        // popup: 'animated fadeIn faster' // Animation (optionnelle)
                        popup: 'popudMode'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'logout.php?id='+v+'&token='+d;
                    } else {
                        window.load();
                    }
                });
                // window.location.href = 'login.php';
            }
        // }

    
// Scroll vers le haut à l'élément header
const btn = document.getElementById("scrollTopBtn");

// Afficher le bouton quand on scrolle
window.addEventListener("scroll", () => {
    console.log(window.scrollY)
    if (window.scrollY > 300) {
        btn.style.display = "flex"; 
    } else {
        btn.style.display = "none";
    }
});

btn.addEventListener("click", () => {
     window.scrollTo({
        top: 0,          
        left: 0,          
        behavior: "smooth" 
    });
});




//                 // Toggle sidebar
// function toggleSidebar() {
//   const sidebar = document.getElementById("sidebar");
//   if (window.innerWidth <= 768) {
//     sidebar.classList.toggle("mobile-open");
//      sidebar.classList.remove("collapsed2");
//   } else {
//     sidebar.classList.toggle("collapsed");
//          sidebar.classList.remove("collapsed2");

//   }
// }




//   window.addEventListener('DOMContentLoaded', () => {
//     const savedMode = localStorage.getItem('mode') || 'light';
//     document.body.setAttribute('data-theme', savedMode);
  

//     const themeToggle = document.querySelector('.theme-toggle');
//     if (themeToggle) {
//       themeToggle.textContent = savedMode === 'dark' ? '☀️' : '🌙';
//     }






//  });

 
// // start toggle navbar
//     let toggle = document.querySelector(".toggle");

// toggle.addEventListener('click', () => {
//     const sidebar = document.getElementById("sidebar");
//     sidebar.classList.toggle("collapsed");
//      if(window.innerWidth <= 768 && toggle.textContent.trim() === '>'){
//         sidebar.classList.add("collapsed2");
//         sidebar.style.cssText = `
//         width:0
//         `;
//         // sidebar.classList.remove("collapsed")
//      }
//     if(toggle.textContent.trim() === '>'){
//         toggle.textContent = '<';
//     } else {
//         toggle.textContent = '>';
//     }
// });

// start 


// Solution corrigée pour le toggle de la sidebar

// Toggle sidebar function améliorée
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.querySelector(".toggle");
    
    if (window.innerWidth <= 768) {
        // Mode mobile
        if (sidebar.classList.contains("mobile-open")) {
            sidebar.classList.remove("mobile-open");
            sidebar.classList.add("collapsed2");
            toggle.textContent = '>';
        } else {
            sidebar.classList.add("mobile-open");
            sidebar.classList.remove("collapsed2", "collapsed");
            toggle.textContent = '<';
        }
    } else {
        // Mode desktop
        if (sidebar.classList.contains("collapsed")) {
            sidebar.classList.remove("collapsed");
            toggle.textContent = '<';
        } else {
            sidebar.classList.add("collapsed");
            toggle.textContent = '>';
        }
        // Supprimer les classes mobiles en mode desktop
        sidebar.classList.remove("collapsed2", "mobile-open");
    }
}

// Toggle navbar amélioré (remplace votre code actuel)
let toggle = document.querySelector(".toggle");

toggle.addEventListener('click', () => {
    const sidebar = document.getElementById("sidebar");
    
    if (window.innerWidth <= 768) {
        // Mode mobile
        if (sidebar.classList.contains("mobile-open")) {
            sidebar.classList.remove("mobile-open");
            sidebar.classList.add("collapsed2");
            toggle.textContent = '>';
        } else {
            sidebar.classList.add("mobile-open");
            sidebar.classList.remove("collapsed2", "collapsed");
            toggle.textContent = '<';
        }
    } else {
        // Mode desktop
        sidebar.classList.toggle("collapsed");
        sidebar.classList.remove("collapsed2", "mobile-open");
        
        if (sidebar.classList.contains("collapsed")) {
            toggle.textContent = '>';
        } else {
            toggle.textContent = '<';
        }
    }
});

// Gestion du redimensionnement de la fenêtre
window.addEventListener('resize', () => {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.querySelector(".toggle");
    
    if (window.innerWidth > 768) {
        // Passage en mode desktop
        sidebar.classList.remove("mobile-open", "collapsed2");
        if (sidebar.classList.contains("collapsed")) {
            toggle.textContent = '>';
        } else {
            toggle.textContent = '<';
        }
    } else {
        // Passage en mode mobile
        sidebar.classList.remove("collapsed");
        if (!sidebar.classList.contains("mobile-open")) {
            sidebar.classList.add("collapsed2");
            toggle.textContent = '>';
        } else {
            toggle.textContent = '<';
        }
    }
});

// Initialisation au chargement de la page
window.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.querySelector(".toggle");
    
    // État initial basé sur la taille d'écran
    if (window.innerWidth <= 768) {
        sidebar.classList.add("collapsed2");
        toggle.textContent = '>';
    } else {
        toggle.textContent = '<';
    }
    
    // Reste de votre code d'initialisation...
    const savedMode = localStorage.getItem('mode') || 'light';
    document.body.setAttribute('data-theme', savedMode);
    
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        themeToggle.textContent = savedMode === 'dark' ? '🔆' : '🌙';
    }
});

// end



  function toggleTheme() {
    const body = document.body;
    const themeToggle = document.querySelector('.theme-toggle');

    const currentTheme = body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    localStorage.setItem('mode', newTheme);
    body.setAttribute('data-theme', newTheme);

    if (themeToggle) {
      themeToggle.textContent = newTheme === 'dark' ? '🔆' : '🌙';
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
   let token = document.querySelector("#token").value;
   console.log(token)


   let voir_plus  = document.querySelector(".voir_plus");
  let card_pies_origin = document.querySelectorAll(".card_pies_origin");
    let category_card = document.querySelector(".category-card");

   voir_plus.addEventListener('click', () => {
  
    card_pies_origin.forEach((e) => {
    e.style.display = 'none';
   })

    fetch(`http://localhost/projet_stage/user/api/produit_select.php?select=1&token=${token}`).then(res => res.json()).then(data =>{
      
      voir_plus.style.display= 'none';

      data.data.forEach((e)=>{

        // ******************
          // Création de l'élément card principal
          const card = document.createElement('div');
          card.className = 'card_pies card_search';
          
          // Partie image
          const categoryImage = document.createElement('div');
          categoryImage.className = 'category-image category-icon';
          
          const img = document.createElement('img');
          img.src = `../admin/protection_image/image_produit.php?img=`+e.image;
          img.alt = 'Category Image';
          img.className = 'category-img';
          
          categoryImage.appendChild(img);
          
          // Partie texte
          const textDiv = document.createElement('div');
          textDiv.className = 'text';
          
          const title = document.createElement('h3');
          title.textContent = e.nom_produit;
          title.className = 'name';
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
          link.href = `insert/insert_product.php?id=${e.id_produit}&email=${email_employe}&page=produit&token=${token}`;
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




   let sidebar_header = document.querySelector(".sidebar-header");
     sidebar_header.addEventListener('click' ,()=>{
        window.location = 'home.php';
     })

</script>
<!-- end designe toggle  -->
 
</body>
</html>