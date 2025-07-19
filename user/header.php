 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Réservation Produits</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body data-theme="light">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">RP</div>
                <div class="logo-text">ReservaPro</div>
            </div>
            <div class="nav-menu">
                <div class="nav-item">
                    <a class="nav-link active" onclick="showPage('home')">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Accueil 
                        </span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" onclick="showPage('products')">
                        <span class="nav-icon">🛒</span>
                        <span class="nav-text">Réserver Produits</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" onclick="showPage('orders')">
                        <span class="nav-icon">📋</span>
                        <span class="nav-text">Mes Commandes</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" onclick="showPage('profile')">
                        <span class="nav-icon">👤</span>
                        <span class="nav-text">Mon Profil</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                    <h1 class="page-title" id="pageTitle">Accueil </h1>
                </div>
                <div class="header-right">
                    <button class="theme-toggle" onclick="toggleTheme()">🌙</button>
                    <div class="user-avatar">JD</div>
                </div>
            </header>

  