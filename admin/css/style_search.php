     <?php
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    header("HTTP/1.1 403 Forbidden");
    header("location:../error.php");
    exit();
}

// Sinon, afficher le CSS normalement
header("Content-Type: text/css");
?>

   
.navbar_employe {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    padding: 1rem 2rem;
    background: var(--gradient) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    margin: 1rem;
}

.add_admin {
    background:var(--addUser);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(110, 142, 251, 0.3);
}

.add_admin:hover {
    transform: translateY(-2px);
    background: var(--addUserHover);
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(110, 142, 251, 0.4);
}

.add_admin a {
    color: white;
    text-decoration: none;
    display: inline-block;
}

.content {
    display: flex;
    align-items: center;
}

.search-container {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 15px;
    color: #a0a0a0;
    font-size: 1rem;
}

#search {
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.95rem;
    width: 250px;
    transition: all 0.3s ease;
    background-color: #f9f9f9;
    color: #333;
}

#search:focus {
    outline: none;
    border-color: #6e8efb;
    box-shadow: 0 0 0 3px rgba(110, 142, 251, 0.2);
    background-color: #fff;
}

/* Animation pour le bouton */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.add_admin:active {
    animation: pulse 0.3s ease;
}