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

<script src="main.js"></script>
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


 // Toggle theme
        function toggleTheme() {
            const body = document.body;
            const themeToggle = document.querySelector('.theme-toggle');
            
            if (body.getAttribute('data-theme') === 'light') {
                body.setAttribute('data-theme', 'dark');
                themeToggle.textContent = '☀️';
            } else {
                body.setAttribute('data-theme', 'light');
                themeToggle.textContent = '🌙';
                // let card_pies = document.querySelector(".card_pies");
                // card_pies.style.cssText = `
                // color: white;
                //  background-color: gray !important;
                //  `;

                //  card_pies.classList.add('red');
                
            }
        }
            </script>

</body>
</html>