   </div>
   <?php 

if (!defined('SECURE_ACCESS')) {
    header('location:error.php');
    exit();
}
?>

<script>


// start redirect to commande page en notification
function commande(){
    window.location = 'commande.php'
}

function produit(){
    window.location = 'view_product.php'
}

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

// Scroll vers le haut à l'élément header
btn.addEventListener("click", () => {
     window.scrollTo({
        top: 0,          
        left: 0,          
        behavior: "smooth" 
    });
});

let logo_admin = document.querySelector(".logo_admin");
logo_admin.addEventListener("click", () => {
    window.location = "acceuil.php";
})

















        // Theme Toggle
        function toggleTheme() {
            const body = document.body;
            const themeIcon = document.getElementById('theme-icon');
            const themeText = document.getElementById('theme-text');
            
            if (body.getAttribute('data-theme') === 'light') {
                body.setAttribute('data-theme', 'dark');
                themeIcon.className = 'fas fa-sun';
                themeText.textContent = 'Mode Clair';
                localStorage.setItem('theme', 'dark');
            } else {
                body.setAttribute('data-theme', 'light');
                themeIcon.className = 'fas fa-moon';
                themeText.textContent = 'Mode Sombre';
                localStorage.setItem('theme', 'light');
            }
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const body = document.body;
            const themeIcon = document.getElementById('theme-icon');
            const themeText = document.getElementById('theme-text');
            
            body.setAttribute('data-theme', savedTheme);
            if (savedTheme === 'dark') {
                themeIcon.className = 'fas fa-sun';
                themeText.textContent = 'Mode Clair';
            }
        });

        // Mobile Navigation Toggle
        function toggleNavbar() {
            const navbar = document.getElementById('navbar');
            const overlay = document.getElementById('overlay');
            const closebtn = document.getElementById('closebtn');
            
            navbar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            if (navbar.classList.contains('active')) {
                closebtn.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                closebtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        }

        // Close navbar when clicking overlay
        document.getElementById('overlay').addEventListener('click', function() {
            toggleNavbar();
        });

        // Logout function
        function logout(v,d) {
            // if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
            //     // Add logout logic here
            //      Swal.fire({
            //         icon: '$type',
            //         title: 'Opération réussie !',
            //         text: '$text',
            //         timer: 3000 
            //     });


// 
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
                         Swal.fire('Action annulée', '', 'info');
                    }
                });
                // window.location.href = 'login.php';
            }
        // }

        // Close navbar on window resize
        window.addEventListener('resize', function() {
            
            if (window.innerWidth > 768) {
                const navbar = document.getElementById('navbar');
                const overlay = document.getElementById('overlay');
                const closebtn = document.getElementById('closebtn');
                
                navbar.classList.remove('active');
                overlay.classList.remove('active');
                closebtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add active link highlighting
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.navbar a');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.style.color = 'var(--primary-color)';
                link.style.background = 'rgba(37, 99, 235, 0.1)';
                link.style.transform = 'translateX(10px)';
            }
        });
    </script>
</body>
</html>